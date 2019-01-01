<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealsWithOrdersResource;
use App\Invoices;
use Illuminate\Http\Request;
use App\Meals;
use App\Orders;
use App\Http\Resources\MealsResource as MealsResource;

class MealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meals = Meals::paginate(10);
        return MealsResource::collection($meals);
    }

    public function getMealsActiveAndTerminated()
    {
        $meals = Meals::where('state','active')->orwhere('state','terminated')->paginate(5);
        return MealsWithOrdersResource::collection($meals);
    }

    public function getMealsAByState($state)
    {
        if ($state === 'active'){
            $meals = Meals::where('state', 'active')->paginate(10);
            return MealsWithOrdersResource::collection(  $meals);
        }elseif ($state === 'terminated'){
            $meals = Meals::where('state', 'terminated')->paginate(10);
            return MealsWithOrdersResource::collection(  $meals);
        }elseif ($state === 'not paid'){
            $meals = Meals::where('state', 'not paid')->paginate(10);
            return MealsWithOrdersResource::collection(  $meals);
        }else{
            $meals = Meals::where('state', 'paid')->paginate(10);
            return MealsWithOrdersResource::collection(  $meals);
        }
    }
    public function getMealsAByDate($date)
    {
        $meals = Meals::whereDate('created_at', $date)->paginate(10);
        return MealsWithOrdersResource::collection($meals);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $meal = new Meals();
        $meal->fill($request->all());
        $meal->save();
        dd($meal);
        return MealsResource($meal);
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


    public function changeStateToNotPaid($id){
        $meal = Meals::findOrFail($id);
        $invoice = Invoices::where('meal_id',$id)->first();
        $orders_changes = Orders::where([['meal_id',$id],['state', '<>', 'delivered'], ['state', '<>', 'not delivered']])->get();
        foreach ($orders_changes as $order) {
            $order->state = "not delivered";
            $order->update();
        }
        $meal->state = "not paid";
        $invoice->state = "not paid";
        $invoice->update();
        $meal->update();
        return new MealsResource($meal);
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

    public function myMeals($id)
    {
        $meal = Meals::where([['responsible_waiter_id', '=', $id], ['state', '=', 'active']])->paginate(10);
        return MealsResource::collection($meal);

    }





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $meal = Meals::findOrFail($id);

        $meal->update($request->all());
        return new MealsResource($meal);
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
}
