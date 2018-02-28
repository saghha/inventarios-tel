<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materiales = \App\Material::paginate(10);
        return response()->json($materiales);
    }
    public function todo(Request $request){
        $materiales = \App\Material::all();
        $prestamos = \App\Prestamo::all();
        $materiales_prestados = array();
        $materiales_respuesta = array();
        foreach($prestamos as $prestamo){
            if($prestamo['fecha_devolucion'] == null){
                $materiales_prestados = $prestamo->getMateriales;
                foreach($materiales_prestados as $value){
                    foreach($materiales as $material){
                        if($material['id'] == $value['id']){
                            $material['cantidad'] -= $value->pivot['cantidad'];
                        }
                    }
                }
            } 
        }
        foreach($materiales as $material){
            if($material['cantidad'] > 0){
                array_push($materiales_respuesta, $material);
            }
        }
        return response()->json($materiales_respuesta);
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
        $validator = Validator::make($input, \App\Material::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $material = \App\Material::create([
            'nombre' => $input['nombre'],
            'descripcion' => $input['descripcion'],
            'imagen' => $input['imagen'],
            'cantidad' => $input['cantidad'],
            'ubicacion' => $input['ubicacion'],
            'observaciones' => $input['observaciones'],
            'sku' => $input['sku']
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
        $validator = Validator::make($input, \App\Material::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $material = \App\Material::findOrFail($id);
        $material->nombre = $input['nombre'];
        $material->descripcion = $input['descripcion'];
        $material->imagen = $input['imagen'];
        $material->cantidad = $input['cantidad'];
        $material->ubicacion = $input['ubicacion'];
        $material->observaciones = $input['observaciones'];
        $material->sku = $input['sku'];
        $material->save();
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
        $material = \App\Material::find($id);
        $material->delete();
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos eliminados correctamente',
            ]
        ]);
    }
}
