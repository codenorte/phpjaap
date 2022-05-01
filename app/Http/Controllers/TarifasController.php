<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarifas;

use Illuminate\Support\Facades\DB;
use Exception;

class TarifasController extends Controller
{
     /**
     * mostrar Tarifas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllTarifas(Request $request){
        if ($request->isJson()) {
            $objeto=Tarifas::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Tarifas',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Tarifas por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdTarifas(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Tarifas::findOrFail($id);
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
     * Crear Tarifas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createTarifas(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
            $objeto=Tarifas::create([
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
     * Actualizar Tarifas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTarifas(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifas::find($id);
                $data = $request->json()->all();
                
                //transaccion
                DB::beginTransaction();

                try {

	                $objeto->BASE = $data['BASE'];
                    $objeto->TARBASE = $data['TARBASE'];
					$objeto->APORTEMINGA = $data['APORTEMINGA'];
					$objeto->DESCRIPCION = $data['DESCRIPCION'];
					$objeto->VALOREXCESO = $data['VALOREXCESO'];
					$objeto->ALCANTARRILLADO = $data['ALCANTARRILLADO'];
					$objeto->IVA = $data['IVA'];
					$objeto->estado = $data['estado'];

	                $objeto->save();
	                
	                DB::commit();

	                return response()->json(array(
	                    'status'=>'sucsess',
	                    'title'=>'Update data',
	                    'message'=>'Tarifa Actualizada',
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
     * Eliminar Tarifas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyTarifas(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Tarifas::findOrFail($id);
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
