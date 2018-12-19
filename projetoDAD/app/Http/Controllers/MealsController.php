<?php

namespace App\Http\Controllers;

use App\Http\Resources\MealsWithOrdersResource;
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

/*
    public function getMealsActiveAndTerminated()
    {
        $meals = Meals::join('orders','meals.id','=','orders.meal_id')
            ->join('items','items.id','=','orders.item_id')
            ->select('meals.*','orders.*','orders.state','items.name')->where('meals.state','active')->orwhere('meals.state','terminated')
            ->paginate(10);
        return MealsResource::collection($meals);
    }*/

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
        //
        $meal = new Meals();
        $meal->fill($request->all());
        $meal->save();
        return response()->json(new MealsResource($meal), 201);
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
