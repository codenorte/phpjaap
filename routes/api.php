<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::apiResource('roles', ProjectController::class)->middleware('auth:api');
//Route::get('/roles',['uses'=>'App\Http\Controllers\RolesController@index']);
//Route::apiResource('roles', 'App\Http\Controllers\RolesController@index');

//Login del usuario
Route::post('/users/login',['uses'=>'App\Http\Controllers\UsersController@login']);
//copiar tabla clave y usuario
Route::put('/actualizarClaveDeUsuario/{users_id}',['uses'=>'App\Http\Controllers\UsersController@actualizarClaveDeUsuario']);
//copiar tabla user a fotos 
Route::get('/copyUserToFotos',['uses'=>'App\Http\Controllers\UsersController@copyUserToFotos']);

Route::get('/getAllUser',['uses'=>'App\Http\Controllers\UsersController@getAllUser']);

/*
*
* REPORTES
*/
Route::get('/createPDF/pdf',['uses'=>'App\Http\Controllers\ReportesController@createPDF']);
//factura normal
Route::get('/verFacturaIdPDF/{factura_id}',['uses'=>'App\Http\Controllers\ReportesController@verFacturaIdPDF']);
//factura ticket
Route::get('/verFacturaIdPDFTicket/{factura_id}',['uses'=>'App\Http\Controllers\ReportesController@verFacturaIdPDFTicket']);
//reporte de cobros por rango de meses consumo agua
Route::get('/reporteObtenerCobroMensual/{controlaniomes_inicio}/{controlaniomes_fin?}',['uses'=>'App\Http\Controllers\ReportesController@reporteObtenerCobroMensual']);
// Reporte de factura realizarPagoFacturaAsistencia
Route::get('/verRealizarPagoFacturaAsistenciaTicket/{NUMFACTURA}',['uses'=>'App\Http\Controllers\ReportesController@verRealizarPagoFacturaAsistenciaTicket']);
//factura ticket ganaderia
Route::get('/verFacturaGanaderiaTicket/{facturasganaderia_id}',['uses'=>'App\Http\Controllers\ReportesController@verFacturaGanaderiaTicket']);

//factura ticket aguasobrante
Route::get('/verFacturaSobranteTicket/{facturasobrante_id}',['uses'=>'App\Http\Controllers\ReportesController@verFacturaSobranteTicket']);

//Generate PDF ticket pago de instalaacion de medidor facturainstalacion,detallefacturainstalacion
Route::get('/verFacturaInstalacionAguaTicket/{facturainstalacion_id}',['uses'=>'App\Http\Controllers\ReportesController@verFacturaInstalacionAguaTicket']);


    //obtener totales de los usuarios
    Route::get('/getAllUserTotales',['uses'=>'App\Http\Controllers\UsersController@getAllUserTotales']);

/***********************************************************************************************************************
***********************************************************************************************************************
******* USUARIOS AUTENTICADOS *****************************************************************************************
***********************************************************************************************************************
*/

