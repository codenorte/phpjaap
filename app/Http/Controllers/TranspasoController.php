<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transpaso;

use Illuminate\Support\Facades\DB;
use Exception;


class TranspasoController extends Controller
{
     /**
     * mostrar Transpaso
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllTranspaso(Request $request){
        if ($request->isJson()) {
            $objeto=Transpaso::All();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Transpaso',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Transpaso por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdTranspaso(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Transpaso::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Transpaso id',
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
     * Crear Transpaso
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createTranspaso(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();

            DB::beginTransaction();

            try {

                $objeto= new Transpaso();

                $objeto->fecha_transpaso = $data['fecha_transpaso'];
                $objeto->detalle = $data['detalle'];
                $objeto->estado = $data['estado'];
                $objeto->IDUSUARIO = $data['IDUSUARIO'];
                $objeto->IDMEDIDOR = $data['IDMEDIDOR'];
                $objeto->save();
                
                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Dato creada',
                    'code'=>201,
                    'data'=>$objeto
                ),201);

            } catch (Exception $e) {
                DB::rollback();
                //throw $e;
                return $e;
            } catch (\Throwable $e) {
                DB::rollback();
                //throw $e;
                return $e;
            }
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Transpaso
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTranspaso(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Transpaso::findOrFail($id);
                $data = $request->json()->all();

                DB::beginTransaction();

                try {

                    $objeto->fecha_transpaso = $data['fecha_transpaso'];
	                $objeto->detalle = $data['detalle'];
	                $objeto->estado = $data['estado'];
	                $objeto->IDUSUARIO = $data['IDUSUARIO'];
	                $objeto->IDMEDIDOR = $data['IDMEDIDOR'];
                
                    $objeto->save();
                    DB::commit();
                    
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Update data',
                        'message'=>'Dato Actualizada',
                        'code'=>200,
                        'data'=>$objeto
                    ),200);
                } catch (Exception $e) {
                    DB::rollback();
                    //throw $e;
                    return $e;
                } catch (\Throwable $e) {
                    DB::rollback();
                    //throw $e;
                    return $e;
                }
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Transpaso
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTranspaso(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Transpaso::find($id);
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
