<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aguasobrante;
use App\Models\Detallefacturasobrante;
use App\Models\Facturassobrante;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class FacturassobranteController extends Controller
{
    /**
     * Realizar cobro de Facturasganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function realizarPagoFacturaSobrante(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();


            $detallefacturasobrante = $data[0]['detallefacturasobrante'];
        	$totales = $data[0]['totales'];


            $numfactura = Facturassobrante::select('NUMFACTURA')->max('NUMFACTURA');
        	$today = Carbon::now();
            //return $data;

            DB::beginTransaction();

            try {

                //crear factura
            	$facturassobrante = new Facturassobrante();
            	$numfactura++;

				$facturassobrante->NUMFACTURA=$numfactura;
				$facturassobrante->FECHAEMISION=$today;
				$facturassobrante->SUBTOTAL=$totales[0]['subtotal'];
				$facturassobrante->IVA=$totales[0]['iva'];
				$facturassobrante->TOTAL=$totales[0]['total'];
				$facturassobrante->estado=1;
				$facturassobrante->USUARIOACTUAL=$totales[0]['USUARIOACTUAL'];
    			$facturassobrante->save();

    			//actualizar detallefactura
    			$i = 0;
    			$detalle = array();
    			foreach ($detallefacturasobrante as $det) {
    				//var_dump($det['IDDETALLEFAC']);
    				$detalle[$i] = Detallefacturasobrante::find($det['IDDETALLEFACSOBRANTE']);
                    //buscar detallefactura si esq es por instalacion y actualizar aguaganaderia saldo de instalacion
                    if($det['DETALLE']=='INSTALACION'){
                        $saldo = 0;
                        $buscar_aguasobrante = Aguasobrante::find($det['IDAGUASOBRANTE']);
                        if($buscar_aguasobrante['SALDO']>=$det['TOTAL']){
                            $buscar_aguasobrante->PAGADO +=$det['TOTAL'];
                            $saldo = $buscar_aguasobrante['SALDO'] - $det['TOTAL'];
                            $buscar_aguasobrante->SALDO=$saldo;
                            $buscar_aguasobrante->save();
                        }
                    }
    				$detalle[$i]->estado = 1;
    				$detalle[$i]->IDFACTURASOBRANTE = $facturassobrante['IDFACTURASOBRANTE'];
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
                    'detallefacturasobrante'=>$detallefacturasobrante,
                    'totales'=>$totales[0],
                    'facturassobrante'=>$facturassobrante,
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
}