Route::group(['middleware' => ['authorized']], function () {

    /*
    *
    * USERS
    */
    //obtener lista de usuarios
    //Route::get('/getAllUser',['uses'=>'App\Http\Controllers\UsersController@getAllUser']);
    Route::get('/getIdUser/{id}',['uses'=>'App\Http\Controllers\UsersController@getIdUser']);
    Route::post('/createUser',['uses'=>'App\Http\Controllers\UsersController@createUser']);
    Route::put('/editUser/{id}',['uses'=>'App\Http\Controllers\UsersController@editUser']);
    Route::delete('/destroyUser/{id}',['uses'=>'App\Http\Controllers\UsersController@destroyUser']);
    //obtener usuarios totales con medidor
    Route::get('/getAllUserMedidor',['uses'=>'App\Http\Controllers\UsersController@getAllUserMedidor']);
    //obtener usuarios con medidor estado activo
    Route::get('/getAllUserMedidorEstado/{asistencia_id}',['uses'=>'App\Http\Controllers\UsersController@getAllUserMedidorEstado']);
    //obtener usuarios con medidor estado activo
    Route::get('/getAllUserMedidorEstadoActivo',['uses'=>'App\Http\Controllers\UsersController@getAllUserMedidorEstadoActivo']);
    //actualizar estado users
    Route::put('/actualizarEstadoUsuario/{users_id}',['uses'=>'App\Http\Controllers\UsersController@actualizarEstadoUsuario']);
    //obtener usuario por header token
    Route::get('/getUserHeader',['uses'=>'App\Http\Controllers\UsersController@getUserHeader']);
    //Obtener datos del usuario y medidor por numeromedidor
    Route::get('/getUserMedidorNumeromedidor/{numero_medidor}',['uses'=>'App\Http\Controllers\UsersController@getUserMedidorNumeromedidor']);
    //Obtener usuarios por cedula con medidor
    Route::get('/getUserRucCi/{rucci}/{roles_id}/{estado}',['uses'=>'App\Http\Controllers\UsersController@getUserRucCi']);
    //obtener usuarios adminnistradores
    Route::get('/getAllUserAdmin',['uses'=>'App\Http\Controllers\UsersController@getAllUserAdmin']);
    // obtener usuario cliente
    Route::get('/getAllUserCliente',['uses'=>'App\Http\Controllers\UsersController@getAllUserCliente']);
    //edit user aguaganaderia
    Route::put('/editUserAguaGanaderia/{id}',['uses'=>'App\Http\Controllers\UsersController@editUserAguaGanaderia']);
    //Obtener usuarios con medidor retirado y usuario sin medidor
    Route::get('/getUserMedidorRetirado',['uses'=>'App\Http\Controllers\UsersController@getUserMedidorRetirado']);


	/*
    *   Aguaganaderia
    */
    Route::get('/getAllAguaganaderia',['uses'=>'App\Http\Controllers\AguaganaderiaController@getAllAguaganaderia']);
    Route::get('/getIdAguaganaderia/{id}',['uses'=>'App\Http\Controllers\AguaganaderiaController@getIdAguaganaderia']);
    Route::post('/createAguaganaderia',['uses'=>'App\Http\Controllers\AguaganaderiaController@createAguaganaderia']);
    Route::put('/editAguaganaderia/{id}',['uses'=>'App\Http\Controllers\AguaganaderiaController@editAguaganaderia']);
    Route::delete('/destroyAguaganaderia/{id}',['uses'=>'App\Http\Controllers\AguaganaderiaController@destroyAguaganaderia']);
    //obtener los totales de aguaganaderia
    Route::get('/getAllAguaganaderiaTotales',['uses'=>'App\Http\Controllers\AguaganaderiaController@getAllAguaganaderiaTotales']);
    //usuarios que no esten en aguaganaderia
    Route::get('/getUserLeftjoinAguaganaderia',['uses'=>'App\Http\Controllers\AguaganaderiaController@getUserLeftjoinAguaganaderia']);
    //mostrar Aguaganaderias por user
    Route::get('/getAllUserAguaganaderia',['uses'=>'App\Http\Controllers\AguaganaderiaController@getAllUserAguaganaderia']);
    // mostrar lista de usuarios registrados para detallefacturaganaderia
    Route::get('/getIdControlAguaganaderia/{controlaguaganaderia_id}',['uses'=>'App\Http\Controllers\AguaganaderiaController@getIdControlAguaganaderia']);


    /*
    *   Aguasobrante
    */
    Route::get('/getAllAguasobrante',['uses'=>'App\Http\Controllers\AguasobranteController@getAllAguasobrante']);
    Route::get('/getIdAguasobrante/{id}',['uses'=>'App\Http\Controllers\AguasobranteController@getIdAguasobrante']);
    Route::post('/createAguasobrante',['uses'=>'App\Http\Controllers\AguasobranteController@createAguasobrante']);
    Route::put('/editAguasobrante/{id}',['uses'=>'App\Http\Controllers\AguasobranteController@editAguasobrante']);
    Route::delete('/destroyAguasobrante/{id}',['uses'=>'App\Http\Controllers\AguasobranteController@destroyAguasobrante']);
    //obtener los totales de sobrante
    Route::get('/getAllAguasobranteTotales',['uses'=>'App\Http\Controllers\AguasobranteController@getAllAguasobranteTotales']);
    // mostrar lista de usuarios registrados para detallefacturasobrante
    Route::get('/getIdControlAguasobrante/{controlaguasobrante_id}',['uses'=>'App\Http\Controllers\AguasobranteController@getIdControlAguasobrante']);
    // mostrar Aguasobrante por user
    Route::get('/getAllUserAguasobrante',['uses'=>'App\Http\Controllers\AguasobranteController@getAllUserAguasobrante']);

    
	/*
    *   roles
    */
    Route::get('/getAllRoles',['uses'=>'App\Http\Controllers\RolesController@getAllRoles']);
    Route::get('/getIdRoles/{id}',['uses'=>'App\Http\Controllers\RolesController@getIdRoles']);
    Route::post('/createRoles',['uses'=>'App\Http\Controllers\RolesController@createRoles']);
    Route::put('/editRoles/{id}',['uses'=>'App\Http\Controllers\RolesController@editRoles']);
    Route::delete('/destroyRoles/{id}',['uses'=>'App\Http\Controllers\RolesController@destroyRoles']);


    /*
    *
    * MEDIDOR
    */
    Route::get('/getAllMedidor',['uses'=>'App\Http\Controllers\MedidorController@getAllMedidor']);
    Route::get('/getIdMedidor/{id}',['uses'=>'App\Http\Controllers\MedidorController@getIdMedidor']);
    Route::post('/createMedidor',['uses'=>'App\Http\Controllers\MedidorController@createMedidor']);
    Route::put('/editMedidor/{id}',['uses'=>'App\Http\Controllers\MedidorController@editMedidor']);
    Route::delete('/destroyMedidor/{id}',['uses'=>'App\Http\Controllers\MedidorController@destroyMedidor']);
    //obtener medidor por usuario
    Route::get('/getMedidorIdUsers/{users_id}',['uses'=>'App\Http\Controllers\MedidorController@getMedidorIdUsers']);
    //obtener medidor y Users
    Route::get('/getIdMedidorUser/{id}',['uses'=>'App\Http\Controllers\MedidorController@getIdMedidorUser']);
    //mostrar Medidor y User por codigo 
    Route::get('/getIdMedidorUserCodigo/{codigo}',['uses'=>'App\Http\Controllers\MedidorController@getIdMedidorUserCodigo']);
    //mostrar Medidor y User por idmedidor
    Route::get('/getIdMedidorUserIdmedidor/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\MedidorController@getIdMedidorUserIdmedidor']);
    //copiar columna visto, creted_ad, update_at de la columna fecha
    Route::get('/copiarColumnafecha',['uses'=>'App\Http\Controllers\MedidorController@copiarColumnafecha']);
    //obtener medidor y users
    Route::get('/getAllMedidorUser',['uses'=>'App\Http\Controllers\MedidorController@getAllMedidorUser']);
    //Update codigo medidor
    Route::put('/updateCodigoMedidor/{numero_medidor}',['uses'=>'App\Http\Controllers\MedidorController@updateCodigoMedidor']);
    //edit estado medidor
    Route::put('/editMedidorEstado/{IDUSUARIO}',['uses'=>'App\Http\Controllers\MedidorController@editMedidorEstado']);
    //Obtener codigo de medidor disponibles
    Route::get('/getCodigoMedidorDisponible',['uses'=>'App\Http\Controllers\MedidorController@getCodigoMedidorDisponible']);
    //Realizar pago de instalacion
    Route::put('/realizarPagoInstalacion/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\MedidorController@realizarPagoInstalacion']);
    //mostrar Medidores activos y user
    Route::get('/getAllMedidorUserActivo',['uses'=>'App\Http\Controllers\MedidorController@getAllMedidorUserActivo']);

    /*
    *
    * MEDIDORUSERS
    */
    Route::get('/copiarMedidoruser',['uses'=>'App\Http\Controllers\MedidorusersController@copiarMedidoruser']);
    //busacr medidores repetidos por cedula
    Route::get('/buscarRepetidoMedidor',['uses'=>'App\Http\Controllers\MedidorusersController@buscarRepetidoMedidor']);

    /*
    *
    * FACTURAS
    */
    Route::get('/getIdFactura/{factura_id}',['uses'=>'App\Http\Controllers\FacturasController@getIdFactura']);
    Route::post('/realizarPagoFactura',['uses'=>'App\Http\Controllers\FacturasController@realizarPagoFactura']);
    //obtener ultimas facturas
    Route::get('/getUltimosPagosFactura/{nummedidor}',['uses'=>'App\Http\Controllers\FacturasController@getUltimosPagosFactura']);


    /*
    *
    * DETALLEFACTURA
    */
    //get All -> obtener detallefactura por medidor
    Route::get('/getAllDetallefacturaNumMedidor/{numero_medidor}',['uses'=>'App\Http\Controllers\DetallefacturaController@getAllDetallefacturaNumMedidor']);
    //crear factura detalle
    Route::post('/createDetallefactura',['uses'=>'App\Http\Controllers\DetallefacturaController@createDetallefactura']);

    //editar detallefactura
    Route::put('/editDetallefactura/{detallefactura_id}',['uses'=>'App\Http\Controllers\DetallefacturaController@editDetallefactura']);

    //obtener lista de anio mes
    Route::get('/getAniomes',['uses'=>'App\Http\Controllers\DetallefacturaController@getAniomes']);
    //obtener lista de usuarios sin lectura mensual
    Route::get('/getListasinlectura/{aniomes}',['uses'=>'App\Http\Controllers\DetallefacturaController@getListasinlectura']);
    //obtener lista medidores sin lectura por tabla controlaniomesdetallefactura 
    Route::get('/getSinlecturaporControlaniomes/{controlaniomes_id}',['uses'=>'App\Http\Controllers\DetallefacturaController@getSinlecturaporControlaniomes']);

    //obtener lista de medidores con factura y detallefactura por cobrar - detallecontrolaniomes
    Route::get('/getDetallefacturaporcobrar/{controlaniomes_id}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturaporcobrar']);

    //obtener lista de detallefactura con lectura y sin lectura controlando el año
    Route::get('/getDetallefacturasinlectura/{controlaniomes_id}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturasinlectura']);

    //obtener historial consumo
    Route::get('/getHistorialConsumo/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\DetallefacturaController@getHistorialConsumo']);
     //obtener ultimos 5 registros de consumo detallefactura
    Route::get('/getUltimosRegistrosDet/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\DetallefacturaController@getUltimosRegistrosDet']);
    //obtener lista de detallefacturas por cobrar
    Route::get('/getDetallefacturaNumMedidor/{numero_medidor}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturaNumMedidor']);
     //lista de facturas pendientes por pagar, buscar por idmedidor
    Route::get('/getDetallefacturaIdmedidor/{idmedidor}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturaIdmedidor']);
    //lista de facturas pendientes por pagar, buscar por codigo
    Route::get('/getDetallefacturaCodigo/{codigo}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturaCodigo']);
    //obtener lista de detallefacturas por cobrar buscar por cedula
    Route::get('/getDetallefacturaCedulaRuc/{cedula}',['uses'=>'App\Http\Controllers\DetallefacturaController@getDetallefacturaCedulaRuc']);
    //copiar columna IDFACTURA de facturas a detallefactura
    Route::get('/copiarFacturaEnDetallefactura',['uses'=>'App\Http\Controllers\DetallefacturaController@copiarFacturaEnDetallefactura']);
    //copiar columna controlaniomes_id mes de la tabla controlaniomesdetallefactura el id
    Route::get('/copiarAniomesdecontrolaniomes',['uses'=>'App\Http\Controllers\DetallefacturaController@copiarAniomesdecontrolaniomes']);
    //copiar columna aniomes a columna created_at
    Route::get('/copiarColumnaAniomes',['uses'=>'App\Http\Controllers\DetallefacturaController@copiarColumnaAniomes']);
    //Obtener lista de usuarios con la suma de meses que debe
    Route::get('/getCountDetallefacturaPorCobrar',['uses'=>'App\Http\Controllers\DetallefacturaController@getCountDetallefacturaPorCobrar']);
    //Obtener lista de usuarios detallefactura por cobrar y cobrados
    Route::get('/getCountDetallefacturaAll',['uses'=>'App\Http\Controllers\DetallefacturaController@getCountDetallefacturaAll']);
    //copiar collumna, 
    Route::get('/copiarColumnaMedAnt',['uses'=>'App\Http\Controllers\DetallefacturaController@copiarColumnaMedAnt']);
    //Calcular cobro mensual consumo agua
    Route::get('/obtenerCobroMensual/{controlaniomes_inicio}/{controlaniomes_fin?}',['uses'=>'App\Http\Controllers\DetallefacturaController@obtenerCobroMensual']);

    /*
    *
    * CONTROLMENSUALDETALLEFACTURA
    */
    //copiar columnas de la tabla detallefactura a controlmensualdetallefactura
    Route::get('/copiarDetallefactura',['uses'=>'App\Http\Controllers\ControlmensualdetallefacturaController@copiarDetallefactura']);

    /*
    *
    * CONTROLANIOMESDETALLEFACTURA
    */
    Route::get('/getAllControlaniomesdetallefactura',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getAllControlaniomesdetallefactura']);

    Route::get('/getIdControlaniomesdetallefactura/{id}',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getIdControlaniomesdetallefactura']);

    Route::post('/createControlaniomesdetallefactura',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@createControlaniomesdetallefactura']);

    Route::put('/editControlaniomesdetallefactura/{id}',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@editControlaniomesdetallefactura']);

    Route::delete('/destroyControlaniomesdetallefactura/{id}',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@destroyControlaniomesdetallefactura']);
    //Obtener lista de medidor nuevos de cada mes
    Route::get('/getMedidoresnuevosmes',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getMedidoresnuevosmes']);
    //Obtener lista de detallefactura nuevos por meses
    Route::get('/getDetallefacturanuevos',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getDetallefacturanuevos']);

    //copiar columnas de aniomes de la columna detallefactura a la tabla controlaniomesdetallefacturaController
    Route::get('/copiarColumnaaniomescontrol',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@copiarColumnaaniomescontrol']);
    //obtener lista de anios por descendente
    Route::get('/getAllContrlaniomesDescencente',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getAllContrlaniomesDescencente']);
    //obtener fecha desde param hasta fin
    Route::get('/getAllControlaniomesDescencenteInicioFin/{inicio}',['uses'=>'App\Http\Controllers\ControlaniomesdetallefacturaController@getAllControlaniomesDescencenteInicioFin']);


    /*
    *   categoriasmat
    */
    Route::get('/getAllCategoriasmat',['uses'=>'App\Http\Controllers\CategoriasmatController@getAllCategoriasmat']);
    Route::get('/getIdCategoriasmat/{id}',['uses'=>'App\Http\Controllers\CategoriasmatController@getIdCategoriasmat']);
    Route::post('/createCategoriasmat',['uses'=>'App\Http\Controllers\CategoriasmatController@createCategoriasmat']);
    Route::put('/editCategoriasmat/{id}',['uses'=>'App\Http\Controllers\CategoriasmatController@editCategoriasmat']);
    Route::delete('/destroyCategoriasmat/{id}',['uses'=>'App\Http\Controllers\CategoriasmatController@destroyCategoriasmat']);

    /*
    *   tipomat
    */
    Route::get('/getAllTipomat',['uses'=>'App\Http\Controllers\TipomatController@getAllTipomat']);
    Route::get('/getIdTipomat/{id}',['uses'=>'App\Http\Controllers\TipomatController@getIdTipomat']);
    Route::post('/createTipomat',['uses'=>'App\Http\Controllers\TipomatController@createTipomat']);
    Route::put('/editTipomat/{id}',['uses'=>'App\Http\Controllers\TipomatController@editTipomat']);
    Route::delete('/destroyTipomat/{id}',['uses'=>'App\Http\Controllers\TipomatController@destroyTipomat']);

    /*
    *   materiales
    */
    Route::get('/getAllMateriales',['uses'=>'App\Http\Controllers\MaterialesController@getAllMateriales']);
    Route::get('/getIdMateriales/{id}',['uses'=>'App\Http\Controllers\MaterialesController@getIdMateriales']);
    Route::post('/createMateriales',['uses'=>'App\Http\Controllers\MaterialesController@createMateriales']);
    Route::put('/editMateriales/{id}',['uses'=>'App\Http\Controllers\MaterialesController@editMateriales']);
    Route::delete('/destroyMateriales/{id}',['uses'=>'App\Http\Controllers\MaterialesController@destroyMateriales']);
    //buscar por nombres 
    Route::get('/buscarNombreMateriales/{material_nombre}',['uses'=>'App\Http\Controllers\MaterialesController@buscarNombreMateriales']);
    

    /*
    *   detallemat
    */
    Route::get('/getAllDetallemat',['uses'=>'App\Http\Controllers\DetallematController@getAllDetallemat']);
    Route::get('/getIdDetallemat/{id}',['uses'=>'App\Http\Controllers\DetallematController@getIdDetallemat']);
    Route::post('/createDetallemat',['uses'=>'App\Http\Controllers\DetallematController@createDetallemat']);
    Route::put('/editDetallemat/{id}',['uses'=>'App\Http\Controllers\DetallematController@editDetallemat']);
    Route::delete('/destroyDetallemat/{id}',['uses'=>'App\Http\Controllers\DetallematController@destroyDetallemat']);

    /*
    *   proveedor
    */
    Route::get('/getAllProveedor',['uses'=>'App\Http\Controllers\ProveedorController@getAllProveedor']);
    Route::get('/getIdProveedor/{id}',['uses'=>'App\Http\Controllers\ProveedorController@getIdProveedor']);
    Route::post('/createProveedor',['uses'=>'App\Http\Controllers\ProveedorController@createProveedor']);
    Route::put('/editProveedor/{id}',['uses'=>'App\Http\Controllers\ProveedorController@editProveedor']);
    Route::delete('/destroyProveedor/{id}',['uses'=>'App\Http\Controllers\ProveedorController@destroyProveedor']);
    //buscar por cedula
    Route::get('/getCedulaProveedor/{cedula}',['uses'=>'App\Http\Controllers\ProveedorController@getCedulaProveedor']);
    //get totales
    Route::get('/getTotalProveedores',['uses'=>'App\Http\Controllers\ProveedorController@getTotalProveedores']);


    /*
    *   compra
    */
    Route::get('/getAllCompra',['uses'=>'App\Http\Controllers\CompraController@getAllCompra']);
    Route::get('/getIdCompra/{id}',['uses'=>'App\Http\Controllers\CompraController@getIdCompra']);
    Route::post('/createCompra',['uses'=>'App\Http\Controllers\CompraController@createCompra']);
    Route::put('/editCompra/{id}',['uses'=>'App\Http\Controllers\CompraController@editCompra']);
    Route::delete('/destroyCompra/{id}',['uses'=>'App\Http\Controllers\CompraController@destroyCompra']);


    /*
    *   corte
    */
    Route::get('/getAllCorte',['uses'=>'App\Http\Controllers\CorteController@getAllCorte']);
    Route::get('/getIdCorte/{id}',['uses'=>'App\Http\Controllers\CorteController@getIdCorte']);
    Route::post('/createCorte',['uses'=>'App\Http\Controllers\CorteController@createCorte']);
    Route::put('/editCorte/{id}',['uses'=>'App\Http\Controllers\CorteController@editCorte']);
    Route::delete('/destroyCorte/{id}',['uses'=>'App\Http\Controllers\CorteController@destroyCorte']);
    //actualizar tabla directo
    Route::get('/actualizarEstadoTablaDirecto',['uses'=>'App\Http\Controllers\CorteController@actualizarEstadoTablaDirecto']);
    //crear todos los cortes contando los no pagados
    Route::get('/createAllCorte',['uses'=>'App\Http\Controllers\CorteController@createAllCorte']);
    //Obtener corte por medidor
    Route::get('/getCorteporMedidor/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\CorteController@getCorteporMedidor']);
    //Buscar cortes por CODIGO
    Route::get('/getCorteporMedidorCodigo/{CODIGO}',['uses'=>'App\Http\Controllers\CorteController@getCorteporMedidorCodigo']);
    //Obtener lista de usuarios con reconexion
    Route::get('/getAllCorteUser',['uses'=>'App\Http\Controllers\CorteController@getAllCorteUser']);


    /*
    *   OTROSPAGOS
    */
    Route::get('/getAllOtrospagos',['uses'=>'App\Http\Controllers\OtrospagosController@getAllOtrospagos']);
    Route::get('/getIdOtrospagos/{id}',['uses'=>'App\Http\Controllers\OtrospagosController@getIdOtrospagos']);
    Route::post('/createOtrospagos/{IDFACTURA}',['uses'=>'App\Http\Controllers\OtrospagosController@createOtrospagos']);
    Route::put('/editOtrospagos/{id}',['uses'=>'App\Http\Controllers\OtrospagosController@editOtrospagos']);
    Route::delete('/destroyOtrospagos/{id}',['uses'=>'App\Http\Controllers\OtrospagosController@destroyOtrospagos']);


    /*
    *   INSTITUCION
    */
    Route::get('/getAllInstitucion',['uses'=>'App\Http\Controllers\InstitucionController@getAllInstitucion']);
    Route::get('/getIdInstitucion/{id}',['uses'=>'App\Http\Controllers\InstitucionController@getIdInstitucion']);
    Route::post('/createInstitucion/{IDFACTURA}',['uses'=>'App\Http\Controllers\InstitucionController@createInstitucion']);
    Route::put('/editInstitucion/{id}',['uses'=>'App\Http\Controllers\InstitucionController@editInstitucion']);
    Route::delete('/destroyInstitucion/{id}',['uses'=>'App\Http\Controllers\InstitucionController@destroyInstitucion']);
    

    /*
    *   TARIFAS
    */
    Route::get('/getAllTarifas',['uses'=>'App\Http\Controllers\TarifasController@getAllTarifas']);
    Route::get('/getIdTarifas/{id}',['uses'=>'App\Http\Controllers\TarifasController@getIdTarifas']);
    Route::post('/createTarifas/{IDFACTURA}',['uses'=>'App\Http\Controllers\TarifasController@createTarifas']);
    Route::put('/editTarifas/{id}',['uses'=>'App\Http\Controllers\TarifasController@editTarifas']);
    Route::delete('/destroyTarifas/{id}',['uses'=>'App\Http\Controllers\TarifasController@destroyTarifas']);	

    /*
    * PLANIFICACION
    */
    Route::get('/getAllPlanificacion',['uses'=>'App\Http\Controllers\PlanificacionController@getAllPlanificacion']);
    Route::get('/getIdPlanificacion/{id}',['uses'=>'App\Http\Controllers\PlanificacionController@getIdPlanificacion']);
    Route::post('/createPlanificacion',['uses'=>'App\Http\Controllers\PlanificacionController@createPlanificacion']);
    Route::put('/editPlanificacion/{id}',['uses'=>'App\Http\Controllers\PlanificacionController@editPlanificacion']);
    Route::delete('/destroyPlanificacion/{id}',['uses'=>'App\Http\Controllers\PlanificacionController@destroyPlanificacion']);    
    //cambia de estado revisando el tiempo de caducidad 
    Route::get('/caducarPlanificacionEstado',['uses'=>'App\Http\Controllers\PlanificacionController@caducarPlanificacionEstado']);
    // Crear Planificacion con todos los medidores activos se registra como NO ASISTIDO a la minga
    Route::put('/createPlanificacionAllUser/{planificacion_id}',['uses'=>'App\Http\Controllers\PlanificacionController@createPlanificacionAllUser']);

    /*
    *   ASISTENCIA
    */
    Route::get('/getAllAsistencia',['uses'=>'App\Http\Controllers\AsistenciaController@getAllAsistencia']);
    Route::get('/getIdAsistencia/{id}',['uses'=>'App\Http\Controllers\AsistenciaController@getIdAsistencia']);
    Route::post('/createAsistencia',['uses'=>'App\Http\Controllers\AsistenciaController@createAsistencia']);
    Route::put('/editAsistencia/{id}',['uses'=>'App\Http\Controllers\AsistenciaController@editAsistencia']);
    Route::delete('/destroyAsistencia/{id}',['uses'=>'App\Http\Controllers\AsistenciaController@destroyAsistencia']);   
    //obtener lista de usuarios asistidos por planificacion_id
    Route::get('/getIdAsistenciaMedidorUsers/{planificacion_id}',['uses'=>'App\Http\Controllers\AsistenciaController@getIdAsistenciaMedidorUsers']);
    //Registrar asistencia, cambio de estado 
    Route::put('/registrarAsistencia/{planificacion_id}',['uses'=>'App\Http\Controllers\AsistenciaController@registrarAsistencia']);
    //Buscar multas de mingas
    Route::get('/buscarMingasUsuarioId/{medidor_id}',['uses'=>'App\Http\Controllers\AsistenciaController@buscarMingasUsuarioId']);

    /*
    *   PAGOASISTENCIA
    */
    Route::post('/realizarPagoFacturaAsistencia',['uses'=>'App\Http\Controllers\PagosasistenciaController@realizarPagoFacturaAsistencia']);
    //ver historial de ultimos 10 pagos
    Route::get('/historialPagosasistencia/{IDMEDIDOR}',['uses'=>'App\Http\Controllers\PagosasistenciaController@historialPagosasistencia']);

    /*
    *   TRANSPASO
    */
    Route::get('/getAllTranspaso',['uses'=>'App\Http\Controllers\TranspasoController@getAllTranspaso']);
    Route::get('/getIdTranspaso/{id}',['uses'=>'App\Http\Controllers\TranspasoController@getIdTranspaso']);
    Route::post('/createTranspaso',['uses'=>'App\Http\Controllers\TranspasoController@createTranspaso']);
    Route::put('/editTranspaso/{id}',['uses'=>'App\Http\Controllers\TranspasoController@editTranspaso']);
    Route::delete('/destroyTranspaso/{id}',['uses'=>'App\Http\Controllers\TranspasoController@destroyTranspaso']);


    /*
    *   DETALLEFACTURAGANADERIA
    */
    Route::get('/getAllDetallefacturaganaderia',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@getAllDetallefacturaganaderia']);
    Route::get('/getIdDetallefacturaganaderia/{id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@getIdDetallefacturaganaderia']);
    Route::post('/createDetallefacturaganaderia/{controlaniomesganaderia_id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@createDetallefacturaganaderia']);
    Route::put('/editDetallefacturaganaderia/{id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@editDetallefacturaganaderia']);
    Route::delete('/destroyDetallefacturaganaderia/{id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@destroyDetallefacturaganaderia']);
    //Crear todas las facturas de la lista
    Route::get('/createAllDetallefacturaganaderia/{controlaniomesganaderia_id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@createAllDetallefacturaganaderia']);
    //Buscar facturas pendientes detallefacturaganaderia
    Route::get('/getDetallefacturaganaderia/{aguaganaderia_id}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@getDetallefacturaganaderia']);
    //Calcular cobro mensual consumo agua ganaderia
    Route::get('/obtenerCobroMensualGanaderia/{controlaniomes_inicio}/{controlaniomes_fin?}',['uses'=>'App\Http\Controllers\DetallefacturaganaderiaController@obtenerCobroMensualGanaderia']);

    /*
    *   DETALLEFACTURAGANADERIA
    */
    Route::get('/getAllDetallefacturasobrante',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@getAllDetallefacturasobrante']);
    Route::get('/getIdDetallefacturasobrante/{id}',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@getIdDetallefacturasobrante']);
    Route::post('/createDetallefacturasobrante/{controlaniomessobrante_id}',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@createDetallefacturasobrante']);
    Route::put('/editDetallefacturasobrante/{id}',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@editDetallefacturasobrante']);
    Route::delete('/destroyDetallefacturasobrante/{id}',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@destroyDetallefacturasobrante']);
    //Buscar facturas pendientes detallefacturasobrante
    Route::get('/getDetallefacturasobrante/{aguasobrante_id}',['uses'=>'App\Http\Controllers\DetallefacturasobranteController@getDetallefacturasobrante']);
    

    /*
    *   TARIFASGANADERIA
    */
    Route::get('/getAllTarifasganaderia',['uses'=>'App\Http\Controllers\TarifasganaderiaController@getAllTarifasganaderia']);
    Route::get('/getIdTarifasganaderia/{id}',['uses'=>'App\Http\Controllers\TarifasganaderiaController@getIdTarifasganaderia']);
    Route::post('/createTarifasganaderia',['uses'=>'App\Http\Controllers\TarifasganaderiaController@createTarifasganaderia']);
    Route::put('/editTarifasganaderia/{id}',['uses'=>'App\Http\Controllers\TarifasganaderiaController@editTarifasganaderia']);
    Route::delete('/destroyTarifasganaderia/{id}',['uses'=>'App\Http\Controllers\TarifasganaderiaController@destroyTarifasganaderia']);
    //mostrar ultimo dato de Tarifasganaderia
    Route::get('/getTarifasganaderiaLatest',['uses'=>'App\Http\Controllers\TarifasganaderiaController@getTarifasganaderiaLatest']);

    /*
    *   TARIFASSOBRANTE
    */
    Route::get('/getAllTarifassobrante',['uses'=>'App\Http\Controllers\TarifassobranteController@getAllTarifassobrante']);
    Route::get('/getIdTarifassobrante/{id}',['uses'=>'App\Http\Controllers\TarifassobranteController@getIdTarifassobrante']);
    Route::post('/createTarifassobrante',['uses'=>'App\Http\Controllers\TarifassobranteController@createTarifassobrante']);
    Route::put('/editTarifassobrante/{id}',['uses'=>'App\Http\Controllers\TarifassobranteController@editTarifassobrante']);
    Route::delete('/destroyTarifassobrante/{id}',['uses'=>'App\Http\Controllers\TarifassobranteController@destroyTarifassobrante']);
    //mostrar ultimo dato de getTarifassobranteLatest
    Route::get('/getTarifassobranteLatest',['uses'=>'App\Http\Controllers\TarifassobranteController@getTarifassobranteLatest']);

    /*
    *   FACTURASGANADERIA
    */
    Route::post('/realizarPagoFacturaGanaderia',['uses'=>'App\Http\Controllers\FacturasganaderiaController@realizarPagoFacturaGanaderia']);
    //historial facturaaguaganaderia
    Route::get('/historialFacturaGanaderia/{IDAGUAGANADERIA}',['uses'=>'App\Http\Controllers\FacturasganaderiaController@historialFacturaGanaderia']);

    /*
    *   CONTROLANIOMESAGUAGANADERIA
    */
    Route::get('/getAllControlaniomesganaderia',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@getAllControlaniomesganaderia']);
    Route::get('/getIdControlaniomesganaderia/{id}',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@getIdControlaniomesganaderia']);
    Route::post('/createControlaniomesganaderia',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@createControlaniomesganaderia']);
    Route::put('/editControlaniomesganaderia/{id}',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@editControlaniomesganaderia']);
    Route::delete('/destroyControlaniomesganaderia/{id}',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@destroyControlaniomesganaderia']);
    //obtener fecha descente controlaniomes
    Route::get('/getAllContrlaniomesDescencenteGanaderia',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@getAllContrlaniomesDescencenteGanaderia']);
    //obtener rango de fecha 
    Route::get('/getAllControlaniomesDescencenteInicioFinGanaderia/{inicio}',['uses'=>'App\Http\Controllers\ControlaniomesganaderiaController@getAllControlaniomesDescencenteInicioFinGanaderia']);

    /*
    *   CONTROLANIOMESAGUASOBRANTE
    */
    Route::get('/getAllControlaniomessobrante',['uses'=>'App\Http\Controllers\ControlaniomessobranteController@getAllControlaniomessobrante']);
    Route::get('/getIdControlaniomessobrante/{id}',['uses'=>'App\Http\Controllers\ControlaniomessobranteController@getIdControlaniomessobrante']);
    Route::post('/createControlaniomessobrante',['uses'=>'App\Http\Controllers\ControlaniomessobranteController@createControlaniomessobrante']);
    Route::put('/editControlaniomessobrante/{id}',['uses'=>'App\Http\Controllers\ControlaniomessobranteController@editControlaniomessobrante']);
    Route::delete('/destroyControlaniomessobrante/{id}',['uses'=>'App\Http\Controllers\ControlaniomessobranteController@destroyControlaniomessobrante']);


    /*
    *   FACTURASGANADERIA
    */
    Route::post('/realizarPagoFacturaSobrante',['uses'=>'App\Http\Controllers\FacturassobranteController@realizarPagoFacturaSobrante']);

});


