<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aguasobrante;
use App\Models\Tarifassobrante;
use App\Models\Detallefacturasobrante;
use App\Models\Controlaniomessobrante;
use App\Models\User;
use App\Models\Fotos;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AguasobranteController extends Controller
{
     /**
     * mostrar Aguasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllAguasobrante(Request $request){
        if ($request->isJson()) {
            $objeto=Aguasobrante::
            with('usersSelect')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Aguasobrante',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Aguasobrante por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdAguasobrante(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Aguasobrante::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Aguasobrante id',
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
     * Crear Aguasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createAguasobrante(Request $request){
        if ($request->isJson()) {

            $data=$request->json()->all();


            $aguasobrante = $data[0]['aguasobrante'];
            $users = $data[0]['users'];
            //return $aguasobrante;

            $buscar_usuario = User::
            where('RUCCI',$users['RUCCI'])
            ->where('roles_id',7)
            ->first();

            if($buscar_usuario){
                return response()->json(['error'=>'Usuario ya está registrado en agua sobrante'],401,[]);
            }
            //transaccion
            DB::beginTransaction();
            try {

                //crear usuario sin acceso al sistema
                $user = new User();
                //$user->usuario=$data['RUCCI'];
                //$user->password=Hash::make($data['RUCCI']);
                $user->roles_id=7;//sobrante
                $user->email=$users['email'];
                $user->IDINSTITUCION=$users['IDINSTITUCION'];
                $user->RUCCI=$users['RUCCI'];
                $user->NOMBRES=$users['NOMBRES'];
                $user->APELLIDOS=$users['APELLIDOS'];
                $user->APADOSN=$users['APADOSN'];
                $user->DIRECCION=$users['DIRECCION'];
                $user->TELEFONO=$users['TELEFONO'];
                $user->CELULAR=$users['CELULAR'];
                $user->SECTOR=$aguasobrante['SECTOR'];
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

                $objeto=new Aguasobrante();
                
                $objeto->IDUSUARIO = $user['id'];
                $objeto->SECTOR = $aguasobrante['SECTOR'];
                $objeto->REFERENCIA = $user['REFERENCIA'];
                $objeto->CODIGOAGUASOBRANTE = $aguasobrante['CODIGOAGUASOBRANTE'];
                $objeto->OBSERVACION = $user['OBSERVACION'];
                $objeto->ESTADO = 1;
                $objeto->VALORPORCONEXION = $aguasobrante['VALORPORCONEXION'];
                $objeto->PAGADO = 0;
                $objeto->SALDO = $aguasobrante['VALORPORCONEXION'];
                $objeto->FECHA = $aguasobrante['FECHA'];

                $objeto->save();

                //obtener tarifasganaderia
                $tarifassobrante = Tarifassobrante::latest()->first();
                //crear factura de instalacion detalefacturaaguasobrante
                $detallefacturasobrante = new Detallefacturasobrante();
                $detallefacturasobrante->IDTARIFASSOBRANTE=$tarifassobrante['IDTARIFASSOBRANTE'];
                $detallefacturasobrante->IDAGUASOBRANTE=$objeto['IDAGUASOBRANTE'];
                $aniomes =Carbon::parse($aguasobrante['FECHA'])->isoFormat('Y-M');
                $detallefacturasobrante->ANIOMES=$aniomes;
                $detallefacturasobrante->SUBTOTAL=$aguasobrante['VALORPORCONEXION'];
                $detallefacturasobrante->TOTAL=$aguasobrante['VALORPORCONEXION'];
                $detallefacturasobrante->OBSERVACION='Instalacion de agua sobrante';
                $detallefacturasobrante->DETALLE = 'INSTALACION';
                $detallefacturasobrante->estado=0;
                $detallefacturasobrante->save();


                DB::commit();
                    
                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Agua sobrante creada',
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
     * Actualizar Aguasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAguasobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Aguasobrante::findOrFail($id);
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
     * Eliminar Aguasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAguasobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Aguasobrante::findOrFail($id);
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
     * mostrar Aguasobrante totales
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllAguasobranteTotales(Request $request){
        if ($request->isJson()) {
            $objeto=Aguasobrante::count();
            $total_activos=Aguasobrante::
            where('ESTADO',1)
            ->count();

            $total_inactivos=Aguasobrante::
            where('ESTADO',0)
            ->count();

            $total_retirados=Aguasobrante::
            where('ESTADO',3)
            ->count();


            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de totales Aguasobrante',
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
     * mostrar Aguasobrante por user
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllUserAguasobrante(Request $request){
        if ($request->isJson()) {

            $objeto=User::
            select(
                'users.*'
            )
            ->with('aguasobrante')
            ->join('aguasobrante','aguasobrante.IDUSUARIO','users.id')
            ->where('aguasobrante.estado','!=',3)
            ->orderBy('users.id','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Aguasobrante',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar lista de usuarios registrados para detallefacturasobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdControlAguasobrante(Request $request, $controlaguasobrante_id)
    {
        if ($request->isJson()){ 
            try
            {

                $aguasobrante=Aguasobrante::
                with('usersSelect')
                ->get();

                $objeto = Detallefacturasobrante::
                with('aguasobrante.usersSelect')
                ->where('controlaniomessobrante_id',$controlaguasobrante_id)
                ->get();

                $i = 0;
                $j = 0;
                $existe = false;
                $sinlectura = array();
                foreach ($aguasobrante as $med) {
                    $existe = false;
                    //los que ya existen
                    foreach ($objeto as $det) {
                        if($med->IDAGUASOBRANTE==$det->IDAGUASOBRANTE){
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
                    'message'=>'Dato Obtenida, Aguasobrante id',
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
