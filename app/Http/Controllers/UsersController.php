<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Institucion;
use App\Models\User;
use App\Models\Roles;
use App\Models\Medidor;
use App\Models\Fotos;
use App\Models\Medidorusers;
use App\Models\Asistencia;
use App\Models\Aguaganaderia;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class UsersController extends Controller
{
    /**
     * UserController constructor.
     * @param User $User
     */
    public function __construct()
    {
        //
    }
    /**
     * mostrar Users
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUser(Request $request){
            //$objeto = User::paginate(10);
            $objeto = User::with('medidor')
            ->where('roles_id',5)
            ->get();
            //$objeto->withPath('/admin/users');
            //return $objeto;
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario',
                'code'=>200,
                //'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        if ($request->isJson()) {
            //$objeto=User::all();
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar User por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdUser(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = User::find($id);
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Usuario Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Get user por header token
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserHeader(Request $request){
        if ($request->isJson()){ 
            try
            {
                $token = $request->header('token');
                $user=User::where('api_token', $token)->first();

                return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Usuario obtenido por token',
                        'message'=>'get User header',
                        'code'=>200,
                        'token'=>$token,
                        'data'=>$user
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
     * Crear User
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createUser(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

            $data=$request->json()->all();


            //generar username
            /*
            $temp_username = substr(microtime(), 1, 8);
            $username=strtolower($this->generarUsername($data['nombres'],$data['apellidos']).$temp_username);
            if($user){
                $temp_username = substr(microtime(), 1, 8);
                $username=strtolower($this->generarUsername($data['nombres'],$data['apellidos']).$temp_username);
            }
            */
            $user=User::where('usuario',$data['RUCCI'])->first();
            $verificarus=0;
            //no existe usuario
            
            if(!$user){
                $verificarus=0;
                //return "no hay usuario";
            }
            //si existe usuario
            else{
                //crear usuario administrador
                if($data['roles_id']!=5){
                    //buscar usuario administrador
                    $useradmin=User::
                    where('usuario',$data['RUCCI'])
                    ->where('roles_id','!=',5)
                    ->first();
                    if($useradmin){
                        $verificarus=1;
                        //return "Existe usuario administrador";
                    }
                    else{
                        $verificarus=0;
                        //return "esta creando usuario aministrador";
                    }
                }
                //crear usuario cliente
                else{
                    $usercliente=User::
                    where('usuario',$data['RUCCI'])
                    ->where('roles_id',5)
                    ->first();
                    if($usercliente){
                        $verificarus=1;
                    }else{
                        $verificarus=0;
                    }
                    //return "usuario ya existe";
                }
            }
            //validar cedula
            // Crear nuevo objeto
            //$validador = new ValidarCedula;
            //return $validador->validar('17214092720012');
            //crear usuario

            //transaccion
            DB::beginTransaction();
            try {
                $objeto = new User();
                $imagen = new Fotos();
                if($verificarus==0){

                    //$objeto->link=$data['RUCCI'];
                    if($data['roles_id']!=5){
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
                    $objeto->ESTADO='1';
                    $objeto->VISTO='0';
                    $objeto->api_token=Str::random(60);
                    $objeto->remember_token=$data['RUCCI'];
                    $objeto->roles_id=$data['roles_id'];
                    $objeto->save();
                    //crear imagen
                    $imagen->title=$data['NOMBRES'].' '.$data['APELLIDOS'];
                    $imagen->description=$data['RUCCI'];
                    $imagen->thumbnail='assets/img/profile/profile_long.png';
                    $imagen->imagelink='assets/img/profile/profile_small.jpg';
                    $imagen->IDUSUARIO=$objeto["id"];
                    $imagen->estado='0';
                    $imagen->save();
                    //return $objeto;
                }else{
                    return response()->json(['error'=>'CI/RUC ya existe, ingrese usuario nuevo'],401,[]);
                }


                //default email destino .- No tocar - correo en el frontend
                $message="";
                if($objeto['email']!=""||$objeto['email']!=null){
                    $message="Usuario creado, email enviado"; 
                }
                else{
                    $message="Usuario creado";
                }
                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>$message,
                    'code'=>201,
                    'data'=>$objeto,
                    'imagen'=>$imagen
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
    //generar username*********
    public function generarUsername($nombres,$apellidos){
                
        $j=1;
        $username="";
        $largo_nombre=count(explode(" ", $nombres));
        $largo_apellido=count(explode(" ", $apellidos));

        //$temp = substr(microtime(), 1, 8);
        if($largo_nombre===2&&$largo_apellido===2){
            list($n1,$n2)=explode(" ", $nombres);
            list($a1,$a2)=explode(" ", $apellidos);
            $n1=preg_replace('/[ <>\'\"]/', '', $n1);
            $n2=preg_replace('/[ <>\'\"]/', '', $n2);
            $a1=preg_replace('/[ <>\'\"]/', '', $a1);
            $a2=preg_replace('/[ <>\'\"]/', '', $a2);
            $username=(substr($n1, 0,$j)).(substr($n2, 0,$j)).(substr($a1, 0,$j)).(substr($a2, 0,$j));
        }
        if($largo_nombre===2&&$largo_apellido===1){
            list($n1,$n2)=explode(" ", $nombres);
            list($a1)=explode(" ", $apellidos);
            $n1=preg_replace('/[ <>\'\"]/', '', $n1);
            $n2=preg_replace('/[ <>\'\"]/', '', $n2);
            $a1=preg_replace('/[ <>\'\"]/', '', $a1);
            $username=(substr($n1, 0,$j)).(substr($n2, 0,$j)).(substr($a1, 0,$j));
        }
        if($largo_nombre===1&&$largo_apellido===2){
            list($n1)=explode(" ", $nombres);
            list($a1,$a2)=explode(" ", $apellidos);
            $n1=preg_replace('/[ <>\'\"]/', '', $n1);
            $a1=preg_replace('/[ <>\'\"]/', '', $a1);
            $a2=preg_replace('/[ <>\'\"]/', '', $a2);
            $username=(substr($n1, 0,$j)).(substr($a1, 0,$j)).(substr($a2, 0,$j));
        }
        if($largo_nombre===1&&$largo_apellido===1){
            list($n1)=explode(" ", $nombres);
            list($a1)=explode(" ", $apellidos);
            $n1=preg_replace('/[ <>\'\"]/', '', $n1);
            $a1=preg_replace('/[ <>\'\"]/', '', $a1);
            $username=(substr($n1, 0,$j)).(substr($a1, 0,$j));
        }
        return $username;
    }
    /**
     * Actualizar User
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUser(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = User::findOrFail($id);
                $data = $request->json()->all();

                //transaccion
                DB::beginTransaction();

                try {

                    if($objeto){
                        if($data['roles_id']==1||$data['roles_id']==2||$data['roles_id']==3||$data['roles_id']==4){
                            $objeto->usuario='A'.$data['RUCCI'];
                            $objeto->remember_token='A'.$data['RUCCI'];
                        }else{
                            $objeto->usuario=$data['RUCCI'];
                            $objeto->remember_token=$data['RUCCI'];
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
                        $objeto->save();

                        //crear imagen
                        $fotos=Fotos::where('IDUSUARIO',$id)->first();

                        $fotos->title=$data['NOMBRES'].' '.$data['APELLIDOS'];
                        $fotos->description=$data['RUCCI'];
                        $fotos->save();
                    }
                    DB::commit();

                    return response()->json(array('status'=>'sucsess','title'=>'Update users','message'=>'Dato Actualizada','code'=>200,'data'=>$objeto),200);

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
     * Actualizar User
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserAguaGanaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = User::findOrFail($id);
                $data = $request->json()->all();

                //transaccion
                DB::beginTransaction();

                try {

                    if($objeto){
                        
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
                        $objeto->save();

                        //crear imagen
                        $fotos=Fotos::where('IDUSUARIO',$id)->first();

                        $fotos->title=$data['NOMBRES'].' '.$data['APELLIDOS'];
                        $fotos->description=$data['RUCCI'];
                        $fotos->save();

                        //buscar aguaganaderia
                        $buscar_ganaderia=Aguaganaderia::
                        where('IDUSUARIO', $id)
                        ->first();

                        $buscar_ganaderia->SECTOR = $data['SECTOR'];
                        $buscar_ganaderia->REFERENCIA = $data['REFERENCIA'];
                        //$buscar_ganaderia->CODIGOAGUAGANADERIA = $aguaganaderia['CODIGOAGUAGANADERIA'];
                        $buscar_ganaderia->OBSERVACION = $data['OBSERVACION'];
                        $buscar_ganaderia->save();
                    }
                    DB::commit();

                    return response()->json(array('status'=>'sucsess','title'=>'Update users','message'=>'Dato Actualizada','code'=>200,'data'=>$objeto),200);

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
     * Eliminar User
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyUser(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = User::findOrFail($id);
                $objeto->delete();
                return response()->json(array('status'=>'sucsess','title'=>'Delete data','message'=>'Dato Eliminada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /*
    * 
    * getToken iniciar sesion *- 
    * Verificar Kernel y comentar //\App\Http\Middleware\VerifyCsrfToken::class,
    */
    function login(Request $request){
        if ($request->isJson()) {
            try{

                $data=$request->json()->all();
                
                $u=User::where('usuario',$data['usuario'])->first();
                if($u){

                    $fotos=Fotos::where('IDUSUARIO',$u['id'])->first();
                    $institucion=Institucion::where('IDINSTITUCION',$u['IDINSTITUCION'])->first();
                    if(Hash::check($data['password'],$u->password)){
                        return response()->json(array(
                            'status'=>'success',
                            'title'=>'Login success',
                            'message'=>'Sesion iniciada correctamente',
                            'code'=>200,
                            'data'=>$u,
                            'fotos'=>$fotos,
                            'estado'=>$u["estado"],
                            'institucion'=>$institucion
                        ),200);
                        Log::alert($error);
                    }
                    else{
                        return response()->json(['error'=>'Clave incorrecto'],406);
                    }
                }
                else{
                    return response()->json(['error'=>'Usuario no existe'],406);
                }
            }
            catch(Exception $e){
                return response()->json(['error'=>'captura excepcion',406]);
                Log::error($e);
            }
        }
    }

    /*
    * 
    * Actualizar columna clave de la tabla usuario
    * Verificar Kernel y comentar //\App\Http\Middleware\VerifyCsrfToken::class,
    */
    function actualizarClaveDeUsuario(Request $request,$usuario_id){
        set_time_limit(0);
        if ($request->isJson()) {
            try{

                $objeto=User::find($usuario_id);
                $data = $request->json()->all();

                //transaccion
                DB::beginTransaction();

                try {

                    //usuariocliente
                    $objeto->password=Hash::make($data['PASSWORD']);
                    $objeto->remember_token=$data['PASSWORD'];
                    $objeto->save();

                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Users update success',
                        'message'=>'usuarios actualizados correctamente',
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
            }
            catch(Exception $e){
                return response()->json(['error'=>'captura excepcion',406]);
                Log::error($e);
            }
        }
    }
    /*
    * 
    * Copiar una tabla a otra
    * 
    */
    function copyUserToFotos(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {
            try{

                $objeto=User::all();

                $fotos= new Fotos();
                $i=0;
                foreach ($objeto as $us) {

                    $datafotos=new Fotos();

                    $datafotos->title=rtrim($us['NOMBRES']).' '.ltrim($us['APELLIDOS']);
                    $datafotos->description=$us['usuario'];
                    $datafotos->thumbnail='assets/img/profile/profile_long.png';
                    $datafotos->imagelink='assets/img/profile/profile_small.jpg';
                    $datafotos->IDUSUARIO=$us['id'];
                    $datafotos->estado=$us['ESTADO'];
                    $datafotos->save();

                    $fotos[$i]=$datafotos;
                    $i++;
                }
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Fotos create success',
                    'message'=>'Tabla fotos actualizados correctamente',
                    'code'=>200,
                    'data'=>$fotos
                ),200);
            }
            catch(Exception $e){
                return response()->json(['error'=>'captura excepcion',406]);
                Log::error($e);
            }
        }
    }
    /**
     * mostrar Users totales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserTotales(Request $request){
        if ($request->isJson()) {
            $total=User::where('roles_id',5)->count();

            //total usuarios
            
            $total_activos=User::where([['roles_id',5],['ESTADO',1],['VISTO',1]])->count();
            $total_inactivos=User::where([['roles_id',5],['ESTADO',0]])->count();
            $total_nuevos=User::where([['roles_id',5],['ESTADO',1],['VISTO',0]])->count();



            //total medidores
            $medidor = Medidor::All();
            $medidor_activos = array();
            $medidor_inactivos = array();
            $medidor_suspendido = array();
            $medidor_retirado = array();
            $activo = 0;
            $inactivo = 0;
            $suspendido = 0;
            $retirado = 0;
            foreach ($medidor as $med) {
                if($med['ESTADO']=='ACTIVO'){
                    $medidor_activos[$activo]= $med;
                    $activo++;
                }
                else if($med['ESTADO']=='INACTIVO'){
                    $medidor_inactivos[$inactivo]= $med;
                    $inactivo++;
                }
                else if($med['ESTADO']=='SUSPENDIDO'){
                    $medidor_suspendido[$suspendido]= $med;
                    $suspendido++;
                }
                else if($med['ESTADO']=='RETIRADO'){
                    $medidor_retirado[$retirado]= $med;
                    $retirado++;
                }
            }


            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Listas totales',
                'code'=>200,
                'total'=>$total,
                'total_activos'=>$total_activos,
                'total_inactivos'=>$total_inactivos,
                'total_nuevos'=>$total_nuevos,
                'total_medidor' =>count($medidor),
                'medidor_activos' =>count($medidor_activos),
                'medidor_inactivos' =>count($medidor_inactivos),
                'medidor_suspendido' =>count($medidor_suspendido),
                'medidor_retirado' =>count($medidor_retirado)
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Users
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserMedidor(Request $request){
        if ($request->isJson()) {
            $objeto=User::
            with('medidor')
            ->where('roles_id',5)
            ->orderBy('id','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Users medidor estado activo
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserMedidorEstado(Request $request,$asistencia_id){
        if ($request->isJson()) {

            $medidor = Medidor::
            select('medidor.*')
            ->join('users','users.id','medidor.IDUSUARIO')
            ->with('usersName')
            ->where('medidor.ESTADO','ACTIVO')
            ->orderBy('SECTOR','ASC')
            ->get();
            //return $medidor;

            $asistencia=Asistencia::
            with('medidorSelect.usersName')
            ->where("IDPLANIFICACION",$asistencia_id)
            ->get();

            $dato = array();
            $j = 0;
            $existe = false;
            $sinlectura = array();
            foreach ($medidor as $med) {
                $existe = false;
                
                foreach ($asistencia as $asis) {
                    if($asis['IDMEDIDOR']==$med['IDMEDIDOR']){
                        $existe = true;
                        break;
                    }
                }
                if(!$existe){
                    $sinlectura[$j]=$med;
                    $j++;
                }
            }

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario',
                'code'=>200,
                'total'=>count($medidor),
                'total_sinlectura'=>count($sinlectura),
                'total_conlectura'=>count($asistencia),
                'data'=>$sinlectura,
                'conlectura'=>$asistencia
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar Users medidor estado activo
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserMedidorEstadoActivo(Request $request){
        if ($request->isJson()) {
            $objeto=User::
            select('users.*')
            ->with('medidor')
            ->join('medidor','users.id','medidor.IDUSUARIO')
            ->where('roles_id',5)
            //->where('users.estado',1)
            ->where('medidor.ESTADO','ACTIVO')
            ->orderBy('medidor.IDMEDIDOR','ASC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * Actualizar User
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstadoUsuario(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = User::find($id);
                $data = $request->json()->all();

                $objeto->ESTADO=$data['ESTADO'];
                $objeto->VISTO=$data['VISTO'];

                $objeto->save();
                return response()->json(array('status'=>'sucsess','title'=>'Update users data','message'=>'Estado Actualizado','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * mostrar datos de usuario y medidor por numeromedidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getUserMedidorNumeromedidor(Request $request,$numero_medidor){
        if ($request->isJson()) {
            $objeto=User::
            select('users.*')
            ->with('medidor')
            ->join('medidor','medidor.IDUSUARIO','users.id')
            ->where('medidor.NUMMEDIDOR',$numero_medidor)
            ->first();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get data NUMMEDIDOR',
                'message'=>'Datos de usuario y medidor',
                'code'=>200,
                'data'=>$objeto
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
    function getUserRucCi(Request $request,$rucci,$roles_id,$estado){
        if ($request->isJson()) {

            //buscar por cedula
            $usuario= new User();
            if($roles_id==5){
                $usuario = Medidorusers::
                with('medidor')
                ->select(
                    'users.*','medidor.IDMEDIDOR',
                    'up.id as up_id','up.RUCCI as up_RUCCI','up.NOMBRES as up_nombres','up.APELLIDOS as up_apellidos',
                    'up.SECTOR as up_sector','up.estado as up_estado','up.APADOSN as up_apodo',
                    'medidorusers.NIVEL'
                )
                ->join('users','users.id','medidorusers.IDUSUARIO_HIJO')
                ->join('users as up','up.id','medidorusers.IDUSUARIO')
                ->join('medidor','medidor.IDMEDIDOR','medidorusers.IDUSUARIO')
                ->where('users.RUCCI',$rucci)
                ->where('users.roles_id',$roles_id)
                ->where('users.estado',$estado)
                ->get();    

            }
            else{
                $usuario = User::
                where('RUCCI',$rucci)
                ->where('roles_id',$roles_id)
                ->where('estado',$estado)
                ->get();
            }
            //return $usuario;

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data user',
                'message'=>'Busqueda de usuario por cedula',
                'code'=>200,
                'total_usuarios'=>count($usuario),
                'rucci'=>$rucci,
                'roles_id'=>$roles_id,
                'estado'=>$estado,
                'data'=>$usuario
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar Users
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserAdmin(Request $request){
        if ($request->isJson()) {
            $objeto = User::
            //where('roles_id','2')
            Where('roles_id','3')
            ->orWhere('roles_id','4')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario administradores',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
            //$objeto=User::all();
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar Users cliente
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserCliente(Request $request){
        if ($request->isJson()) {
            $objeto = User::where('roles_id',5)->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de usuario',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Obtener usuarios con medidor retirado y usuario sin medidor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getUserMedidorRetirado(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {
            
            $objeto = User::
            select('users.*')
            ->leftJoin('medidor', function ($join){
                $join->on('medidor.IDUSUARIO', 'users.id');
            })
            ->with('medidor')
            ->where('roles_id',5)
            ->get();

            //MEDIDORES RETIRADOS
            $medidor_retirado=array();
            $dat=0;
            foreach ($objeto as $med) {
                if(count($med['medidor'])>0){
                    if($med['medidor'][0]['ESTADO']=='RETIRADO'){
                        $medidor_retirado[$dat]=$med;
                        $dat++;
                    }
                }
            }
            //USUARIOS sin medidor
            $sinmedidor=array();
            $num=0;
            foreach ($objeto as $med) {
                if(count($med['medidor'])==0){
                    $sinmedidor[$num]=$med;
                    $num++;
                }
            }


            $dato_nuevo=array();
            $j=0;
            foreach ($medidor_retirado as $retirado) {
                array_push($sinmedidor,$retirado);
            }

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get Users, Medidor',
                'message'=>'Lista de medidores retirados y sin medidores',
                'code'=>200,
                'sinmedidor'=>count($sinmedidor),
                'data'=>$sinmedidor
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
