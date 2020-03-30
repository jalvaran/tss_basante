<?php
$myPage="salud_procesos_gerenciales.php";
include_once("../sesiones/php_control.php");

////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////


        
        
include_once ('funciones/function.php');  //En esta funcion está la paginacion

include_once("Configuraciones/salud_procesos_gerenciales.ini.php");  //Clases de donde se escribirán las tablas
$obTabla = new Tabla($db);

$statement = $obTabla->CreeFiltro($Vector);
//print($statement);
$Vector["statement"]=$statement;   //Filtro necesario para la paginacion


$obTabla->VerifiqueExport($Vector);

include_once("css_construct.php");
print("<html>");
print("<head>");

$css =  new CssIni($myTitulo);
print("</head>");
print("<body>");
//Cabecera
$css->CabeceraIni($myTitulo); //Inicia la cabecera de la pagina
    $css->CreaBotonDesplegable("DialNuevoProceso","NUEVO");
    $css->CreaBotonDesplegable("DialAgregarSoporte","AGREGAR SOPORTE A PROCESO EXISTENTE");
$css->CabeceraFin(); 

///////////////Creamos el contenedor
    /////
    /////
$css->CrearDiv("principal", "container", "center",1,1);
include_once("procesadores/SaludProcesosGerenciales.process.php");  //Clases de donde se escribirán las tablas
//Formulario de nuevo proceso en ventana de dialogo

$css->CrearCuadroDeDialogo("DialNuevoProceso", "Nuevo Proceso Gerencial");
    $css->CrearForm2("FrmGuardar", $myPage, "post", "_self");   
        $css->CrearInputText("TxtFecha", "date", "<strong>Fecha:</strong><br>", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
        print("<br><strong>IPS:</strong><br>");
        $css->CrearSelectTable("CmbIps", "empresapro", "", "idEmpresaPro", "RazonSocial", "CodigoPrestadora", "Ciudad", "", "", 1, "Seleccione la IPS");
        
        print("<br><strong>EPS:</strong><br>");
        $css->CrearTableChosen("CmbEps", "salud_eps", "", "nombre_completo", "cod_pagador_min", "sigla_nombre", "cod_pagador_min", 300, 1, "EPS", "");
        //$css->CrearSelectTable("CmbEps", "salud_eps", "", "cod_pagador_min", "nombre_completo", "cod_pagador_min", "", "", "", 1, "Seleccione la EPS");
        $css->CrearInputText("TxtNombre", "text", "<strong>Nombre del Proceso:</strong><br>", "", "Nombre", "", "", "", 230, 30, 0, 1);
        print("<strong>Concepto:</strong><br>");
        $css->CrearSelectTable("CmbConcepto", "salud_procesos_gerenciales_conceptos", "", "ID", "Concepto", "ID", "", "", "", 1);
        $css->CrearTextArea("TxtObservaciones", "<strong>Observaciones:</strong><br>", "", "Observaciones", "", "", "", 200, 60, 0, 1);
        print("<br><strong>Primer Soporte:</strong><br>");
        $css->CrearUpload("Soporte");
        print("<br>");
        $css->CrearBotonConfirmado("BtnGuardar", "Guardar");
    $css->CerrarForm();
$css->CerrarCuadroDeDialogo();

//Formulario para agregar soporte a proceso existente

$css->CrearCuadroDeDialogo("DialAgregarSoporte", "Agregar Soporte a un Proceso Existente");
    $id=$obCon->ObtenerMAX("salud_procesos_gerenciales", "ID", 1, "");
    $css->CrearForm2("FrmAgregar", $myPage, "post", "_self");
        $css->CrearInputNumber("idProceso", "number", "<strong>ID del proceso:</strong><br>", 0, "ID", "", "", "", 100, 30, 0, 1, 1, $id, 1);
        $css->CrearInputText("TxtFecha", "date", "<br><strong>Fecha:</strong><br>", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
        $css->CrearTextArea("TxtObservaciones", "<strong>Observaciones:</strong><br>", "", "Observaciones", "", "", "", 200, 60, 0, 1);
        print("<br><strong>Soporte:</strong><br>");
        $css->CrearUpload("Soporte");
        print("<br>");
        $css->CrearBotonConfirmado("BtnAgregarSoporte", "Agregar");
    $css->CerrarForm();
$css->CerrarCuadroDeDialogo();


$Vector["statement"]=$statement;   //Filtro necesario para la paginacion
$css->DivNotificacionesJS();
//print($statement);
///////////////Creamos la imagen representativa de la pagina
    /////
    /////


////Paginacion
////


$Ruta="";
print("<div style='height: 50px;'>");   //Dentro de un DIV para no hacerlo tan grande
print(pagination($Ruta,$statement,$limit,$page));
print("</div>");
////
///Dibujo la tabla
////
///
 
/////

$obTabla->DibujeTabla($Vector);

$css->CerrarDiv();//Cerramos contenedor Principal
$css->Footer();
$css->AgregaJS(); //Agregamos javascripts
$css->AnchoElemento("CmbEps_chosen", "300");
//$css->AgregaSubir();    
////Fin HTML  
print("</body></html>");


?>