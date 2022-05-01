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
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 3cm 2cm 2cm;
        }


        .header {
          
          width: 100%;
          height: 15%;
          display: table;
          margin-top: -65px;
        }

        .header_logo {
          display: table-cell;
          text-align: center;
          vertical-align: middle;
          /*
          border: red solid 1px;
          */
          margin: 0 0 0 2px;
        }

        .header_text {
          display: table-cell;
          text-align: center;
          /*
          vertical-align: middle;
          border: red solid 2px;
          */
          font-size: 16px;
        }
        .header_text2 {
          display: table-cell;
          text-align: left;
          vertical-align: middle;
          /*
          border: red solid 2px;
          */
          font-size: 16px;
        }

        .datosUsuario {
          
          width: 100%;
          display: table;
        }

        .datosUsuario_text_left {
          display: table-cell;
          /*
          border: red solid 1px;
          */
          margin: 0 0 0 2px;
        }

        .datosUsuario_text {
          padding-left: 50px;
          display: table-cell;
          /*
          border: red solid 2px;
          */
          font-size: 16px;
        }
        .detallefactura{
          font-size: 12px;
        }
        table thead tr th{
          border-bottom: 1px solid;
        }
        table tbody tr td,table thead tr th{
          border-left: 1px solid;
        }
        table tbody tr td:first-child, table thead tr th:first-child{
          border-left: 0px;
        }
        table tr td{
          font-size: 12px;
        }

    </style>
    
  </head>

  <body>
    <div class="header">
      <div class="header_logo">
        <img src="img/logo2.jpeg" alt="" style="width: 235px;">
        <!--
        <img src="{{asset('img/logo.png')}}" alt="" style="width: 100px;padding-left: 5px;">
        -->
      </div>
      <div class="header_text">
        <strong style="color: blue;">JUNTA ADMINISTRADORA DE AGUA DE "TOCAGÓN"</strong>
        <h5>FACTURA MENSUAL</h5>

        <span> telf: 2954222</span> - <span>Cel: 0959095477</span>
        
      </div>
      <div class="header_text2">
        @if($factura)
        <h3 style="text-align: center;vertical-align: top;">Nº: <strong>{{$factura->NUMFACTURA}}</strong> </h3>
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
        <span>Nro MEDIDOR: {{$medidor->NUMMEDIDOR}}</span><br>
        <span>RUC/CI: {{$medidor->users['RUCCI']}}</span><br>
        <span>CLIENTE: {{$medidor->users['NOMBRES']}} {{$medidor->users['APELLIDOS']}} </span><br>
        <span>DIRECCION: {{$medidor->users['DIRECCION']}}</span><br>
        <span>SECTOR: {{$medidor->users['SECTOR']}}</span>
      </div>
      @endIf
    </div>
    <hr>

    <div id="content">
      <div class="detallefactura">
        <span>
          <strong>PAGO MESES: {{$totaldetalle}}</strong>&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
        <span>
          <strong>CONSUMO: {{$total_consumo}} m3</strong>&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
        <span><strong>Excedido(m3):{{$total_medida_excedido}}m3</strong></span>
        <span><strong>$( {{$total_tarifa_excedido}} )</strong></span>
      </div>


      <div class="ibox-content">
        <table class="table">
          <thead>
            <tr>
              <th>Mes</th>
              <th>Lect. Ant</th>
              <th>Lect. Act</th>
              <th>Consumo</th>
              <th>Exceso</th>
              <th>Subtotal</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($detallefactura as $det)
              <tr>
               <td>{{$det->ANIOMES}}</td>
               <td>{{$det->MEDIDAANT}}</td>
               <td>{{$det->MEDIDAACT}}</td>
               <td>{{$det->CONSUMO}}</td>
               <td>{{$det->MEDEXCEDIDO}}</td>
               <td style="text-align: right;">{{ number_format($det->SUBTOTAL,2) }}</td>
               <td style="text-align: right;">{{ number_format($det->TOTAL,2) }}</td>
             </tr>
            @endforeach
            @if($reconexion>0)
              <tr>
                <td>
                  Reconexion
                </td>
                <td colspan="5"></td>
                <td style="text-align: right;">
                  $ {{ number_format($reconexion, 2) }}
                </td>
              </tr>
            @endif
          </tbody>
          <tfoot>
            <tr>
              <td colspan = "5"></td>
              <td><strong>Subtotal:</strong></td>
              <td><strong>{{$factura->SUBTOTAL}}</strong></td>
            </tr>
            <tr>
              <td colspan = "5"></td>
              <td><strong>Iva:</strong></td>
              <td><strong>{{$factura->IVA}}</strong></td>
            </tr>
            <tr>
              <td colspan = "5"></td>
              <td><strong>Tar excedido:</strong></td>
              <td><strong>{{$total_tarifa_excedido}}</strong></td>
            </tr>
            <tr>
              <td colspan = "5"></td>
              <td><strong>TOTAL:</strong></td>
              <td><strong>{{$factura->TOTAL}}</strong></td>
            </tr>
         </tfoot>
        </table>

      </div>

   </div>



</body>
</html>

