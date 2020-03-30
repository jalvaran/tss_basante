<?php
/*
 * Parametros de configuracion titulos
 * Columnas Excluidas
 */
$TablaConfig="titulos_listados_promocion";
$Vector[$TablaConfig]["Excluir"]["idActa"]=1;
$Vector[$TablaConfig]["Excluir"]["TotalPagoComisiones"]=1;
$Vector[$TablaConfig]["Excluir"]["idCliente"]=1;
$Vector[$TablaConfig]["Excluir"]["NombreCliente"]=1;   //Indico que esta columna no se mostrará
$Vector[$TablaConfig]["Excluir"]["FechaVenta"]=1;
$Vector[$TablaConfig]["Excluir"]["TotalAbonos"]=1;
$Vector[$TablaConfig]["Excluir"]["Saldo"]=1;   

/*
 * Parametros de configuracion usuarios
 * Columnas Excluidas
 */
$TablaConfig="usuarios";
$Vector[$TablaConfig]["Excluir"]["Password"]=1;
$Vector[$TablaConfig]["Excluir"]["Role"]=1;


?>