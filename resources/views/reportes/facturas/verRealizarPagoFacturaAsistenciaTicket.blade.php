<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>JAAP | Factura Asamblea/Minga</title>

    
    <style>


      * {
            font-size: 11px;
            font-family: 'DejaVu Sans', serif;
        }

        html {
          margin: 0pt 25pt 0pt 20pt;
          line-height: 85%;
        }

        h1 {
            
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
          /*
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
            */
        }
        td{
            border-top: 1px solid black;
          /*
            border-collapse: collapse;
            margin: 0 auto;
            */
        }

        td.precio {
            text-align: right;
            font-size: 11px;
        }

        td.cantidad {
            font-size: 11px;
        }

        td.producto {
            text-align: center;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 20px;
            max-width: 20px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        .header, .cabecera {
          display: table;
          /*
          width: 100%;
          height: 15%;
          margin-top: -65px;
          */
        }
        .header_logo {
          display: table-cell;
          /*
          text-align: center;
          */
          /*
          border: red solid 1px;
          */
          vertical-align: middle;
          margin: 0 0 0 2px;
        }
        .header_text {
          display: table-cell;
          text-align: center;
          /*
          vertical-align: middle;
          border: red solid 2px;
          */
          /*
          */
          font-size: 16px;
          font-weight: bold;
        }
        .header_text2 {
          /*
          display: table-cell;
          border: red solid 2px;
          */
          /*text-align: left;*/
          vertical-align: middle;
          font-size: 16px;
        }

        
    </style>
    
  </head>

  <body>
    <div class="header">
      <div class="header_logo">
        <img src="img/tocagon_negro-copia2.jpg" alt="" style="width: 235px;">
        <!--
        <img src="{{asset('img/logo.png')}}" alt="" style="width: 100px;padding-left: 5px;">
        -->
      </div>
    </div>
    <div class="cabecera">
      @if($institucion)
      <div class="header_text">
        <br>
        <strong style="font-size: 13px!important;">{{$institucion->NOMBREINST}}</strong><br>
        <strong style="font-weight: bold!important;">RUC: {{$institucion->RUC}} </strong><br>
        <strong>Tlf: {{$institucion->CELULAR}} </strong>
        
      </div>
      @endIf
      <div class="header_text2">
        @if($NUMFACTURA)
        <br>
        <h3>Nro. Comprobante Pago Multa: <strong>{{$NUMFACTURA}}</strong> </h3>
        @endIf

      </div>

    </div>

    <div class="datosUsuario">
      @if($pagosasistencia)
      <div class="datosUsuario_text">
        <span>CAJA: {{$pagosasistencia[0]->USUARIOACTUAL}} </span> <br>
        <span>FECHA EMISION: {{$pagosasistencia[0]->FECHAPAGO}} </span>
      </div>
      <br>
      @endIf
      
      @if($medidor)
      <div class="datosUsuario_text_left">
        <span>Nro MEDIDOR: {{$medidor->CODIGO}}</span><br>
        <span>RUC/CI: {{$medidor->users['RUCCI']}}</span><br>
        <span>CLIENTE: {{$medidor->users['NOMBRES']}} {{$medidor->users['APELLIDOS']}} </span><br>
        <span>DIRECCION: {{$medidor->users['DIRECCION']}}</span><br>
        <span>SECTOR: {{$medidor->users['SECTOR']}}</span>
      </div>
      @endIf
    </div>

    <div id="content">
      <div class="detallefactura">
        <span>
          <br>
          <strong>PAGO FACTURAS: {{$total_facturas}}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
      </div>


      <div class="ibox-content">
        <br>
        <table class="table">
          <thead>
            <tr>
              <th style="border-bottom: 1px solid black;width:45px">EVENTO</th>
              <th style="border-bottom: 1px solid black">DET</th>
              <th style="border-bottom: 1px solid black">FECHA</th>
              <th style="border-bottom: 1px solid black">MULTA</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pagosasistencia as $pago)
              <tr>
               <td>
                {{$pago->asistencia['planificacion']['TIPOPLANIFICACION']}}
               </td>
               <td style="text-align: justify">
                {{ Str::limit($pago->asistencia['planificacion']['DESCRIPCION'], $limit = 60, $end = '...') }}
               </td>
               <td style="text-align: center">
                {{$pago->asistencia['planificacion']['FECHA']}}
               </td>
               <td style="text-align: right;">
                {{ number_format($pago->VALORMINGAS, 2) }}
               </td>
             </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan = "2"></td>
              <td style="border-top: 1px solid black"><strong>TOTAL:</strong></td>
              <td style="border-top: 1px solid black;text-align: right;"><strong>{{ number_format($total, 2) }}</strong></td>
            </tr>
         </tfoot>
        </table>
      </div>
    </div>



</body>
</html>

