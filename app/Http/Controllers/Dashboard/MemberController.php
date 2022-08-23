<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('freelancer_id', Auth::user()->id)->get();

        $progress = Order::where([
            ['freelancer_id', Auth::user()->id],
            ['order_status_id', 2]
        ])->count();

        $completed = Order::where([
            ['freelancer_id', Auth::user()->id],
            ['order_status_id', 1]
        ])->count();

        $freelancer = Order::where([
            ['buyer_id', Auth::user()->id],
            ['order_status_id', 2]
        ])->distinct('freelancer_id')->count();

        return view('pages.dashboard.index', [
            'orders'     => $orders,
            'progress'   => $progress,
            'completed'  => $completed,
            'freelancer' => $freelancer
        ]);
    }
}
