<?php

namespace App\Http\Controllers;

use App\Meals;
use Illuminate\Http\Request;
use App\Restaurant_tables;
use App\Http\Resources\TablesResource as TablesResource;

class TablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TablesResource::collection(Restaurant_tables::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFreeTables()
    {
        $tables = Restaurant_tables::all();

        $list = array();

        foreach($tables as $table){
            if(Meals::where([['state','=' ,'active'],['table_number', '=', $table->table_number]])->first() == null){
                $item = $tables->find('table_name',$table->table_number);
                array_push ( $list ,$item);
            }
        }


        return collect($list);
    }

    public function create()
    {
        $table = new Restaurant_tables();
        return response()->json(new TablesResource($table), 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required'
        ]);

         $table = new Restaurant_tables();
         $table->fill($request->all());
         $table->save();
        return response()->json(new TablesResource( $table), 201);
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
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'table_number' => 'required',
        ]);

        $table = Restaurant_tables::where('table_number', $id)->get();
        $table->table_number = $data['table_number'];
        return new TablesResource($table);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $table = Restaurant_tables::where('table_number','=', $id)->get();
        $table->each->delete();
        return response()->json(null, 204);
    }
}
