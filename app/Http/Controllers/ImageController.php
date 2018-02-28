<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function imageUploadPost(Request $request){
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ]);

        $imageName = str_random(64).'.'.$request->photo->getClientOriginalExtension();
        $url = $request->file('photo')->storeAs('public/photos', $imageName);

        return response()->json([
            'url' => '/storage/photos/'.$imageName
        ]);
    }
}
