<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institucion;

use Illuminate\Support\Facades\DB;
use Exception;


class InstitucionController extends Controller
{
    /**
     * mostrar Institucion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllInstitucion(Request $request){
        if ($request->isJson()) {
            $objeto=Institucion::latest()->first();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Institucion',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Institucion por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdInstitucion(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Institucion::findOrFail($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Dato Obtenida, rol id',
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
     * Crear Institucion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createInstitucion(Request $request){
        if ($request->isJson()) {
            $data=$request->json()->all();

            //transaccion
            DB::beginTransaction();

            try {

            	$objeto = new Institucion();

	            $objeto->NOMBREINST = $data['NOMBREINST'];
				$objeto->DIRECCION = $data['DIRECCION'];
				$objeto->TELEFONO = $data['TELEFONO'];
				$objeto->EMAIL = $data['EMAIL'];
				$objeto->RUC = $data['RUC'];
				$objeto->CELULAR = $data['CELULAR'];
				$objeto->LOGO = $data['LOGO'];
				$objeto->ESTADO = $data['ESTADO'];
				$objeto->PAGINAWEB = $data['PAGINAWEB'];

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
                return $e;
            } catch (\Throwable $e) {
                DB::rollback();
                return $e;
            }

        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Institucion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editInstitucion(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Institucion::findOrFail($id);
                $data = $request->json()->all();

                //transaccion
	            DB::beginTransaction();

	            try {

		            $objeto->NOMBREINST = $data['NOMBREINST'];
					$objeto->DIRECCION = $data['DIRECCION'];
					$objeto->TELEFONO = $data['TELEFONO'];
					$objeto->EMAIL = $data['EMAIL'];
					$objeto->RUC = $data['RUC'];
					$objeto->CELULAR = $data['CELULAR'];
					$objeto->LOGO = $data['LOGO'];
					$objeto->ESTADO = $data['ESTADO'];
					$objeto->PAGINAWEB = $data['PAGINAWEB'];

					$objeto->save();

					DB::commit();
	                return response()->json(array(
	                    'status'=>'sucsess',
	                    'title'=>'Update data',
	                    'message'=>'Dato Actualizada',
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
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Institucion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyInstitucion(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Institucion::findOrFail($id);
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
