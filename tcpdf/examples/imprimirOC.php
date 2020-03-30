<?php

include("../../modelo/php_conexion.php");

$IDOC = $_REQUEST["idOT"];
$idFormatoCalidad=5;

$Documento="<strong>ORDEN DE COMPRA No. $IDOC</strong>";
require_once('Encabezado.php');

////////////////////////////////////////////
/////////////Obtengo valores de la cotizacion
////////////////////////////////////////////

$obVenta=new conexion(1);

  $DatosOC=$obVenta->DevuelveValores("ordenesdecompra","ID",$IDOC);
  $fecha=$DatosOC["Fecha"];
  $observaciones=$DatosOC["Descripcion"];
  $Tercero=$DatosOC["Tercero"];
  $Usuarios_idUsuarios=$DatosOC["UsuarioCreador"];
		  
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
    
    require_once('imprimirOC_DatosTercero.php');
    require_once('imprimirOC_Items.php');
    $nombre_file=$fecha."_".$nombre;
		
//Close and output PDF document
$nombre_file="OrdenCompra_".$IDOC."_".$fecha."_".$nombre;
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>