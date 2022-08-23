<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateDetailUserRequest;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Models\DetailUser;
use App\Models\ExperienceUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
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
        return view('pages.dashboard.profile', [
            'user'           => Auth::user(),
            'experienceUser' => ExperienceUser::where('detail_user_id', Auth::user()->id)->get() ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $profileRequest, UpdateDetailUserRequest $detailUserRequest, $id)
    {
        $dataProfile    = $profileRequest->all();
        $dataDetailUser = $detailUserRequest->all();


        // get photo

        // delete old file
        if ($profileRequest->hasFile('photo')) {
            $photo = DetailUser::where('users_id', Auth::user()->id)->first()['photo'];
            if ($photo) {
                unlink(public_path($photo));
            } else {
                $filename = uniqid() . $profileRequest->photo->getClientOriginalName();
                $profileRequest->file('photo')->move('assets/user/', $filename);
            }
        }

        User::where('id', Auth::user()->id)->update([
            'name'  => $dataProfile['name'],
            'email' => $dataProfile['email'],
        ]);

        DetailUser::where('users_id', Auth::user()->id)->update([
            'photo'          => 'assets/user/' . $filename,
            'role'           => $dataDetailUser['role'],
            'contact_number' => $dataDetailUser['contact_number'],
            'biography'      => $dataDetailUser['biography']
        ]);

        $experienceUserId = ExperienceUser::where('detail_user_id', Auth::user()->id)->first();

        if (isset($experienceUserId)) {
            foreach ($dataProfile['experience'] as $key => $value) {
                $experienceUser                 = ExperienceUser::find($key);
                $experienceUser->detail_user_id = Auth::user()->id;
                $experienceUser->experience     = $value;
                $experienceUser->save();
            }
        } else {
            foreach ($dataProfile['experience'] as $item) {
                ExperienceUser::create([
                    'detail_user_id' => Auth::user()->id,
                    'experience'     => $item
                ]);
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
    }


    // custom

    public function deletePhoto()
    {
        $detailUser = Auth::user()->detailUser;

        if (file_exists(public_path($detailUser['photo']))) {
            unlink(public_path($detailUser['photo']));
        } else {
            $detailUser->photo = null;
            $detailUser->save();
        }

        $detailUser->photo = null;
        $detailUser->save();

        toast()->success('Delete has been success');
        return back();
    }
}
