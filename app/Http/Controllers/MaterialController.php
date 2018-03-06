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
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getMatPrestadosxAlumno']]);
    }
    public function index()
    {
        $materiales = \App\Material::paginate(10);
        return response()->json($materiales);
    }
    
    public function countMaterialesPrestados(Request $request){
        $materiales = \App\Material::all();
        $prestamos = \App\Prestamo::all();
        $materiales_prestados = array();
        $countMateriales = 0;
        foreach($prestamos as $prestamo){
            if($prestamo['fecha_devolucion'] == null){
                $materiales_prestados = $prestamo->getMateriales;
                foreach($materiales_prestados as $value){
                    foreach($materiales as $material){
                        if($material['id'] == $value['id']){
                            $countMateriales += $value->pivot['cantidad'];
                        }
                    }
                }
            } 
        }
        return response()->json([
            'prestados' => $countMateriales
        ]);
    }

    public function getMaterialesPrestados(Request $request){
        $materiales = \App\Material::all();
        $prestamos = \App\Prestamo::all();
        $materiales_prestados = array();
        $prestados = array();
        foreach($prestamos as $prestamo){
            if($prestamo['fecha_devolucion'] == null){
                $materiales_prestados = $prestamo->getMateriales;
                foreach($materiales_prestados as $value){
                    foreach($materiales as $material){
                        if($material['id'] == $value['id']){
                            if(isset($material['prestado'])){
                                $material['prestado'] += $value->pivot['cantidad'];
                            }
                            else{
                                $material['prestado'] = $value->pivot['cantidad'];
                            }
                            if(isset($prestados[$material['nombre']])){
                                $persona = \App\Persona::find($prestamo['id_persona']);
                                $prestados[$material['nombre']]['cantidad'] += $value->pivot['cantidad'];
                                array_push($prestados[$material['nombre']]['personas'],[
                                    'nombre_persona' => $persona['nombre'],
                                    'apellido_p' => $persona['apellido_p'],
                                    'apellido_m' => $persona['apellido_m'],
                                    'rol' => $persona['rol'],
                                    'cantidad' => $value->pivot['cantidad']    
                                ]);
                            }
                            else{
                                $persona = \App\Persona::find($prestamo['id_persona']);
                                $prestados[$material['nombre']] = array();
                                $temp = [
                                    'nombre_persona' => $persona['nombre'],
                                    'apellido_p' => $persona['apellido_p'],
                                    'apellido_m' => $persona['apellido_m'],
                                    'rol' => $persona['rol'],
                                    'cantidad' => $value->pivot['cantidad']];
                                $prestados[$material['nombre']]['descripcion'] = $material['descripcion'];
                                $prestados[$material['nombre']]['cantidad'] = $value->pivot['cantidad'];
                                $prestados[$material['nombre']]['id'] = $material['id'];
                                $prestados[$material['nombre']]['nombre'] = $material['nombre'];
                                $prestados[$material['nombre']]['personas'] = array();
                                array_push($prestados[$material['nombre']]['personas'], $temp);
                            }
                        }
                    }
                }
            } 
        }
        $retorno =  array();
        foreach($prestados as $value){
            array_push($retorno, $value);
        }
        return response()->json($retorno);
    }

    public function getMatPrestadosxAlumno(Request $request, $id){
        $materiales = \App\Material::all();
        $prestamos = \App\Prestamo::all();
        $materiales_prestados = array();
        $prestados = array();
        foreach($prestamos as $prestamo){
            if($prestamo['fecha_devolucion'] == null and $prestamo['id_persona'] == $id){
                $materiales_prestados = $prestamo->getMateriales;
                foreach($materiales_prestados as $value){
                    foreach($materiales as $material){
                        if($material['id'] == $value['id']){
                            if(isset($material['prestado'])){
                                $material['prestado'] += $value->pivot['cantidad'];
                            }
                            else{
                                $material['prestado'] = $value->pivot['cantidad'];
                            }
                            if(isset($prestados[$material['nombre']])){
                                $persona = \App\Persona::find($prestamo['id_persona']);
                                $prestados[$material['nombre']]['cantidad'] += $value->pivot['cantidad'];
                                // array_push($prestados[$material['nombre']]['personas'],[
                                //     'nombre_persona' => $persona['nombre'],
                                //     'apellido_p' => $persona['apellido_p'],
                                //     'apellido_m' => $persona['apellido_m'],
                                //     'rol' => $persona['rol'],
                                //     'cantidad' => $value->pivot['cantidad']    
                                // ]);
                            }
                            else{
                                $persona = \App\Persona::find($prestamo['id_persona']);
                                $prestados[$material['nombre']] = array();
                                $temp = [
                                    'nombre_persona' => $persona['nombre'],
                                    'apellido_p' => $persona['apellido_p'],
                                    'apellido_m' => $persona['apellido_m'],
                                    'rol' => $persona['rol'],
                                    'cantidad' => $value->pivot['cantidad']];
                                $prestados[$material['nombre']]['descripcion'] = $material['descripcion'];
                                $prestados[$material['nombre']]['cantidad'] = $value->pivot['cantidad'];
                                $prestados[$material['nombre']]['id'] = $material['id'];
                                $prestados[$material['nombre']]['nombre'] = $material['nombre'];
                                $prestados[$material['nombre']]['personas'] = array();
                                //array_push($prestados[$material['nombre']]['personas'], $temp);
                            }
                        }
                    }
                }
            } 
        }
        $retorno =  array();
        foreach($prestados as $value){
            array_push($retorno, $value);
        }
        return response()->json($retorno);
    }

    public function mostRequired(){
        $materiales = \App\Material::all();
        $prestamos = \App\Prestamo::all();
        $materiales_prestados = array();
        $prestados_porcentaje = array();
        $mas_representativos = array();
        foreach($prestamos as $prestamo){
            if($prestamo['fecha_devolucion'] == null){
                $materiales_prestados = $prestamo->getMateriales;
                foreach($materiales_prestados as $value){
                    foreach($materiales as $material){
                        if($material['id'] == $value['id']){
                            if(isset($material['prestado'])){
                                $material['prestado'] += $value->pivot['cantidad'];
                            }
                            else{
                                $material['prestado'] = $value->pivot['cantidad'];
                            }
                        }
                    }
                }
            } 
        }
        foreach($materiales as $material){
            $porcentaje = (1-(($material->cantidad - $material->prestado)/$material->cantidad))*100;
            $prestados_porcentaje[$material->nombre] = ['porcentaje' => $porcentaje, 'cantidad' => $material->cantidad];
        }
        arsort($prestados_porcentaje);
        $count = 0;
        foreach($prestados_porcentaje as $key => $prestado){
            //dd($prestado['porcentaje']);
            if($count < 5){
                array_push($mas_representativos,['nombre' => $key, 'porcentaje' => $prestado['porcentaje'], 'cantidad' => $prestado['cantidad']]);
                $count += 1;
            }
        }
        return response()->json($mas_representativos);
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
        $prestados = 0;
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
        $prestamos = \App\Prestamo::all();
        foreach($prestamos as $prestamo){
            $materiales_prestados = $prestamo->getMateriales;
            if($prestamo['fecha_devolucion'] == null){
                foreach($materiales_prestados as $value){
                    if($id == $value->pivot['id_material']){
                        $prestados += $value->pivot['cantidad'];
                    }
                }
            }
        }
        //dd($prestados);
        if($prestados > $input['cantidad']){
            return response()->json([
                'result' =>[
                    'type' => 'Error',
                    'message' => 'No puede ingresar menos cantidad de materiales de los prestados',
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
        $prestamos = \App\Prestamo::all();
        foreach($prestamos as $prestamo){
            $materiales_prestados = $prestamo->getMateriales;
            if($prestamo['fecha_devolucion'] == null){
                foreach($materiales_prestados as $value){
                    if($id == $value->pivot['id_material']){
                        return response()->json([
                            'result' =>[
                                'type' => 'Error',
                                'message' => 'Existen prestamos vigentes de este material',
                            ] 
                        ]);
                    }
                }
            }
        }
        $material->delete();
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos eliminados correctamente',
            ]
        ]);
    }
}
