<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institucion;
use App\Models\User;
use App\Models\Roles;
use App\Models\Fotos;
use App\Models\Medidor;
use App\Models\Facturas;
use App\Models\Detallefactura;
use App\Models\Corte;
use App\Models\Otrospagos;
use App\Models\Tarifas;
use App\Models\Controlaniomesdetallefactura;
use App\Models\Otrosconceptos;
use App\Models\Pagosasistencia;
use App\Models\Asistencia;

use App\Models\Aguaganaderia;
use App\Models\Detallefacturaganaderia;
use App\Models\Facturasganaderia;

use App\Models\Aguasobrante;
use App\Models\Detallefacturasobrante;
use App\Models\Facturassobrante;

use App\Models\Detallefacturainstalacion;
use App\Models\Facturasinstalacion;

use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportesController extends Controller
{
    
    // Generate PDF
    public function createPDF() {
        set_time_limit(0);
        $user = User::all();
        
        $pdf = PDF::loadView('vista-pdf', ['user'=>$user]);
        $pdf->save(base_path().'/public/users/getIdUser.pdf');
        return $pdf->stream();
    }

    // Generate PDF
    public function verFacturaIdPDF($factura_id) {
        set_time_limit(0);
        $buscar_corte = Otrospagos::where('NUMFACTURA',$factura_id)
        ->first();
        $reconexion = 0;
        //return $factura_id;
        if($buscar_corte!=null||$buscar_corte){
            $reconexion = $buscar_corte['TOTAL'];
        }


        $factura = Facturas::with('detallefactura')
            ->where('NUMFACTURA',$factura_id)
            ->first();
        $detallefactura = $factura['detallefactura'];
        $totaldetalle=count($factura['detallefactura']);

        $medidor_id= $factura['detallefactura'][0]['IDMEDIDOR'];
        $medidor = Medidor::
        with('users')
        ->where('IDMEDIDOR',$medidor_id)
        ->first();

        $institucion = Institucion::latest()->first();


        $total_consumo=0;//$
        $total_medida_excedido=0;//m3
        $total_tarifa_excedido=0;//$
        foreach ($detallefactura as $det) {
            $total_consumo+=$det['CONSUMO'];
            $total_medida_excedido+=$det['MEDEXCEDIDO'];
            $total_tarifa_excedido+=$det['TAREXCEDIDO'];
        }
        //return $total_medida_excedido;
        
        //return count($factura['detallefactura']);

        //return $factura;
        /*
        return view('reportes.facturas.verFacturaIdPDF')->with([
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido
        ]);
        */

        $pdf = PDF::loadView('reportes.facturas.verFacturaIdPDF', 
            [
                'institucion'=>$institucion,
                'medidor'=>$medidor,
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido,
                'reconexion'=>$reconexion
            ]
        );
        $pdf->save(base_path().'/public/reportes/facturas/verFacturaIdPDF.pdf');
        return $pdf->stream();
    }

    // Generate PDF ticket pago factura
    public function verFacturaIdPDFTicket($factura_id) {
        set_time_limit(0);
        
        $buscar_corte = Otrospagos::where('NUMFACTURA',$factura_id)
        ->first();
        $reconexion = 0;
        //return $factura_id;
        if($buscar_corte!=null||$buscar_corte){
            $reconexion = $buscar_corte['TOTAL'];
        }


        $factura = Facturas::with('detallefactura')
            ->where('NUMFACTURA',$factura_id)
            ->first();
        $detallefactura = $factura['detallefactura'];
        $totaldetalle=count($factura['detallefactura']);

        $medidor_id= $factura['detallefactura'][0]['IDMEDIDOR'];
        $medidor = Medidor::
        with('users')
        ->where('IDMEDIDOR',$medidor_id)
        ->first();

        $institucion = Institucion::latest()->first();


        $total_consumo=0;//$
        $total_medida_excedido=0;//m3
        $total_tarifa_excedido=0;//$
        foreach ($detallefactura as $det) {
            $total_consumo+=$det['CONSUMO'];
            $total_medida_excedido+=$det['MEDEXCEDIDO'];
            $total_tarifa_excedido+=$det['TAREXCEDIDO'];
        }



        //return $reconexion;
        //return $total_medida_excedido;
        
        //return count($factura['detallefactura']);

        //return $factura;
        /*
        return view('reportes.facturas.verFacturaIdPDF')->with([
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido
        ]);
        */

        $customPaper = array(0,0,230.45,1440);
        
        $pdf = PDF::loadView('reportes.facturas.verFacturaIdPDFTicket', 
            [
                'institucion'=>$institucion,
                'medidor'=>$medidor,
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido,
                'reconexion'=>$reconexion
                
            ]
        )->setPaper($customPaper);
        $pdf->save(base_path().'/public/reportes/facturas/verFacturaIdPDFTicket.pdf');
        return $pdf->stream();
    }

     /**
     * Reporte de cobro mensual, por rango de fechas
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    function reporteObtenerCobroMensual(Request $request,$controlaniomes_inicio,$controlaniomes_fin=null){
        set_time_limit(0);

        $institucion = Institucion::latest()->first();

        $rango = false;
        $objeto=new Detallefactura();

        //return $controlaniomes_fin;

        $controlaniomes =array();
        if($controlaniomes_fin!=null){
            $rango = true;
        }
        //hay rango de fechas desde y hasta
        if($rango==true){
            $objeto=Detallefactura::
            whereBetween('controlaniomes_id', [$controlaniomes_inicio, $controlaniomes_fin])
            ->where('estado',1)
            ->get();

            $controlaniomes[0]= Controlaniomesdetallefactura::find($controlaniomes_inicio);
            $controlaniomes[1]= Controlaniomesdetallefactura::find($controlaniomes_fin);;

        }else{
            $objeto=Detallefactura::
            where('controlaniomes_id', $controlaniomes_inicio)
            ->where('estado',1)
            ->get();
            $controlaniomes[0]= Controlaniomesdetallefactura::find($controlaniomes_inicio);
        }
        //return $controlaniomes[0];

        $numero_meses = 1;
        if($controlaniomes_inicio!=$controlaniomes_fin){
            $numero_meses = $controlaniomes_fin-$controlaniomes_inicio;
            $numero_meses++;
        }
        //return $numero_meses;

        $fechainicio = Carbon::parse($controlaniomes[0]['aniomes']);
        $fechafin = Carbon::parse($controlaniomes[1]['aniomes']);

        //return $fecha->monthName;


        $suma_consumo = 0;
        $suma_excedido = 0;
        $suma_tar_excedido = 0;
        $suma_subtotal = 0;
        $suma_total = 0;

        foreach ($objeto as $det) {
            $suma_consumo += $det['CONSUMO'];
            $suma_excedido += $det['MEDEXCEDIDO'];
            $suma_tar_excedido += $det['TAREXCEDIDO'];
            $suma_subtotal += $det['SUBTOTAL'];
            $suma_total += $det['TOTAL'];
        }


        /**
        return view('reportes.contabilidad.reporteObtenerCobroMensual')->with([
                
                'institucion'=>$institucion,
                'suma_consumo'=>round($suma_consumo,2),
                'suma_excedido'=>round($suma_excedido,2),
                'suma_tar_excedido'=>round($suma_tar_excedido,2),
                'suma_subtotal'=>round($suma_subtotal,2),
                'suma_total'=>round($suma_total,2),
                'total_cobrados'=>count($objeto),
                'controlaniomes'=>$controlaniomes
        ]);
        /*
        */

        $pdf = PDF::loadView('reportes.contabilidad.reporteObtenerCobroMensual', 
            [
                'institucion'=>$institucion,
                'suma_consumo'=>round($suma_consumo,2),
                'suma_excedido'=>round($suma_excedido,2),
                'suma_tar_excedido'=>round($suma_tar_excedido,2),
                'suma_subtotal'=>round($suma_subtotal,2),
                'suma_total'=>round($suma_total,2),
                'total_cobrados'=>count($objeto),
                'controlaniomes'=>$controlaniomes,
                'numero_meses'=>$numero_meses,
                'fechainicio'=>$fechainicio,
                'fechafin'=>$fechafin,

            ]
        )->setPaper('A4');
        $pdf->save(base_path().'/public/reportes/contabilidad/reporteObtenerCobroMensual.pdf');
        return $pdf->stream();
    }


     /**
     * Reporte de factura realizarPagoFacturaAsistencia
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     */
    public function verRealizarPagoFacturaAsistenciaTicket($NUMFACTURA) {
        set_time_limit(0);
        
        $pagosasistencia = Pagosasistencia::
        with('asistencia.planificacion')
        ->where('NUMFACTURA',$NUMFACTURA)
        ->get();

        $medidor=Medidor::
        with('users')
        ->where('IDMEDIDOR',$pagosasistencia[0]['asistencia']['IDMEDIDOR'])
        ->first();

        //return $medidor;

        $total=0;//$
        $total_facturas=0;//m3
        foreach ($pagosasistencia as $pago) {
            $total+=$pago['VALORMINGAS'];
            $total_facturas++;
        }

        //return $pagosasistencia;

        $institucion = Institucion::latest()->first();





        //return $reconexion;
        //return $total_medida_excedido;
        
        //return count($factura['detallefactura']);

        //return $factura;
        /*
        return view('reportes.facturas.verFacturaIdPDF')->with([
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido
        ]);
        */

        $customPaper = array(0,0,230.45,1440);
        
        $pdf = PDF::loadView('reportes.facturas.verRealizarPagoFacturaAsistenciaTicket', 
            [
                'institucion'=>$institucion,
                'medidor'=>$medidor,
                'pagosasistencia'=>$pagosasistencia,
                'NUMFACTURA'=>$NUMFACTURA,
                'total_facturas'=>$total_facturas,
                'total'=>$total,
                //'total_medida_excedido'=>$total_medida_excedido,
                //'total_tarifa_excedido'=>$total_tarifa_excedido,
                //'reconexion'=>$reconexion
                
            ]
        )->setPaper($customPaper);
        $pdf->save(base_path().'/public/reportes/facturas/verRealizarPagoFacturaAsistenciaTicket.blade.pdf');
        return $pdf->stream();
    }
    /* 
    Generate PDF ticket pago facturaganaderia
    */
    public function verFacturaGanaderiaTicket($facturasganaderia_id) {
        set_time_limit(0);
        
        $facturaganaderia = Facturasganaderia::with('detallefacturaganaderia')
            ->where('IDFACTURASGANADERIA',$facturasganaderia_id)
            ->first();
        $detallefacturaganaderia = $facturaganaderia['detallefacturaganaderia'];
        $totaldetalle=count($facturaganaderia['detallefacturaganaderia']);

        $aguaganderia_id= $facturaganaderia['detallefacturaganaderia'][0]['IDAGUAGANADERIA'];
        $aguaganaderia = Aguaganaderia::
        with('users')
        ->where('IDAGUAGANADERIA',$aguaganderia_id)
        ->first();

        $institucion = Institucion::latest()->first();


        $subtotal=0;
        $total=0;
        foreach ($detallefacturaganaderia as $det) {
            $subtotal+=$det['SUBTOTAL'];
            $total+=$det['TOTAL'];
        }
        //return $detallefacturaganaderia;
        /*
        return view('reportes.facturas.verFacturaIdPDF')->with([
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido
        ]);
        */

        $customPaper = array(0,0,230.45,1440);
        
        $pdf = PDF::loadView('reportes.facturas.verFacturaGanaderiaTicket', 
            [
                'institucion'=>$institucion,
                'aguaganaderia'=>$aguaganaderia,
                'facturaganaderia'=>$facturaganaderia,
                'detallefacturaganaderia'=>$detallefacturaganaderia,
                'totaldetalle'=>$totaldetalle
            ]
        )->setPaper($customPaper);
        $pdf->save(base_path().'/public/reportes/facturas/verFacturaGanaderiaTicket.pdf');
        return $pdf->stream();
    }

    /* 
    Generate PDF ticket pago facturasobrante
    */
    public function verFacturaSobranteTicket($facturasobrante_id) {
        set_time_limit(0);

        $facturasobrante = Facturassobrante::
        with('detallefacturasobrante')
            ->where('IDFACTURASOBRANTE',$facturasobrante_id)
            ->first();
        $detallefacturasobrante = $facturasobrante['detallefacturasobrante'];
        $totaldetalle=count($facturasobrante['detallefacturasobrante']);

        $aguasobrante_id= $facturasobrante['detallefacturasobrante'][0]['IDAGUASOBRANTE'];
        $aguasobrante = Aguasobrante::
        with('users')
        ->where('IDAGUASOBRANTE',$aguasobrante_id)
        ->first();

        $institucion = Institucion::latest()->first();


        $subtotal=0;
        $total=0;
        foreach ($detallefacturasobrante as $det) {
            $subtotal+=$det['SUBTOTAL'];
            $total+=$det['TOTAL'];
        }
        //return $detallefacturaganaderia;
        /*
        return view('reportes.facturas.verFacturaIdPDF')->with([
                'factura'=>$factura,
                'detallefactura'=>$detallefactura,
                'totaldetalle'=>$totaldetalle,
                'total_consumo'=>$total_consumo,
                'total_medida_excedido'=>$total_medida_excedido,
                'total_tarifa_excedido'=>$total_tarifa_excedido
        ]);
        */

        $customPaper = array(0,0,230.45,1440);
        
        $pdf = PDF::loadView('reportes.facturas.verFacturaSobranteTicket', 
            [
                'institucion'=>$institucion,
                'aguasobrante'=>$aguasobrante,
                'facturasobrante'=>$facturasobrante,
                'detallefacturasobrante'=>$detallefacturasobrante,
                'totaldetalle'=>$totaldetalle
            ]
        )->setPaper($customPaper);
        $pdf->save(base_path().'/public/reportes/facturas/verFacturaSobranteTicket.pdf');
        return $pdf->stream();
    }

    /* 
    Generate PDF ticket pago de instalaacion de medidor facturainstalacion,detallefacturainstalacion
    */
    public function verFacturaInstalacionAguaTicket($facturainstalacion_id) {
        set_time_limit(0);

        $facturasinstalacion = Facturasinstalacion::
        with('detallefacturainstalacion')
        ->where('IDFACTURA',$facturainstalacion_id)
        ->first();


        $detallefacturainstalacion = $facturasinstalacion['detallefacturainstalacion'];
        $totaldetalle=count($facturasinstalacion['detallefacturainstalacion']);

        $IDMEDIDOR= $detallefacturainstalacion[0]['IDMEDIDOR'];
        
        $medidor = Medidor::
        with('usersName')
        ->where('IDMEDIDOR',$IDMEDIDOR)
        ->first();

        $institucion = Institucion::latest()->first();

        //return $medidor;

        $total=0;
        foreach ($detallefacturainstalacion as $det) {
            $total+=$det['TOTAL'];
        }

        $customPaper = array(0,0,230.45,1440);
        
        $pdf = PDF::loadView('reportes.facturas.verFacturaInstalacionAguaTicket', 
            [
                'institucion'=>$institucion,
                'medidor'=>$medidor,
                'facturasinstalacion'=>$facturasinstalacion,
                'detallefacturainstalacion'=>$detallefacturainstalacion,
                'totaldetalle'=>$totaldetalle
            ]
        )->setPaper($customPaper);
        $pdf->save(base_path().'/public/reportes/facturas/verFacturaInstalacionAguaTicket.pdf');
        return $pdf->stream();
    }
}
