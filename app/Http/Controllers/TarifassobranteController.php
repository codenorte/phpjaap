<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarifassobrante;

use Illuminate\Support\Facades\DB;
use Exception;


class TarifassobranteController extends Controller
{
     /**
     * mostrar Tarifassobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllTarifassobrante(Request $request){
        if ($request->isJson()) {
            $objeto=Tarifassobrante::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Tarifassobrante',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Tarifassobrante por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdTarifassobrante(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tarifassobrante::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Tarifassobrante id',
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
     * Crear Tarifassobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createTarifassobrante(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
             //transaccion
            DB::beginTransaction();

            try {
                $objeto = new Tarifassobrante();

                $objeto->TARIFAMENSUAL=$data['TARIFAMENSUAL'];
                $objeto->DESCRIPCION=$data['DESCRIPCION'];
                $objeto->IVA=$data['IVA'];
                $objeto->estado=$data['estado'];

                $objeto->save();
                
                DB::commit();

                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Dato creada Tarifassobrante',
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
     * Actualizar Tarifassobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTarifassobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifassobrante::find($id);
                $data = $request->json()->all();
                
                //transaccion
                DB::beginTransaction();

                try {

                    $objeto->TARIFAMENSUAL=$data['TARIFAMENSUAL'];
                    $objeto->DESCRIPCION=$data['DESCRIPCION'];
                    $objeto->IVA=$data['IVA'];
                    $objeto->estado=$data['estado'];

                    $objeto->save();
                    
                    DB::commit();

                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Update data',
                        'message'=>'Tarifassobrante Actualizada',
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
     * Eliminar Tarifassobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTarifassobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifassobrante::find($id);
                $objeto->delete();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'Dato Eliminada Tarifassobrante',
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
     * mostrar ultimo dato de Tarifassobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarifassobranteLatest(Request $request)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tarifassobrante::latest()->first();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida Ultimo registro de Tarifassobrante',
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
}
