<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Items;
use App\Http\Resources\ItemsResource as ItemsResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;


class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = Items::paginate(12);
        return ItemsResource::collection($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Items();
        return response()->json(new ItemsResource($item), 201);
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
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'type' => 'required',
            'images' => 'required'
        ]);

        $item = new Items();

        $file = Input::file('images');
        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $uploadedFile = str_random(10) . '.' . $ext;

        Storage::disk('public')->put('items/'.$uploadedFile, File::get($file));
        $item->photo_url = $uploadedFile;
        $item->name = $data['name'];
        $item->price = $data['price'];
        $item->description = $data['description'];
        $item->type = $data['type'];
        $item->save();
        return response()->json(new ItemsResource($item), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ItemsResource(Items::find($id));
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
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);

        $item = Items::findOrFail($id);
        $item->update($request->all());
        return new ItemsResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $item = Items::findOrFail($id);
        $item->forceDelete();
        return response()->json(new ItemsResource($item), 204);
    }
}
