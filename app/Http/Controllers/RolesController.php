<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;

class RolesController extends Controller
{
     /**
     * mostrar Roles
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllRoles(Request $request){
        if ($request->isJson()) {
            $objeto=Roles::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos roles',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Roles por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdRoles(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Roles::findOrFail($id);
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
     * Crear Roles
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createRoles(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
            $objeto=Roles::create([
                'nombre'=>$data['nombre'],
                'descripcion'=>$data['descripcion'],
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
     * Actualizar Roles
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editRoles(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Roles::findOrFail($id);
                $data = $request->json()->all();
                $objeto->nombre = $data['nombre'];
                $objeto->descripcion = $data['descripcion'];
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
     * Eliminar Roles
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyRoles(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Roles::findOrFail($id);
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
