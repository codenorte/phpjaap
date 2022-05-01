<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>Bolsa de Empleos | Reporte Administrador</title>

    
    <style>
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 3cm 2cm 2cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #2a0927;
            color: white;
            text-align: center;
            line-height: 30px;
        }
        h3{
        	color: black;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #2a0927;
            color: white;
            text-align: center;
            line-height: 35px;
        }
    </style>
    
  </head>

  <body>
    <header>
      <!--
-->      
    <div class="feed-element">
        <h2><strong>JAAP</strong></h2>
        <h3>Reportes de usuarios Admnistradores</h3>

        <img src="assets/img/itca-logo.png" alt="" style="width: 80px;padding-left: 5px;">

    </div>
    </header>
    <footer>
      <table>
        <tr>
          <td>
            <p class="izq">
              http://be.tecnologicoitca.com/
            </p>
          </td>
          <td>
            <p class="page">
              Página
            </p>
          </td>
        </tr>
      </table>
    </footer>
    <div id="content">

      <h6 class="text-danger">
        Total administradores: 
      </h6>
      <span class="text-danger">Hola</span>
      <div class="ibox-content">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Usuario</th>
            </tr>
          </thead>
          <tbody>
            @foreach($user as $emp)
              <tr>
               <td>{{$emp->id}}</td>
               <td>{{$emp->usuario}}</td>
             </tr>
            @endforeach
            
          </tbody>
        </table>

      </div>

</div>



</body>
</html>

