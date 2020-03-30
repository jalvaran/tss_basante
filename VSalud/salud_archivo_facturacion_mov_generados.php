<?php
$myPage="salud_archivo_facturacion_mov_generados.php";
include_once("../sesiones/php_control.php");

////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////


        
        
include_once ('funciones/function.php');  //En esta funcion está la paginacion

include_once("Configuraciones/salud_archivo_facturacion_mov_generados.ini.php");  //Clases de donde se escribirán las tablas
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
$css->CabeceraFin(); 

///////////////Creamos el contenedor
    /////
    /////
$css->CrearDiv("principal", "container", "center",1,1);
$obTabla->FormularioRangoFechas($myPage,$statement, "");
$statement=$obTabla->FiltroRangoFechas("fecha_factura", $statement, "");
$Vector["statement"]=$statement;   //Filtro necesario para la paginacion
$css->DivNotificacionesJS();
//print($statement);
///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
$css->CrearImageLink("../VMenu/MnuInventarios.php", "../images/af.png", "_self",100,100);

if($TipoUser=="administrador"){
    
    $Consulta=$obCon->Query("SELECT  COUNT(num_factura) as TotalFacturas,SUM(valor_neto_pagar) as Total FROM $statement  ");
    $DatosFacturas=$obCon->FetchArray($Consulta);
    $TotalFacturas=  number_format($DatosFacturas["TotalFacturas"]);
    $Total=  number_format($DatosFacturas["Total"]);
    $css->CrearNotificacionAzul("Total de Facturas = $TotalFacturas, Valor = $Total", 16);
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