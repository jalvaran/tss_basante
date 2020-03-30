<?php

include("../../modelo/php_conexion.php");

$idTraslado = $_REQUEST["idTraslado"];
$idFormatoCalidad=17;

$Documento="<strong>TRASLADO No. $idTraslado</strong>";
require_once('Encabezado.php');

////////////////////////////////////////////
/////////////Obtengo valores de la cotizacion
////////////////////////////////////////////

$obVenta=new conexion(1);

  $DatosTraslado=$obVenta->DevuelveValores("traslados_mercancia","ID",$idTraslado);
  $fecha=$DatosTraslado["Fecha"];
  $observaciones=$DatosTraslado["Descripcion"];
  $Origen=$DatosTraslado["Origen"];
  $Destino=$DatosTraslado["Destino"];
  $Usuarios_idUsuarios=$DatosTraslado["Abre"];
		  
////////////////////////////////////////////
/////////////Encabezado 2
////////////////////////////////////////////


$nombre="";    
		  
	////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador
////////////////////////////////////////////


    $registros2=$obVenta->DevuelveValores("usuarios","Identificacion",$Usuarios_idUsuarios);
    $nombreUsuario=$registros2["Nombre"];
    $ApellidoUsuario=$registros2["Apellido"];
    $DatosSucursalOrigen=$obVenta->DevuelveValores("empresa_pro_sucursales","ID",$Origen);
    $DatosSucursalDestino=$obVenta->DevuelveValores("empresa_pro_sucursales","ID",$Destino);
    $registros2=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$DatosSucursalOrigen["idEmpresaPro"]);

    $RazonSocialEP=$registros2["RazonSocial"];
    $DireccionEP=$registros2["Direccion"];
    $TelefonoEP=$registros2["Celular"];
    $CiudadEP=$registros2["Ciudad"];
    $NITEP=$registros2["NIT"];
    
    $Usuario=$nombreUsuario." ".$ApellidoUsuario;
    
    require_once('Encabezado2_Traslado.php');
    require_once('items_traslado.php');
    //$pdf->writeHTML("Ver hoja Anexa", true, false, false, false, '');
    $nombre_file=$fecha."_".$nombre;
		
//Close and output PDF document
$nombre_file="Traslado_".$idTraslado."_".$fecha."_".$nombre;
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>