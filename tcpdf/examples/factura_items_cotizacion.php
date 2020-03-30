<?php
/*
 * Archivo con la informacion de una factura generada desde remisiones
 * 
 */

$pdf->writeHTML("<br>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        <td align="center" ><strong>Referencia</strong></td>
        <td align="center" colspan="3"><strong>Producto o Servicio</strong></td>
        <td align="center" ><strong>Precio Unitario</strong></td>
        <td align="center" ><strong>Cantidad</strong></td>
        <td align="center" ><strong>Valor Total</strong></td>
    </tr>
    
         
EOD;

$sql="SELECT fi.Dias, fi.Referencia, fi.Nombre, fi.ValorUnitarioItem, fi.Cantidad, fi.SubtotalItem"
        . " FROM facturas_items fi WHERE fi.idFactura='$idFactura'";
$Consulta=$obVenta->Query($sql);
$h=1;  

while($DatosItemFactura=mysql_fetch_array($Consulta)){
    $ValorUnitario=  number_format($DatosItemFactura["ValorUnitarioItem"]);
    $SubTotalItem=  number_format($DatosItemFactura["SubtotalItem"]);
    $Multiplicador=$DatosItemFactura["Cantidad"];
    
    if($DatosItemFactura["Dias"]>1){
        $Multiplicador="$DatosItemFactura[Cantidad] X $DatosItemFactura[Dias]";
    }
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
        <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemFactura[Nombre]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$ValorUnitario</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">$Multiplicador</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$SubTotalItem</td>
    </tr>
    
     
    
        
EOD;
    
}

$tbl .= <<<EOD
        </table>
EOD;

$pdf->MultiCell(180, 150, $tbl, 1, 'C', 1, 0, '', '', true,1, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');

?>