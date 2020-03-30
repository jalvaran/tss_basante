<?php
/* 
 * Este archivo se encarga de escuchar las peticiones de diferentes acciones
 */
include_once("../../modelo/php_conexion.php");  //Clases de donde se escribirán las tablas
//include_once("../../modelo/PrintPos.php");      //Imprime documentos en la impresora pos
include_once("../css_construct.php");
session_start();
$idUser=$_SESSION['idUser'];

if(isset($_REQUEST["idAccion"])){
       
    $obCon = new conexion($idUser);
        
    $idAccion=$obCon->normalizar($_REQUEST["idAccion"]);
    
    switch ($idAccion){
        case 1: //Crear alarmas
            
            break;
        case 2: //Leer Alarmas
            
            break;
        case 3: //Cambia estado a alarma leido
            
            break;
        case 4: //Cambia estado a alarma tratado
            
            break;
        
    }
}else{
    print("No se recibió parametro");
}
?>