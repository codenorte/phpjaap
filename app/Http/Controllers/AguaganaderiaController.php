<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aguaganaderia;
use App\Models\Tarifasganaderia;
use App\Models\Detallefacturaganaderia;
use App\Models\User;
use App\Models\Fotos;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AguaganaderiaController extends Controller
{
     /**
     * mostrar Aguaganaderias
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllAguaganaderia(Request $request){
        if ($request->isJson()) {

            $objeto=Aguaganaderia::
            with('usersSelect')
            ->orderBy('SECTOR','ASC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Aguaganaderia',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Aguaganaderia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdAguaganaderia(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Aguaganaderia::
                with('usersSelect')
                ->where('IDAGUAGANADERIA',$id)
                ->first();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Aguaganaderia id',
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
     * Crear Aguaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createAguaganaderia(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();

            $aguaganaderia = $data[0]['aguaganaderia'];
            $users = $data[0]['users'];
            //return $users;
            
            $buscar_usuario = User::
            where('RUCCI',$users['RUCCI'])
            ->where('roles_id',6)
            ->first();
            if($buscar_usuario){
                return response()->json(['error'=>'Usuario ya está registrado en aguaganaderia'],401,[]);
            }
            //return $buscar_usuario;

            //transaccion
            DB::beginTransaction();
            try {

                //crear usuario sin acceso al sistema
                $user = new User();
                //$user->usuario=$data['RUCCI'];
                //$user->password=Hash::make($data['RUCCI']);
                $user->roles_id=6;//ganaderia
                $user->email=$users['email'];
                $user->IDINSTITUCION=$users['IDINSTITUCION'];
                $user->RUCCI=$users['RUCCI'];
                $user->NOMBRES=$users['NOMBRES'];
                $user->APELLIDOS=$users['APELLIDOS'];
                $user->APADOSN=$users['APADOSN'];
                $user->DIRECCION=$users['DIRECCION'];
                $user->TELEFONO=$users['TELEFONO'];
                $user->CELULAR=$users['CELULAR'];
                $user->SECTOR=$users['SECTOR'];
                $user->REFERENCIA=$users['REFERENCIA'];
                $user->OBSERVACION=$users['OBSERVACION'];
                $user->ESTADO='1';
                $user->VISTO='0';
                $user->api_token=Str::random(60);
                //$user->remember_token=$data['RUCCI'];
                //return $user; 
                $user->save();

                //crear imagen
                $imagen = new Fotos();
                $imagen->title=$users['NOMBRES'].' '.$users['APELLIDOS'];
                $imagen->description=$users['RUCCI'];
                $imagen->thumbnail='assets/img/profile/profile_long.png';
                $imagen->imagelink='assets/img/profile/profile_small.jpg';
                $imagen->IDUSUARIO=$user["id"];
                $imagen->estado='0';
                $imagen->save();
                
                //crear aguaganaderia
                $objeto=new Aguaganaderia();
                $objeto->IDUSUARIO = $user['id'];
                $objeto->SECTOR = $user['SECTOR'];
                $objeto->REFERENCIA = $user['REFERENCIA'];
                $objeto->CODIGOAGUAGANADERIA = $aguaganaderia['CODIGOAGUAGANADERIA'];
                $objeto->OBSERVACION = $user['OBSERVACION'];
                $objeto->ESTADO = 1;
                $objeto->VALORPORCONEXION = $aguaganaderia['VALORPORCONEXION'];
                $objeto->PAGADO = 0;
                $objeto->SALDO = $aguaganaderia['VALORPORCONEXION'];
                $objeto->FECHA = $aguaganaderia['FECHA'];

                $objeto->save();

                //obtener tarifasganaderia
                $tarifasganaderia = Tarifasganaderia::latest()->first();
                
                $detallefacturaganaderia = new Detallefacturaganaderia();
                $detallefacturaganaderia->IDTARIFASGANADERIA=$tarifasganaderia['IDTARIFASGANADERIA'];
                $detallefacturaganaderia->IDAGUAGANADERIA=$objeto['IDAGUAGANADERIA'];
                $aniomes =Carbon::parse($aguaganaderia['FECHA']);
                $detallefacturaganaderia->ANIOMES=$aniomes;
                $detallefacturaganaderia->SUBTOTAL=$aguaganaderia['VALORPORCONEXION'];
                $detallefacturaganaderia->TOTAL=$aguaganaderia['VALORPORCONEXION'];
                $detallefacturaganaderia->OBSERVACION='Instalacion de agua de ganaderia';
                $detallefacturaganaderia->DETALLE = 'INSTALACION';
                $detallefacturaganaderia->estado=0;
                $detallefacturaganaderia->save();

                DB::commit();
                    
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Aguaganaderia creada, Detalle factura creada',
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
     * Actualizar Aguaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAguaganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Aguaganaderia::findOrFail($id);
                $data = $request->json()->all();
                $objeto->nombre = $data['nombre'];
                $objeto->descripcion = $data['descripcion'];
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
     * Eliminar Aguaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAguaganaderia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Aguaganaderia::findOrFail($id);
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
     * mostrar Aguaganaderias totales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllAguaganaderiaTotales(Request $request){
        if ($request->isJson()) {
            $objeto=Aguaganaderia::count();
            $total_activos=Aguaganaderia::
            where('ESTADO',1)
            ->count();

            $total_inactivos=Aguaganaderia::
            where('ESTADO',0)
            ->count();

            $total_retirados=Aguaganaderia::
            where('ESTADO',3)
            ->count();


            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de totales Aguaganaderia',
                'code'=>200,
                'data'=>$objeto,
                'total_activos'=>$total_activos,
                'total_inactivos'=>$total_inactivos,
                'total_retirados'=>$total_retirados
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }


     /**
     * mostrar Aguaganaderias leftjoin
    SELECT * FROM users us
    LEFT JOIN aguaganaderia agua
    ON us.id = agua.IDUSUARIO
    WHERE agua.IDUSUARIO IS NULL AND  us.roles_id=5;
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getUserLeftjoinAguaganaderia(Request $request){
        if ($request->isJson()) {

            $objeto=User::select(
                'users.*'
            )
            ->leftJoin('aguaganaderia', function ($join){
                $join->on('aguaganaderia.IDUSUARIO', 'users.id');
            })
            ->whereNull('aguaganaderia.IDUSUARIO')
            ->where('users.roles_id',5)
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Aguaganaderia',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

     /**
     * mostrar Aguaganaderias por user
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserAguaganaderia(Request $request){
        if ($request->isJson()) {

            $objeto=User::
            select(
                'users.*'
            )
            ->with('aguaganaderia')
            ->join('aguaganaderia','aguaganaderia.IDUSUARIO','users.id')
            ->where('aguaganaderia.estado','!=',3)
            ->orderBy('users.id','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Aguaganaderia',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * mostrar lista de usuarios registrados para detallefacturaganaderia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdControlAguaganaderia(Request $request, $controlaguaganaderia_id)
    {
        if ($request->isJson()){ 
            try
            {

                $aguaganaderia=Aguaganaderia::
                with('usersSelect')
                ->get();

                //return $aguaganaderia;

                $objeto = Detallefacturaganaderia::
                with('aguaganaderia.usersSelect')
                ->where('controlaniomesganaderia_id',$controlaguaganaderia_id)
                ->get();

                $i = 0;
                $j = 0;
                $existe = false;
                $sinlectura = array();
                foreach ($aguaganaderia as $med) {
                    $existe = false;
                    //los que ya existen
                    foreach ($objeto as $det) {
                        if($med->IDAGUAGANADERIA==$det->IDAGUAGANADERIA){
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
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Aguaganaderia id',
                    'code'=>200,
                    //'total'=>count($objeto),
                    'total_sinlectura'=>count($sinlectura),
                    'sinlectura'=>$sinlectura,
                    'conlectura'=>count($objeto),
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
