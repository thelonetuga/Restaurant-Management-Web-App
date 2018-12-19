<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\Http\Resources\OrdersResource as OrdersResource;

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

    public function waiterPendingConfirmedOrders($responsible_waiter_id)
    {
        $pending = Orders::where([['state', '<>', 'prepared'],
            ['state', '<>', 'delivered'],
            ['state', '<>', 'not delivered'], ['state', '<>', 'in preparation']])->get()->reject(function ($order) use ($responsible_waiter_id) {
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

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'edit_meal' => 'required',
            'items' => 'required',
        ]);

        $meal_id = $request['edit_meal'];

        $orderReturn = null;
        foreach ($request['items'] as $item_id) {
            $order = new Orders();
            $order->state = "pending";
            $order->item_id = $item_id;
            $order->meal_id = $meal_id;
            $order->start = now();
            $order->save();
            $orderReturn = $order;
        }

        return response()->json(new OrdersResource($orderReturn), 201);
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
