<?php 
//este archivo actualiza el campo de una tabla con texto enriquecido
$myPage="AgregarTextoEnriquecido.php";
include_once("../sesiones/php_control.php");
include_once("css_construct.php");
$obVenta=new Conexion($idUser);
//////Si recibo una Cotizacion
$Tabla="";
$idItem="";
if(!empty($_REQUEST['Tabla'])){  //datos donde se actualizá un campo con texto enriquecido
   $Tabla=$obVenta->normalizar($_REQUEST['Tabla']);     //tabla donde se agregará el texto enriquecido
   $idTabla=$obVenta->normalizar($_REQUEST['idTabla']); //id de la tabla donde se agregará el texto enriquecido
   $idItem=$obVenta->normalizar($_REQUEST['idItem']);   //el item  donde se agregará el texto enriquecido
   $Campo=$obVenta->normalizar($_REQUEST['Campo']);     //campo donde se agregará el texto enriquecido
}

//Si recibo el texto enriquecido
if(!empty($_REQUEST['BtnAgregarTexto'])){ 
    
    $Texto=$obVenta->normalizar($_REQUEST['TxtTexto']);
    $obVenta->ActualizaRegistro($Tabla, $Campo, $Texto, $idTabla, $idItem);   //Se actuliza el texto enriquecido
}
	
print("<html>");
print("<head>");
$css =  new CssIni("Agregar Texto enriquecido a una tabla");

print("</head>");
print("<body>");
       
    $css->CabeceraIni("Texto enriquecido"); //Inicia la cabecera de la pagina
       
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
   
    $css->CrearDiv("Secundario", "container", "center",1,1);
    if($idItem>0){
        $css->CrearNotificacionVerde("Agregar texto enriquecido a un campo", 16);
        $css->CrearForm2("FrmAgregarAnexo", $myPage, "post", "_self");
        $css->CrearInputText("Tabla", "hidden", "", $Tabla, "", "", "", "", "", "", 0, 1);
        $css->CrearInputText("idTabla", "hidden", "", $idTabla, "", "", "", "", "", "", 0, 1);
        $css->CrearInputText("idItem", "hidden", "", $idItem, "", "", "", "", "", "", 0, 1);
        $css->CrearInputText("Campo", "hidden", "", $Campo, "", "", "", "", "", "", 0, 1);
        $css->CrearTextAreaEnriquecida("TxtTexto", "", "", "", "", "", "", "", "", 0, 1, "");
        $css->CrearBotonConfirmado("BtnAgregarTexto", "Agregar");
        $css->CerrarForm();
        $css->CrearDiv("DivAnexos", "", "left", 1, 1);
        $Consulta=$obVenta->ConsultarTabla($Tabla, "WHERE $idTabla='$idItem'");
        while($DatosTexto=$obVenta->FetchArray($Consulta)){
            $css->CrearNotificacionNaranja("Texto Actual:", 16);
            print($DatosTexto[$Campo]);
        }
        $css->CerrarDiv();
    }else{
        $css->CrearNotificacionRoja("No se selecciono la tabla", 16);
    }							
   	
    
    $css->CerrarDiv();//Cerramos contenedor Secundario
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->Footer();
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->IniTextoEnriquecido();
    
    print("</body></html>");
?>