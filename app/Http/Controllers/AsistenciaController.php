<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Planificacion;

use Illuminate\Support\Facades\DB;
use Exception;

class AsistenciaController extends Controller
{
     /**
     * mostrar Asistencia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllAsistencia(Request $request){
        if ($request->isJson()) {
            $objeto=Asistencia::
            //orderBy('IDASISTENCIA','DESC')
            All();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Asistencia',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Asistencia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdAsistencia(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Asistencia::findOrFail($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Asistencia id',
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
     * Crear Asistencia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createAsistencia(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();
            $objeto=Asistencia::create([
                'nombre'=>$data['nombre'],
                'descripcion'=>$data['descripcion'],
                'estado'=>$data['estado']
            ]);
             return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Create data',
                'message'=>'Dato creada',
                'code'=>201,
                'data'=>$objeto
            ),201);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Asistencia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAsistencia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Asistencia::findOrFail($id);
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
     * Eliminar Asistencia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAsistencia(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Asistencia::findOrFail($id);
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
     * mostrar Asistencia por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdAsistenciaMedidorUsers(Request $request, $planificacion_id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Asistencia::
                select(
                    'asistencia.*'
                )
                ->join('medidor','medidor.IDMEDIDOR','asistencia.IDMEDIDOR')
                ->join('users','users.id','medidor.IDUSUARIO')
                ->where('IDPLANIFICACION',$planificacion_id)
                ->with('medidorSelect.usersName')
                ->orderBy('users.SECTOR','ASC')
                ->get();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, lista de usuarios',
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
     * Registrar asistencia, cambio de estado 
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarAsistencia(Request $request, $asistencia_id)
    {
        if ($request->isJson()){ 
            try
            {   
                //transaccion
                DB::beginTransaction();
                try {

                    $data = $request->json()->all();

                    $objeto = Asistencia::find($asistencia_id);
                    $planificacion = Planificacion::find($objeto['IDPLANIFICACION']);

                    if($data['ASISTENCIA']=='SI'){
                        $objeto->ASISTENCIA = 'SI';
                        $objeto->VALORMULTA = 0;
                        $objeto->OBSEVACION = 'SI';
                        $objeto->estado = '1';

                    }
                    else{
                        $objeto->ASISTENCIA = 'NO';
                        $objeto->VALORMULTA = $planificacion['VALORMULTA'];
                        $objeto->OBSEVACION = 'NO';
                        $objeto->estado = '0';
                    }
                    $objeto->save();

                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Edit Asistencia',
                        'message'=>'Asistencia editada',
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
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Buscar multas de mingas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarMingasUsuarioId(Request $request, $medidor_id)
    {
        if ($request->isJson()){ 
            try
            {   
                //transaccion
                DB::beginTransaction();
                try {

                    $objeto = Asistencia::
                    with('planificacion')
                    ->where('IDMEDIDOR',$medidor_id)
                    ->where('ASISTENCIA','NO')
                    ->where('OBSEVACION','NO')
                    ->get();

                    $total=count($objeto);
                    if(count($objeto)==0){
                        $objeto=null;
                        $total=null;
                    }
                    
                    DB::commit();
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Get data',
                        'message'=>'Lista de registros de mingas/asambleas',
                        'code'=>200,
                        'total'=>$total,
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
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
}
