<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medidor;
use App\Models\User;
use Carbon\Carbon;

use App\Models\Medidorusers;

class MedidorusersController extends Controller
{
    /**
     * Copiar de la tabla medidor a medidorusers
     *
     * @return \Illuminate\Http\Response
     */
    public function copiarMedidoruser(Request $request)
    {
        set_time_limit(0);
        if ($request->isJson()) {
            $objeto=Medidor::all();

            $num=0;
            $array=array();
            $today = Carbon::now();

            foreach ($objeto as $us) {
                $medidorusers=new Medidorusers();
                $medidorusers->FECHA=$today;
                $medidorusers->IDUSUARIO=$us['IDUSUARIO'];
                $medidorusers->IDMEDIDOR=$us['IDMEDIDOR'];
                $medidorusers->ESTADO=1;
                $medidorusers->NIVEL=1;
                $medidorusers->save();

                $array[$num]=$medidorusers;
                $num++;
            }

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de medidor',
                'code'=>200,
                'total'=>count($array),
                'data'=>$array
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    /**
     * buscar datos repetidos en medidor
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarRepetidoMedidor(Request $request)
    {
        set_time_limit(0);
        if ($request->isJson()) {
            $objeto=Medidor::all();

            $medidorusers=Medidorusers::all();
            /*
            $users=User::all();

            $dato = array();
            $contador = 0;
            $i = 0;
            foreach ($users as $us) {
                $i=0;
                foreach ($users as $us2) {
                    //var_dump($us['RUCCI'].'--'.$us2['RUCCI']);
                    if($us['RUCCI']==$us2['RUCCI']){
                        $i++;
                    }
                    //var_dump($i);
                }
                if($i>=2){
                    $dato[$contador] = $us;
                    $contador++;
                }
            }
            return $dato;
            */

            $num=0;
            $array=array();
            $today = Carbon::now();

            foreach ($medidorusers as $us) {

                $medus = Medidorusers::find($us['id']);
                $medus->IDUSUARIO_HIJO=$us['IDUSUARIO'];
                
                $medus->save();

                $array[$num]=$medus;
                $num++;
            }

            return response()->json(array(
                'status'=>'sucsess',
                'title'=>'Get all data',
                'message'=>'Lista de datos de medidor',
                'code'=>200,
                'total'=>count($array),
                'data'=>$array
            ),200);
        }
        return response()->json(['error'=>'Unauthorized'],401,[]);
    }

    
}
