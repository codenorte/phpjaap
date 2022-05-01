<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoriasmat;

class CategoriasmatController extends Controller
{
     /**
     * mostrar Categoriasmat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllCategoriasmat(Request $request){
        if ($request->isJson()) {
            $objeto=Categoriasmat::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Categoriasmat',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Categoriasmat por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdCategoriasmat(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Categoriasmat::findOrFail($id);
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
     * Crear Categoriasmat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createCategoriasmat(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
            $objeto=Categoriasmat::create([
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
     * Actualizar Categoriasmat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCategoriasmat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Categoriasmat::findOrFail($id);
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
     * Eliminar Categoriasmat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCategoriasmat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Categoriasmat::findOrFail($id);
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
