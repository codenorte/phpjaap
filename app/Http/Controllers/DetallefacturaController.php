<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Medidor;
use App\Models\Facturas;
use App\Models\Tarifas;
use App\Models\Detallefactura;
use App\Models\Controlaniomesdetallefactura;
use App\Models\Otrosconceptos;
use App\Models\Otrospagos;
use App\Models\Corte;
use App\Models\Pagosasistencia;
use App\Models\Facturasinstalacion;
use App\Models\Facturasganaderia;
use App\Models\Facturassobrante;

use App\Models\ValidarCedula;
//use Tavo\ValidadorEc;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

use Validator;
use Illuminate\Validation\Rule;

class DetallefacturaController extends Controller
{
    /**
     * mostrar detallefactura por NUMEROMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDetallefacturaNumMedidor(Request $request, $numero_medidor)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Detallefactura::
                select('detallefactura.*')
                ->join('medidor','medidor.IDMEDIDOR','detallefactura.IDMEDIDOR')
                ->where('medidor.NUMMEDIDOR',$numero_medidor)
                ->orderBy('detallefactura.controlaniomes_id','DESC')
                ->get();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data Id',
                    'message'=>'Dato detalle factura obtenida por numero de medidor',
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
    /**
     * Crear facturadetalle
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createDetallefactura(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {
            //transaccion
            $data=$request->json()->all();
            //return $data;

            /*
            // Validator::make($data, [
            //     'controlaniomes_id' => [
            //         'required',
            //          Rule::unique('detallefactura')->where(function ($query) use($controlaniomes_id,$IDMEDIDOR) {
            //            return $query->where('controlaniomes_id', $controlaniomes_id)->where('IDMEDIDOR', $IDMEDIDOR);
            //          });
            //     ],
            // ],$messages);





            $messages = array('unique' => 'Dato :attribute ya existe.');

            $validator = Validator::make($request->all(), [
                'IDMEDIDOR' => 'required|unique:detallefactura,IDMEDIDOR|unique:detallefactura,controlaniomes_id'
            ],$messages);

            // $validator = Validator::make($request->all(), [
            //     'controlaniomes_id' => 'required|unique:detallefactura,controlaniomes_id',
            //     'IDMEDIDOR' => 'required|unique:detallefactura,IDMEDIDOR',
            // ],$messages);


            // $validator = Validator::make($data, [
            //     'controlaniomes_id' => [
            //         'required',
            //          Rule::unique('detallefactura')->where(function ($query) use($controlaniomes_id,$IDMEDIDOR) {
            //            return $query->where('controlaniomes_id', $controlaniomes_id)->where('IDMEDIDOR', $IDMEDIDOR);
            //          });
            //     ],
            // ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        'title'=>'Datos ya existen',
                        'message'=>'Datos deben ser unicos',
                        'status'=>'error',
                        'code'=>200,
                        'error' => $validator->messages()
                    ],
                    200);
            }

            return $validator;
            */

