<?php
$myPage="salud_registro_glosas.php";
include_once("../sesiones/php_control.php");

////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////


        
        
include_once ('funciones/function.php');  //En esta funcion está la paginacion

include_once("Configuraciones/salud_registro_glosas.ini.php");  //Clases de donde se escribirán las tablas
$obTabla = new Tabla($db);
$obVenta=new conexion($idUser);
$statement = $obTabla->CreeFiltro($Vector);

$FitroAdicional="";
if(isset($_REQUEST["TxtFechaInicialRango"])){
    $FechaIni=$obVenta->normalizar($_REQUEST["TxtFechaInicialRango"]);
    $FechaFin=$obVenta->normalizar($_REQUEST["TxtFechaFinalRango"]);
    $FitroAdicional=" AND fecha_factura >= '$FechaIni' AND fecha_factura <= '$FechaFin'";
}

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
$css->CabeceraFin(); 

///////////////Creamos el contenedor
    /////
    /////
$css->CrearDiv("principal", "container", "center",1,1);
$obTabla->FormularioRangoFechas($myPage,$statement, "");
$statement=$obTabla->FiltroRangoFechas("FechaReporte", $statement, "");
$Vector["statement"]=$statement;   //Filtro necesario para la paginacion
$css->DivNotificacionesJS();
//print($statement);
///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
$css->CrearImageLink("../VMenu/MnuSalud.php", "../images/glosas2.png", "_self",100,100);

//print($statement."<br>".$statement2);

if($TipoUser=="administrador"){
    
    $Consulta=$obVenta->Query("SELECT  COUNT(num_factura) as TotalFacturas,SUM(GlosaEPS) as TotalEPS,SUM(GlosaAceptada) as TotalAceptado FROM $statement  ");
    $DatosGlosas=$obVenta->FetchArray($Consulta);
    $TotalFacturas=  number_format($DatosGlosas["TotalFacturas"]);
    $TotalEPS=  number_format($DatosGlosas["TotalEPS"]);
    $TotalAceptado=number_format($DatosGlosas["TotalAceptado"]);
    $Diferencia=number_format($DatosGlosas["TotalEPS"]-$DatosGlosas["TotalAceptado"]);
    $css->CrearNotificacionAzul("Total Facturas = $TotalFacturas, Total EPS = $TotalEPS, Total Aceptado = $TotalAceptado, Diferencia = $Diferencia", 16);
}

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
//$css->AgregaSubir();    
////Fin HTML  
print("</body></html>");


?>