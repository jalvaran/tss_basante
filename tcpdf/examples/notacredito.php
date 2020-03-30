<?php
include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID del comprobante a que se imprimirá 
////////////////////////////////////////////
$idComprobante = $_REQUEST["idComprobante"];

$idFormatoCalidad=13;

$Documento="<strong>NOTA CREDITO No. $idComprobante</strong>";
require_once('Encabezado.php');
////////////////////////////////////////////
/////////////Obtengo valores del comprobante
////////////////////////////////////////////
$CentroDeCostoComprobante=1;			
$obVenta=new conexion(1);
$DatosNotaCredito=$obVenta->DevuelveValores("notascredito","ID",$idComprobante);
$DatosFactura=$obVenta->DevuelveValores("facturas","idFacturas",$DatosNotaCredito["idFactura"]);
$DatosCentroCostos=$obVenta->DevuelveValores("centrocosto","ID",$CentroDeCostoComprobante);
$DatosEmpresaPro=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$DatosCentroCostos["EmpresaPro"]);
$fecha=$DatosNotaCredito["Fecha"];
$Concepto=$DatosNotaCredito["Concepto"];
$Usuarios_idUsuarios=$DatosNotaCredito["Usuarios_idUsuarios"];

		  
$nombre_file=$idComprobante."_NotaCredito_".$fecha;
		   
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0" align="left" style="border-radius: 10px;">
    <tr>
        <td><strong>Ciudad:</strong> $DatosEmpresaPro[Ciudad]</td>
        <td><strong>Fecha:</strong> $fecha</td>
        <td><strong>Afecta a Factura.:</strong> $DatosFactura[Prefijo] - $DatosFactura[NumeroFactura]</td>
    </tr>
    <tr>
        <td colspan="3"><strong>Razon Social de la Empresa:</strong> $DatosEmpresaPro[RazonSocial] $DatosEmpresaPro[NIT]</td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Concepto:</strong> $Concepto</td>
        
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
        <td><strong>Codigo PUC</strong></td>
        <td><strong>Cuenta</strong></td>
        <td><strong>Débitos</strong></td>
        <td><strong>Créditos</strong></td>
    </tr>
    
</table>
       
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');


$Consulta=$obVenta->ConsultarTabla("librodiario", "WHERE Tipo_Documento_Intero='NOTA CREDITO' AND Num_Documento_Interno='$idComprobante'");
$TotalDebitos=0;
$TotalCreditos=0;
$h=0;
while($DatosLibro=  $obVenta->FetchArray($Consulta)){
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    
    $TotalDebitos=$TotalDebitos+$DatosLibro["Debito"];
    $TotalCreditos=$TotalCreditos+$DatosLibro["Credito"];
    $Debito=  number_format($DatosLibro["Debito"]);
    $Credito=  number_format($DatosLibro["Credito"]);
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr align="left">
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
$TotalDebitos= number_format($TotalDebitos);
$TotalCreditos= number_format($TotalCreditos);
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0" align="left">
    <tr align="left" >
    <td colspan="2" align="right"><strong>TOTALES: </strong></td>  
    <td align="center"><strong>$TotalDebitos</strong></td> 
    <td align="center"><strong>$TotalCreditos</strong></td>  
    </tr>    
    <tr align="left" >
        <td style="height: 100px;" >Preparado:</td>
        <td style="height: 100px;" >Revisado:</td>
        <td style="height: 100px;" >Aprobado:</td>
        <td style="height: 100px;" >Contabilizado:</td>
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