<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>JAAP | Factura Ganaderia</title>

    
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
        <h3>COBRO DE AGUA GANADERIA</h3>
        
      </div>
      @endIf
      <div class="header_text2">
        @if($facturaganaderia)
        <br>
        <h3>No. Comprobante: <strong>{{$facturaganaderia->NUMFACTURA}}</strong> </h3>
        @endIf
      </div>

    </div>

    <div class="datosUsuario">
      @if($facturaganaderia)
      <div class="datosUsuario_text">
        <span>CAJA: {{$facturaganaderia->USUARIOACTUAL}} </span> <br>
        <span>FECHA EMISION: {{$facturaganaderia->FECHAEMISION}} </span>
      </div>
      <br>
      
      @endIf
      @if($aguaganaderia)
      <div class="datosUsuario_text_left">
        <span>RUC/CI: {{$aguaganaderia->users['RUCCI']}}</span><br>
        <span>CLIENTE: {{$aguaganaderia->users['NOMBRES']}} {{$aguaganaderia->users['APELLIDOS']}} </span><br>
        <span>SECTOR: {{$aguaganaderia->users['SECTOR']}}</span>
      </div>
      @endIf
    </div>

    <div id="content">
      <div class="detallefactura">
        <span>
          <br>
          <strong>PAGO MESES: {{$totaldetalle}}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
      </div>


      <div class="ibox-content">
        <br>
        <table class="table">
          <thead>
            <tr>
              <th style="border-bottom: 1px solid black;width:150px">DET</th>
              <th style="border-bottom: 1px solid black">MES</th>
              <th style="border-bottom: 1px solid black">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detallefacturaganaderia as $det)
              <tr>
                <td>
                {{$det->OBSERVACION}}
               </td>
               <td>
                {{$det->ANIOMES}}
               </td>
               <td style="text-align: right;">
                $ {{ number_format($det->TOTAL, 2) }}
               </td>
             </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan = "1" style="border-top: 1px solid black"></td>
              <td style="border-top: 1px solid black"><strong>SUBTOTAL:</strong></td>
              <td style="border-top: 1px solid black;text-align: right">
                <strong>{{ number_format($facturaganaderia->SUBTOTAL, 2) }}</strong>
              </td>
            </tr>
            <tr>
              <td colspan = "1"></td>
              <td><strong>IVA:</strong></td>
              <td style="text-align: right;"><strong>{{ number_format($facturaganaderia->IVA, 2) }}</strong></td>
            </tr>
            <tr>
              <td colspan = "1"></td>
              <td style="border-top: 1px solid black"><strong>TOTAL:</strong></td>
              <td style="border-top: 1px solid black;text-align: right;"><strong>{{ number_format($facturaganaderia->TOTAL, 2) }}</strong></td>
            </tr>
         </tfoot>
        </table>
      </div>
    </div>



</body>
</html>

