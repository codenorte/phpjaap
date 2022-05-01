<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detallefactura;
use App\Models\Controlmensualdetallefactura;

use Carbon\Carbon;

class ControlmensualdetallefacturaController extends Controller
{
     /**
     * copiar columnas de la tabla detallefactura a controlmensualdetallefactura
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function copiarDetallefactura(Request $request)
    {
    	set_time_limit(0);
        if ($request->isJson()){ 
            try
            {
                $objeto = Detallefactura::select(
                	'detallefactura.IDMEDIDOR',
			        'detallefactura.ANIOMES',
			        'detallefactura.IDDETALLEFAC',
			        'detallefactura.estado'
                )
                ->get();
                
                $i=0;
                $controlmensual=array();

                
                
                foreach ($objeto as $det) {
                	$controlmensualdetallefactura = new Controlmensualdetallefactura();
                	$controlmensualdetallefactura->IDMEDIDOR=$det['IDMEDIDOR'];
			        $controlmensualdetallefactura->ANIOMES=Carbon::parse($det['ANIOMES']);
			        $controlmensualdetallefactura->estado=$det['estado'];
			        $controlmensualdetallefactura->IDDETALLEFAC=$det['IDDETALLEFAC'];
			        $controlmensualdetallefactura->save();

			        $controlmensual[$i]=$controlmensualdetallefactura;
                	$i++;
                }

                return response()->json(array(
                	'status'=>'sucsess',
                	'title'=>'Get data',
                	'message'=>'Dato Obtenida',
                	'code'=>200,
                	//'data'=>$objeto,
                	'controlmensual'=>$controlmensual
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
