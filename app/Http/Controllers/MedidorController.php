<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medidor;
use App\Models\User;
use App\Models\Fotos;
use App\Models\Medidorusers;
use App\Models\Detallefacturainstalacion;
use App\Models\Facturasinstalacion;
use App\Models\Tarifas;
use App\Models\Detallefactura;
use App\Models\Facturas;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class MedidorController extends Controller
{
    /**
     * mostrar Medidors
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllMedidor(Request $request){
        if ($request->isJson()) {
            $objeto=Medidor::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de medidor',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Medidor por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdMedidor(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Medidor::findOrFail($id);
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Medidor Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Crear Medidor
     * medidor, medidorusers,users,fotos,facturas,detallefactura
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createMedidor(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

            $data=$request->json()->all();

            $medidor=$data[0]['medidor'];
            $users=$data[0]['users'];
            $controlaniomes=$data[0]['controlaniomes'];
            $usuario_actual=$data[0]['usuario_actual'];

            $buscar_usuario = User::
            where('RUCCI',$users['RUCCI'])
            ->where('roles_id',5)
            ->first();

            $today = Carbon::now();

            if($buscar_usuario){
                return response()->json(['error'=>'Datos ya existen, por favor cree un nuevo registro'],401,[]);
            }

            //transaccion
            DB::beginTransaction();
            try {

                $us = new User();
                $imagen = new Fotos();

                //usuario
                if(empty($users['USUARIO'])){
                    $us->usuario=$users['RUCCI'];
                }
                else{
                    $us->usuario=$users['USUARIO'];
                }
                //return $users['USUARIO'];
                //password
                if($users['PASSWORD']!=null||$users['PASSWORD']!=''){
                    $us->password=Hash::make($users['PASSWORD']);
                    $us->remember_token=$users['PASSWORD'];
                }
                else{
                    $us->password=Hash::make($users['RUCCI']);
                    $us->remember_token=$users['RUCCI'];
                }
                $us->email=$users['email'];
                $us->IDINSTITUCION=$users['IDINSTITUCION'];
                $us->RUCCI=$users['RUCCI'];
                $us->NOMBRES=$users['NOMBRES'];
                $us->APELLIDOS=$users['APELLIDOS'];
                $us->APADOSN=$users['APADOSN'];
                $us->DIRECCION=$users['DIRECCION'];
                $us->TELEFONO=$users['TELEFONO'];
                $us->CELULAR=$users['CELULAR'];
                $us->SECTOR=$users['SECTOR'];
                $us->REFERENCIA=$users['REFERENCIA'];
                $us->OBSERVACION=$users['OBSERVACION'];
                $us->ESTADO='0';
                $us->VISTO='0';
                $us->api_token=Str::random(60);
                $us->roles_id=5;
                $us->save();

                //crear imagen
                $imagen->title=$users['NOMBRES'].' '.$users['APELLIDOS'];
                $imagen->description=$users['RUCCI'];
                $imagen->thumbnail='assets/img/profile/profile_long.png';
                $imagen->imagelink='assets/img/profile/profile_small.jpg';
                $imagen->IDUSUARIO=$us["id"];
                $imagen->estado='0';
                $imagen->save();

                //crear medidor
                $med = new Medidor();
                $med->IDUSUARIO = $us["id"];
                $med->SERIE = $medidor["SERIE"];
                $med->NUMMEDIDOR = $medidor["NUMMEDIDOR"];
                $med->CODIGO = $medidor["CODIGO"];
                $med->ESTADO = 'ACTIVO';
                $med->VALORPORCONEXION = $medidor["VALORPORCONEXION"];
                $med->PAGADO = 'NO';
                $med->SALDO = $medidor["VALORPORCONEXION"];
                $med->FECHA = $medidor['FECHA'];
                $med->visto = '0';
                $med->save();

                //create medidorusers
                $medidorusers=new Medidorusers();
                $medidorusers->FECHA=$today;
                $medidorusers->IDUSUARIO=$us['id'];
                $medidorusers->IDMEDIDOR=$med["IDMEDIDOR"];
                $medidorusers->ESTADO=1;
                $medidorusers->NIVEL=1;
                $medidorusers->save();


                //crear factura
                $numfactura = Facturas::select('NUMFACTURA')->max('NUMFACTURA');
                $facturas = new Facturas();
                $numfactura++;
                $facturas->NUMFACTURA = $numfactura;
                $facturas->FECHAEMISION = $today;
                $facturas->SUBTOTAL = 0.0;
                $facturas->IVA = 0.0;
                $facturas->TOTAL = 0.0;
                $facturas->USUARIOACTUAL = $usuario_actual;
                $facturas->estado = 1; //CAMBIAR AQUI

                $facturas->save();


                //crear la primera facturadetalle//
                //obtener el ultimo registro de tarifas
                $tarifas = Tarifas::latest()->first();
                $detallefactura=new Detallefactura();

                $detallefactura->IDTARIFAS = $tarifas['IDTARIFAS'];
                $detallefactura->IDMEDIDOR = $med["IDMEDIDOR"];
                $detallefactura->ANIOMES = $controlaniomes["aniomes"];
                $detallefactura->MEDIDAANT = 0;
                $detallefactura->MEDIDAACT = 0;
                $detallefactura->CONSUMO = 0;
                $detallefactura->MEDEXCEDIDO = 0;
                $detallefactura->TAREXCEDIDO = 0;

                $detallefactura->APORTEMINGA = 0;
                $detallefactura->ALCANTARILLADO = 0;
                $detallefactura->SUBTOTAL = 0;
                $detallefactura->TOTAL = 0;
                $detallefactura->OBSERVACION = 'SI';
                $detallefactura->estado = 1;
                $detallefactura->IDFACTURA = $facturas['IDFACTURA'];
                $detallefactura->controlaniomes_id = $controlaniomes['id'];
                $detallefactura->NUMFACTURA = $facturas['NUMFACTURA'];

                $detallefactura->save();


                DB::commit();
                //default email destino .- No tocar - correo en el frontend
                $message="";
                if($users['email']!=""||$users['email']!=null){
                    $message="Usuario creado, email enviado"; 
                }
                else{
                    $message="Usuario creado";
                }
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>$message,
                    'code'=>201,
                    'allData'=>$data,
                    'data'=>$users,
                    'medidor'=>$medidor,
                    'med'=>$med,
                    'imagen'=>$imagen,
                    'medidorusers'=>$medidorusers,
                    'facturas'=>$facturas,
                    'detallefactura'=>$detallefactura
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
     * Actualizar Medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editMedidor(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Medidor::findOrFail($id);
                $data = $request->json()->all();


                if($objeto){
                    if($data['ROLES_ID']!=5){
                        $objeto->usuario='A'.$data['RUCCI'];
                    }else{
                        $objeto->usuario=$data['RUCCI'];
                    }
                    $objeto->password=Hash::make($data['RUCCI']);
                    $objeto->email=$data['email'];
                    $objeto->IDINSTITUCION=$data['IDINSTITUCION'];
                    $objeto->RUCCI=$data['RUCCI'];
                    $objeto->NOMBRES=$data['NOMBRES'];
                    $objeto->APELLIDOS=$data['APELLIDOS'];
                    $objeto->APADOSN=$data['APADOSN'];
                    $objeto->DIRECCION=$data['DIRECCION'];
                    $objeto->TELEFONO=$data['TELEFONO'];
                    $objeto->CELULAR=$data['CELULAR'];
                    $objeto->SECTOR=$data['SECTOR'];
                    $objeto->REFERENCIA=$data['REFERENCIA'];
                    $objeto->OBSERVACION=$data['OBSERVACION'];
                    $objeto->remember_token=$data['RUCCI'];
                    $objeto->save();
                }

                return response()->json(array('status'=>'sucsess','title'=>'Update Medidor','message'=>'Dato Actualizada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMedidor(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Medidor::findOrFail($id);
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
     * mostrar Medidor por id de usuario
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedidorIdUsers(Request $request, $users_id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = User::
                with('medidorusers.medidor.users')
                //->with('medidor')
                ->where('id',$users_id)
                ->first();
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Medidor Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * mostrar Medidor y User por medidor_id 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdMedidorUser(Request $request, $num_medidor)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Medidor::
                with('users')
                ->where('NUMMEDIDOR',$num_medidor)
                ->first();
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Medidor Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * mostrar Medidor y User por codigo 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdMedidorUserCodigo(Request $request, $codigo)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Medidor::
                with('users')
                ->where('CODIGO',$codigo)
                ->first();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Medidor Obtenido',
                    'code'=>200,
                    'saldo'=>$objeto['SALDO'],
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
     * mostrar Medidor y User por codigo 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdMedidorUserIdmedidor(Request $request, $IDMEDIDOR)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Medidor::
                with('users')
                ->where('IDMEDIDOR',$IDMEDIDOR)
                ->first();
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Medidor Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * mostrar Users
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllMedidorUser(Request $request){
        if ($request->isJson()) {
            $objeto=Medidor::
            select(
                'medidor.*'
            )
            ->join('users','users.id','medidor.IDUSUARIO')
            ->with('users')
            ->orderBy('IDMEDIDOR','ASC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de medidores',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /*copiar*/
    /**
     * copiar columna visto, creted_ad, update_at de la columna fecha
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarColumnafecha(Request $request)
    {
        if ($request->isJson()){ 
            try
            {
                $medidor = Medidor::All();
                $dato = array();
                $i = 0;
                foreach ($medidor as $med) {
                    $med->visto = 1;
                    $med->created_at = $med['FECHA'];
                    $med->updated_at = $med['FECHA'];
                    $med->save();

                    $dato[$i] = $med;
                    $i++;
                }
                //return $dato;
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Medidor copiado',
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
     * actualizar codigo de medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function updateCodigoMedidor(Request $request,$num_medidor){
        set_time_limit(0);
        if ($request->isJson()) {
            $data=$request->json()->all();

            $objeto =Medidor::
            where('NUMMEDIDOR',$num_medidor)
            ->first();

            //return $objeto;

            if($objeto==null||$objeto==''){
                return response()->json(['error'=>'Datos no existen'],401,[]);
            }
            else{
                //transaccion
                DB::beginTransaction();

                try {
                    
                    $objeto->CODIGO = $data['CODIGO'];

                    $objeto->save();

                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Update data',
                        'message'=>'Medidor codigo actualizado',
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
     * actualizar estado del medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function editMedidorEstado(Request $request,$IDUSUARIO){
        set_time_limit(0);
        if ($request->isJson()) {
            $data=$request->json()->all();

            $objeto =Medidor::
            where('IDUSUARIO',$IDUSUARIO)
            ->first();

            //return $objeto;

            if($objeto==null||$objeto==''){
                return response()->json(['error'=>'Datos no existen'],401,[]);
            }
            else{
                //transaccion
                DB::beginTransaction();

                try {
                    
                    $objeto->ESTADO = $data['ESTADO'];
                    if($data['ESTADO']=='RETIRADO'){
                        $objeto->CODIGO=null;
                    }

                    $objeto->save();

                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Update data',
                        'message'=>'Medidor estado actualizado',
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
     * Obtener codigo de medidor disponibles
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodigoMedidorDisponible(Request $request)
    {
        if ($request->isJson()){ 
            try
            {
                $medidor = Medidor::select(
                    'CODIGO'
                )
                ->where('CODIGO','!=',null)
                ->groupBy('CODIGO')
                ->orderBy('CODIGO','ASC')
                ->get();

                $ultimo_numero = Medidor::select('CODIGO')->max('CODIGO');

                $numero=0;
                $dato = array();
                foreach ($medidor as $med) {
                    $dato[$numero]=$med['CODIGO'];
                    $numero++;
                }
                $dato[$numero]=$ultimo_numero+1;
                $arrayRange = range(1,max($dato));
                $missingValues = array_diff($arrayRange,$dato);

                $array=array();
                $i=0;
                foreach ($missingValues as $value) {
                    array_push($array,$value);
                }
                array_push($array,($ultimo_numero+1));

                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get codigo medidor',
                    'message'=>'Codigo Obtenido',
                    'code'=>200,
                    'data'=>$array
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
     * mostrar Medidors
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function realizarPagoInstalacion(Request $request,$IDMEDIDOR){
        set_time_limit(0);
        if ($request->isJson()) {

            $alldata=$request->json()->all();
            $data = $alldata[0]['medidor_model'];
            $USUARIOACTUAL = $alldata[0]['USUARIOACTUAL'];

            $objeto=Medidor::where('IDMEDIDOR',$IDMEDIDOR)->first();

            //return $data;
            $abono = $data['SALDO'];
            $saldo = $objeto['SALDO'];
            if($saldo==0){
                return response()->json(['error' => 'No tiene valores pendientes de pago'], 401, []);
            }
            if($abono>$saldo){
                return response()->json(['error' => 'Pago debe ser menor o igual al saldo pendiente'], 401, []);
            }

            //transaccion
            DB::beginTransaction();

            try {

                //actualizar medidor
                $calular_saldo = $saldo - $abono;
                $objeto->SALDO = $calular_saldo;
                if($calular_saldo==0){
                    $objeto->PAGADO = 'SI';
                }
                $objeto->save();

                $today =Carbon::now();
                //buscar ultimo numero de factura
                $buscar_numerofactura = Facturasinstalacion::select('NUMFACTURA')->max('NUMFACTURA');
                $numerofac = 0;
                if($buscar_numerofactura){
                    $numerofac = $buscar_numerofactura;
                }
                $numerofac++;

                //crear facturainstalacion
                $facturainstalacion = new Facturasinstalacion();
                $facturainstalacion->NUMFACTURA = $numerofac;
                $facturainstalacion->FECHAEMISION = $today;
                $facturainstalacion->TOTAL = $abono;
                $facturainstalacion->USUARIOACTUAL = $USUARIOACTUAL;
                $facturainstalacion->estado = '1';
                $facturainstalacion->save();

                //crear detallefacturainstalacion
                $detallefacturainstalacion = new Detallefacturainstalacion();
                $detallefacturainstalacion->IDMEDIDOR = $IDMEDIDOR;
                $detallefacturainstalacion->TOTAL = $abono;
                $detallefacturainstalacion->OBSERVACION = 'Pago por instalacion';
                $detallefacturainstalacion->estado = 1;
                $detallefacturainstalacion->IDFACTURA = $facturainstalacion['IDFACTURA'];
                $detallefacturainstalacion->NUMFACTURA = $facturainstalacion['NUMFACTURA'];
                $detallefacturainstalacion->save();

                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Pago realizada',
                    'message'=>'Pago de instalacion de medidor',
                    'code'=>201,
                    'data'=>$facturainstalacion,
                    'medidor'=>$objeto,
                    'detallefacturainstalacion'=>$detallefacturainstalacion
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
     * mostrar Medidores activos y user
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllMedidorUserActivo(Request $request){
        if ($request->isJson()) {
            $objeto=Medidor::
            with('users')
            ->where('ESTADO','!=','RETIRADO')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de medidor',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
