<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\AdvantageService;
use App\Models\AdvantageUser;
use App\Models\Order;
use App\Models\Service;
use App\Models\Tagline;
use App\Models\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.landing.index', [
            'services' => Service::all(),
        ]);
    }

    // custom

    public function explore()
    {
        return view('pages.landing.explorer', [
            'services' => Service::all(),
        ]);
    }

    public function detail($id)
    {
        return view('pages.landing.detail', [
            'service'          => Service::find('id', $id),
            'advantageUser'    => AdvantageUser::find('service_id', $id),
            'advntageService'  => AdvantageService::find('service_id', $id),
            'thumbnailService' => ThumbnailService::find('service_id', $id),
            'tagline'          => Tagline::find('service_id', $id),
        ]);
    }

    public function booking($id)
    {
        $service = Service::find($id);
        $user_buyer = Auth::user()->id;

        if($service->users_id == $user_buyer)
        {
            toast()->warning('You cannot book your own service');
            return redirect()->back();
        }

        $order = Order::create([
            'buyer_id'        => $user_buyer,
            'freelancer_id'   => $service->user->id,
            'service_id'      => $service->id,
            'file'            => null,
            'note'            => null,
            'expired'         => date('d-m-y', strtotime('+3 days')),
            'order_status_id' => 4,
        ]);

        return redirect()->route('detail.booking.landing', $order);
    }

    public function detailBooking($id)
    {
        return view('pages.landing.booking', [
            'order' => Order::find($id)
        ]);
    }
}
