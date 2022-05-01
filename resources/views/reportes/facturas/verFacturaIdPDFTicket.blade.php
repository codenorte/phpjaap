<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>JAAP | Factura</title>

    
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
        @if($factura)
        <br>
        <h3>Nro. Factura: <strong>{{$factura->NUMFACTURA}}</strong> </h3>
        @endIf
        @if($institucion)
        @endIf
      </div>

    </div>

    <div class="datosUsuario">
      @if($factura)
      <div class="datosUsuario_text">
        <span>CAJA: {{$factura->USUARIOACTUAL}} </span> <br>
        <span>FECHA EMISION: {{$factura->FECHAEMISION}} </span>
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
          <strong>PAGO MESES: {{$totaldetalle}}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
        <span>
          <strong>CONSUMO: {{$total_consumo}} m3</strong><br>
        </span>
        <span><strong>Excedido(m3):{{$total_medida_excedido}}m3</strong></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span><strong>$( {{ number_format($total_tarifa_excedido, 2) }} )</strong></span>
      </div>


      <div class="ibox-content">
        <br>
        <table class="table">
          <thead>
            <tr>
              <th style="border-bottom: 1px solid black;width:45px">DET</th>
              <th style="border-bottom: 1px solid black">EXCESO</th>
              <th style="border-bottom: 1px solid black">VALOR</th>
              <th style="border-bottom: 1px solid black">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detallefactura as $det)
              <tr>
               <td>
                {{$det->ANIOMES}}
               </td>
               <td style="text-align: center">
                $ {{ number_format($det->TAREXCEDIDO, 2) }}
               </td>
               <td style="text-align: center">
                $ {{ number_format(($det->APORTEMINGA + $det->SUBTOTAL), 2) }}
               </td>
               <td style="text-align: right;">
                $ {{ number_format($det->TOTAL, 2) }}
               </td>
             </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan = "2" style="border-top: 1px solid black"></td>
              <td style="border-top: 1px solid black"><strong>SUBTOTAL:</strong></td>
              <td style="border-top: 1px solid black;text-align: right">
                <strong>{{ number_format($factura->SUBTOTAL, 2) }}</strong>
              </td>
            </tr>
            <tr>
              <td colspan = "2"></td>
              <td><strong>IVA:</strong></td>
              <td style="text-align: right;"><strong>{{ number_format($factura->IVA, 2) }}</strong></td>
            </tr>
            <tr>
              <td colspan = "2"></td>
              <td><strong>RECONEXIÓN:</strong></td>
              <td style="text-align: right;"><strong>{{ number_format($reconexion, 2) }}</strong></td>
            </tr>
            <tr>
              <td colspan = "2"></td>
              <td style="border-top: 1px solid black"><strong>TOTAL:</strong></td>
              <td style="border-top: 1px solid black;text-align: right;"><strong>{{ number_format($factura->TOTAL, 2) }}</strong></td>
            </tr>
         </tfoot>
        </table>
      </div>
    </div>



</body>
</html>

