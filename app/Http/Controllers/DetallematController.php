<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detallemat;

class DetallematController extends Controller
{
     /**
     * mostrar Detallemat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllDetallemat(Request $request){
        if ($request->isJson()) {
            $objeto=Detallemat::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Detallemat',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Detallemat por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdDetallemat(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Detallemat::findOrFail($id);
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
     * Crear Detallemat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createDetallemat(Request $request){
        if ($request->isJson()) {
            
            $data=$request->json()->all();

            $objeto = new Detallemat();

            $objeto->nombre = $data['nombre'];
			$objeto->detalle = $data['detalle'];
			$objeto->codigo = $data['codigo'];
			$objeto->serial = $data['serial'];
			$objeto->estado = 1;
			$objeto->materiales_id = $data['materiales_id'];
			$objeto->tipomat_id = $data['tipomat_id'];
            
			$objeto->save();

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
     * Actualizar Detallemat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDetallemat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallemat::findOrFail($id);
                $data = $request->json()->all();

                $objeto->nombre = $data['nombre'];
				$objeto->detalle = $data['detalle'];
				$objeto->codigo = $data['codigo'];
				$objeto->serial = $data['serial'];
				$objeto->estado = 1;
				$objeto->materiales_id = $data['materiales_id'];
				$objeto->tipomat_id = $data['tipomat_id'];

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
     * Eliminar Detallemat
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyDetallemat(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallemat::findOrFail($id);
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
