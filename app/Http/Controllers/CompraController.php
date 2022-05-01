<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompras;
use App\Models\Detallemat;
use App\Models\Proveedor;
use App\Models\Materiales;

use Illuminate\Support\Facades\DB;
use Exception;

class CompraController extends Controller
{
     /**
     * mostrar Compra
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllCompra(Request $request){
        if ($request->isJson()) {
            $objeto=Compra::
            with('proveedor')
            ->with('detallecompras')
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Compra',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Compra por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdCompra(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Compra::findOrFail($id);
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
     * Crear Compra
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createCompra(Request $request){
        if ($request->isJson()) {
            //transaccion

            DB::beginTransaction();

            try {
            
                $data=$request->json()->all();
                //return $data[0]['ciruc'];
                //return count($data[1]);
                /*
                * CABECERA DE LA FACTURA
                */
                //buscar proveedor
                $buscar_proveedor = Proveedor::
                where('ciruc',$data[0]['ciruc'])
                ->where('estado',1)
                ->first();
                //return $buscar_proveedor;
                //no existe proveedor, crea
                if(!$buscar_proveedor){
    	        	$proveedor = new Proveedor();
    	            $proveedor->ciruc = $data[0]['ciruc'];
    				$proveedor->nombres = $data[0]['nombres'];
    				$proveedor->apellidos = $data[0]['apellidos'];
    				$proveedor->razon_social = $data[0]['razon_social'];
    				$proveedor->direccion = $data[0]['direccion'];
    				$proveedor->celular = $data[0]['celular'];
    				
    				$proveedor->estado = 1;
    				$proveedor->save();
                	$buscar_proveedor = $proveedor;
                }
                /*
                * PIE DE FACTURA
                */
                //crear nueva compra 
                $compra = new Compra();
                $compra->numfactura = $data[0]['numfactura'];
                $compra->fechaemision = $data[0]['fechaemision'];
                $compra->subtotal = $data[0]['subtotal'];
                $compra->iva = $data[0]['iva'];
                $compra->total = $data[0]['total'];
                $compra->estado = $data[0]['estado'];
                $compra->proveedor_id = $buscar_proveedor['id'];
                $compra->usuarioactual = $data[0]['usuarioactual'];
                
                $compra->save();


                /*
                * CUERPO DE LA FACTURA
                * Hace recorrido
                *  material puede ingresar varios materiales
                */
                //buscar material
                $recorrer_mat = 0;
                $recorrer_det = 0;
                $salida_material = array();
                $salida_detallematerial = array();
                foreach ($data[1] as $dat) {
                    $buscar_material = Materiales::
                    where('id',$dat['material_id'])
                    ->where('estado',1)
                    ->first();
                    //return $buscar_material;
                    //no existe materiales
                    if(!$buscar_material){
                    	$material = new Materiales();
        	            $material->nombre = $dat['nombre'];
        				$material->detalle = $dat['detalle'];
        				$material->codigo = $dat['codigo'];
        				$material->stock = $dat['cantidad'];
        				$material->total = $dat['cantidad'];
        				$material->estado = 1;
        				$material->categoriasmat_id = $dat['categoriasmat_id'];
        				$material->save();

        				$buscar_material = $material;

                    }
                    //si existe actualizar stock
                    else{
                    	$buscar_material->stock += $dat['cantidad'];
        				$buscar_material->total += $dat['cantidad'];
        				$buscar_material->save();
                    }
                    //return $buscar_material['id'];
                    //crear detalle compras
                    $calculo_total =  round($dat['cantidad'] * $dat['precio'], 2);

                    $detalle_compras = new DetalleCompras();
                    $detalle_compras->nombre = $buscar_material['nombre'];
                    $detalle_compras->detalle = $buscar_material['detalle'];
                    $detalle_compras->codigo = $buscar_material['codigo'];
                    $detalle_compras->cantidad = $dat['cantidad'];
                    $detalle_compras->precio = $dat['precio'];
                    $detalle_compras->total = $calculo_total;
                    $detalle_compras->estado = $data[0]['estado'];
                    $detalle_compras->compras_id = $compra['id'];
                    $detalle_compras->materiales_id = $buscar_material['id'];
                    $detalle_compras->save();

                    //return $detalle_compras;



                    //crear detallemat
                    $detamaterial=array();
        			for ($i=0; $i < $dat['cantidad']; $i++) { 

        				$nuevo_detallemat = new Detallemat();

        	            $nuevo_detallemat->nombre = $buscar_material['nombre'];
        				$nuevo_detallemat->detalle = $buscar_material['detalle'];
        				$nuevo_detallemat->codigo = $buscar_material['codigo'];
        				$nuevo_detallemat->estado = 1;
                        $nuevo_detallemat->detallecompras_id = $detalle_compras['id'];
        				$nuevo_detallemat->materiales_id = $buscar_material['id'];
        				$nuevo_detallemat->tipomat_id = $dat['tipomat_id'];

        				$nuevo_detallemat->save();

        				$detamaterial[$i] = $nuevo_detallemat;
        			}
                    $salida_detallematerial[$recorrer_det] = $detamaterial;
                    $recorrer_det++;

                    $salida_material[$recorrer_mat] = $buscar_material;
                    $recorrer_mat++;
                }
                //return $salida_detallematerial;
                //return $detamaterial;
                DB::commit();

                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Create data',
                    'message'=>'Dato creada',
                    'code'=>201,
                    'buscar_proveedor'=>$buscar_proveedor,
                    'buscar_material'=>$buscar_material,
                    'compra'=>$compra,
                    'compra'=>$detalle_compras,
                    'salida_material'=>$salida_material,
                    'salida_detallematerial'=>$salida_detallematerial
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
     * Actualizar Compra
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCompra(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Compra::findOrFail($id);
                $data = $request->json()->all();

                $objeto->nombre = $data['nombre'];
				$objeto->detalle = $data['detalle'];
				$objeto->codigo = $data['codigo'];
				$objeto->stock = $data['stock'];
				$objeto->total = $data['total'];
				$objeto->estado = $data['estado'];
				$objeto->categoriasmat_id = $data['categoriasmat_id'];

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
     * Eliminar Compra
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCompra(Request $request, $id)
    {
        if ($request->isJson()) {
            try {
                $objeto = Compra::findOrFail($id);
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
