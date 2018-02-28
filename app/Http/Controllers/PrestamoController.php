<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prestamos = \App\Prestamo::all();
        //$retorno['prestamos'] = $prestamos;
        foreach($prestamos as $prestamo){
            $materiales = $prestamo->getMateriales;
            $persona = \App\Persona::findOrFail($prestamo['id_persona']);
            $prestamo['nombre_persona'] = $persona['nombre'];
            $prestamo['apellido_p'] = $persona['apellido_p'];
            $prestamo['apellido_m'] = $persona['apellido_m'];

            // foreach($materiales as $material){
            //     if(!isset($retorno[$prestamo['id']])){
            //         $retorno[$prestamo['id']] = [$material];
            //     }
            //     else{
            //         array_push($retorno[$prestamo['id']],$material);
            //     }
            // }
        }
        return response()->json($prestamos);
    }

    public function onlyAvaibles(){
        $prestamos = \App\Prestamo::all();
        $retorno = array();
        foreach($prestamos as $prestamo){
            $materiales = $prestamo->getMateriales;
            if($prestamo['fecha_devolucion'] == null){
                $persona = \App\Persona::findOrFail($prestamo['id_persona']);
                $prestamo['nombre_persona'] = $persona['nombre'];
                $prestamo['apellido_p'] = $persona['apellido_p'];
                $prestamo['apellido_m'] = $persona['apellido_m'];
                array_push($retorno, $prestamo);  
            }
        }
        return response()->json($retorno);
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
        $validator = Validator::make($input, \App\Prestamo::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $prestamo = \App\Prestamo::create([
            'id_persona' => $input['id_persona'],
            'fecha_prestamo' => $input['fecha_prestamo'],
            'fecha_esperada_devolucion' => $input['fecha_esperada_devolucion'],
            'comentarios' => $input['comentarios']
        ]);
        foreach($input['materiales'] as $material){
            $prestamo->getMateriales()->attach($material['id'],['sku'=> $material['sku'], 'cantidad' => $material['cantidad']]);
        }
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
        $validator = Validator::make($input, \App\Prestamo::$rules);
        if($validator->fails()){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'datos ingresados no validos',
                    'errors' => $validator->errors()
                ]
            ]);
        }
        $prestamo = \App\Prestamo::findOrFail($id);
        $prestamo->id_persona = $input['id_persona'];
        $prestamo->fecha_prestamo = $input['fecha_prestamo'];
        $prestamo->fecha_esperada_devolucion = $input['fecha_esperada_devolucion'];
        $prestamo->fecha_devolucion = $input['fecha_devolucion'];
        $prestamo->comentarios = $input['comentarios'];
        $prestamo->save();
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
