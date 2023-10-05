<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Owlwebdev\Ecom\Models\Order;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->has('p')) {
            $p     = explode(' - ', $request->get('p'));
            $pFrom = $p[0];
            $pTo   = $p[1];
        } else {
            $pFrom = Carbon::today()->addWeek(-1)->format("Y-m-d");
            $pTo   = Carbon::today()->format("Y-m-d");
        }

        $dateTimeFrom = $pFrom . ' 00:00:00';
        $dateTimeTo = $pTo . ' 23:59:59';

        $min = \Illuminate\Support\Carbon::parse($dateTimeFrom)->timestamp*1000; //*1000 for js
        $max = Carbon::parse($dateTimeTo)->timestamp*1000;                       //*1000 for js

        /* @var Order $order */

        $orders = Order::query()
            ->where('created_at','>=',$dateTimeFrom)
            ->where('created_at','<=',$dateTimeTo)
            ->get();

        $ordersByStatusData = [
            'new' => [
                'count' => 0,
                'price' => 0
            ],
            'in_progress' => [
                'count' => 0,
                'price' => 0
            ],
            'canceled' => [
                'count' => 0,
                'price' => 0
            ],
            'complete' => [
                'count' => 0,
                'price' => 0
            ],
        ];

        foreach ($orders as $order){
            switch ($order->order_status_id){
                case 2:
                    $ordersByStatusData['new']['count']++;
                    $ordersByStatusData['new']['price'] += $order->total_price;
                    break;
                case 5:
                    $ordersByStatusData['in_progress']['count']++;
                    $ordersByStatusData['in_progress']['price'] += $order->total_price;
                    break;
                case 3:
                    $ordersByStatusData['canceled']['count']++;
                    $ordersByStatusData['canceled']['price'] += $order->total_price;
                    break;
                case 6:
                    $ordersByStatusData['complete']['count']++;
                    $ordersByStatusData['complete']['price'] += $order->total_price;
                    break;
            }
        }

        $orders = Order::query()
            ->where('created_at','>=',$dateTimeFrom)
            ->where('created_at','<=',$dateTimeTo)
            ->whereIn('order_status_id', [2,3,4,5,6])
            ->orderBy('created_at', 'desc')
            ->paginate();

        $days = [];
        $last_orders = Order::query()
            ->where('created_at','>=',$dateTimeFrom)
            ->where('created_at','<=',$dateTimeTo)
            ->whereIn('order_status_id', [2,3,4,5,6])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as "success"')
            ))->pluck('success', 'date')->toArray();
        if (!empty($last_orders)){
            $last_period = CarbonPeriod::create($dateTimeFrom, \Illuminate\Support\Carbon::now()->toDateString());

            // Iterate over the period
            foreach ($last_period as $date) {

                $d = $date->timestamp*1000;

                $days[] = [$d, isset($last_orders[$date->format('Y-m-d')])?$last_orders[$date->format('Y-m-d')]:0];
            }
        }

        return view('admin.home',[
            'dateFrom'              => $pFrom,
            'dateTo'                => $pTo,
            'orders_by_status_data' => $ordersByStatusData,
            'orders' => $orders,
            'days' => json_encode($days),
            'min' => $min,
            'max' => $max
        ]);
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.auth.login');
        } else {
            if (Auth::attempt([
                'email'    => $request->get('email'),
                'password' => $request->get('password')
            ],true)) {
                return redirect()->route('admin');
            }
            else {
                return redirect()->back()->with('error','Email or password is not correct!');
            }
        }
    }
}
