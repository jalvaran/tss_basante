<?php 
$myPage="CambiarPW.php";
include_once("../sesiones/php_control.php");
include_once("css_construct.php");

$obTabla = new Tabla($db);
$obCon=new conexion($idUser);

print("<html>");
print("<head>");
$css =  new CssIni("Cambiar Password");

print("</head>");
print("<body>");
    

    $css->CabeceraIni("Cambiar Password"); //Inicia la cabecera de la pagina
   
    $css->CabeceraFin(); 
    $css->CrearDiv("principal", "container", "center",1,1);
    
    if(isset($_REQUEST["BtnCambiarPW"])){
        $idUsuario=$obCon->normalizar($_REQUEST["idUsuario"]);
        $Pass= md5($obCon->normalizar($_REQUEST["TxtPass"]));
        
        $obCon->ActualizaRegistro("usuarios", "password", $Pass, "idUsuarios", $idUsuario, 0);
        $css->CrearNotificacionVerde("Contraseña cambiada Satisfactoriamente", 16);
        exit();
    }
    
    if(isset($_REQUEST["idUsuario"])){
        $idUsuario=$obCon->normalizar($_REQUEST["idUsuario"]);
        $DatosUsuarios=$obCon->ValorActual("usuarios", "Nombre,Apellido", " idUsuarios='$idUsuario'");
        $css->CrearForm2("FrmCambiarPass", $myPage, "post", "_self");
            
            $css->CrearNotificacionAzul("Digite la nueva contraseña para el usuario ".$DatosUsuarios["Nombre"]." ".$DatosUsuarios["Apellido"], 16);
            $css->CrearInputText("idUsuario", "hidden", "", $idUsuario, "", "", "", "", 0, 0, 0, 0);
            $css->CrearInputText("TxtPass", "password", "", "", "", "", "", "", 300, 30, 0, 1, "Password", "", "");
            print("<br>");
            $css->CrearBotonConfirmado("BtnCambiarPW", "Cambiar Password");
        $css->CerrarForm();
    
    }
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    
    print("</body></html>");
    ob_end_flush();
?>