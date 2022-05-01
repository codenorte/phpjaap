<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detallefacturasobrante;
use App\Models\Tarifassobrante;
use App\Models\Controlaniomessobrante;
use App\Models\Aguasobrante;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class DetallefacturasobranteController extends Controller
{
     /**
     * mostrar Detallefacturasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDetallefacturasobrante(Request $request){
        if ($request->isJson()) {
            $objeto=Detallefacturasobrante::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Detallefacturasobrante',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Detallefacturasobrante por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdDetallefacturasobrante(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Detallefacturasobrante::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Detallefacturasobrante id',
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
     * Crear Detallefacturasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createDetallefacturasobrante(Request $request,$controlaniomessobrante_id){
        if ($request->isJson()) {
            $data=$request->json()->all();

            //buscar controlanioganaderia
            $controlaniomessobrante = Controlaniomessobrante::find($controlaniomessobrante_id);
            //buscar tarifas
            $tarifassobrante = Tarifassobrante::latest()->first();
             //transaccion
            DB::beginTransaction();

            try {


                $i = 0;
                $array= array();
                foreach ($data as $med) {

                    $objeto = new Detallefacturasobrante();


                    if(!empty($med['checked'])){
                        
                        $objeto->IDTARIFASSOBRANTE=$tarifassobrante['IDTARIFASSOBRANTE'];
                        $objeto->IDAGUASOBRANTE=$med['IDAGUASOBRANTE'];
                        $objeto->ANIOMES=$controlaniomessobrante['aniomes'];
                        $objeto->SUBTOTAL=$tarifassobrante['TARIFAMENSUAL'];
                        $objeto->TOTAL=$tarifassobrante['TARIFAMENSUAL'];
                        $objeto->OBSERVACION=$controlaniomessobrante['detalle'];
                        $objeto->DETALLE='CONSUMO';
                        $objeto->estado=0;
                        $objeto->controlaniomessobrante_id=$controlaniomessobrante_id;
                        
                        $objeto->save();
                        
                        $array[$i]=$objeto;
                        $i++;
                    }
                }

                
                DB::commit();

	             return response()->json(array(
	                'status'=>'sucsess',
	                'title'=>'Create data',
	                'message'=>'Dato creada Detallefacturasobrante',
	                'code'=>201,
	                'data'=>$data,
                    'total'=>count($array),
                    'datas'=>$array
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
     * Actualizar Detallefacturasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDetallefacturasobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallefacturasobrante::find($id);
                $data = $request->json()->all();
                
                //transaccion
                DB::beginTransaction();

                try {

	                $objeto->BASE = $data['BASE'];
					$objeto->TARBASE = $data['TARBASE'];
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
	                    'message'=>'Detallefacturasobrante Actualizada',
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
     * Eliminar Detallefacturasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyDetallefacturasobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallefacturasobrante::find($id);
                $objeto->delete();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'Dato Eliminada Detallefacturasobrante',
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
     * Obtener lista de facturas detallesobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturasobrante(Request $request,$aguasobrante_id){
        if ($request->isJson()) {
            $porpagar=Aguasobrante::
            select('detallefacturasobrante.*','tarifassobrante.*')
            ->join('detallefacturasobrante','detallefacturasobrante.IDAGUASOBRANTE','aguasobrante.IDAGUASOBRANTE')
            ->join('tarifassobrante','detallefacturasobrante.IDTARIFASSOBRANTE','tarifassobrante.IDTARIFASSOBRANTE')
            ->where([['aguasobrante.IDAGUASOBRANTE',$aguasobrante_id],['detallefacturasobrante.estado',0]])
            //->orderBy('ANIOMES','DESC')
            ->get();

            if(count($porpagar)==0){
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'No data',
                    'message'=>'Detallefactura por cobrar no encontrado',
                    'code'=>200,
                    'total'=>null,
                    'data'=>null
                ),200);
            }

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Detallefactura por cobrar',
                'code'=>200,
                'total'=>count($porpagar),
                'data'=>$porpagar
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
