<?php
$pdf->AddPage();
$pdf->writeHTML("<H3 align='center'>RELACION DE FECHAS DE ENTREGA Y AJUSTE DE LOS ITEMS FACTURADOS</H3><BR>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="1">
    <tr>
        <td align="center" ><strong>Referencia</strong></td>
        <td align="center" colspan="3"><strong>Producto o Servicio</strong></td>
        <td align="center" ><strong>Fecha de Entrega</strong></td>
        <td align="center" ><strong>Cantidad Entregada</strong></td>
        <td align="center" ><strong>Fecha de Ajuste</strong></td>
        <td align="center" ><strong>Cantidad Ajustada</strong></td>
        <td align="center" ><strong>Multiplicador</strong></td>
        
    </tr>
    
         
EOD;

$sql="SELECT fi.GeneradoDesde, fi.NumeroIdentificador"
        . " FROM facturas_items fi WHERE fi.idFactura='$idFactura'";
$Consulta=$obVenta->Query($sql);
$DatosItemFactura=mysql_fetch_array($Consulta);
$Tabla=$DatosItemFactura["GeneradoDesde"];
$ID=$DatosItemFactura["NumeroIdentificador"];

$sql="SELECT *"
        . " FROM rem_devoluciones rd WHERE rd.NumDevolucion='$ID'";
$Consulta=$obVenta->Query($sql);       
     

while($DatosDevolucion=mysql_fetch_array($Consulta)){
    $sql="SELECT * FROM rem_relaciones WHERE idItemCotizacion=$DatosDevolucion[idItemCotizacion] AND idRemision=$DatosDevolucion[idRemision]";
    $DatosRemision=$obVenta->Query($sql); 
    $DatosRemision=$obVenta->FetchArray($DatosRemision);
    $DatosProducto=$obVenta->DevuelveValores("cot_itemscotizaciones","ID",$DatosDevolucion["idItemCotizacion"]);
    $tbl .= <<<EOD
    
    
    <tr>
        <td align="left" >$DatosProducto[Referencia]</td>
        <td align="left" colspan="3">$DatosProducto[Descripcion]</td>
        <td align="left" >$DatosRemision[FechaEntrega]</td>
        <td align="left" >$DatosRemision[CantidadEntregada]</td>
        <td align="left" >$DatosDevolucion[FechaDevolucion]</td>
        <td align="left" >$DatosDevolucion[Cantidad]</td>
        <td align="left" >$DatosDevolucion[Dias]</td>
        
    </tr>
    
     
    
        
EOD;
    
}

$tbl .= <<<EOD
        </table>
EOD;

$pdf->MultiCell(180, 150, $tbl, 0, 'C', 1, 0, '', '', true,0, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');

?>