<?php

include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID del comprobante a que se imprimirá 
////////////////////////////////////////////
$idComprobante = $_REQUEST["ImgPrintComp"];

$idFormatoCalidad=10;

$Documento="<strong>NOTA DE CONTABILIDAD No. $idComprobante</strong>";
require_once('Encabezado.php');
////////////////////////////////////////////
/////////////Obtengo valores de la NOTA
////////////////////////////////////////////
			
$obVenta=new conexion(1);
$DatosNota=$obVenta->DevuelveValores("notascontables","ID",$idComprobante);
$fecha=$DatosNota["Fecha"];
$Concepto=$DatosNota["Detalle"];
$idProveedor=$DatosNota["idProveedor"];
$Usuarios_idUsuarios=$DatosNota["Usuario_idUsuario"];

$DatosProveedor=$obVenta->DevuelveValores("proveedores","idProveedores",$idProveedor);

$DatosEmpresaPro=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$DatosNota["EmpresaPro"]);
		  
$nombre_file="NotaContable_".$fecha."_".$idComprobante;
		   

$Valor=number_format($DatosNota["Total"]);
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr>
        <td><strong>Ciudad:</strong> $DatosEmpresaPro[Ciudad]</td>
        <td><strong>Fecha:</strong> $DatosNota[Fecha]</td>
        <td><strong>No.:</strong> $idComprobante</td>
    </tr>
    <tr>
        <th colspan="3"><strong>Tercero:</strong> $DatosProveedor[RazonSocial] $DatosProveedor[Num_Identificacion] $DatosProveedor[Ciudad]</th>
    </tr> 
    <tr>
        <th colspan="3"><strong>Por concepto de:</strong> $DatosNota[Detalle]</th>
    </tr> 
    
</table>
        
<br>
<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');

////////////////////////////////////////
///Dibujo movientos contables

$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr align="center">
        <td><strong>Documento</strong></td>
        <td><strong>Cuenta PUC</strong></td>
        <td><strong>Nombre Cuenta</strong></td>
        <td><strong>Débitos</strong></td>
        <td><strong>Créditos</strong></td>
    </tr>
    
</table>
       
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
$h=0;

$Consulta=$obVenta->ConsultarTabla("librodiario", "WHERE Tipo_Documento_Intero='NotaContable' AND Num_Documento_Interno='$idComprobante'");

while($DatosLibro=  $obVenta->FetchArray($Consulta)){
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    
    $Debito=  number_format($DatosLibro["Debito"]);
    $Credito=  number_format($DatosLibro["Credito"]);
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr align="left">
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosLibro[Num_Documento_Externo]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosLibro[CuentaPUC]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosLibro[NombreCuenta]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$Debito</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$Credito</td>
    </tr>
    
</table>
       
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');
}

///////////
///////Espacio para firmas
//
//

$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0" align="left">
    <tr align="left" >
        <td style="height: 70px;" >Preparado:</td>
        <td style="height: 70px;" >Revisado:</td>
        <td style="height: 70px;" >Contabilidad:</td>
    </tr>
   
</table>
       
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>