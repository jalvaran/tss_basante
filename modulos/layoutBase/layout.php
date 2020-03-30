<?php
/**
 * Pagina base para la plataforma TSS
 * 2018-11-27, Julian Alvaran Techno Soluciones SAS
 */
$myPage="layout.php";
$myTitulo="Plataforma TSS";
include_once("../../sesiones/php_control_usuarios.php");
include_once("../../constructores/paginas_constructor.php");
$css =  new PageConstruct($myTitulo, ""); //objeto con las funciones del html
$obCon = new conexion($idUser); //Conexion a la base de datos

$css->PageInit($myTitulo);

    //Codigo del desarrollador para su pagina
        
$css->PageFin();

//print('<script src="jsPages/administrador.js"></script>');  //scrip propio de la pagina

$css->Cbody();
$css->Chtml();

?>