<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>JAAP | Reporte General</title>

    
    <style>


      * {
            
            font-family: Arial;
        }

        html {
          margin: 3cm 2.5cm 3cm 2.5cm;
        }

        h1 {
            
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        .table {
            border-top: 1px solid black;
          /*
            border-collapse: collapse;
            */
            margin: 0 auto;
            height: 5%;
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

        .header {
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
          margin: 0 0 0 2px;
          border: red solid 1px;
          */
          vertical-align: middle;
        }
        .header_text {
          display: table-cell;
          text-align: center;
          /*
          */
          vertical-align: middle;
          /*
          border: red solid 2px;
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
        .tabla_contactos{
          display: table;
          width: 100%;
        }
        .datos_contactos{
          display: table-cell;
          /*
          border: red solid 1px;
          */
          font-size: 11px;
          font-weight: bold;
          text-align: left;
        }

        
    </style>
    
  </head>

  <body>
    <div class="header">
      <div class="header_logo">
        <!--
        <img src="{{asset('img/logo2.jpeg')}}" alt="" style="width: 235px;padding-left: 5px;">
        -->
        <img src="img/logo2.jpeg" alt="" style="width: 200px;">
      </div>
      @if($institucion)
      <div class="header_text">
        <br>
        <h3>
          <strong>{{$institucion->NOMBREINST}}</strong><br><br>
        </h3>

        <div class="tabla_contactos">
          <div class="datos_contactos">
            @if($institucion->DIRECCION!=null||$institucion->DIRECCION!='')
            <strong>DIRECCION: {{$institucion->DIRECCION}}</strong><br>
            @endIf
            @if($institucion->TELEFONO!=null||$institucion->TELEFONO!='')
            <strong>Tlf: {{$institucion->TELEFONO}}</strong><br>
            @endIf
            @if($institucion->CELULAR!=null||$institucion->CELULAR!='')
            <strong>Cel: {{$institucion->CELULAR}}</strong>
            @endIf
          </div>
          <div class="datos_contactos">
            @if($institucion->RUC!=null||$institucion->RUC!='')
            <strong style="font-weight: bold!important;">RUC: {{$institucion->RUC}} </strong><br>
            @endIf
            @if($institucion->EMAIL!=null||$institucion->EMAIL!='')
            <strong>EMAIL: {{$institucion->EMAIL}}</strong>
            @endIf
          </div>
        </div>
      </div>
      @endIf
    </div>
    <br>
    <h3 style="text-align: center;">REPORTE GENERAL DE COBROS</h3>
    @if($numero_meses>1)
      <h4 style="text-align: center;">
        Meses
        {{$fechainicio->monthName}} {{$fechainicio->year}} a {{$fechafin->monthName}} {{$fechafin->year}}
      </h4>
    @endIf
    @if($numero_meses==1)
      <h4 style="text-align: center;">
        Mes 
        {{$fechainicio->monthName}} {{$fechainicio->year}}
      </h4>
    @endIf




    <br><br><br>
    <h4>
      CORRESPONDIENTE A
      @if($numero_meses>1)
          {{$numero_meses}} MESES
      @endIf
      @if($numero_meses==1)
        {{$numero_meses}} MES
      @endIf
    </h4>
    <hr>

    <div id="content">
      <div class="ibox-content">
        <br>
        <table class="table">
          <thead>
            <tr>
              <th>DESCRIPCION</th>
              <th>TOTALES</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                Consumo mensual total
              </td>
              <td style="text-align: right">
                {{$suma_consumo}} <small>m3</small>
              </td>
            </tr>
            <tr>
              <td>
                Suma de medida de exceso mensual total
              </td>
              <td style="text-align: right">
                {{$suma_excedido}} <small>m3</small>
              </td>
            </tr>
            <tr>
              <td>
                Suma de subtotal mensual total
              </td>
              <td style="text-align: right;">
                $ {{ number_format($suma_subtotal, 2, ",", ".")}} <small>USD</small>
              </td>
           </tr>
           <tr>
              <td>
                Suma de cobro de excesos mensual total
              </td>
              <td style="text-align: right;">
                $ {{ number_format($suma_tar_excedido, 2, ",", ".")}} <small>USD</small>
              </td>
           </tr>
          </tbody>
          <tfoot>
           <tr>
              <td>
                SUMA TOTAL DE COBRO
              </td>
              <td style="text-align: right;">
                <strong>
                $ {{ number_format($suma_total, 2, ",", ".")}} <small>USD</small>
                </strong>
              </td>
           </tr>
          </tfoot>
          
        </table>
      </div>

    </div>



</body>
</html>

