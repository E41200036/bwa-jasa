<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Service\StoreServiceRequest;
use App\Http\Requests\Dashboard\Service\UpdateServiceRequest;
use App\Models\AdvantageService;
use App\Models\AdvantageUser;
use App\Models\Service;
use App\Models\Tagline;
use App\Models\ThumbnailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Monolog\Handler\PushoverHandler;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.dashboard.service.index', [
            'services' => Auth::user()->service
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.dashboard.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            $service = Service::create([
                'users_id'       => Auth::user()->id,
                'title'          => $request->title,
                'description'    => $request->description,
                'revision_limit' => $request->revision_limit,
                'delivery_time'  => $request->delivery_time,
                'price'          => $request->price,
                'note'           => $request->note,
            ]);

            foreach ($request->advantage_service as $item) {
                $advantage_service = AdvantageService::create([
                    'service_id' => $service['id'],
                    'advantage'  => $item
                ]);
            }

            foreach ($request->advantage_user as $item) {
                $advantage_user = AdvantageUser::create([
                    'service_id' => $service['id'],
                    'advantage'  => $item
                ]);
            }

            foreach ($request->thumbnail_service as $item) {
                $filename = uniqid() . $item->getClientOriginalName();
                ThumbnailService::create([
                    'service_id' => $service['id'],
                    'thumbnail'  => 'assets/thumbnail-service/' . $filename
                ]);

                $item->move(public_path('assets/thumbnail-service'), $filename);
            }

            foreach ($request->tagline as $item) {
                $tagline = Tagline::create([
                    'service_id' => $service['id'],
                    'tagline'  => $item
                ]);
            }

            toast()->success('Service has been success');
            return redirect()->back();
        } catch (\Throwable $th) {
            throw $th;
            toast()->warning('Service has been failed');
            return redirect()->route('member.service.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        return view('pages.dashboard.service.edit', [
            'service'           => $service,
            'advantage_service' => AdvantageService::where('service_id', $service->id)->get(),
            'tagline'           => Tagline::where('service_id', $service->id)->get(),
            'advantage_user'    => AdvantageUser::where('service_id', $service->id)->get(),
            'thumbnail_service' => ThumbnailService::where('service_id', $service->id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        DB::beginTransaction();
        try {
            $service->update($request->all());

            // * advantage service
            if ($request->advantage_service_old) {

                foreach ($request->advantage_service_old as $key => $value) {
                    $advantage_service             = AdvantageService::find($key);
                    $advantage_service->service_id = $service->id;
                    $advantage_service->advantage  = $value;
                    $advantage_service->save();
                }
            }

            if ($request->advantage_service) {

                foreach ($request->advantage_service as $value) {
                    AdvantageService::create([
                        'service_id' => $service->id,
                        'advantage'  => $value
                    ]);
                }
            }

            // * advantage user
            if ($request->advantage_user_old) {

                foreach ($request->advantage_user_old as $key => $value) {
                    $advantage_user             = AdvantageUser::find($key);
                    $advantage_user->service_id = $service->id;
                    $advantage_user->advantage  = $value;
                    $advantage_user->save();
                }
            }

            if ($request->advantage_user) {

                foreach ($request->advantage_user as $value) {
                    AdvantageUser::create([
                        'service_id' => $service->id,
                        'advantage'  => $value
                    ]);
                }
            }

            // * tagline
            if ($request->tagline_old) {

                foreach ($request->tagline_old as $key => $value) {
                    $tagline             = Tagline::find($key);
                    $tagline->service_id = $service->id;
                    $tagline->tagline    = $value;
                    $tagline->save();
                }
            }

            if ($request->tagline) {

                foreach ($request->tagline as $value) {
                    Tagline::create([
                        'service_id' => $service->id,
                        'tagline'    => $value
                    ]);
                }
            }

            // * thumbnail service
            if ($request->thumbnail_service_old) {
                foreach ($request->thumbnail_service_old as $key => $value) {
                    if (ThumbnailService::find($key)->id == $key) {

                        // delete old file
                        $old_photo = ThumbnailService::find($key)->thumbnail;
                        if ($old_photo) {
                            unlink(public_path($old_photo));
                        }

                        $filename = uniqid() . $value->getClientOriginalName();

                        $thumbnail_service             = ThumbnailService::find($key);
                        $thumbnail_service->service_id = $service->id;
                        $thumbnail_service->thumbnail  = 'assets/thumbnail-service/' . $filename;
                        $thumbnail_service->save();

                        // store new file
                        $value->move(public_path('assets/thumbnail-service'), $filename);
                    }
                }
            }

            if ($request->thumbnail_service) {
                foreach ($request->thumbnail_service as $key => $value) {
                    $filename = uniqid() . $value->getClientOriginalName();

                    $thumbnail_service             = new ThumbnailService();
                    $thumbnail_service->service_id = $service->id;
                    $thumbnail_service->thumbnail  = 'assets/thumbnail-service/' . $filename;
                    $thumbnail_service->save();

                    // store new file
                    $value->move(public_path('assets/thumbnail-service'), $filename);
                }
            }

            DB::commit();
            toast()->success('Update success');
            return redirect()->back();
        } catch (\Throwable $th) {

            dd($th);
            DB::rollBack();
            toast()->warning('Update failed');
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
