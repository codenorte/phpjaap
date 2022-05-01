<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Detallefactura;
use App\Models\Medidor;
use App\Models\Controlaniomesdetallefactura;
use App\Models\Tarifas;

class ControlaniomesdetallefacturaController extends Controller
{
	 /**
     * Obtener lista de aniomes 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllControlaniomesdetallefactura(Request $request){
        if ($request->isJson()) {
            //obtener ultimo registro
            $ultimoregistro = Controlaniomesdetallefactura::get()->last();
            //$nueva_fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');
            $nueva_fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');
            //return $nueva_fecha;


            $controlaniomesdetallefactura=Controlaniomesdetallefactura::
            withCount('detallefactura as detallefactura_total')
            //withCount(['detallefactura', 'med'])
            //->with('detallefactura.medidoractivo')
            /*
            ->withCount([
                'detallefactura.medidor',
                'detallefactura as totalmedidor'=> function (Builder $query) {
                $query->where('ESTADO', 'ACTIVO');
            }])
            */
            ->withCount([
                'detallefactura',
                'detallefactura as cobrados'=> function (Builder $query) {
                $query->where('estado', 1);
            }])
            ->withCount([
                'detallefactura',
                'detallefactura as porcobrar'=> function (Builder $query) {
                $query->where('estado', 0);
            }])
            ->orderBy('id','DESC')
            //->limit(10)
            ->get();
            

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de meses consumidos, controlaniomes',
                'code'=>200,
                //'ultimoregistro'=>$ultimoregistro,
                'nueva_fecha'=>$nueva_fecha,
                'total'=>count($controlaniomesdetallefactura),
                'data'=>$controlaniomesdetallefactura
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar Controlaniomesdetallefactura por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdControlaniomesdetallefactura(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {

                $objeto = Controlaniomesdetallefactura::
                withCount([
                	'detallefactura',
                	'detallefactura as cobrados'=> function (Builder $query) {
                	$query->where('estado', 1);
				}])
				->withCount([
                	'detallefactura',
                	'detallefactura as porcobrar'=> function (Builder $query) {
                	$query->where('estado', 0);
				}])
                ->where('id',$id)
                ->first();

                //obtener el ultimo registro de tarifas
                $tarifas = Tarifas::latest()->first();
                
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Controlaniomesdetallefactura Obtenido',
                    'code'=>200,
                    'data'=>$objeto,
                    'tarifas'=>$tarifas
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
     * Crear Controlaniomesdetallefactura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createControlaniomesdetallefactura(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

            //obtener ultimo registro
            $ultimoregistro = Controlaniomesdetallefactura::get()->last();
            $nueva_fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');

            $data=$request->json()->all();

            $objeto = new Controlaniomesdetallefactura();

            $objeto->aniomes=$nueva_fecha;
            $objeto->detalle=$data['detalle'];
            $objeto->conlectura=0;
            $objeto->sinlectura=0;
            $objeto->estado=1;

            $objeto->save();
                
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Create data',
                'message'=>'Control aniomes creado',
                'code'=>201,
                'data'=>$objeto
            ),201);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Controlaniomesdetallefactura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editControlaniomesdetallefactura(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomesdetallefactura::findOrFail($id);
                $data = $request->json()->all();


                if($objeto){
                    
                    $objeto->aniomes=$data['aniomes'];
		            $objeto->detalle=$data['detalle'];
		            $objeto->conlectura=$data['conlectura'];
		            $objeto->sinlectura=$data['sinlectura'];
		            $objeto->estado=$data['estado'];

                    $objeto->save();
                }

                return response()->json(array('status'=>'sucsess','title'=>'Update Controlaniomesdetallefactura','message'=>'Dato Actualizada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Controlaniomesdetallefactura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyControlaniomesdetallefactura(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomesdetallefactura::findOrFail($id);
                $objeto->delete();
                return response()->json(array('status'=>'sucsess','title'=>'Delete data','message'=>'Dato Eliminada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }

     /**
     * copiar columnas de aniomes de la columna detallefactura a la tabla controlaniomesdetallefacturaController
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarColumnaaniomescontrol(Request $request)
    {
    	set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
            	$detallefactura=Detallefactura::
	            select('detallefactura.ANIOMES')
	            ->groupBy('ANIOMES')
	            ->orderBy('IDDETALLEFAC','ASC')
	            ->get();

	            //return $detallefactura;

                $i=0;
                $controlaniomes=array();
                foreach ($detallefactura as $det) {
                	$controlaniomesdetallefactura = new Controlaniomesdetallefactura();
			        $controlaniomesdetallefactura->aniomes=$det['ANIOMES'];
			        $controlaniomesdetallefactura->detalle='Registo mensual';
			        $controlaniomesdetallefactura->estado=1;
			        $controlaniomesdetallefactura->save();

			        $controlaniomes[$i]=$controlaniomesdetallefactura;
                	$i++;
                }

                return response()->json(array(
                	'status'=>'sucsess',
                	'title'=>'Get data',
                	'message'=>'Dato Obtenida',
                	'code'=>200,
                	'controlaniomes'=>$controlaniomes
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
     * Obtener lista de medidor nuevos de cada mes
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getMedidoresnuevosmes(Request $request){
        if ($request->isJson()) {
            $medidor=Medidor::
            select(
            	'FECHA',
            	(DB::raw('count(*) as medidoresregistrados, FECHA'))
            	//(DB::raw("SELECT COUNT(*) FROM medidor where estado='INACTIVO'"))
            	//(DB::raw("COUNT(*) as useracativo, estado='ACTIVO'"))
        	)
        	
            //->selectRaw('ESTADO as usuarioactivo', 'ACTIVO')
        	//select(\DB::raw('YEAR(birth_date) < 2001 as adult, COUNT(id) as amount')
            //->where('ESTADO','ACTIVO')
            ->groupBy(DB::raw('YEAR(FECHA)'),DB::raw('MONTH(FECHA)'))
            ->get();

            $medidorinactivos=Medidor::
            select(
            	'FECHA',
            	(DB::raw('count(*) as medidoresinactivados, FECHA'))
        	)
            ->where('ESTADO','INACTIVO')
            ->groupBy(DB::raw('YEAR(FECHA)'),DB::raw('MONTH(FECHA)'))
            ->get();
            //return $medidorinactivos;

            $medidoractivos=Medidor::
            select(
            	'FECHA',
            	(DB::raw('count(*) as medidoresactivos, FECHA'))
        	)
            ->where('ESTADO','ACTIVO')
            ->groupBy(DB::raw('YEAR(FECHA)'),DB::raw('MONTH(FECHA)'))
            ->get();

            $dato = array();
            $num = 0;
            //asignaciones
            //sumatorias
            $total = 0;
            $totalactivos = 0;
            $totalinactivos = 0;
            foreach ($medidor as $med) {
	            $i = 0;
	            $j = 0;
            	foreach ($medidorinactivos as $inactivos) {
            		if($med['FECHA']==$inactivos['FECHA']){
            			$j=$inactivos['medidoresinactivados'];
            		}
            	}
            	foreach ($medidoractivos as $activos) {
            		if($med['FECHA']==$activos['FECHA']){
            			$i=$activos['medidoresactivos'];
            		}
            	}
            	$total += $med['medidoresregistrados'];
            	$totalactivos +=$i;
				$totalinactivos +=$j;
            	//asignar a la lista medidor
            	$med->totalregistrados=$total;
            	$med->totalactivos=$totalactivos;
            	$med->totalinactivos=$totalinactivos;
            	
            	$med->activos=$i;
            	$med->inactivos=$j;

            	$dato[$num] = $med;
            	$num++;
            }

            //return $dato;

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Historial de consumo, detallefactura',
                'code'=>200,
                'data'=>$dato,
                'medidorinactivos'=>$medidorinactivos,
                'medidoractivos'=>$medidoractivos
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

     /**
     * Obtener lista de detallefactura nuevos por meses
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturanuevos(Request $request){
    	set_time_limit(0);
        if ($request->isJson()) {

        	$medidor=Medidor::
        	select(
        		'IDMEDIDOR','ESTADO','FECHA'
        	)
            ->where('ESTADO','ACTIVO')
            ->get();



            $detallefactura=Detallefactura::
            select(
            	'IDDETALLEFAC','IDMEDIDOR','ANIOMES','controlaniomes_id'
            )
            ->get();
            //return $detallefactura;

            $dato = array();
            $x=0;
            $y=0;
            //conteo
            $i = 0;
            $j = 0;
            $existe = false;
            $sinlectura = array();
            /*
            foreach ($medidor as $med) {
                $existe = false;
                //los que ya existen
                foreach ($detallefactura as $det) {
                    if($med->IDMEDIDOR==$det->IDMEDIDOR){
                        $existe = true;
                        break;
                    }
                }
                if(!$existe){
                    $sinlectura[$j]=$med;
                    $j++;
                }
                $i++;
            }
            */
            foreach ($medidor as $med) {
            	$existe = false;
	            $fecha =Carbon::parse($med['FECHA']);
	            $date = $fecha->format('Y-m');
	            $med->FECHA = $date;
            	foreach ($detallefactura as $det) {
            		if($det['ANIOMES']==$med['FECHA']){
	                    if($med->IDMEDIDOR==$det->IDMEDIDOR){
	                        $existe = true;
	                        break;
	                    }
            		}
            		if(!$existe){
	                    $sinlectura[$j]=$med;
	                    $j++;
	                }
            	}
            }
            return $sinlectura;



        	
            /*
            $controlaniomesdetallefactura = Controlaniomesdetallefactura::
            withCount([
            	'detallefactura',
            	'detallefactura as cobrados'=> function (Builder $query) {
            	$query->where('estado', 1);
			}])
			->withCount([
            	'detallefactura',
            	'detallefactura as porcobrar'=> function (Builder $query) {
            	$query->where('estado', 0);
			}])
            ->get();

            $i = 0;
            $dato = array();
            foreach ($controlaniomesdetallefactura as $control) {
            	$control->conlectura = $control['']
            }
		*/



            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Obtener lista de nuevas facturas',
                'code'=>200,
                'total_data'=>count($controlaniomesdetallefactura),
                'data'=>$controlaniomesdetallefactura
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
     /**
     * Obtener anio descentetemente
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllContrlaniomesDescencente(Request $request){
        if ($request->isJson()) {
            $objeto=Controlaniomesdetallefactura::
            orderBy('id','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Controlaniomesdetallefactura descendente',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
     /**
     * Obtener anio descentetemente con parametro desde inicial hasta fin por controlaniomes
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllControlaniomesDescencenteInicioFin(Request $request,$inicio){
        if ($request->isJson()) {
            

            $buscar_ultimoregistro = Controlaniomesdetallefactura::select('id')->max('id');

            //hay rango de fechas desde y hasta
            $objeto=Controlaniomesdetallefactura::
            whereBetween('id', [$inicio, $buscar_ultimoregistro])
            ->orderBy('id','DESC')
            ->get();

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Compra',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}

