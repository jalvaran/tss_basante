<?php

include("../../modelo/php_conexion.php");

$IDCoti = $_REQUEST["ImgPrintCoti"];
$idFormatoCalidad=1;

$Documento="<strong>COTIZACION No. $IDCoti</strong>";
require_once('Encabezado.php');

////////////////////////////////////////////
/////////////Obtengo valores de la cotizacion
////////////////////////////////////////////

$obVenta=new conexion(1);

  $DatosCotizacion=$obVenta->DevuelveValores("cotizacionesv5","ID",$IDCoti);
  $fecha=$DatosCotizacion["Fecha"];
  $observaciones=$DatosCotizacion["Observaciones"];
  $Clientes_idClientes=$DatosCotizacion["Clientes_idClientes"];
  $Usuarios_idUsuarios=$DatosCotizacion["Usuarios_idUsuarios"];
		  
////////////////////////////////////////////
/////////////Encabezado 2
////////////////////////////////////////////


$nombre="";    
		  
	////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador
////////////////////////////////////////////


    $registros2=$obVenta->DevuelveValores("usuarios","idUsuarios",$Usuarios_idUsuarios);
    $nombreUsuario=$registros2["Nombre"];
    $ApellidoUsuario=$registros2["Apellido"];

    $registros2=$obVenta->DevuelveValores("empresapro","idEmpresaPro",1);

    $RazonSocialEP=$registros2["RazonSocial"];
    $DireccionEP=$registros2["Direccion"];
    $TelefonoEP=$registros2["Celular"];
    $CiudadEP=$registros2["Ciudad"];
    $NITEP=$registros2["NIT"];
    $DatosBancarios=$registros2["DatosBancarios"];
    $Vendedor=$nombreUsuario." ".$ApellidoUsuario;
    
    require_once('Encabezado2.php');
    require_once('items_cotizacion.php');
    //$pdf->writeHTML("Ver hoja Anexa", true, false, false, false, '');
    $nombre_file=$fecha."_".$nombre;
		
//Close and output PDF document
$nombre_file="Cotizacion_".$IDCoti."_".$fecha."_".$nombre;
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>