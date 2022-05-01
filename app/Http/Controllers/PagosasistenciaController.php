<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagosasistencia;
use App\Models\Asistencia;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class PagosasistenciaController extends Controller
{
    /**
     * Realizar pago de la factura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function realizarPagoFacturaAsistencia(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

        	$data=$request->json()->all();

            $numfactura = Pagosasistencia::max('NUMFACTURA');
        	//$numfactura = Pagosasistencia::All();
        	$today = Carbon::now();

            $USUARIOACTUAL = $data[0]['USUARIOACTUAL'];
            $asistencia = $data[0]['asistencia'];
            //return $asistencia;

            //return $asistencia;

            DB::beginTransaction();

            try {

            	$numfactura++;
    			$i = 0;
    			$pagosasistencia = array();
    			foreach ($asistencia as $asis) {
                    //actualizar asistencia
                    $buscar_asistencia=Asistencia::find($asis['IDASISTENCIA']);
                    $buscar_asistencia->OBSEVACION='SI';
                    $buscar_asistencia->estado=1;
                    $buscar_asistencia->save();

                	//crear pagosasistencia
                	$pagos = new Pagosasistencia();
        			$pagos->IDASISTENCIA = $asis['IDASISTENCIA'];
        			$pagos->FECHAPAGO = $today;
                	$pagos->NUMMINGAS = 1;
        			$pagos->VALORMINGAS = $asis['VALORMULTA'];
        			$pagos->OBSERVACION = 'SI';
        			$pagos->USUARIOACTUAL = $USUARIOACTUAL[0]['USUARIOACTUAL'];
                    $pagos->NUMFACTURA = $numfactura;
        			$pagos->estado = 1;
        			$pagos->save();

                    $pagosasistencia[$i]=$pagos;
    				$i++;
    			}

                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Pago realizado',
                    'message'=>'Factura del evento creado',
                    'code'=>201,
                    'pagosasistencia_total'=>count($pagosasistencia),
                    'data'=>$pagosasistencia,
                    'asistencia'=>$asistencia
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

        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * Historial de pagos de las mingas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function historialPagosasistencia(Request $request, $IDMEDIDOR)
    {
        set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
                $objeto = Pagosasistencia::
                select(
                    'pagosasistencia.*',
                    (DB::raw('SUM(VALORMINGAS) as TOTAL'))
                )
                ->with('asistencia.planificacion')
                ->join('asistencia','asistencia.IDASISTENCIA','pagosasistencia.IDASISTENCIA')
                //->join('medidor','medidor.IDMEDIDOR','asistencia.IDMEDIDOR')
                ->where('asistencia.IDMEDIDOR',$IDMEDIDOR)
                ->orderBy('pagosasistencia.FECHAPAGO', 'DESC')
                ->groupBy('pagosasistencia.NUMFACTURA')
                ->take(10)
                ->get();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Obtener ultimos pagos asistencia',
                    'code'=>200,
                    'total'=>count($objeto),
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
