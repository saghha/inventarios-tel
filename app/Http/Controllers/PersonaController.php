<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personas = \App\Persona::all();
        return response()->json($personas);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, \App\Persona::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $material = \App\Persona::create([
            'nombre' => $input['nombre'],
            'apellido_p' => $input['apellido_p'],
            'apellido_m' => $input['apellido_m'],
            'rol' => $input['rol'],
            'rut' => $input['rut'],
        ]);
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos ingresados correctamente',
            ]
        ]); 
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
        $input = json_decode($request->getContent(), true);
        $validator = Validator::make($input, \App\Persona::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $persona = \App\Persona::findOrFail($id);
        $persona->nombre = $input['nombre'];
        $persona->apellido_p = $input['apellido_p'];
        $persona->apellido_m = $input['apellido_m'];
        $persona->rut = $input['rut'];
        $persona->rol = $input['rol'];
        $persona->save();
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos ingresados correctamente',
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material = \App\Persona::find($id);
        $material->delete();
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos eliminados correctamente',
            ]
        ]);
    }
}
