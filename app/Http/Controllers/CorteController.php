<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corte;
use App\Models\Otrospagos;
use App\Models\Detallefactura;
use App\Models\Otrosconceptos;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class CorteController extends Controller
{
	 /**
     * mostrar Corte
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllCorte(Request $request){
        if ($request->isJson()) {
            $objeto=Corte::all();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Corte',
                'code'=>200,
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
    /**
     * mostrar Corte por id
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdCorte(Request $request, $id)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Corte::findOrFail($id);
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
     * Crear Corte
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function createCorte(Request $request){
    	set_time_limit(0);
        if ($request->isJson()) {
            $data=$request->json()->all();

            $buscar_corte = Corte::
            where('IDMEDIDOR',$data['IDMEDIDOR'])
            ->where('estado',0)
            ->get();


            if(count($buscar_corte)>0){
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Corte no creado',
                    'message'=>'Multa por reconexion ya existe, corte editado',
                    'code'=>201,
                    'data'=>null
                ),201);
            }

            $detallefactura = Detallefactura::
            where('IDMEDIDOR',$data['IDMEDIDOR'])
            ->where('estado',0)
            ->get();
            //preguntar el tiempo para el que esta establecido en tabla Otrosconceptos
            $otrosconceptos = Otrosconceptos::latest()->first();

            //contar si los meses son mas de 3 por pagar
            if(count($detallefactura)>=$otrosconceptos['TIEMPO']){

            	//return "El tiempo es superior a 3 meses, se aplica multa de reconexion";
                //transaccion
                DB::beginTransaction();

                try {

                    $today = Carbon::now();
    	            $objeto=new Corte();

    	            $objeto->IDMEDIDOR= $data['IDMEDIDOR'];
    				$objeto->CORTE= 'NO';
    				$objeto->FECHA= $today;
    				$objeto->OBSERVACION= $otrosconceptos['DESCRIPCION'];
    				$objeto->MULTA= $otrosconceptos['CANTIDAD'];
    				$objeto->MORA= count($detallefactura);
    				$objeto->PAGADO= 'NO';
    				$objeto->estado= 0;

    				$objeto->save();

    				DB::commit();
    	            return response()->json(array(
    	                'status'=>'sucsess',
    	                'title'=>'Create data corte',
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
            else{
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Corte no creado',
                    'message'=>'No aplica reconexion',
                    'code'=>201,
                    'data'=>null
                ),201);
            	//return "No aplica";
            }
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * Actualizar Corte
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCorte(Request $request, $id)
    {	
    	set_time_limit(0);
        if ($request->isJson()) {
            try {

            	$objeto = Corte::findOrFail($id);
                $data = $request->json()->all();

	            //transaccion
	            DB::beginTransaction();

	            try {

		            $objeto->IDMEDIDOR= $data['IDMEDIDOR'];
					$objeto->CORTE= $data['CORTE'];
					$objeto->FECHA= $data['FECHA'];
					$objeto->OBSERVACION= $data['OBSERVACION'];
					$objeto->MULTA= $data['MULTA'];
					$objeto->MORA= $data['MORA'];
					$objeto->PAGADO= $data['PAGADO'];
					$objeto->estado= $data['estado'];

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
     * Eliminar Corte
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyCorte(Request $request, $id)
    {
    	set_time_limit(0);
        if ($request->isJson()) {
            try {
                $objeto = Corte::findOrFail($id);
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
     * Actualizar tabla directamente.
     * Actualizar columna PAGADO = SI, estado 
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarEstadoTablaDirecto(Request $request)
    {
    	set_time_limit(0);
        if ($request->isJson()) {
            try {
                $objeto = Corte::All();

                $i =0;
                $dato = array();
                foreach ($objeto as $dat) {
                	/*
                	if($dat['PAGADO']=='SI'){
                		$dat->estado = 1;
                	}
                	else{
                		$dat->estado = 0;
                	}
                	*/
                	$dat->PAGADO = "SI";
                	$dat->estado = 1;
                	$dat->save();
                	$dato[$i]=$dat;
                	$i++;
                }
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'actualizar tabla directo',
                    'code'=>200,
                    'data'=>$dato
                ),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * CREAR CORTE DIRECTO SUMANDO LOS DETALLEFACTURA
     * y crear corte
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAllCorte(Request $request)
    {
    	set_time_limit(0);
        if ($request->isJson()) {
            try {
                $objeto = Detallefactura::
                where('estado',0)
                ->get();

                $lista_medidores = Detallefactura::
                where('estado',0)
                ->groupBy('IDMEDIDOR')
                ->get();

                //return $lista_medidores;
                $today = Carbon::now();

                $i =0;
                $dato = array();

                foreach ($lista_medidores as $med) {
                	$j = 0;
	                foreach ($objeto as $det) {

	                	if($med['IDMEDIDOR']==$det['IDMEDIDOR']){
	                		$j++;
	                	}
	                }
	                if($j>=4){
		                $corte=new Corte();
		                $corte->IDMEDIDOR= $med['IDMEDIDOR'];
						$corte->CORTE= 'NO';
						$corte->FECHA= $today;
						$corte->OBSERVACION= 'multa por mora';
						$corte->MULTA= 5.00;
						$corte->MORA= $j;
						$corte->PAGADO= 'NO';
						$corte->estado= 0;
	                	$corte->save();
	                	$dato[$i]=$corte;
	                }
                	$i++;
                }
                return response()->json(array(
                    'status'=>'sucsess',
                    'title'=>'Delete data',
                    'message'=>'actualizar tabla directo',
                    'code'=>200,
                    'total'=>count($dato),
                    'data'=>$dato
                ),200);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'No content'], 406);
            }

        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
    /**
     * Buscar cortes por IDMEDIDOR
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorteporMedidor(Request $request, $NUMMEDIDOR)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Corte::
                select('corte.*')
                ->join('medidor','medidor.IDMEDIDOR','corte.IDMEDIDOR')
                ->where('medidor.NUMMEDIDOR',$NUMMEDIDOR)
                ->where('corte.estado',0)
                ->get();
                if(count($objeto)>0){
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Dato obtenida',
                        'message'=>'Tiene multas por reconexion',
                        'code'=>200,
                        'data'=>$objeto
                    ),200);
                }
                else{
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'No hay datos',
                        'message'=>'No Tiene multas por reconexion',
                        'code'=>200,
                        'data'=>null
                    ),200);
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
     * Buscar cortes por CODIGO
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorteporMedidorCodigo(Request $request, $CODIGO)
    {
        if ($request->isJson()){ 
            try
            {
                $objeto = Corte::
                select('corte.*')
                ->join('medidor','medidor.IDMEDIDOR','corte.IDMEDIDOR')
                ->where('medidor.CODIGO',$CODIGO)
                ->where('corte.estado',0)
                ->get();
                if(count($objeto)>0){
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'Dato obtenida',
                        'message'=>'Tiene multas por reconexion',
                        'code'=>200,
                        'data'=>$objeto
                    ),200);
                }
                else{
                    return response()->json(array(
                        'status'=>'sucsess',
                        'title'=>'No hay datos',
                        'message'=>'No Tiene multas por reconexion',
                        'code'=>200,
                        'data'=>null
                    ),200);
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
     * Obtener lista de usuarios con reconexion
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function getAllCorteUser(Request $request){
        if ($request->isJson()) {
            $objeto=Corte::
            select(
                'corte.*'
            )
            ->join('medidor','medidor.IDMEDIDOR','corte.IDMEDIDOR')
            ->with('medidorSelect.usersName')
            ->where('corte.estado',0)
            ->get();
            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos Corte',
                'code'=>200,
                'total'=>count($objeto),
                'data'=>$objeto
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }
}
