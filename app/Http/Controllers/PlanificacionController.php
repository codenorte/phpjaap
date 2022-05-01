<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planificacion;
use App\Models\Medidor;
use App\Models\Asistencia;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class PlanificacionController extends Controller
{
     /**
     * mostrar Planificacion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllPlanificacion(Request $request){
        if ($request->isJson()) {
            $objeto=Planificacion::
            withCount('asistencia')
            ->orderBy('IDPLANIFICACION','DESC')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Planificacion',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Planificacion por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdPlanificacion(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Planificacion::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, planificacion id',
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
     * Crear Planificacion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createPlanificacion(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();

            DB::beginTransaction();

            try {

                $objeto= new Planificacion();

                $objeto->TIPOPLANIFICACION = $data['TIPOPLANIFICACION'];
                $objeto->LUGAR = $data['LUGAR'];
                $objeto->FECHA = $data['FECHA'];
                $objeto->VALORMULTA = $data['VALORMULTA'];
                $objeto->DESCRIPCION = $data['DESCRIPCION'];
                $objeto->estado = $data['estado'];
                $objeto->save();
                
                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
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
     * Actualizar Planificacion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editPlanificacion(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Planificacion::findOrFail($id);
                $data = $request->json()->all();

                DB::beginTransaction();

                try {

                    $objeto->TIPOPLANIFICACION = $data['TIPOPLANIFICACION'];
                    $objeto->LUGAR = $data['LUGAR'];
                    $objeto->FECHA = $data['FECHA'];
                    $objeto->VALORMULTA = $data['VALORMULTA'];
                    $objeto->DESCRIPCION = $data['DESCRIPCION'];
                    $objeto->estado = $data['estado'];
                    $objeto->save();
                    DB::commit();
                    
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Update data',
                        'message'=>'Planificacion Actualizada',
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
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Planificacion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPlanificacion(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Planificacion::findOrFail($id);
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
     * cambia de estado revisando el tiempo de caducidad 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function caducarPlanificacionEstado(Request $request){
        if ($request->isJson()) {
            $objeto=Planificacion::
            where('estado',1)
            ->orderBy('IDPLANIFICACION','DESC')
            ->get();

            DB::beginTransaction();
            if(count($objeto)>0){
                try {

                    $today =Carbon::parse(Carbon::now())->isoFormat('Y-MM-DD');
                    $i = 0;
                    $array = array();
                    foreach ($objeto as $plan) {
                        if($today>$plan['FECHA']){
                            $plan->estado = 0;
                            $plan->save();

                            $array[$i]=$plan;
                            //return "Hay caducidad";
                        }
                        $i++;
                    }
                    DB::commit();
                    if(count($array)>0){
                        return response()->json(array(
                            'status'=>'sucsess',
                            'title'=>'Update data Planificacion',
                            'message'=>'Los datos fueron actualizados, tiempo caducado',
                            'code'=>200,
                            'total'=>count($array),
                            'data'=>$array
                        ),200);
                    }
                    else{
                         return response()->json(array(
                            'status'=>'sucsess',
                            'title'=>'Dato encontrado, Planificacion',
                            'message'=>'Existe planificacion activo',
                            'code'=>200,
                            'data'=>null
                        ),200);
                    }

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

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Dato no encontrado, Planificacion',
                'message'=>'No existe actualizacion',
                'code'=>200,
                'data'=>null
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * Crear Planificacion con todos los medidores activos se registra como NO ASISTIDO a la minga
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createPlanificacionAllUser(Request $request,$planificacion_id){
        if ($request->isJson()) {
            $data=$request->json()->all();

            //return $data[0]; 

            $planificacion = Planificacion::find($planificacion_id);
            //return $planificacion;

            $medidores=Medidor::
            where('ESTADO','ACTIVO')
            ->get();

            $asistencia=Asistencia::
            where("IDPLANIFICACION",$planificacion_id)
            ->get();

            $j = 0;
            $existe = false;
            $sinlectura = array();
            foreach ($medidores as $med) {
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

            DB::beginTransaction();

            try {

                $i = 0;
                $array= array();
                foreach ($data as $med) {

                    $objeto= new Asistencia();

                    $objeto->IDPLANIFICACION=$planificacion['IDPLANIFICACION'];
                    $objeto->IDMEDIDOR=$med['IDMEDIDOR'];
                    if(!empty($med['checked'])){
                        $objeto->ASISTENCIA='SI';
                        $objeto->VALORMULTA=0;
                        $objeto->OBSEVACION='SI';
                        $objeto->estado='1';
                    }
                    else{
                        $objeto->ASISTENCIA='NO';
                        $objeto->VALORMULTA=$planificacion['VALORMULTA'];
                        $objeto->OBSEVACION='NO';
                        $objeto->estado='0';
                    }
                    $objeto->DESCRIPCION=$planificacion['DESCRIPCION'];
                    $objeto->save();


                    $array[$i]=$objeto;
                    $i++;
                }

                
                DB::commit();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data Asistencia',
                    'message'=>'Lista de usuarios registrados',
                    'code'=>201,
                    'total'=>count($array),
                    'data'=>$array
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
}
