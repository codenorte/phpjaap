<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aguaganaderia;
use App\Models\Detallefacturaganaderia;
use App\Models\Facturasganaderia;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class FacturasganaderiaController extends Controller
{
    /**
     * Realizar cobro de Facturasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function realizarPagoFacturaGanaderia(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();

            //return $data;

            $detallefacturaganaderia = $data[0]['detallefacturaganaderia'];
        	$totales = $data[0]['totales'];


            $numfactura = Facturasganaderia::select('NUMFACTURA')->max('NUMFACTURA');
        	$today = Carbon::now();

            DB::beginTransaction();

            try {

                //crear factura
            	$facturasganaderia = new Facturasganaderia();
            	$numfactura++;

				$facturasganaderia->NUMFACTURA=$numfactura;
				$facturasganaderia->FECHAEMISION=$today;
				$facturasganaderia->SUBTOTAL=$totales[0]['subtotal'];
				$facturasganaderia->IVA=$totales[0]['iva'];
				$facturasganaderia->TOTAL=$totales[0]['total'];
				$facturasganaderia->estado=1;
				$facturasganaderia->USUARIOACTUAL=$totales[0]['USUARIOACTUAL'];
    			$facturasganaderia->save();

    			//actualizar detallefactura
    			$i = 0;
    			$detalle = array();
    			foreach ($detallefacturaganaderia as $det) {
    				//var_dump($det['IDDETALLEFAC']);
    				$detalle[$i] = Detallefacturaganaderia::find($det['IDDETALLEFACGANADERIA']);
                    //buscar detallefactura si esq es por instalacion y actualizar aguaganaderia saldo de instalacion
                    if($det['DETALLE']=='INSTALACION'){
                        $saldo = 0;
                        $buscar_aguaganaderia = Aguaganaderia::find($det['IDAGUAGANADERIA']);
                        if($buscar_aguaganaderia['SALDO']>=$det['TOTAL']){
                            $buscar_aguaganaderia->PAGADO +=$det['TOTAL'];
                            $saldo = $buscar_aguaganaderia['SALDO'] - $det['TOTAL'];
                            $buscar_aguaganaderia->SALDO=$saldo;
                            $buscar_aguaganaderia->save();
                        }
                    }
    				$detalle[$i]->estado = 1;
    				$detalle[$i]->IDFACTURASGANADERIA = $facturasganaderia['IDFACTURASGANADERIA'];
    				$detalle[$i]->NUMFACTURA = $numfactura;
    				$detalle[$i]->save();

    				$i++;
    			}

            	//return $detalle;
                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Dato actualizado',
                    'message'=>'Factura creada',
                    'code'=>200,
                    'data'=>$data,
                    'detallefacturaganaderia'=>$detallefacturaganaderia,
                    'totales'=>$totales[0],
                    'facturasganaderia'=>$facturasganaderia,
                    'detalle'=>$detalle
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
     * Historial de pagos de agua ganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function historialFacturaGanaderia(Request $request, $IDAGUAGANADERIA)
    {
        set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
                $objeto = Facturasganaderia::
                select(
                    'facturasganaderia.*','detallefacturaganaderia.IDAGUAGANADERIA',
                    //(DB::raw('SUM(detallefacturaganaderia.TOTAL) as TOTAL_SUMA'))
                )
                ->with('detallefacturaganaderia.aguaganaderia')
                ->join('detallefacturaganaderia','detallefacturaganaderia.IDFACTURASGANADERIA','facturasganaderia.IDFACTURASGANADERIA')
                
                ->where('detallefacturaganaderia.IDAGUAGANADERIA',$IDAGUAGANADERIA)
                ->orderBy('facturasganaderia.FECHAEMISION', 'DESC')
                //->groupBy('pagosasistencia.NUMFACTURA')
                ->take(10)
                ->get();

                if($objeto[0]['IDFACTURASGANADERIA']){
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Get data',
                        'message'=>'Obtener ultimos 10 pagos asistencia',
                        'code'=>200,
                        'total'=>count($objeto),
                        'data'=>$objeto
                    ),200);
                }
                return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Sin datos',
                        'message'=>'Obtener ultimos 10 pagos asistencia',
                        'code'=>200,
                        'total'=>null,
                        'data'=>null
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
