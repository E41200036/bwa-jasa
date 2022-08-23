<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MyOrder\UpdateMyOrderRequest;
use App\Models\AdvantageService;
use App\Models\AdvantageUser;
use App\Models\Order;
use App\Models\Service;
use App\Models\Tagline;
use App\Models\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
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
        return view('pages.dashboard.order.index', [
            'orders' => Order::where('freelancer_id', Auth::user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('pages.dashboard.order.detail', [
            'service'          => Service::where('service_id', $id)->get(),
            'thumbnail'        => ThumbnailService::where('service_id', $id)->get(),
            'advantageUser'    => AdvantageUser::where('service_id', $id)->get(),
            'advantageService' => AdvantageService::where('service_id', $id)->get(),
            'tagline'          => Tagline::where('service_id', $id)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('pages.dashboard.order.edit', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMyOrderRequest $request, Order $order)
    {
        $data = $request->all();
        if(isset($data['file'])) {
            $data['file'] = $request->file('file')->store('assets/order/attachment', 'public');
        }

        $order->update([
            'file' => $data['file'],
            'note' => $data['note']
        ]);

        toast()->success('Submit order has been success!');
        return redirect()->route('member.order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }


    // custom
    public function accepted($id)
    {
        Order::where('id', $id)->update(['order_status_id' => 2]);
        toast()->success('Accept order has been success!');
        return redirect()->back();
    }

    public function rejected($id)
    {
        Order::where('id', $id)->update(['order_status_id' => 3]);
        toast()->success('Reject order has been success!');
        return redirect()->back();
    }
}
