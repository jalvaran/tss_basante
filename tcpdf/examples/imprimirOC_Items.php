<?php
/*
 * Archivo con la informacion de los items de una orden de compra
 * 
 */

$pdf->writeHTML("<br>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Referencia</strong></td>
        <td align="center" colspan="3" style="border-bottom: 2px solid #ddd;"><strong>Producto o Servicio</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Precio Unitario</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Cantidad</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Valor Total</strong></td>
    </tr>
    
         
EOD;

$sql="SELECT * FROM ordenesdecompra_items WHERE NumOrden='$IDOC'";
$Consulta=$obVenta->Query($sql);
 $h=1;  
 $SubtotalFinal=0;
 $IVAFinal=0;
 $TotalFinal=0;
while($DatosItemFactura=$obVenta->FetchArray($Consulta)){
    $SubtotalFinal=$SubtotalFinal+$DatosItemFactura["Subtotal"];
    $IVAFinal=$IVAFinal+$DatosItemFactura["IVA"];
    $ValorUnitario=  number_format($DatosItemFactura["ValorUnitario"]);
    $SubTotalItem=  number_format($DatosItemFactura["Subtotal"]);
    $Multiplicador=$DatosItemFactura["Cantidad"];
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    $tbl .= <<<EOD
    
    <tr>
        <td align="left" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemFactura[Referencia]</td>
        <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemFactura[Descripcion]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$ValorUnitario</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">$Multiplicador</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$SubTotalItem</td>
    </tr>
    
     
    
        
EOD;
    
}

$tbl .= <<<EOD
        </table>
EOD;

$pdf->MultiCell(180, 170, $tbl, 1, 'C', 1, 0, '', '', true,1, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');


////Totales de la cotizacion
////
////

$Subtotal=number_format($SubtotalFinal);
$IVA=number_format($IVAFinal);
$Total=number_format($SubtotalFinal+$IVAFinal);
//$TotalLetras=numtoletras($TotalFactura, "PESOS COLOMBIANOS");


$tbl = <<<EOD
        
<table  cellpadding="2" border="0">
    <tr>
        <td height="25" colspan="4" style="border-bottom: 1px solid #ddd;background-color: white;"></td> 
        
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>SUBTOTAL:</h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>$ $Subtotal</h3></td>
    </tr>
    <tr>
        <td colspan="4" height="25" border="1" style="border-bottom: 1px solid #ddd;background-color: white;"><strong>Términos y Condiciones:</strong> <br>$DatosOC[Condiciones]</td> 
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>IVA:</h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>$ $IVA</h3></td>
    </tr>
    <tr>
        <td colspan="2" height="50" align="center" style="border-bottom: 1px solid #ddd;background-color: white;"><br/><br/><br/><br/><br/>Autoriza: ________________</td> 
        <td  height="50" align="center" style="border-bottom: 1px solid #ddd;background-color: white;"><br/><br/><br/><br/><br/>Cargo:  _______</td> 
        <td  height="50" align="center" style="border-bottom: 1px solid #ddd;background-color: white;"><br/><br/><br/><br/><br/>Firma:  _________</td> 
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>TOTAL:</h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>$ $Total</h3></td>
    </tr>
     
</table>

        
EOD;

$pdf->MultiCell(180, 30, $tbl, 1, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');

?>