<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materiales;

class MaterialesController extends Controller
{
     /**
     * mostrar Materiales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllMateriales(Request $request){
        if ($request->isJson()) {
            $objeto=Materiales::
            with('categoriasmat')
            ->where('estado',1)
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Materiales',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Materiales por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdMateriales(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Materiales::findOrFail($id);
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
     * Crear Materiales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createMateriales(Request $request){
        if ($request->isJson()) {
            
            $data=$request->json()->all();

            $objeto = new Materiales();
            $objeto->nombre = $data['nombre'];
			$objeto->detalle = $data['detalle'];
			$objeto->codigo = $data['codigo'];
			$objeto->stock = $data['stock'];
			$objeto->total = $data['total'];
			$objeto->estado = 1;
			$objeto->categoriasmat_id = $data['categoriasmat_id'];
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
     * Actualizar Materiales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editMateriales(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Materiales::findOrFail($id);
                $data = $request->json()->all();

                $objeto->nombre = $data['nombre'];
				$objeto->detalle = $data['detalle'];
				$objeto->codigo = $data['codigo'];
				$objeto->stock = $data['stock'];
				$objeto->total = $data['total'];
				$objeto->estado = $data['estado'];
				$objeto->categoriasmat_id = $data['categoriasmat_id'];

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
     * Eliminar Materiales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMateriales(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Materiales::findOrFail($id);
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

    /**
     * Buscar material por nombre
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarNombreMateriales(Request $request, $material_nombre)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Materiales::
                where('estado',1)
                ->where('nombre','LIKE','%'.$material_nombre.'%')
                //->orWhere('codigo','LIKE','%'.$material_nombre.'%')
                ->get();
                if(count($objeto)>0){
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Dato encontrado',
                        'message'=>'Material encontrado',
                        'code'=>200,
                        'total'=>count($objeto),
                        'data'=>$objeto
                    ),200);
                }
                else{
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Dato no encontrado',
                        'message'=>'Material no existe',
                        'code'=>300,
                        'total'=>count($objeto),
                        'data'=>$objeto
                    ),200);
                }
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
}
