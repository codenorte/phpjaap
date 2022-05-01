<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fotos;

class FotosController extends Controller
{
    /**
     * mostrar Fotos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllFotos(Request $request){
        if ($request->isJson()) {
            $objeto=Fotos::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de Fotos',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Fotos por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdFotos(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Fotos::findOrFail($id);
                return response()->json(array('status'=>'sucsess','title'=>'Get data','message'=>'Fotos Obtenido','code'=>200,'data'=>$objeto),200);
            }
            catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Crear Fotos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createFotos(Request $request){
        set_time_limit(0);
        if ($request->isJson()) {

            $data=$request->json()->all();

            $objeto=new Fotos();
            if($data['ROLES_ID']!=5){
                $objeto->usuario='A'.$data['RUCCI'];
            }else{
                $objeto->usuario=$data['RUCCI'];
            }
            $objeto->password=Hash::make($data['RUCCI']);
            $objeto->email=$data['EMAIL'];
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
            $objeto->roles_id=$data['ROLES_ID'];
            $objeto->save();
                

            //default email destino .- No tocar - correo en el frontend
            $message="";
            if($objeto['email']!=""||$objeto['email']!=null){
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
                'data'=>$objeto,
                'imagen'=>$imagen
            ),201);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * Actualizar Fotos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editFotos(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Fotos::findOrFail($id);
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

                return response()->json(array('status'=>'sucsess','title'=>'Update Fotos','message'=>'Dato Actualizada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Eliminar Fotos
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyFotos(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Fotos::findOrFail($id);
                $objeto->delete();
                return response()->json(array('status'=>'sucsess','title'=>'Delete data','message'=>'Dato Eliminada','code'=>200,'data'=>$objeto),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
}
