<?php

include("../../modelo/php_conexion.php");
////////////////////////////////////////////
/////////////Obtengo el ID de la Factura a que se imprimirá 
////////////////////////////////////////////
$obVenta=new conexion(1);
$idEgresos = $_REQUEST["ImgPrintComp"];

$idFormatoCalidad=11;

$Documento="<strong>COMPROBANTE DE EGRESO No. $idEgresos</strong>";
require_once('Encabezado.php');

////////////////////////////////////////////
/////////////Obtengo datos del egresos
////////////////////////////////////////////


$DatosEgreso=$obVenta->DevuelveValores("egresos","idEgresos",$idEgresos);

$nombre_file=$DatosEgreso["idEgresos"]."_Egreso_".$DatosEgreso["Beneficiario"];
$fecha=$DatosEgreso["Fecha"];
$Concepto=$DatosEgreso["Concepto"];
$Tercero=$DatosEgreso["NIT"];
$Usuarios_idUsuarios=$DatosEgreso["Usuario_idUsuario"];
$Valor=  number_format($DatosEgreso["Valor"]-$DatosEgreso["Retenciones"]);
require_once('Egreso_DatosTercero.php');


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

$Consulta=$obVenta->ConsultarTabla("librodiario", "WHERE Tipo_Documento_Intero='CompEgreso' AND Num_Documento_Interno='$idEgresos'");

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
        <br><br>
<table border="1" cellpadding="2" cellspacing="0" align="left">
    <tr align="left" >
        <td style="height: 70px;" ><strong>Total:</strong> $Valor</td>
        <td style="height: 70px;" >Recibido por:</td>
        <td style="height: 70px;" >Cedula:</td>
    </tr>
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


///////////////////////////////////////////
/////////////////////IMPRESORA POS
$DatosImpresora=$obVenta->DevuelveValores("config_puertos", "ID", 1);
$VectorEgresos["Fut"]=1;

if($DatosImpresora["Habilitado"]=="SI"){
    print("<script>alert('Entra')</script>");
    $obVenta->ImprimeEgresoPOS($idEgresos,$VectorEgresos,$DatosImpresora["Puerto"],1);

}

?>