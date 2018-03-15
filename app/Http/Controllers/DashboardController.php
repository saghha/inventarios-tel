<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('home');
    }

    public function get_token(){
        $api_token = Auth::user()->api_token;
        return response()->json(['api_token' => $api_token]);
    }
    public function get_username(){
        return Auth::user()->name;
    }

    public function get_user(){
        return Auth::user();
    }

    public function changePassword(Request $request){
        $input = json_decode($request->getContent(), true);
        $user = Auth::user();
        //$password = bcrypt($input['password']);
        if (!(Hash::check($input['password'], Auth::user()->password))) {
            // The passwords matches
            return response()->json([
                'result' => [
                    'type' => 'Error',
                    'message' => 'La contraseña actual no es correcta'
                ]
            ]);
        }
        if(strcmp($input['password'], $input['new']) == 0){
            //Current password and new password are same
            return response()->json([
                'result' => [
                    'type' => 'Error',
                    'message' => 'La contraseña no puede ser igual a la anterior'
                ]
            ]);
        }
        //dd($request);
        $validatedData = Validator::make($request->all(),[
            'password' => 'required',
            'new' => 'required|string|min:6|confirmed',
        ]);
        if($validatedData->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $user->password = bcrypt($input['new']);
        $user->save();
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'La contraseña fue cambiada correctamente.',
            ]
        ]);
        //return response()->json($user);
    }
}
