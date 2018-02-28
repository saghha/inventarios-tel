<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;

class ExcelUploadController extends Controller
{
    public function ExcelUploadPost(Request $request){
        $this->validate($request, [
            'excel' => 'required|mimes:xls,xlsx,csv'
        ]);
        $resultado = array();
        $file = $request->file('excel');
        $data = Excel::load($file, function($reader) {
            $reader->noHeading();
            $reader->ignoreEmpty();
        })->get();
        $flag= false;
        foreach ($data as $row) {
            $arreglo = $row;
            if(count($arreglo) == 6){
                if($flag){
                    if($arreglo[1] != null or $arreglo[1] != ""){
                        array_push($resultado, [
                            'nombre' => $arreglo[5],
                            'apellido_p' => $arreglo[3],
                            'apellido_m' => $arreglo[4],
                            'rol' => $arreglo[1],
                            'rut' => $arreglo[2],
                        ]);
                        $persona = \App\Persona::firstOrCreate(['rut' => $arreglo[2]],
                        [   'nombre' => $arreglo[5],
                            'apellido_p' => $arreglo[3],
                            'apellido_m' => $arreglo[4],
                            'rol' => $arreglo[1],
                        ]);
                    }
                }
                if($arreglo[1] === "ROL" ){
                    $flag = true;
                }
            }
        }
        // $personas = \App\Persona::all();
        
        // $encontrado = true;
        // foreach($personas as $persona){
        //     $flag = false;
        //     $encontrado = true;
        //     foreach($data as $row){
        //         if($flag){
        //             if($arreglo[1] != null or $arreglo[1] != ""){
        //                 if($arreglo[2] == $persona->rut){
        //                     $encontrado = false;
        //                 }
        //             }
        //         }
        //         if($arreglo[1] === "ROL"){
        //             $flag = true;
        //         }
        //     }
        // }
        return response()->json([
            'result' =>[
                'type' => 'Success',
                'message' => 'datos ingresados correctamente',
            ]
        ]);
    }
}
