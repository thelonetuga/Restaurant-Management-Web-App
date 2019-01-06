<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrdersResource;
use App\User;
use ArrayObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Orders;
use App\Meals;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Expr\Array_;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $orders = Orders::paginate(10);
        return OrdersResource::collection($orders);
    }

    public function cookOrders($responsible_cook_id)
    {

        $orders = Orders::where('responsible_cook_id', $responsible_cook_id)->where([['state', '<>', 'prepared'],
            ['state', '<>', 'delivered'],
            ['state', '<>', 'not delivered']])->orderBy('state', 'in preparation')->orderBy('updated_at', 'ASC')->paginate(10);


        return OrdersResource::collection($orders);
    }

    public function noCookOrders()
    {

        $orders = Orders::whereNull('responsible_cook_id')->where([['state', '=', 'confirmed']])->orwhere([['state', '=', 'pending']])->orderBy('state', 'DESC')->orderBy('responsible_cook_id')->paginate(20);
        return OrdersResource::collection($orders);
    }

    public function waiterPendingConfirmedOrders($responsible_waiter_id)
    {
        $pending = Orders::where([['state', '<>', 'prepared'],
            ['state', '<>', 'delivered'],
            ['state', '<>', 'not delivered'], ['state', '<>', 'in preparation']])->orderBy('state', 'ASC')->get()->reject(function ($order) use ($responsible_waiter_id) {
            return $order->meal->responsible_waiter_id != $responsible_waiter_id;
        });
        return OrdersResource::collection($pending);
    }

    public function waiterPreparedOrders($responsible_waiter_id)
    {
        $pending = Orders::where([['state', '=', 'prepared']])->get()->reject(function ($order) use ($responsible_waiter_id) {
            return $order->meal->responsible_waiter_id != $responsible_waiter_id;
        });
        return OrdersResource::collection($pending);
    }

    public function changeStateAfter5Sec($id)
    {
        $order = Orders::findOrFail($id);
        $order->state = 'confirmed';
        $order->update();
        return new OrdersResource($order);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }


    public function statisticsMeals(){
        $response=[];
        $totalOfMealsByFunc=Meals::select([DB::raw('count(*) AS total'),'responsible_waiter_id AS resp'])->where('state','!=','active')->where('state','!=','terminated')->groupBy('responsible_waiter_id')
            ->get();
        $totalOfDays=Meals::select([DB::raw('DATE(start)')])->where('state','!=','active')->where('state','!=','terminated')
            ->groupBy(DB::raw('DATE(start)'))
            ->get();
        array_push($response,count($totalOfDays));
        array_push($response,$totalOfMealsByFunc);
        return Response::json($response, 200);
    }
    public function averageByCook(){
        $response=[];
        $totalOfDays=Orders::select([DB::raw('DATE(start)')])->where('state','!=','pending')->where('state','!=','confirmed')->where('state','!=','in preparation')->where('state','!=','prepared')
            ->groupBy(DB::raw('DATE(start)'))
            ->get();
        $totalOfOrdersByCook=Orders::select([DB::raw('count(*) AS total'),'responsible_cook_id AS resp'])->where('state','!=','confirmed')->where('state','!=','in preparation')->where('state','!=','prepared')->groupBy('responsible_cook_id')
            ->get();
        array_push($response,count($totalOfDays));
        array_push($response,$totalOfOrdersByCook);
        return Response::json($response, 200);
    }
    public function averageByWaiter(){
        $response=[];
        $totalOfDays=Orders::select([DB::raw('DATE(start)')])->where('state','!=','pending')->where('state','!=','confirmed')->where('state','!=','in preparation')->where('state','!=','prepared')
            ->groupBy(DB::raw('DATE(start)'))
            ->get();
        $totalOfOrdersByWaiter=Orders::join('meals', 'orders.meal_id', '=', 'meals.id')->select([DB::raw('count(*) AS total'),'meals.responsible_waiter_id AS resp'])
            ->where('orders.state','!=','pending')->where('orders.state','!=','confirmed')->where('orders.state','!=','in preparation')->where('orders.state','!=','prepared')
            ->groupBy('meals.responsible_waiter_id')
            ->get();
        array_push($response,count($totalOfDays));
        array_push($response,$totalOfOrdersByWaiter);
        return Response::json($response, 200);
    }

    public function statsCookOrders($id)
    {
        $day = array();
        $handled = array();

        $orders = Orders::where('responsible_cook_id', $id)->orderby('start')->get();
        $checkDate = date_create_from_format('Y-m-d H:i:s', Orders::where('responsible_cook_id', $id)->orderby('start')->first()->end)->format('d-m-Y');
        array_push($day, $checkDate);
        $i = 0;

        foreach ($orders as $order) {
            if ($order->end != null) {
                $orderDate = date_create_from_format('Y-m-d H:i:s', $order->end)->format('d-m-Y');
                if ($orderDate == $checkDate) {
                    $i += 1;
                } else {
                    array_push($day, $orderDate);
                    //$i = 0;
                    $checkDate = $orderDate;
                }
            }
        }
        array_push($handled, $i);
        $array = array(sizeof($day), $handled);

        return $array;
    }

    public function statsWaiterOrders($id)
    {
        $day = array();
        $handled = array();
        $orders = array();
        $meals = Meals::where('responsible_waiter_id', $id)->orderBy('start')->take(500)->get();
        foreach ($meals as $meal) {
            array_push($orders, $meal->orders()->get());
        }
        $checkDate = date_create_from_format('Y-m-d H:i:s', $meals->first()->orders()->first()->end)->format('d-m-Y');

        array_push($day, $checkDate);
        $i = 0;

        foreach ($orders as $meals) {
            foreach ($meals as $order) {
                if ($order['end'] != null) {
                    $orderDate = date_create_from_format('Y-m-d H:i:s', $order->end)->format('d-m-Y');
                    if ($orderDate == $checkDate) {
                        $i += 1;
                    } else {
                        array_push($handled, $i);
                        array_push($day, $orderDate);
                        $i = 0;
                        $checkDate = $orderDate;
                    }
                }
            }
        }
        $array = array($day, $handled);

        return $array;
    }


    public function storeMultiple(Request $request)
    {
        $request->validate([
            'edit_meal' => 'required',
            'items' => 'required',
        ]);

        $meal_id = $request['edit_meal'];

        $orderReturn = Collection::make();
        foreach ($request['items'] as $item_id) {
            $order = new Orders();
            $order->state = "pending";
            $order->item_id = $item_id;
            $order->meal_id = $meal_id;
            $order->start = Carbon::now();
            $order->save();
            $orderReturn->push($order);
        }
        return response()->json(OrdersResource::collection($orderReturn), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return OrderResource
     */
    public function update($id, Request $request)
    {
        $order = Orders::findOrFail($id);
        $order->update($request->all());
        return new OrdersResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ordersByMeal($id)
    {
        $orders = Orders::where('meal_id', $id)->get();
        return OrdersResource::collection($orders);

    }

    public function delete($id)
    {
        $order = Orders::findOrFail($id);
        $order->delete();
        return response()->json(null, 204);
    }
}
