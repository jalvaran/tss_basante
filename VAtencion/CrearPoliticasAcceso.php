<?php
$myPage="CrearPoliticasAcceso.php";
include_once("../sesiones/php_control.php");

include_once("css_construct.php");
print("<html>");
print("<head>");

$css =  new CssIni("Crear Politicas de Acceso");
print("</head>");

print("<body onload=document.getElementById('BtnEnviar').click();>"); 
    
//Cabecera
$css->CabeceraIni("Crear Politicas de Acceso"); 
$idSel="";
if(isset($_REQUEST["CmbTipoUser"])){
    $idSel=$_REQUEST["CmbTipoUser"]; 
}    
   
$css->CabeceraFin(); 


$css->CrearDiv("principal", "container", "center",1,1);

    
   $css->CrearDiv("DivTipoUsuario", "", "center", 1, 1);
    $css->CrearForm2("FrmSeleccionarTipo", $myPage, "POST", "_SELF");
        $Page="Consultas/Menus.draw.php?idTipo=";
        $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbTipoUser`,`DivMenusConfig`,`2`);return false;";
        print("<strong>Tipo de Usuario:</strong><br>");
        //$css->CrearSelectTable($Nombre, $tabla, $Condicion, $idItemValue, $OptionDisplay1, $OptionDisplay2, $Evento, $FuncionJS, $idSel, $Requerido, $LeyendaInicial)
        $css->CrearSelectTable("CmbTipoUser", "usuarios_tipo", " WHERE Tipo<>'administrador'", "ID", "Tipo", "ID", "onChange", $FuncionJS, $idSel,"");
        $css->CrearDiv("DivBotones", "", "center", 0, 1);
            $css->CrearBotonEvento("BtnEnviar", "Enviar", 1, "OnClick", $FuncionJS, "naranja", "");
        $css->CerrarDiv();    
   $css->CerrarForm();
    $css->CerrarDiv();
   $css->CrearDiv("DivMenusConfig", "", "left", 1, 1);
   $css->CerrarDiv();
   

$css->CerrarDiv();//Cerramos contenedor Principal
//$css->Footer();
$css->AgregaJS(); //Agregamos javascripts
//$css->AgregaSubir();    
////Fin HTML  
print("</body></html>");


?>