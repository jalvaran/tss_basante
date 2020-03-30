<?php 
session_start();
include_once("../modelo/php_conexion.php");
include_once("css_construct.php");
if (!isset($_SESSION['username']))
{
  exit("No se ha iniciado una sesion <a href='../index.php' >Iniciar Sesion </a>");
  
}
$NombreUser=$_SESSION['nombre'];
$idUser=$_SESSION['idUser'];	

	
print("<html>");
print("<head>");
$css =  new CssIni("Usuario no Autorizado");

print("</head>");
print("<body>");
    $obVenta = new conexion($idUser);
   
    $myPage="401.php";
    $css->CabeceraIni("Error de Permisos"); //Inicia la cabecera de la pagina
    
       
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
    print("<br>");
    ///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
    $css->CrearImageLink("../VMenu/Menu.php", "../images/401.png", "_self",250,400);
    
    $css->CerrarDiv();
    $css->Footer();
    print("</body></html>");
?>