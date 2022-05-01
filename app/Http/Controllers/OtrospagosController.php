<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otrospagos;
use App\Models\Facturas;
use App\Models\Corte;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class OtrospagosController extends Controller
{
    /**
     * mostrar Otrospagos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllOtrospagos(Request $request){
        if ($request->isJson()) {
            $objeto=Otrospagos::
            All();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Otrospagos',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Otrospagos por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdOtrospagos(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Otrospagos::findOrFail($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida otrospagos',
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
     * Crear Otrospagos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createOtrospagos(Request $request,$IDFACTURA){
        if ($request->isJson()) {
            
            $data=$request->json()->all();

            //return $data;
            $corte = Corte::where('IDCORTE',$data['IDCORTE'])
            ->first();

            $factura= Facturas::
            where('IDFACTURA',$IDFACTURA)
            ->first();

            $today = Carbon::now();

            //return $factura;
            //transaccion
            DB::beginTransaction();

            try {
            	//actualizar corte

	            $objeto = new Otrospagos();

	            $objeto->IDCORTE = $data['IDCORTE'];
				$objeto->DERCONX = $corte['MULTA'];
				$objeto->MULRECX = 0;
				$objeto->INTERES = 0;
				$objeto->TOTAL = $corte['MULTA'];
				$objeto->NUMFACTURA = $factura['NUMFACTURA'];
				$objeto->USUARIOACTUAL = $data['USUARIOACTUAL'];
				$objeto->FECHAPAGO = $today;
				$objeto->estado = 1;
				$objeto->IDFACTURA = $factura['IDFACTURA'];

				$objeto->save();


            	$corte->PAGADO = 'SI';
            	$corte->estado = 1;
            	$corte->save();

            	DB::commit();
	            return response()->json(array(
	                'status'=>'sucsess',
	                'title'=>'Create data otrospagos',
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
     * Actualizar Otrospagos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editOtrospagos(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Otrospagos::findOrFail($id);
                $data = $request->json()->all();

                $objeto->IDCORTE = $data['IDCORTE'];
				$objeto->DERCONX = $data['DERCONX'];
				$objeto->MULRECX = $data['MULRECX'];
				$objeto->INTERES = $data['INTERES'];
				$objeto->TOTAL = $data['TOTAL'];
				$objeto->NUMFACTURA = $data['NUMFACTURA'];
				$objeto->USUARIOACTUAL = $data['USUARIOACTUAL'];
				$objeto->FECHAPAGO = $data['FECHAPAGO'];
				$objeto->estado = $data['estado'];
				$objeto->IDFACTURA = $data['IDFACTURA'];

                $objeto->save();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Update data',
                    'message'=>'Dato Actualizada',
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
     * Eliminar Otrospagos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyOtrospagos(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Otrospagos::findOrFail($id);
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
