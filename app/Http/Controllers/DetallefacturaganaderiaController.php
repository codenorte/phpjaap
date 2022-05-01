<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Aguaganaderia;
use App\Models\Detallefacturaganaderia;
use App\Models\Controlaniomesganaderia;
use App\Models\Tarifasganaderia;
use App\Models\Facturasganaderia;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

class DetallefacturaganaderiaController extends Controller
{
     /**
     * mostrar Detallefacturaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllDetallefacturaganaderia(Request $request){
        if ($request->isJson()) {
            $objeto=Detallefacturaganaderia::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Detallefacturaganaderia',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Detallefacturaganaderia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdDetallefacturaganaderia(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Detallefacturaganaderia::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Detallefacturaganaderia id',
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
     * Crear Detallefacturaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createDetallefacturaganaderia(Request $request,$controlaniomesganaderia_id){
        if ($request->isJson()) {
            $data=$request->json()->all();
            //buscar controlanioganaderia
            $controlaniomesganaderia = Controlaniomesganaderia::find($controlaniomesganaderia_id);
            //buscar tarifas
            $tarifasganaderia = Tarifasganaderia::latest()->first();
            
             //transaccion
            DB::beginTransaction();

            try {

                $i = 0;
                $array= array();
                foreach ($data as $med) {

            	    $objeto = new Detallefacturaganaderia();

                    if(!empty($med['checked'])){
                	    $objeto->IDTARIFASGANADERIA=$tarifasganaderia['IDTARIFASGANADERIA'];
    				    $objeto->IDAGUAGANADERIA=$med['IDAGUAGANADERIA'];
    				    $objeto->ANIOMES=Carbon::parse($controlaniomesganaderia['aniomes']);
                        $objeto->SUBTOTAL=$tarifasganaderia['TARIFAMENSUAL'];
                        $objeto->TOTAL=$tarifasganaderia['TARIFAMENSUAL'];
                        $objeto->OBSERVACION=$controlaniomesganaderia['detalle'];
                        $objeto->DETALLE='CONSUMO';
                        $objeto->estado=0;
                        $objeto->controlaniomesganaderia_id=$controlaniomesganaderia_id;
                        
                        $objeto->save();
                        
                        $array[$i]=$objeto;
                        $i++;
                    }
                }

                DB::commit();

	            return response()->json(array(
	                'status'=>'sucsess',
	                'title'=>'Create data',
	                'message'=>'Dato creada Detallefacturaganaderia',
	                'code'=>201,
	                'total'=>count($array),
                    'data'=>$array
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
     * Actualizar Detallefacturaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editDetallefacturaganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallefacturaganaderia::find($id);
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
	                    'message'=>'Detallefacturaganaderia Actualizada',
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
     * Eliminar Detallefacturaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyDetallefacturaganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Detallefacturaganaderia::find($id);
                $objeto->delete();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'Dato Eliminada Detallefacturaganaderia',
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
     * Crear todas las facturas de la lista de aguaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createAllDetallefacturaganaderia(Request $request, $controlaniomesganaderia_id){
        set_time_limit(0);
        if ($request->isJson()) {
            //$data=$request->json()->all();
             //transaccion
            DB::beginTransaction();

            try {
                $controlaniomesganaderia= Controlaniomesganaderia::where('id',$controlaniomesganaderia_id)->first();
                $aguaganaderia = Aguaganaderia::All();
                $tarifasganaderia = Tarifasganaderia::latest()->first();
                
                $i = 0;
                $datos = array();
                foreach ($aguaganaderia as $usuarios) {

                    $buscar = Detallefacturaganaderia::
                    where('IDAGUAGANADERIA',$usuarios['IDAGUAGANADERIA'])
                    ->where('controlaniomesganaderia_id',$controlaniomesganaderia_id)
                    ->first();

                    if(!$buscar){
                        //var_dump("aqui prueba");
                        $objeto = new Detallefacturaganaderia();
                        $objeto->IDTARIFASGANADERIA=$tarifasganaderia['IDTARIFASGANADERIA'];
                        $objeto->IDAGUAGANADERIA=$usuarios['IDAGUAGANADERIA'];
                        $objeto->ANIOMES=Carbon::parse($controlaniomesganaderia['aniomes']);
                        $objeto->SUBTOTAL=$tarifasganaderia['TARIFAMENSUAL'];
                        $objeto->TOTAL=$tarifasganaderia['TARIFAMENSUAL'];
                        $objeto->OBSERVACION=$controlaniomesganaderia['detalle'];
                        $objeto->estado=0;
                        $objeto->controlaniomesganaderia_id=$controlaniomesganaderia_id;
                        $objeto->save();

                        $datos[$i] = $objeto;

                        $i++;
                    }

                }

                
                DB::commit();

                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Dato creada Detallefacturaganaderia',
                    'code'=>201,
                    'data'=>$datos
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
     * Obtener lista de facturas detalleganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaganaderia(Request $request,$aguaganaderia_id){
        if ($request->isJson()) {
            $porpagar=Aguaganaderia::
            select('detallefacturaganaderia.*','tarifasganaderia.*')
            ->join('detallefacturaganaderia','detallefacturaganaderia.IDAGUAGANADERIA','aguaganaderia.IDAGUAGANADERIA')
            ->join('tarifasganaderia','detallefacturaganaderia.IDTARIFASGANADERIA','tarifasganaderia.IDTARIFASGANADERIA')
            ->where([['aguaganaderia.IDAGUAGANADERIA',$aguaganaderia_id],['detallefacturaganaderia.estado',0]])
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
     /**
     * Calcular cobro mensual
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function obtenerCobroMensualGanaderia(Request $request,$controlaniomes_inicio,$controlaniomes_fin=null){
        set_time_limit(0);
        if ($request->isJson()) {

            DB::beginTransaction();
            try {
                $rango = false;
                if($controlaniomes_fin!=null){
                    $rango = true;
                }
                //hay rango de fechas desde y hasta
                if($rango==true){
                    //calcular cuantos dias tiene el calendario
                    $date = new DateTime($controlaniomes_fin);
                    $date->modify('last day of this month');
                    $fecha_fin= $date->format('Y-m-d');

                    $facturasganaderia = Facturasganaderia::
                    with('detallefacturaganaderia')
                    ->whereBetween('FECHAEMISION', [Carbon::parse($controlaniomes_inicio), $fecha_fin])
                    ->get();

                    //obtener total de usuarios cobrados
                    $total_usuarioscobrados = Facturasganaderia::
                    join('detallefacturaganaderia','detallefacturaganaderia.IDFACTURASGANADERIA','facturasganaderia.IDFACTURASGANADERIA')
                    ->whereBetween('FECHAEMISION', [Carbon::parse($controlaniomes_inicio), $fecha_fin])
                    ->groupBy('detallefacturaganaderia.IDAGUAGANADERIA')
                    ->get();

                    //usuarios por cobrar por meses
                    $total_usuariosporcobrar = Detallefacturaganaderia::
                    where('ANIOMES', '>=',Carbon::parse($controlaniomes_inicio))
                    ->where('ANIOMES', '<=',$fecha_fin)
                    ->where('estado',0)
                    ->groupBy('detallefacturaganaderia.IDAGUAGANADERIA')
                    ->get();

                }else{
                    $facturasganaderia = Facturasganaderia::
                    with('detallefacturaganaderia')
                    ->where('FECHAEMISION', '>=',[Carbon::parse($controlaniomes_inicio)])
                    ->get();

                    //obtener total de usuarios cobrados
                    $total_usuarioscobrados = Facturasganaderia::
                    join('detallefacturaganaderia','detallefacturaganaderia.IDFACTURASGANADERIA','facturasganaderia.IDFACTURASGANADERIA')
                    ->where('FECHAEMISION', '>=',[Carbon::parse($controlaniomes_inicio)])
                    ->groupBy('detallefacturaganaderia.IDAGUAGANADERIA')
                    ->get();

                    //usuarios por cobrar por meses
                    $total_usuariosporcobrar = Detallefacturaganaderia::
                    where('ANIOMES', '>=',Carbon::parse($controlaniomes_inicio))
                    ->where('estado',0)
                    ->groupBy('detallefacturaganaderia.IDAGUAGANADERIA')
                    ->get();
                }
                $suma_subtotalganaderia = 0;
                $suma_ivaganaderia = 0;
                $suma_totalganaderia = 0;

                $suma_mesescobrados = 0;
                $suma_numeroinstalacioncobrados = 0;
                $suma_dineroinstalacioncobrados = 0;

                $detalle = array();
                $i = 0;
                foreach ($facturasganaderia as $ganaderia) {

                    $suma_subtotalganaderia += $ganaderia['SUBTOTAL'];
                    $suma_ivaganaderia += $ganaderia['IVA'];
                    $suma_totalganaderia += $ganaderia['TOTAL'];

                    foreach ($ganaderia['detallefacturaganaderia'] as $detallegan) {
                        if($detallegan['DETALLE']=='INSTALACION'){
                            $suma_numeroinstalacioncobrados++;
                            $suma_dineroinstalacioncobrados += $detallegan['TOTAL'];
                        }
                        $detalle[$i] = $detallegan;
                        $suma_mesescobrados ++;
                        $i++;
                    }
                }

                

                $suma_totalganaderia+=$suma_ivaganaderia;

                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get all data',
                    'message'=>'Reporte general de cobros',
                    'code'=>200,

                    'controlaniomes_inicio'=>$controlaniomes_inicio,
                    'controlaniomes_fin'=>$controlaniomes_fin,

                    'suma_subtotalganaderia'=>$suma_subtotalganaderia,
                    'suma_ivaganaderia'=>$suma_ivaganaderia,
                    'suma_totalganaderia'=>$suma_totalganaderia,

                    'suma_mesescobrados'=>$suma_mesescobrados,
                    'total_usuarioscobrados_ganaderia'=>count($total_usuarioscobrados),
                    'total_usuariosporcobrar_ganaderia'=>count($total_usuariosporcobrar),


                    'suma_numeroinstalacioncobrados'=>$suma_numeroinstalacioncobrados,
                    'suma_dineroinstalacioncobrados'=>$suma_dineroinstalacioncobrados,

                    'total_facturasganaderia'=>count($facturasganaderia),
                    'facturasganaderia'=>$facturasganaderia
                    
                ),200);
            } catch (Exception $e) {
                DB::rollback();
                return $e;
            } catch (\Throwable $e) {
                DB::rollback();
                return $e;
            }
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
