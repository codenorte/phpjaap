<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipomat;

class TipomatController extends Controller
{
     /**
     * mostrar Tipomat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllTipomat(Request $request){
        if ($request->isJson()) {
            $objeto=Tipomat::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Tipomat',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Tipomat por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdTipomat(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tipomat::findOrFail($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, rol id',
                    'code'=>200,
                    'data'=>$objeto
                ),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Crear Tipomat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createTipomat(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
            $objeto=Tipomat::create([
                'nombre'=>$data['nombre'],
                'detalle'=>$data['detalle'],
                'estado'=>$data['estado']
            ]);
             return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Create data',
                'message'=>'Dato creada',
                'code'=>201,
                'data'=>$objeto
            ),201);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Tipomat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTipomat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tipomat::findOrFail($id);
                $data = $request->json()->all();
                $objeto->nombre = $data['nombre'];
                $objeto->detalle = $data['detalle'];
                $objeto->estado = $data['estado'];
                $objeto->save();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Update data',
                    'message'=>'Dato Actualizada',
                    'code'=>200,
                    'data'=>$objeto
                ),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Tipomat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTipomat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tipomat::findOrFail($id);
                $objeto->delete();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'Dato Eliminada',
                    'code'=>200,
                    'data'=>$objeto
                ),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
}
