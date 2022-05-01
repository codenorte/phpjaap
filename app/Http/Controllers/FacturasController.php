<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medidor;
use App\Models\Facturas;
use App\Models\Detallefactura;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;


class FacturasController extends Controller
{
    /**
     * Realizar pago de la factura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function realizarPagoFactura(Request $request){
        if ($request->isJson()) {

        	$data=$request->json()->all();

        	$detallefactura = $data[0]['detallefactura'];
        	$users = $data[0]['users'];
        	$totales = $data[0]['totales'];

        	
        	$numfactura = Facturas::select('NUMFACTURA')->max('NUMFACTURA');
        	$today = Carbon::now();

            DB::beginTransaction();

            try {

            	//crear factura
            	$facturas = new Facturas();
            	$numfactura++;
            	$facturas->NUMFACTURA = $numfactura;
    			$facturas->FECHAEMISION = $today;
    			$facturas->SUBTOTAL = $totales[0]['subtotal'];
    			$facturas->IVA = $totales[0]['iva'];
    			$facturas->TOTAL = $totales[0]['total'];
    			$facturas->USUARIOACTUAL = $totales[0]['USUARIOACTUAL'];
    			$facturas->estado = 1;
    			$facturas->save();


    			//actualizar detallefactura
    			$i = 0;
    			$detalle = array();
    			foreach ($detallefactura as $det) {
    				//var_dump($det['IDDETALLEFAC']);
    				$detalle[$i] = Detallefactura::findOrFail($det['IDDETALLEFAC']);
    				$detalle[$i]->estado = 1;
    				$detalle[$i]->NUMFACTURA = $numfactura;
    				$detalle[$i]->OBSERVACION = 'SI';
    				$detalle[$i]->IDFACTURA = $facturas['IDFACTURA'];
    				$detalle[$i]->save();

    				$i++;
    			}

                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Dato actualizado',
                    'message'=>'Factura creada',
                    'code'=>200,
                    'data'=>$data,
                    'detallefactura'=>$detallefactura,
                    'users'=>$users,
                    'totales'=>$totales[0],
                    'facturas'=>$facturas,
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
     * obtener factura por id Factura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdFactura(Request $request, $factura_id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Facturas::with('detallefactura')
                ->where('IDFACTURA',$factura_id)
                ->first();
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Dato Obtenida','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * obtener 10 ultimas facturas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    // $factura = Factura::latest()->first();
    public function getUltimosPagosFactura(Request $request, $IDMEDIDOR)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Facturas::
                select('facturas.*')
                ->with('otrospagos')
                ->with('detallefactura')
                ->join('detallefactura','facturas.IDFACTURA','detallefactura.IDFACTURA')
                ->join('medidor','medidor.IDMEDIDOR','detallefactura.IDMEDIDOR')
                ->where('medidor.IDMEDIDOR',$IDMEDIDOR)
                ->orderBy('detallefactura.controlaniomes_id', 'DESC')
                ->groupBy('facturas.IDFACTURA')
                ->take(10)
                ->get();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Obtener ultimos pagos',
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
