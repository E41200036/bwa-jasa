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
use Illuminate\Support\Facades\File;

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
            'service' => Auth::user()->service
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
        $data['users_id'] = Auth::user()->id;

        $service = Service::create($request->all());

        foreach ($request->advantage_service as $key => $value) {
            $advantage_service = new AdvantageService();
            $advantage_service->users_id = $service->id;
            $advantage_service->advantage_id = $value;
            $advantage_service->save();
        }

        // advantage user
        foreach ($request->advantage_user as $key => $value) {
            $advantage_user = new AdvantageService();
            $advantage_user->users_id = $service->id;
            $advantage_user->advantage_id = $value;
            $advantage_user->save();
        }

        // add to thumbnail service
        if ($request->hasFile($request->thumbnail)) {
            foreach ($request->thumbnail as $file) {
                $path = $file->store('assets/service/thumbnail', 'public');

                $thumbnail_service = new ThumbnailService();
                $thumbnail_service->service_id = $service['id'];
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        // tagline
        foreach ($request->tagline as $value) {
            $tagline = new Tagline();
            $tagline->service_id = $service['id'];
            $tagline->tagline = $value;
            $tagline->save();
        }

        toast()->success('Save has been success');
        return redirect()->route('member.service.index');
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
        // update service
        $service->update($request->all());

        foreach($request->advantage_service as $key => $value) {
            $advantage_service = AdvantageService::find($key);
            $advantage_service->advantage = $value;
            $advantage_service->save();
        }

        // add new advantage
        if(isset($request->advantage_service)) {
            foreach($request->advantage_service as $key => $value) {
                $advantage_service = new AdvantageService();
                $advantage_service->service_id = $service->id;
                $advantage_service->advantage = $value;
                $advantage_service->save();
            }
        }

        foreach($request->advantage_user as $key => $value) {
            $advantage_user = AdvantageUser::find($key);
            $advantage_user->advantage = $value;
            $advantage_user->save();
        }

        // add new advantage
        if(isset($request->advantage_user)) {
            foreach($request->advantage_user as $key => $value) {
                $advantage_user = new AdvantageUser();
                $advantage_user->service_id = $service->id;
                $advantage_user->advantage = $value;
                $advantage_user->save();
            }
        }

        foreach($request->tagline as $key => $value) {
            $tagline = Tagline::find($key);
            $tagline->tagline = $value;
            $tagline->save();
        }

        // add new advantage
        if(isset($request->tagline)) {
            foreach($request->tagline as $key => $value) {
                $tagline = new Tagline();
                $tagline->service_id = $service->id;
                $tagline->tagline = $value;
                $tagline->save();
            }
        }

        if($request->hasFile($request->thumbnails)) {
            foreach($request->thumbnails as $key => $value) {
                // get old
                $getPhoto = ThumbnailService::where('id', $key)->first();
                // store new photo
                $path = $value->store('assets/service/thumbnail', 'public');
                // update thumbnail
                $thumbnail_service = ThumbnailService::find($key);
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();

                $data = 'storage/' . $getPhoto['photo'];
                if(File::exists($data)) {
                    File::delete($data);
                } else {
                    File::delete('assets/app/public/' . $getPhoto['photo']);
                }
            }
        }

        if($request->hasFile('thumbnail')) {
            foreach($request->file('thumbnail') as $file) {
                $path  = $file->store('assets/service/thumbnail', 'public');

                $thumbnail_service = new ThumbnailService();
                $thumbnail_service->service_id = $service['id'];
                $thumbnail_service->thumbnail = $path;
                $thumbnail_service->save();
            }
        }

        toast()->success('Update has been success');
        return back();
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
