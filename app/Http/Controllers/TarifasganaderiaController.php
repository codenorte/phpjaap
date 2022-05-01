<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarifasganaderia;

use Illuminate\Support\Facades\DB;
use Exception;


class TarifasganaderiaController extends Controller
{
     /**
     * mostrar Tarifasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllTarifasganaderia(Request $request){
        if ($request->isJson()) {
            $objeto=Tarifasganaderia::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Tarifasganaderia',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Tarifasganaderia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdTarifasganaderia(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tarifasganaderia::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Tarifasganaderia id',
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
     * Crear Tarifasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createTarifasganaderia(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
             //transaccion
            DB::beginTransaction();

            try {
                $objeto = new Tarifasganaderia();

                $objeto->TARIFAMENSUAL=$data['TARIFAMENSUAL'];
                $objeto->DESCRIPCION=$data['DESCRIPCION'];
                $objeto->IVA=$data['IVA'];
                $objeto->estado=$data['estado'];
                
                $objeto->save();
                
                DB::commit();

                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Dato creada Tarifasganaderia',
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
     * Actualizar Tarifasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTarifasganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifasganaderia::find($id);
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
                        'message'=>'Tarifasganaderia Actualizada',
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
     * Eliminar Tarifasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTarifasganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifasganaderia::find($id);
                $objeto->delete();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'Dato Eliminada Tarifasganaderia',
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
     * mostrar ultimo dato de Tarifasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarifasganaderiaLatest(Request $request)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tarifasganaderia::latest()->first();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida Ultimo registro de Tarifasganaderia',
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
