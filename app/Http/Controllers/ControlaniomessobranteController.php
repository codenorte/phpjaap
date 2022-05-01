<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Controlaniomessobrante;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ControlaniomessobranteController extends Controller
{
     /**
     * mostrar Controlaniomessobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllControlaniomessobrante(Request $request){
        if ($request->isJson()) {

            //obtener ultimo registro
            $ultimoregistro = Controlaniomessobrante::get()->last();
            $nueva_fecha = null;
            if($ultimoregistro){
                $nueva_fecha =Carbon::parse($ultimoregistro['aniomes'])->addMonth()->isoFormat('Y-M');
            }

            $objeto=Controlaniomessobrante::
            withCount('detallefacturasobrante as total_registro')
            ->withCount([
                'detallefacturasobrante as cobrados'=> function ($query) {
                $query->where('estado', 1);
            }])
            ->withCount([
                'detallefacturasobrante as porcobrar'=> function ($query) {
                $query->where('estado', 0);
            }])
            ->orderBy('id','DESC')
            ->get();
            
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Controlaniomessobrante',
                'code'=>200,
                'nueva_fecha'=>$nueva_fecha,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Controlaniomessobrante por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdControlaniomessobrante(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Controlaniomessobrante::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, Controlaniomessobrante id',
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
     * Crear Controlaniomessobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createControlaniomessobrante(Request $request){
        if ($request->isJson()) {

            $data=$request->json()->all();
            //transaccion
            DB::beginTransaction();
            try {

                $objeto=new Controlaniomessobrante();
                
                $objeto->aniomes = Carbon::parse($data['aniomes'])->isoFormat('Y-M');
                $objeto->detalle = $data['detalle'];
                $objeto->estado = $data['estado'];

                $objeto->save();

                DB::commit();
                    
                 return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Controlaniomessobrante creada',
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
     * Actualizar Controlaniomessobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editControlaniomessobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomessobrante::findOrFail($id);
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
     * Eliminar Controlaniomessobrante
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyControlaniomessobrante(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Controlaniomessobrante::findOrFail($id);
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