            DB::beginTransaction();
            try {

                $buscar = Detallefactura::
                where('controlaniomes_id',$data['controlaniomes_id'])
                //->where('ANIOMES',$data['ANIOMES'])
                ->where('IDMEDIDOR',$data['IDMEDIDOR'])
                ->first();


                if($buscar!=null||$buscar!=''){
                    return response()->json(['error'=>'Datos ya existen, por favor cree un nuevo registro'],401,[]);
                }
                //obtener el ultimo registro de tarifas
                $tarifas = Tarifas::latest()->first();

                $objeto=new Detallefactura();

                $objeto->IDTARIFAS = $tarifas['IDTARIFAS'];
                $objeto->IDMEDIDOR = $data['IDMEDIDOR'];
                $objeto->ANIOMES = $data['ANIOMES'];
                $objeto->MEDIDAANT = $data['MEDIDAANT'];
                $objeto->MEDIDAACT = $data['MEDIDAACT'];
                //calculos
                //calcular consumo
                $consumo = $data['MEDIDAACT'] - $data['MEDIDAANT'];

                $excedido = 0;
                $tarifaexcedido = 0;
                if($consumo>$tarifas['BASE']){
                    //medida excedido
                    $excedido = $consumo - $tarifas['BASE'];
                    //valor exceso
                    $tarifaexcedido = round($excedido * $tarifas['VALOREXCESO'], 2);
                }
                //total
                $total = $tarifas['TARBASE'] + $tarifas['APORTEMINGA'] + $tarifaexcedido;

                $objeto->CONSUMO = $consumo;
                $objeto->MEDEXCEDIDO = $excedido;
                $objeto->TAREXCEDIDO = $tarifaexcedido;

                $objeto->APORTEMINGA = $tarifas['APORTEMINGA'];
                $objeto->ALCANTARILLADO = $tarifas['ALCANTARRILLADO'];
                $objeto->SUBTOTAL = $tarifas['TARBASE'];
                $objeto->TOTAL = $total;
                $objeto->OBSERVACION = 'NO';
                $objeto->estado = 0;
                $objeto->controlaniomes_id = $data['controlaniomes_id'];

                $objeto->save();

                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Nuevo dato creado',
                    'message'=>'Nueva facturadetalle creada',
                    'code'=>201,
                    'data'=>$objeto
                ),201);
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

    /**
     * editar facturadetalle
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function editDetallefactura(Request $request,$detallefactura_id){
        set_time_limit(0);
        if ($request->isJson()) {
            $data=$request->json()->all();

            $objeto = Detallefactura::
            where('IDDETALLEFAC',$detallefactura_id)
            ->where('estado',0)
            ->first();

            if($objeto==null||$objeto==''||$data['MEDIDAACT']<$objeto['MEDIDAANT']){
                return response()->json(['error'=>'Datos no se puede editar, el registro ingresado es incorrecto'],401,[]);
            }
            else{
                //transaccion
                DB::beginTransaction();

                try {
                    
                    //obtener el ultimo registro de tarifas
                    $tarifas = Tarifas::latest()->first();


                    $objeto->IDTARIFAS = $tarifas['IDTARIFAS'];
                    //$objeto->MEDIDAANT = $data['MEDIDAANT'];
                    $objeto->MEDIDAACT = $data['MEDIDAACT'];
                    //calculos
                    //calcular consumo
                    $consumo = $data['MEDIDAACT'] - $objeto['MEDIDAANT'];

                    $excedido = 0;
                    $tarifaexcedido = 0;
                    if($consumo>$tarifas['BASE']){
                        //medida excedido
                        $excedido = $consumo - $tarifas['BASE'];
                        //valor exceso
                        $tarifaexcedido = round($excedido * $tarifas['VALOREXCESO'], 2);
                    }
                    //total
                    $total = $tarifas['TARBASE'] + $tarifas['APORTEMINGA'] + $tarifaexcedido;

                    $objeto->CONSUMO = $consumo;
                    $objeto->MEDEXCEDIDO = $excedido;
                    $objeto->TAREXCEDIDO = $tarifaexcedido;
                    $objeto->SUBTOTAL = $tarifas['TARBASE'];
                    $objeto->TOTAL = $total;

                    $objeto->save();

                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Editado dato correctamente',
                        'message'=>'Dato facturadetalle editada',
                        'code'=>200,
                        'data'=>$objeto
                    ),200);
                } catch (Exception $e) {
                    DB::rollback();
                    return $e;
                } catch (\Throwable $e) {
                    DB::rollback();
                    return $e;
                }
            }
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * copiar tabla factura a detallefactura
     * copia IDFACTURA en DETALLEFACTURA
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function copiarFacturaEnDetallefactura(Request $request){
    	set_time_limit(0);
        if ($request->isJson()) {
        	//copiar columna IDFACTURA de facturas a detallefactura IDFACTURA
        	/*
            //$facturas=Facturas::limit(10)->get();
            $detallefactura=Detallefactura::
            select(
                'IDDETALLEFAC','IDFACTURA','estado'
            )
            ->where([['IDFACTURA','!=',null],['estado',null]])
            ->limit(10000)
            ->get();
            
            //return $detallefactura;

            $i=0;
            $array=array();
            foreach ($detallefactura as $det) {
            	$det->estado = 1;
            	$det->save();
                //return $det;
            	$array[$i] = $det;
            	$i++;
            }
            return $array;
            */
            
            //actualizar estado de facturas a 1
            /*
            $facturas = Facturas::
            select('IDFACTURA','estado')
            ->get();

            $i=0;
            $array=array();
            foreach ($facturas as $fact) {
            	$fact->estado = 1;
            	//return $fact;
            	$fact->save();
            	$array[$i] = $fact;
            	$i++;
            }
            return $facturas;
            */
            //actualizar estado los que no tienen IDFACTURA, crea una relacion entre factura y detallefactura
            //$numfactura = Facturas::select('NUMFACTURA')->max('NUMFACTURA');
            /*
            $detallefactura=Detallefactura::
            where([['OBSERVACION','NO'],['estado',null]])
            ->orderBy('ANIOMES','ASC')
            ->get();
            */
            
            //crear factura a los que si tienen pagados 
            $numfactura = Facturas::select('NUMFACTURA')->max('NUMFACTURA');
            $detallefactura=Detallefactura::
            where([['OBSERVACION','SI'],['estado',null]])
            ->orderBy('ANIOMES','ASC')
            ->get();


            $i=0;
            $array=array();
            $factdato=array();
            foreach ($detallefactura as $det) {
            	$numfactura++;

            	$det->estado = 1;
            	$det->NUMFACTURA = $numfactura;
            	$fecha = Carbon::parse($det['ANIOMES']);
            	$fecha->addDays(2);
            	

            	//crear factura
            	$facturas = new Facturas();

            	$facturas->NUMFACTURA = $numfactura;
				$facturas->FECHAEMISION = $fecha;
				$facturas->SUBTOTAL = $det['SUBTOTAL'];
				$facturas->IVA = 0.0;
				$facturas->TOTAL = $det['TOTAL'];
				$facturas->USUARIOACTUAL = 'Tocagon Anguaya Jessica';
				$facturas->estado = 1; //CAMBIAR AQUI

				$facturas->save();

				$det->IDFACTURA = $facturas['IDFACTURA'];
            	$det->save();
            	$array[$i] = $det;
            	$factdato[$i] = $facturas;
            	
            	$i++;
            }
            

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de medidor',
                'code'=>200,
                'data'=>$factdato,
                'detallefactura'=>$array
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar historial factura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getHistorialConsumo(Request $request,$id_medidor){
        if ($request->isJson()) {
            $porpagar=Medidor::
            select('detallefactura.*')
            ->join('detallefactura','detallefactura.IDMEDIDOR','medidor.IDMEDIDOR')
            ->where('medidor.IDMEDIDOR',$id_medidor)
            ->orderBy('controlaniomes_id','DESC')
            ->limit(10)
            ->get();

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Historial de consumo, detallefactura',
                'code'=>200,
                'total'=>count($porpagar),
                'data'=>$porpagar
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

     /**
     * Obtener 5 ultimos registros de un usuario por NUMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getUltimosRegistrosDet(Request $request, $IDMEDIDOR){
        if ($request->isJson()) {
            $objeto=Detallefactura::
            select(
                'medidor.IDMEDIDOR','medidor.NUMMEDIDOR','detallefactura.ANIOMES','detallefactura.MEDIDAANT','detallefactura.MEDIDAACT',
                'detallefactura.CONSUMO','detallefactura.MEDEXCEDIDO'
            )
            ->join('medidor','medidor.IDMEDIDOR','detallefactura.IDMEDIDOR')
            ->where('medidor.IDMEDIDOR',$IDMEDIDOR)
            ->orderBy('detallefactura.ANIOMES','DESC')
            ->limit(5)
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de ultimos 5 registros de consumo',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * lista de facturas pendientes por pagar, buscar por medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaNumMedidor(Request $request,$numero_medidor){
        if ($request->isJson()) {
            $porpagar=Medidor::
            select('detallefactura.*','tarifas.*')
            ->join('detallefactura','detallefactura.IDMEDIDOR','medidor.IDMEDIDOR')
            ->join('tarifas','detallefactura.IDTARIFAS','tarifas.IDTARIFAS')
            ->where([['medidor.NUMMEDIDOR',$numero_medidor],['detallefactura.estado',0]])
            //->orderBy('ANIOMES','DESC')
            ->get();

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
     * lista de facturas pendientes por pagar, buscar por idmedidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaIdmedidor(Request $request,$IDMEDIDOR){
        if ($request->isJson()) {
            $porpagar=Medidor::
            select('detallefactura.*','tarifas.BASE','tarifas.TARBASE','tarifas.APORTEMINGA as TAR_APORTEMINGA','tarifas.DESCRIPCION','tarifas.VALOREXCESO','tarifas.ALCANTARRILLADO','tarifas.IVA','tarifas.estado')
            ->join('detallefactura','detallefactura.IDMEDIDOR','medidor.IDMEDIDOR')
            ->join('tarifas','detallefactura.IDTARIFAS','tarifas.IDTARIFAS')
            ->where([['medidor.IDMEDIDOR',$IDMEDIDOR],['detallefactura.estado',0]])
            //->orderBy('ANIOMES','DESC')
            ->get();

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
     * lista de facturas pendientes por pagar, buscar por codigo
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaCodigo(Request $request,$codigo){
        if ($request->isJson()) {
            $porpagar=Medidor::
            select('detallefactura.*','tarifas.BASE','tarifas.TARBASE','tarifas.APORTEMINGA as TAR_APORTEMINGA','tarifas.DESCRIPCION','tarifas.VALOREXCESO','tarifas.ALCANTARRILLADO','tarifas.IVA','tarifas.estado')
            ->join('detallefactura','detallefactura.IDMEDIDOR','medidor.IDMEDIDOR')
            ->join('tarifas','detallefactura.IDTARIFAS','tarifas.IDTARIFAS')
            ->where([['medidor.CODIGO',$codigo],['detallefactura.estado',0]])
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
     * lista de facturas pendientes por pagar, buscar por cedula
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaCedulaRuc(Request $request,$cedula){
        if ($request->isJson()) {

            //buscar por cedula
            $buscar_por_cedula = User::
            where('RUCCI',$cedula)
            ->get();
            //return $buscar_por_cedula;

            $porpagar=Medidor::
            select('detallefactura.*','tarifas.*')
            ->join('detallefactura','detallefactura.IDMEDIDOR','medidor.IDMEDIDOR')
            ->join('tarifas','detallefactura.IDTARIFAS','tarifas.IDTARIFAS')
            ->join('users','users.id','medidor.IDUSUARIO')
            ->where([['users.RUCCI',$cedula],['detallefactura.estado',0]])
            //->orderBy('ANIOMES','DESC')
            ->get();

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Detallefactura por cobrar',
                'code'=>200,
                'total_medidores'=>count($buscar_por_cedula),
                'medidores'=>$buscar_por_cedula,
                'data'=>$porpagar
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * obtener lista medidores sin lectura por mes
     *
     * @param Request $aniomes
     * @return \Illuminate\Http\JsonResponse
     */
    function getAniomes(Request $request){
        if ($request->isJson()) {
            
            $detallefactura=Detallefactura::
            select('detallefactura.ANIOMES')
            ->groupBy('ANIOMES')
            ->orderBy('IDDETALLEFAC','ASC')
            ->get();
            //return $detallefactura;

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Medidor sin lectura',
                'code'=>200,
                'total_detallefactura'=>count($detallefactura),
                'detallefactura'=>$detallefactura
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * obtener lista medidores sin lectura por mes
     *
     * @param Request $aniomes
     * @return \Illuminate\Http\JsonResponse
     */
    function getListasinlectura(Request $request,$aniomes){
        if ($request->isJson()) {
            $medidor=Medidor::
            where('ESTADO','ACTIVO')
            ->get();
            //return $aniomes;
            $detallefactura=Detallefactura::
            where('ANIOMES',$aniomes)
            ->get();
            //return $detallefactura;

            $i = 0;
            $j = 0;
            $existe = false;
            $sinlectura = array();
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

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Medidor sin lectura',
                'code'=>200,
                'total_sinlectura'=>count($sinlectura),
                'total_detallefactura'=>count($detallefactura),
                //'total_medidor'=>count($medidor),
                'data'=>$sinlectura,
                'detallefactura'=>$detallefactura
                //'medidor'=>$medidor
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * obtener lista medidores sin lectura por tabla controlaniomesdetallefactura 
     *
     * @param Request $aniomes
     * @return \Illuminate\Http\JsonResponse
     */
    function getSinlecturaporControlaniomes(Request $request,$controlaniomes_id){
        set_time_limit(0);
        if ($request->isJson()) {
            $medidor=Medidor::
            select(
                'IDMEDIDOR','ESTADO','FECHA'
            )
            ->where('ESTADO','ACTIVO')
            ->get();

            //return $aniomes;
            $detallefactura=Detallefactura::
            select(
                'IDDETALLEFAC','IDMEDIDOR','ANIOMES','controlaniomes_id'
            )
            ->where('controlaniomes_id',$controlaniomes_id)
            ->get();
            //return $detallefactura;
            
            $controlaniomesdetallefactura = Controlaniomesdetallefactura::
            select(
                'id','aniomes','conlectura','sinlectura'
            )
            ->where('id',$controlaniomes_id)
            ->first();
            //return $controlaniomesdetallefactura;

            $existe = false;

            $total_sinlectura = 0;
            $total_conlectura = 0;

            $i = 1;

            foreach ($medidor as $med) {
                $existe = false;
                $fecha =Carbon::parse($med['FECHA']);
                //$date = $fecha->format('Y-m');
                $date = $fecha->isoFormat('Y-M');
                //los que tienen lectura
                foreach ($detallefactura as $det) {

                    //var_dump($det['IDDETALLEFAC']);
                    
                    //var_dump($i);
                    if($det['IDMEDIDOR']==$med['IDMEDIDOR']){
                        $existe = true;
                        break;
                    }
                    
                }
                //los que no tienen lectura
                if(!$existe){
                    $total_sinlectura++;
                }
                else{
                    $total_conlectura++;
                }
            }
            $controlaniomesdetallefactura->conlectura_count = count($detallefactura);
            $controlaniomesdetallefactura->sinlectura_count = count($medidor)-count($detallefactura);
            //$controlaniomesdetallefactura->save();
            //return $controlaniomesdetallefactura;
            

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Medidor sin lectura',
                'code'=>200,
                'total_medidor'=>count($medidor),
                'total_detallefactura'=>count($detallefactura),
                //'total_sinlectura'=>$total_sinlectura,
                //'conlectura'=>$total_conlectura,
                'controlaniomesdetallefactura'=>$controlaniomesdetallefactura
                //'total_detallefactura'=>count($detallefactura),
                //'total_medidor'=>count($medidor),
                //'detallefactura'=>$detallefactura,
                //'sinlectura'=>$sinlectura
                //'medidor'=>$medidor
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * obtener lista de medidores con factura y detallefactura por cobrar - detallecontrolaniomes
     *
     * @param Request $aniomes
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturaporcobrar(Request $request,$controlaniomes_id){
        set_time_limit(0);
        if ($request->isJson()) {
            $medidor=Medidor::
            select(
                'IDMEDIDOR','ESTADO','FECHA'
            )
            ->where('ESTADO','ACTIVO')
            ->get();

            //return $aniomes;
            $detallefactura=Detallefactura::
            select(
                'IDDETALLEFAC','IDMEDIDOR','ANIOMES','controlaniomes_id','estado'
            )
            ->where('controlaniomes_id',$controlaniomes_id)
            ->get();

            $controlaniomesdetallefactura = Controlaniomesdetallefactura::
            where('id',$controlaniomes_id)
            ->first();

            $i = 0;
            $j = 0;
            $detallefactura_cobrados = array();
            $detallefactura_porcobrar = array();
            foreach ($detallefactura as $det) {
                if($det['estado']==1){
                    $detallefactura_cobrados[$i] = $det;
                    $i++;
                }
                else if($det['estado']==0){
                    $detallefactura_porcobrar[$j] = $det;
                    $j++;
                }
            }

            $controlaniomesdetallefactura->conlectura_count = count($detallefactura);
            $controlaniomesdetallefactura->sinlectura_count = count($medidor)-count($detallefactura);
            //$controlaniomesdetallefactura->save();
            //return $controlaniomesdetallefactura;
            
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Medidor sin lectura',
                'code'=>200,
                'total_medidor'=>count($medidor),
                'total_detallefactura'=>count($detallefactura),
                'total_detallefactura_cobrados'=>count($detallefactura_cobrados),
                'total_detallefactura_porcobrar'=>count($detallefactura_porcobrar),
                //'total_sinlectura'=>$total_sinlectura,
                //'conlectura'=>$total_conlectura,
                'controlaniomesdetallefactura'=>$controlaniomesdetallefactura,
                //'total_detallefactura'=>count($detallefactura),
                //'total_medidor'=>count($medidor),
                'detallefactura_cobrados'=>$detallefactura_cobrados,
                'detallefactura_porcobrar'=>$detallefactura_porcobrar,
                //'sinlectura'=>$sinlectura
                //'medidor'=>$medidor
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * obtener lista de detallefactura con lectura y sin lectura controlando el año
     *
     * @param Request $aniomes
     * @return \Illuminate\Http\JsonResponse
     */
    function getDetallefacturasinlectura(Request $request,$controlaniomes_id){
        set_time_limit(0);
        if ($request->isJson()) {

            $medidor=Medidor::
            select(
                'IDMEDIDOR','ESTADO','FECHA','IDUSUARIO','NUMMEDIDOR','CODIGO'
            )
            ->with('usersName')
            //->where('ESTADO','ACTIVO')
            ->get();
            //return $medidor;

            //return $aniomes;
            $detallefactura=Detallefactura::
            select(
                'IDDETALLEFAC','IDMEDIDOR','ANIOMES','MEDIDAANT','MEDIDAACT','controlaniomes_id','estado','CONSUMO','MEDEXCEDIDO'
            )
            //->with('medidor.users:id,usuario')
            ->with('medidorUsuario')
            ->where('controlaniomes_id',$controlaniomes_id)
            ->get();

            //return $detallefactura;

            $controlaniomesdetallefactura = Controlaniomesdetallefactura::
            where('id',$controlaniomes_id)
            ->first();

            $date = Carbon::parse($controlaniomesdetallefactura['aniomes'])->format('Y-m-d');
            $controlaniomes = Carbon::createFromFormat('Y-m-d', $date);
            
            //cambiar fecha para comparar y agregar el medidor a la fecha de facturacion
            $i = 0;
            $medidor_existe = array();
            $copiamedidor = array();
            foreach ($medidor as $med) {
                $fecha = Carbon::createFromFormat('Y-m-d', $med['FECHA']);
                $fecha->day=1;
                //cambia la fecha para comparar con controlaniomes
                if($fecha<=$controlaniomes){
                    $medidor_existe[$i] = $med;
                    $copiamedidor[$i] = $med;
                    //var_dump($med->CODIGO);
                    $i++;
                }
            }
            //var_dump($medidor_existe[400]);
            //aliminar el medidor repetido del array
            $j = 0;
            foreach ($medidor_existe as $medsin) {
                foreach ($detallefactura as $det) {
                    //return $detallefactura[2];
                    if($det['IDMEDIDOR']==$medsin['IDMEDIDOR']){
                        unset($copiamedidor[$j]);
                    }
                }
                $j++;
            }
            $x = 0;
            $medidor_sinlectura = array();
            foreach ($copiamedidor as $copia) {
                $medidor_sinlectura[$x] = $copia;
                $x++;
            }

            //buscar medida anterior de los medidores no registrados el consumo
            //NOTA esta regresando a la medida anterior, por esa razon
            //se debe colocar la medidaactual del dato obtenido para poder calcular
            $lecturaanteriorDetallefactura = Detallefactura::
            select(
                'IDMEDIDOR','MEDIDAANT','MEDIDAACT','ANIOMES'
            )
            ->where('controlaniomes_id',($controlaniomes_id-1))
            ->get();

            $num = 0;
            $datosanteriores = array();
            foreach ($copiamedidor as $copymed ) {
                foreach ($lecturaanteriorDetallefactura as $lect) {
                    if($copymed['IDMEDIDOR']==$lect['IDMEDIDOR']){
                        $datosanteriores[$num] = $lect;
                        $num++;
                        //var_dump('-------'.$copymed['IDMEDIDOR']);
                        break;
                    }
                }
            }
            //return $datosanteriores;
            $xxx = 0;
            foreach ($medidor_sinlectura as $sinl) {
                foreach ($datosanteriores as $datan) {
                    //$sinl->detalleanterior = [];
                    if($sinl['IDMEDIDOR']==$datan['IDMEDIDOR']){
                        $sinl->detalleanterior = $datan;
                    }
                }
            }



            $controlaniomesdetallefactura->conlectura_count = count($detallefactura);
            $controlaniomesdetallefactura->sinlectura_count = count($medidor_existe)-count($detallefactura);
            
            
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Medidor sin lectura',
                'code'=>200,
                'total_detallefactura'=>count($detallefactura),
                'total_medidoractual'=>count($medidor_existe),
                'total_medidor_sinlectura'=>count($medidor_sinlectura),
                'controlaniomesdetallefactura'=>$controlaniomesdetallefactura,
                'medidor_sinlectura'=>$medidor_sinlectura,
                //'medidoractual'=>$medidor_existe,
                'detallefactura'=>$detallefactura,
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /****************************/
    /**
     * copiar columna controlaniomes_id mes de la tabla controlaniomesdetallefactura el id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarAniomesdecontrolaniomes(Request $request)
    {
        set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
                $detallefactura=Detallefactura::
                select('IDDETALLEFAC','ANIOMES','controlaniomes_id')
                ->get();
                $controlaniomesdetallefactura = Controlaniomesdetallefactura::All();


                $i=0;
                $detallefacturaarray=array();
                foreach ($detallefactura as $det) {
                    foreach ($controlaniomesdetallefactura as $control) {
                        if($det['ANIOMES']==$control['aniomes']){
                            
                            $det->controlaniomes_id=$control['id'];
                            $det->save();

                            $detallefacturaarray[$i]=$det['controlaniomes_id'];
                            $i++;
                            continue;
                        }
                    }

                }

                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida',
                    'code'=>200,
                    'detallefactura'=>count($detallefactura),
                    'total_detallefacturaarray'=>count($detallefacturaarray),
                    'detallefacturaarray'=>$detallefacturaarray
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
     * copiar columna aniomes a columna created_at
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarColumnaAniomes(Request $request)
    {
        set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
                $detallefactura = Detallefactura::
                select('IDDETALLEFAC','ANIOMES','created_at')
                ->get();

                $dato = array();
                $i = 0;
                foreach ($detallefactura as $det) {
                    
                    $det->created_at=Carbon::parse($det['ANIOMES']);
                    $det->save();
                    
                    $dato[$i] = $det;
                    $i++;
                }
                
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Columna detalle aniomes copiado',
                    'code'=>200,
                    'data'=>$dato
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
     * Obtener lista de usuarios con la suma de meses que debe
        SELECT COUNT(det.IDMEDIDOR),det.IDDETALLEFAC,det.controlaniomes_id,det.IDMEDIDOR FROM detallefactura det 
        WHERE det.estado = 0
        GROUP BY det.IDMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getCountDetallefacturaPorCobrar(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {
            
            $objeto=Detallefactura::
            select(
                'IDMEDIDOR','ANIOMES','estado','controlaniomes_id',
                (DB::raw('count(*) as mesesporpagar, IDMEDIDOR'))
            )
            ->with('medidor.users')
            ->groupBy('IDMEDIDOR')
            ->where('estado',0)
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de usuarios por pagar, por meses totales de deuda',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
     /**
     * Obtener lista de usuarios detallefactura por cobrar y cobrados
        SELECT COUNT(det.IDMEDIDOR),det.IDDETALLEFAC,det.controlaniomes_id,det.IDMEDIDOR FROM detallefactura det 
        WHERE det.estado = 0
        GROUP BY det.IDMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getCountDetallefacturaAll(Request $request){
        if ($request->isJson()) {

            $porcobrar=Detallefactura::
            select(
                'IDMEDIDOR','ANIOMES','estado','controlaniomes_id',
                (DB::raw('count(*) as mesesporpagar, IDMEDIDOR'))
            )
            ->with('medidor.users')
            ->groupBy('IDMEDIDOR')
            ->where('estado',0)
            ->get();

            $detallefactura=Detallefactura::
            select(
                'IDMEDIDOR','ANIOMES','estado','controlaniomes_id'
                //(DB::raw('count(*) as mesesporpagar, IDMEDIDOR'))
            )
            ->with('medidor.users')
            ->groupBy('IDMEDIDOR')
            //->where('estado',1)
            ->get();

            $copia_detallefactura = Detallefactura::
            select('IDMEDIDOR','ANIOMES','estado','controlaniomes_id')
            ->with('medidor.users')
            ->groupBy('IDMEDIDOR')
            ->get();

            $j = 0;
            foreach ($detallefactura as $det) {
                foreach ($porcobrar as $cobrar) {
                    if($det['IDMEDIDOR']==$cobrar['IDMEDIDOR']){
                        unset($copia_detallefactura[$j]);
                    }
                }
                $j++;
            }

            
            $data = array();
            $i = 0;
            foreach ($copia_detallefactura as $cop) {
                $data[$i]=$cop;
                $i++;
            }

            $k = count($data);
            foreach ($porcobrar as $cobrar) {
                $data[$k]=$cobrar;
                $k++;
            }
            //echo(array_push($flowers, "Rose", "Jasmine", "Lili", "Hibiscus", "Tulip"));
            //array_push($data,$copia_detallefactura,$porcobrar);

            //return $data;




            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de usuarios por pagar, por meses totales de deuda',
                'code'=>200,
                'total'=>count($detallefactura),
                'totalporcobrar'=>count($porcobrar),
                'totalsalida'=>count($data),
                'cobrados'=>count($copia_detallefactura),
                'data'=>$data
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    //copiar columnna en datatable
    /**
     * mostrar detallefactura por NUMEROMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarColumnaMedAnt(Request $request)
    {
        if ($request->isJson()){ 
            try
            {
                $mesanterior = Detallefactura::
                select('detallefactura.*')
                ->where('ANIOMES','2021-6')
                ->get();

                $mesactual = Detallefactura::
                select('detallefactura.*')
                ->where('estado',0)
                ->where('ANIOMES','2021-7')
                ->get();

                $j = 0;
                $dato = array();
                foreach ($mesanterior as $ant) {
                    foreach ($mesactual as $actual) {
                        if($ant['IDMEDIDOR']==$actual['IDMEDIDOR']){
                            $actual->MEDIDAANT = $ant['MEDIDAACT'];
                            //calcular consumo
                            //$consumo = 0;
                            $consumo = $actual['MEDIDAACT'] - $ant['MEDIDAACT'];

                            $actual->CONSUMO = $consumo;
                            //medida excedido
                            $medida_escedido = 0;
                            if($consumo>15){
                                $medida_escedido = $consumo - 15;
                            }
                            $actual->MEDEXCEDIDO = $medida_escedido;
                            $actual->TAREXCEDIDO = round($medida_escedido*0.20,2);
                            $actual->TOTAL = round(3 + $medida_escedido*0.20,2);

                            $actual->save();

                            $dato[$j] = $actual;
                            $j++;
                        }
                    }
                }
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data Id',
                    'message'=>'Dato detalle factura obtenida por numero de medidor',
                    'code'=>200,
                    'total'=>count($dato),
                    'data'=>$dato
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
     * Calcular cobro mensual
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function obtenerCobroMensual(Request $request,$controlaniomes_inicio,$controlaniomes_fin=null){
        if ($request->isJson()) {

            $rango = false;
            $objeto=new Detallefactura();

            //return $controlaniomes_fin;

            $controlaniomes =array();
            if($controlaniomes_fin!=null){
                $rango = true;
            }
            //hay rango de fechas desde y hasta
            if($rango==true){
                $objeto=Detallefactura::
                whereBetween('controlaniomes_id', [$controlaniomes_inicio, $controlaniomes_fin])
                ->where('estado',1)
                ->get();

                $controlaniomes[0]= Controlaniomesdetallefactura::find($controlaniomes_inicio);
                $controlaniomes[1]= Controlaniomesdetallefactura::find($controlaniomes_fin);;

            }else{
                $objeto=Detallefactura::
                where('controlaniomes_id', $controlaniomes_inicio)
                ->where('estado',1)
                ->get();
                $controlaniomes[0]= Controlaniomesdetallefactura::find($controlaniomes_inicio);
            }

            /*******segundo metodo por factura****/
            //obtener fecha inicial
            $controlaniomes_in=Controlaniomesdetallefactura::where('id',$controlaniomes_inicio)->first();
            //obtener fecha fin
            $controlaniomes_f=Controlaniomesdetallefactura::where('id',$controlaniomes_fin)->first();

            $fecha_inicial = Carbon::parse($controlaniomes_in['aniomes']);
            //$fecha_fin = Carbon::parse($controlaniomes_f['aniomes']);
            //calcular cuantos dias tiene el calendario
            $date = new DateTime($controlaniomes_f['aniomes']);
            $date->modify('last day of this month');
            $fecha_fin= $date->format('Y-m-d');

            //obtener total usuarios cobrados
            $listafacturas = Facturas::
            with('detallefacturaselect')
            ->with('otrospagos')
            ->whereBetween('FECHAEMISION', [$fecha_inicial, $fecha_fin])
            ->where('estado',1)
            ->get();
            
            //obtener facturas emitidas en esa fecha
            $total_usuarioscobrados = Facturas::
            join('detallefactura','detallefactura.IDFACTURA','facturas.IDFACTURA')
            ->whereBetween('FECHAEMISION', [$fecha_inicial, $fecha_fin])
            ->groupBy('detallefactura.IDMEDIDOR')
            ->get();
            //usuarios por cobrar por meses
            $total_usuariosporcobrar = Detallefactura::
            where('controlaniomes_id', '>=',$controlaniomes_inicio)
            ->where('controlaniomes_id', '<=',$controlaniomes_fin)
            ->where('estado',0)
            ->groupBy('detallefactura.IDMEDIDOR')
            ->get();

            $suma_consumo = 0;
            $suma_excedido = 0;
            $suma_tar_excedido = 0;
            $suma_aporteminga = 0;
            $suma_alcantarillado = 0;
            $suma_subtotal = 0;
            $suma_total_det = 0;
            $suma_reconexion = 0;
            $suma_subtotal_fact = 0;
            $suma_total_fact = 0;

            $suma_meses_cobrados = 0;
            $lista_detallefactura = array();
            $contar_lista =0;
            foreach ($listafacturas as $fact) {
                //factura

                $suma_subtotal_fact += $fact['SUBTOTAL'];
                $suma_total_fact += $fact['TOTAL'];
                foreach ($fact['detallefacturaselect'] as $det) {
                    $suma_consumo += $det['CONSUMO'];
                    $suma_excedido += $det['MEDEXCEDIDO'];
                    $suma_tar_excedido += $det['TAREXCEDIDO'];
                    $suma_aporteminga += $det['APORTEMINGA'];
                    $suma_alcantarillado += $det['ALCANTARILLADO'];
                    $suma_meses_cobrados++;
                    $suma_subtotal += $det['SUBTOTAL'];
                    $suma_total_det += $det['TOTAL'];
                    
                    $lista_detallefactura[$contar_lista]=$det;
                    $contar_lista++;
                }
                //buscar multas
                if(count($fact['otrospagos'])>0){
                    foreach ($fact['otrospagos'] as $recon) {
                        $suma_reconexion += $recon['TOTAL'];
                    }
                }
            }
            //sumas totales detalle
            $total_sumar_detalle = $suma_tar_excedido+$suma_aporteminga+$suma_alcantarillado+$suma_subtotal;
            $total_total = $suma_tar_excedido+$suma_aporteminga+$suma_alcantarillado+$suma_subtotal +$suma_reconexion;
            
            //pago de mingas
            $pagosasistencia = Pagosasistencia::select(
                'pagosasistencia.IDPAGOASISTENCIA','pagosasistencia.IDASISTENCIA','pagosasistencia.FECHAPAGO',
                'pagosasistencia.VALORMINGAS','pagosasistencia.NUMFACTURA'
            )
            ->with('asistenciaselect.medidorSelect.usersName')
            ->whereBetween('FECHAPAGO', [$fecha_inicial, $fecha_fin])
            ->get();
            $suma_mingapago = 0;
            foreach ($pagosasistencia as $mingapago) {
                $suma_mingapago += $mingapago['VALORMINGAS'];
            }

            //factura por instalacion
            $facturasinstalacion = Facturasinstalacion::
            whereBetween('FECHAEMISION', [$fecha_inicial, $fecha_fin])
            ->get();
            $suma_facturainstalacion = 0;
            foreach ($facturasinstalacion as $instala) {
                $suma_facturainstalacion += $instala['TOTAL'];
            }
            //factura ganaderia
            /*$facturasganaderia = Facturasganaderia::
            whereBetween('FECHAEMISION', [$fecha_inicial, $fecha_fin])
            ->get();
            $suma_ganaderia = 0;
            foreach ($facturasganaderia as $ganaderia) {
                $suma_ganaderia += $ganaderia['TOTAL'];
            }*/

            //factura aguasobrante
            /*$facturassobrante = Facturassobrante::
            whereBetween('FECHAEMISION', [$fecha_inicial, $fecha_fin])
            ->get();
            $suma_aguasobrante = 0;
            foreach ($facturassobrante as $sobrante) {
                $suma_aguasobrante += $sobrante['TOTAL'];
            }*/

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Reporte general de cobros',
                'code'=>200,

                //'controlaniomes_in'=>$controlaniomes_in,
                //'controlaniomes_f'=>$controlaniomes_f,
                //'fecha_inicial'=>$fecha_inicial,
                //'fecha_fin'=>$fecha_fin,

                'suma_consumo'=>round($suma_consumo,2),
                'suma_excedido'=>round($suma_excedido,2),
                'suma_tar_excedido'=>round($suma_tar_excedido,2),
                'suma_aporteminga'=>round($suma_aporteminga,2),
                'suma_alcantarillado'=>round($suma_alcantarillado,2),
                'suma_subtotal'=>round($suma_subtotal,2),
                'suma_total_det'=>round($suma_total_det,2),
                'total_sumar_detalle'=>round($total_sumar_detalle,2),

                'suma_reconexion'=>$suma_reconexion,
                'total_total'=>$total_total,

                'suma_subtotal_fact'=>round($suma_subtotal_fact,2),
                'suma_total_fact'=>round($suma_total_fact,2),
                'total_facturas'=>count($listafacturas),

                'suma_meses_cobrados'=>$suma_meses_cobrados,
                'total_usuarioscobrados'=>count($total_usuarioscobrados),
                'total_usuariosporcobrar'=>count($total_usuariosporcobrar),
                'pagosasistencia'=>$pagosasistencia,

                'pagosasistencia_total'=>count($pagosasistencia),
                'suma_mingapago'=>$suma_mingapago,
                'facturasinstalacion'=>count($facturasinstalacion),
                'suma_facturainstalacion'=>$suma_facturainstalacion,
                //'facturasganaderia'=>count($facturasganaderia),
                //'suma_ganaderia'=>$suma_ganaderia,
                //'facturassobrante'=>count($facturassobrante),
                //'suma_aguasobrante'=>$suma_aguasobrante,
                //'total'=>count($objeto),
                'controlaniomes'=>$controlaniomes
                //'lista_detallefactura'=>$lista_detallefactura,
                //'listafacturas'=>$listafacturas
                
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
