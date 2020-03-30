<?php
//require_once('tcpdf_include.php');
//require_once('../../librerias/numerosletras.php');
include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////
$idOT = $_REQUEST["idOT"];
////////////////////////////////////////////
/////////////Obtengo valores de la Remision
////////////////////////////////////////////
	
$idFormatoCalidad=6;

$Documento="<strong>ORDEN DE SERVICIO No. $idOT</strong>";
require_once('Encabezado.php');

$obVenta=new conexion(1);
$DatosOT=$obVenta->DevuelveValores("ordenesdetrabajo","ID",$idOT);
$Fecha=$DatosOT["FechaOT"];
$Hora=substr($DatosOT["Hora"],10,-3);
$Fecha=$Fecha." ".$Hora;
$Actividad=$DatosOT["Descripcion"];
$Clientes_idClientes=$DatosOT["idCliente"];
$Usuarios_idUsuarios=$DatosOT["idUsuarioCreador"];

$DatosTipoOrden=$obVenta->DevuelveValores("ordenesdetrabajo_tipo","ID",$DatosOT["TipoOrden"]);

////////////////////////////////////////////
/////////////Obtengo datos del cliente y centro de costos
////////////////////////////////////////////

$DatosCliente=$obVenta->DevuelveValores("clientes","idClientes",$Clientes_idClientes);
////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador y de la empresa propietaria
////////////////////////////////////////////
$DatosUsuario=$obVenta->DevuelveValores("usuarios","idUsuarios",$Usuarios_idUsuarios);
$nombreUsuario=$DatosUsuario["Nombre"]." ".$DatosUsuario["Apellido"] ;
$DatosEmpresaPro=$obVenta->DevuelveValores("empresapro","idEmpresaPro",1);
$RazonSocialEP=$DatosEmpresaPro["RazonSocial"];
$DireccionEP=$DatosEmpresaPro["Direccion"];
$TelefonoEP=$DatosEmpresaPro["Celular"];
$CiudadEP=$DatosEmpresaPro["Ciudad"];
$NITEP=$DatosEmpresaPro["NIT"];
		  
$nombre_file="OT_".$Fecha."_".$DatosCliente["RazonSocial"];

require_once('imprimirOT_DatosInstalacion.php');
require_once('imprimirOT_Actividades.php');

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>