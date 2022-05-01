<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detallefacturaganaderia;
use App\Models\Controlaniomesganaderia;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

class ControlaniomesganaderiaController extends Controller
{
     /**
     * mostrar Controlaniomesganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllControlaniomesganaderia(Request $request){
        if ($request->isJson()) {

        	//obtener ultimo registro
            $ultimoregistro = Controlaniomesganaderia::get()->last();
            $nueva_fecha = null;
            if($ultimoregistro){
            	$nueva_fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');
            }
            //return $ultimoregistro;
            

            //$objeto=Controlaniomesganaderia::All();

            $objeto=Controlaniomesganaderia::
            withCount('detallefacturaganaderia as total_registro')
            ->withCount([
                'detallefacturaganaderia as cobrados'=> function ($query) {
                $query->where('estado', 1);
            }])
            ->withCount([
                'detallefacturaganaderia as porcobrar'=> function ($query) {
                $query->where('estado', 0);
            }])
            ->orderBy('id','DESC')
            ->get();
            
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Controlaniomesganaderia',
                'code'=>200,
                'nueva_fecha'=>$nueva_fecha,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Controlaniomesganaderia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdControlaniomesganaderia(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Controlaniomesganaderia::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Controlaniomesganaderia id',
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
     * Crear Controlaniomesganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createControlaniomesganaderia(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

            $data=$request->json()->all();
            //transaccion
            DB::beginTransaction();
            try {
                //buscar ultimo registro
                $ultimoregistro = Controlaniomesganaderia::get()->last();
                $fecha = new Carbon();
                if($ultimoregistro){
                    $fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');
                    //return "hola";
                }else{
                    $fecha=Carbon::parse($data['aniomes'])->isoFormat('Y-M');
                }
                //return $nueva_fecha;
                //return $ultimoregistro;

                $objeto=new Controlaniomesganaderia();
                $objeto->aniomes = $fecha;
                $objeto->detalle = $data['detalle'];
                $objeto->estado = $data['estado'];

                $objeto->save();

                DB::commit();
                    
                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Controlaniomesganaderia creada',
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
     * Actualizar Controlaniomesganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editControlaniomesganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomesganaderia::findOrFail($id);
                $data = $request->json()->all();

                $objeto->aniomes = $data['aniomes'];
                $objeto->detalle = $data['detalle'];
                $objeto->estado = $data['estado'];

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
     * Eliminar Controlaniomesganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyControlaniomesganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomesganaderia::findOrFail($id);
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

     /**
     * Obtener anio descentetemente aguaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllContrlaniomesDescencenteGanaderia(Request $request){
        if ($request->isJson()) {
            
            /*$objeto=Controlaniomesganaderia::
            orderBy('id','DESC')
            ->get();*/

            $fecha = Detallefacturaganaderia::select(
                'detallefacturaganaderia.ANIOMES','detallefacturaganaderia.controlaniomesganaderia_id',
                DB::raw("DATE_FORMAT(ANIOMES, '%Y-%m') ANIOMES"),
                DB::raw('YEAR(ANIOMES) year, MONTH(ANIOMES) month')
            )
            ->groupBy('year','month')
            ->orderBy('IDDETALLEFACGANADERIA','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista fecha descendente controlaniomesganaderia',
                'code'=>200,
                'total'=>count($fecha),
                'data'=>$fecha
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
     /**
     * Obtener anio descentetemente con parametro desde inicial hasta fin por controlaniomesganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllControlaniomesDescencenteInicioFinGanaderia(Request $request,$inicio){
        if ($request->isJson()) {
            


            $fechafin = Detallefacturaganaderia::select(
                'detallefacturaganaderia.ANIOMES','detallefacturaganaderia.controlaniomesganaderia_id'
            )
            ->groupBy('ANIOMES')
            ->orderBy('ANIOMES','DESC')
            ->get();

            $buscar_ultimoregistro = $fechafin[0]['ANIOMES'];

            $objeto= Detallefacturaganaderia::
            select(
                'detallefacturaganaderia.controlaniomesganaderia_id',
                DB::raw("DATE_FORMAT(ANIOMES, '%Y-%m') ANIOMES"),
                DB::raw('YEAR(ANIOMES) year, MONTH(ANIOMES) month')
            )
            ->whereBetween('ANIOMES', [Carbon::parse($inicio),$buscar_ultimoregistro])
            ->groupBy('year','month')
            ->orderBy('ANIOMES','DESC')
            ->get();

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de fecha fin aguaganaderia',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

}
