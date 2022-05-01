<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
     /**
     * mostrar Proveedor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllProveedor(Request $request){
        if ($request->isJson()) {
            $objeto=Proveedor::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Proveedor',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Proveedor por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdProveedor(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Proveedor::find($id);
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Detalles Proveedor',
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
     * Crear Proveedor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createProveedor(Request $request){
        if ($request->isJson()) {
            
            $data=$request->json()->all();

            $objeto = new Proveedor();
            
            $objeto->ciruc = $data['ciruc'];
			$objeto->nombres = $data['nombres'];
			$objeto->apellidos = $data['apellidos'];
			$objeto->razon_social = $data['razon_social'];
			$objeto->direccion = $data['direccion'];
			$objeto->celular = $data['celular'];
			$objeto->telefono = $data['telefono'];
			$objeto->email = $data['email'];
			$objeto->pagina_web = $data['pagina_web'];
			$objeto->estado = 1;

			$objeto->save();

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
     * Actualizar Proveedor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProveedor(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Proveedor::findOrFail($id);
                $data = $request->json()->all();

				$objeto->ciruc = $data['ciruc'];
				$objeto->nombres = $data['nombres'];
				$objeto->apellidos = $data['apellidos'];
				$objeto->razon_social = $data['razon_social'];
				$objeto->direccion = $data['direccion'];
				$objeto->celular = $data['celular'];
				$objeto->telefono = $data['telefono'];
				$objeto->email = $data['email'];
				$objeto->pagina_web = $data['pagina_web'];
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
     * Eliminar Proveedor
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyProveedor(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Proveedor::findOrFail($id);
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
     * mostrar Proveedor por cedula/ruc
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCedulaProveedor(Request $request, $cedula)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Proveedor::
                //where('ciruc',$cedula)
                where('ciruc','LIKE','%'.$cedula.'%')
                ->where('estado',1)
                ->get();
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Get data',
                    'message'=>'Proveedor encontrada',
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
     * get total proveedores
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getTotalProveedores(Request $request){
        if ($request->isJson()) {
            $objeto=Proveedor::all();
            $total_activos = Proveedor::
            where('estado',1)
            ->get();
            $total_inactivos = Proveedor::
            where('estado',0)
            ->get();

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Proveedor',
                'code'=>200,
                'total'=>count($objeto),
                'total_activos'=>count($total_activos),
                'total_inactivos'=>count($total_inactivos)

            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
