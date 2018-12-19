<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource as UserResource;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->get();
        return UserResource::collection($users);
    }

    public function getBlockUsers()
    {
        $usersBlock = User::where('blocked', 1)->paginate(5);
        return UserResource::collection($usersBlock);
    }

    public function getUnblockUsers()
    {
        $usersUnblock = User::where('blocked', 0)->paginate(5);
        return UserResource::collection($usersUnblock);
    }

    public function unblockUser($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = 0;
        $user->save();
        return new UserResource($user);
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->blocked = 1;
        $user->save();
        return new UserResource($user);
    }


    public function getUser(Request $request)
    {
        return new UserResource($request->user());
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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'type' => 'required',
        ]);

        $user = new User();
        $user->password = str_random(10);
        $user->fill($request->all());
        $user->save();
        return response()->json(new UserResource($user), 201);
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
     * @param  int $idnp
     *     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'photo_url' => '',
            'last_shift_start' => '',
            'last_shift_end' => '',
            'shift_active' => ''
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
