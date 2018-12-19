<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Items;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\User;



class ImageController extends Controller
{
    public function uploadItemPhoto(Request $request)
    {
        $item = Items::findOrFail($request->get('id'));

        if ($item->photo_url) {
            Storage::disk('public')->delete('items/' . $item->photo_url);
        }
        $file = Input::file('images');
        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $uploadedFile = str_random(10) . '.' . $ext;

        if (!Storage::disk('public')->put('items/'.$uploadedFile, File::get($file))) {
            return response()->json([
                'message' => 'Problem uploading Item photo.',
                'status' => 422
            ], 422);
        }
        $item->photo_url = $uploadedFile;
        $item->save();


        return response()->json(
            [
                'status' => 201,
                'success' => 'Item photo updated.',
                'photo' => $uploadedFile
            ]
        );
    }

    public function uploadUserPhoto(Request $request)
    {
        $user = User::findOrFail($request->get('id'));

        if ($user->photo_url) {
            Storage::disk('public')->delete('profiles/' . $user->photo_url);
        }
        $file = Input::file('images');
        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $uploadedFile = str_random(10) . '.' . $ext;

        if (!Storage::disk('public')->put('profiles/' . $uploadedFile, File::get($file))) {
            return response()->json([
                'message' => 'Problem uploading profiles photo.',
                'status' => 422
            ], 422);
        }
        $user->photo_url = $uploadedFile;
        $user->save();


        return response()->json(
            [
                'status' => 201,
                'success' => 'Profile photo updated.',
                'photo' => $uploadedFile
            ]
        );
    }
}
