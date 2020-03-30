<?php
$myPage="vista_salud_facturas_diferencias.php";
include_once("../sesiones/php_control.php");

////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////


        
        
include_once ('funciones/function.php');  //En esta funcion está la paginacion

include_once("Configuraciones/vista_salud_facturas_diferencias.ini.php");  //Clases de donde se escribirán las tablas
$obTabla = new Tabla($db);
$obCon=new conexion($idUser);
$statement = $obTabla->CreeFiltro($Vector);
$Vector2["Tabla"]="salud_archivo_facturacion_mov_generados";
$statement2 = $obTabla->CreeFiltro($Vector2);
$pos = strpos($statement2, "WHERE");
$FitroAdicional="";
if(isset($_REQUEST["TxtFechaInicialRango"])){
    $FechaIni=$obCon->normalizar($_REQUEST["TxtFechaInicialRango"]);
    $FechaFin=$obCon->normalizar($_REQUEST["TxtFechaFinalRango"]);
    $FitroAdicional=" AND fecha_factura >= '$FechaIni' AND fecha_factura <= '$FechaFin'";
}
if($pos === FALSE){
    
    $statement2 .=" WHERE estado='DIFERENCIA' $FitroAdicional";
}else{
    $statement2 .=" AND estado='DIFERENCIA' $FitroAdicional";
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
$statement=$obTabla->FiltroRangoFechas("fecha_factura", $statement, "");
$Vector["statement"]=$statement;   //Filtro necesario para la paginacion
$css->DivNotificacionesJS();
//print($statement);
///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
$css->CrearImageLink("../VMenu/MnuInventarios.php", "../images/historial3.png", "_self",100,100);

//print($statement."<br>".$statement2);

if($TipoUser=="administrador"){
    $statement2= str_replace ("vista_salud_facturas_diferencias","salud_archivo_facturacion_mov_generados",$statement2);
    $Consulta=$obCon->Query("SELECT  (SELECT SUM(`valor_neto_pagar`) FROM $statement2) as Total,SUM(valor_pagado) as ValorPagado FROM $statement  ");
    $DatosFacturacion=$obCon->FetchArray($Consulta);
    $Total=  number_format($DatosFacturacion["Total"]);
    $ValorPagado=  number_format($DatosFacturacion["ValorPagado"]);
    $Diferencia=number_format($DatosFacturacion["Total"]-$DatosFacturacion["ValorPagado"]);
    $css->CrearNotificacionAzul("Total Facturas = $Total, Total Pagado = $ValorPagado, Diferencia = $Diferencia", 16);
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