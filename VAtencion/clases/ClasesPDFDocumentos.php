<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class Documento extends Tabla{
    
    public function PDF_Egreso($idEgreso) {
        $idFormato=11;
        $DatosEgreso=$this->obCon->DevuelveValores("egresos","idEgresos",$idEgreso);
        $fecha=$DatosEgreso["Fecha"];
        $Concepto=$DatosEgreso["Concepto"];
        $Tercero=$DatosEgreso["NIT"];
        $idUsuario=$DatosEgreso["Usuario_idUsuario"];
        
        $DatosUsuario=$this->obCon->ValorActual("usuarios", " Nombre , Apellido ", " idUsuarios='$idUsuario'");
        $Valor=  $DatosEgreso["Valor"]-$DatosEgreso["Retenciones"];
        $DatosTercero=$this->obCon->DevuelveValores("proveedores","Num_Identificacion",$Tercero);
        if($DatosTercero["Num_Identificacion"]==''){
            $DatosTercero=$this->obCon->DevuelveValores("clientes","Num_Identificacion",$Tercero);
        }
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        
        $Documento="$DatosFormatos[Nombre] $idEgreso";
        
        $this->PDF_Ini("Egreso", 8, "");
        $DatosEgreso= $this->obCon->DevuelveValores("egresos", "idEgresos", $idEgreso);
        $this->PDF_Encabezado($fecha,1, $idFormato, "",$Documento);
        $this->Datos_Generales($fecha, $Concepto, $DatosTercero, $DatosUsuario, "");
        
        $html= $this->HTML_Movimiento_Contable("CompEgreso",$idEgreso,"");
        $this->PDF_Write("<br><br><br><br><br><br><br><br><br>".$html);
        $html= $this->HTML_Movimiento_Firmas_Egresos($Valor);
        $this->PDF_Write("<br><br>".$html);
        $this->PDF_Output("Egreso_$idEgreso");
    }
    //HTML Firmas Egresos
    public function HTML_Movimiento_Firmas_Egresos($Valor) {
        $html = ' 
            <table border="1" cellpadding="2" cellspacing="0" align="left">
            <tr align="left" >
                <td style="height: 70px;" ><strong>Total:</strong> '.number_format($Valor).'</td>
                <td style="height: 70px;" >Recibido por:</td>
                <td style="height: 70px;" >Cedula:</td>
            </tr>
            <tr align="left" >
                <td style="height: 70px;" >Preparado:</td>
                <td style="height: 70px;" >Revisado:</td>
                <td style="height: 70px;" >Contabilidad:</td>
            </tr>

        </table>

        ';
        return($html);
    }
    //HTML Movimientos Contables
    public function HTML_Movimiento_Contable($TipoDocumento,$NumDocumento,$Vector) {
        $Consulta=$this->obCon->ConsultarTabla("librodiario", "WHERE Tipo_Documento_Intero='$TipoDocumento' AND Num_Documento_Interno='$NumDocumento'");
        $html = '   
            <table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
                <tr align="center">
                    <td><strong>Tercero</strong></td>
                    <td><strong>Documento</strong></td>
                    <td><strong>Cuenta PUC</strong></td>
                    <td><strong>Nombre Cuenta</strong></td>
                    <td><strong>Concepto</strong></td>
                    <td><strong>Débitos</strong></td>
                    <td><strong>Créditos</strong></td>
                </tr>

            
        ';
        $h=0;
        $Debitos=0;
        $Creditos=0;
        while($DatosLibro=$this->obCon->FetchArray($Consulta)){
            $Debitos=$Debitos+$DatosLibro["Debito"];
            $Creditos=$Creditos+$DatosLibro["Credito"];
            if($h==0){
                $Back="#f2f2f2";
                $h=1;
            }else{
                $Back="white";
                $h=0;
            } 
            $html.= '  
            
                <tr align="left">
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Tercero_Identificacion"].'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Num_Documento_Externo"].'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["CuentaPUC"].'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["NombreCuenta"].'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Concepto"].'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Debito"]).'</td>
                    <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Credito"]).'</td>
                </tr>

            
            ';

        }
        $Back='#F7F8E0';
        $html.='<tr > '
                . '<td align="rigth" colspan="5" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">Totales:</td>'
                . '<td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Debitos).'</td>
                   <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Creditos).'</td>'
                . '</tr>';
        $html.='</table>';
        return($html);
    }
    //HTML Datos Tercero Egresos
    public function Datos_Generales($fecha,$Concepto,$DatosTercero,$DatosUsuario,$Vector) {
        $html ='       
            <table cellpadding="1" border="1">
                <tr>
                    <td><strong>Tercero:</strong></td>
                    <td colspan="3">'.$DatosTercero["RazonSocial"].'</td>

                </tr>
                <tr>
                    <td><strong>NIT:</strong></td>
                    <td colspan="3">'.$DatosTercero["Num_Identificacion"].' - '.$DatosTercero["DV"].'</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Dirección:</strong></td>
                    <td><strong>Ciudad:</strong></td>
                    <td><strong>Telefono:</strong></td>
                </tr>
                <tr>
                    <td colspan="2">'.$DatosTercero["Direccion"].'</td>
                    <td>'.$DatosTercero["Ciudad"].'</td>
                    <td>'.$DatosTercero["Telefono"].'</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Fecha: </strong></td>
                    <td colspan="2">'.$fecha.'</td>
                </tr>

            </table>       
        ';
        $this->PDF->MultiCell(93, 25, $html, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
        $html = '        
            <table cellpadding="1" border="1">
                <tr>
                    <td colspan="3"><strong>Concepto:</strong></td>


                </tr>
                <tr>
                    <td colspan="3" height="36">'.$Concepto.' </td>

                </tr>
                <tr>
                    <td colspan="3"><strong>Creado Por:</strong> '.$DatosUsuario["Nombre"].' '.$DatosUsuario["Apellido"].' </td>

                </tr>


            </table>       
        ';

    $this->PDF->MultiCell(92, 25, $html, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
    }
    
    //Comprobante de ingreso
    
     public function PDF_CompIngreso($idIngreso) {
        $idFormato=4;
        $DatosIngreso=$this->obCon->DevuelveValores("comprobantes_ingreso","ID",$idIngreso);
        $fecha=$DatosIngreso["Fecha"];
        $Concepto=$DatosIngreso["Concepto"];
        $idCliente=$DatosIngreso["Clientes_idClientes"];
        $Tercero=$DatosIngreso["Tercero"];
        $idUsuario=$DatosIngreso["Usuarios_idUsuarios"];
        
        $DatosUsuario=$this->obCon->ValorActual("usuarios", " Nombre , Apellido ", " idUsuarios='$idUsuario'");
        $Valor=  $DatosIngreso["Valor"];
        $DatosTercero[]="";
        if($Tercero>0){
            $DatosTercero=$this->obCon->DevuelveValores("clientes","Num_Identificacion",$Tercero);
            if($DatosTercero["Num_Identificacion"]==''){
                $DatosTercero=$this->obCon->DevuelveValores("proveedores","Num_Identificacion",$Tercero);
            }
        }
        if($idCliente>0){
            $DatosTercero=$this->obCon->DevuelveValores("clientes","idClientes",$idCliente);
        }
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        
        $Documento="$DatosFormatos[Nombre] $idIngreso";
        
        $this->PDF_Ini("ComprobanteIngreso", 8, "");
        
        $this->PDF_Encabezado($fecha,1, $idFormato, "",$Documento);
        
        $this->Datos_Generales($fecha, $Concepto, $DatosTercero, $DatosUsuario, "");
        
        $html= $this->HTML_Movimiento_Contable("ComprobanteIngreso",$idIngreso,"");
        
        $this->PDF_Write("<br><br><br><br><br><br><br><br><br>".$html);
        $html=$this->HTML_Firmas_Documentos();
        $this->PDF_Write("<br>".$html);
        /*
        $html= $this->HTML_Movimiento_Firmas_Egresos($Valor);
        $this->PDF_Write("<br><br>".$html);
         * 
         */
        $this->PDF_Output("ComprobanteIngreso_$idIngreso");
    }
    
    //html firmas
    
    public function HTML_Firmas_Documentos() {
        $html='<pre>
        ________________________            __________________________           ________________________
        Recibe:                             Entrega:                             Revisa:
                </pre>';
        return($html);
    }
    
    //Comprobante de ingreso
    
     public function PDF_CompBajasAltas($idComprobante) {
        $idFormato=25;
        $fecha=date("Y-m-d");
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        
        $Documento="$DatosFormatos[Nombre] No. $idComprobante";
        
        $this->PDF_Ini("ComprobanteBajasAltas", 8, "");
        $DatosComprobante= $this->obCon->DevuelveValores("prod_bajas_altas", "ID", $idComprobante);
        $DatosUsuarios= $this->obCon->DevuelveValores("usuarios", "idUsuarios", $DatosComprobante["Usuarios_idUsuarios"]);
               
        $this->PDF_Encabezado($fecha,1, $idFormato, "",$Documento);
        $html="<br><br><br><br><pre>";
        $html.="<strong>El día $DatosComprobante[Fecha], se realiza por parte del Colaborador(a) $DatosUsuarios[Nombre] $DatosUsuarios[Apellido], Identificado con Documento $DatosUsuarios[Identificacion],"
             . "Un movimiento de $DatosComprobante[Movimiento] en inventarios de $DatosComprobante[Cantidad] Unidades del producto $DatosComprobante[Nombre] con Referencia:  $DatosComprobante[Referencia],"
             . "Para Constancia se firma por las partes:</strong> </pre>"; 
        $this->PDF_Write("<br>".$html);
        $html=$this->HTML_Firmas_Documentos();
        $this->PDF_Write("<br><br><br><br>".$html);
        
        $this->PDF_Output("ComprobanteAltasBajas_$idComprobante");
    }
    
    
    
    //HTML Movimientos Contables condicionado
    public function HTML_Movimiento_Contable_Condicionado($Condicion,$Vector) {
        $Consulta=$this->obCon->ConsultarTabla("librodiario", $Condicion);
        $html = '   
            <table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
                <tr align="center">
                    <td><strong>Tercero</strong></td>
                    <td><strong>Documento Interno</strong></td>
                    <td><strong>Documento Referencia</strong></td>
                    <td><strong>Cuenta PUC</strong></td>
                    <td><strong>Nombre Cuenta</strong></td>
                    <td><strong>Concepto</strong></td>
                    <td><strong>Débitos</strong></td>
                    <td><strong>Créditos</strong></td>
                </tr>

            
        ';
        $h=0;
        $Debitos=0;
        $Creditos=0;
        while($DatosLibro=$this->obCon->FetchArray($Consulta)){
            $Debitos=$Debitos+$DatosLibro["Debito"];
            $Creditos=$Creditos+$DatosLibro["Credito"];
            if(!($DatosLibro["Debito"]==0 and $DatosLibro["Credito"]==0)){
                $NumeroDocInt=$DatosLibro["Num_Documento_Interno"];
                if($DatosLibro["Tipo_Documento_Intero"]=='FACTURA'){
                    $DatosNumeroDocInt=$this->obCon->DevuelveValores("facturas","idFacturas",$DatosLibro["Num_Documento_Interno"]);
                    $NumeroDocInt=$DatosNumeroDocInt["NumeroFactura"];

                }
                $DatosDocumentoInterno=$this->obCon->DevuelveValores("documentos_generados","Libro",$DatosLibro["Tipo_Documento_Intero"]);
                $DocInt=$DatosDocumentoInterno["Abreviatura"];
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                } 
                $html.= '  

                    <tr align="left">
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Tercero_Identificacion"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DocInt.' '.$NumeroDocInt.'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Num_Documento_Externo"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["CuentaPUC"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["NombreCuenta"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Concepto"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Debito"]).'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Credito"]).'</td>
                    </tr>
                ';

            }
        }
        $Back='#F7F8E0';
        $html.='<tr > '
                . '<td align="rigth" colspan="6" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">Totales:</td>'
                . '<td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Debitos).'</td>
                   <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Creditos).'</td>'
                . '</tr>';
        $html.='</table>';
        return($html);
    }
    
    //HTML Movimientos Contables condicionado simple
    public function HTML_Movimientos_Resumen($sql,$Vector) {
       $Consulta= $this->obCon->Query($sql);
        //$Consulta=$this->obCon->ConsultarTabla("librodiario", $Condicion);
        $html = '   
            <table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
                <tr align="center">
                    <td><strong>Tercero</strong></td>
                    
                    <td><strong>Cuenta PUC</strong></td>
                    <td><strong>Nombre Cuenta</strong></td>
                   
                    <td><strong>Débitos</strong></td>
                    <td><strong>Créditos</strong></td>
                </tr>

            
        ';
        $h=0;
        $Debitos=0;
        $Creditos=0;
        while($DatosLibro=$this->obCon->FetchArray($Consulta)){
            $Debitos=$Debitos+$DatosLibro["Debito"];
            $Creditos=$Creditos+$DatosLibro["Credito"];
            if(!($DatosLibro["Debito"]==0 and $DatosLibro["Credito"]==0)){
                //$NumeroDocInt=$DatosLibro["Num_Documento_Interno"];
                //if($DatosLibro["Tipo_Documento_Intero"]=='FACTURA'){
                  //  $DatosNumeroDocInt=$this->obCon->DevuelveValores("facturas","idFacturas",$DatosLibro["Num_Documento_Interno"]);
                    //$NumeroDocInt=$DatosNumeroDocInt["NumeroFactura"];

                //}
                //$DatosDocumentoInterno=$this->obCon->DevuelveValores("documentos_generados","Libro",$DatosLibro["Tipo_Documento_Intero"]);
                //$DocInt=$DatosDocumentoInterno["Abreviatura"];
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                } 
                $html.= '  

                    <tr align="left">
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["Tercero_Identificacion"].'<br>'.$DatosLibro["Tercero_Razon_Social"].'</td>
                        
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["CuentaPUC"].'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosLibro["NombreCuenta"].'</td>
                        
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Debito"]).'</td>
                        <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosLibro["Credito"]).'</td>
                    </tr>
                ';

            }
        }
        $Back='#F7F8E0';
        $html.='<tr > '
                . '<td align="rigth" colspan="3" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">Totales:</td>'
                . '<td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Debitos).'</td>
                   <td style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($Creditos).'</td>'
                . '</tr>';
        $html.='</table>';
        return($html);
    }
    //Comprobante de movimientos Contables
    
     public function PDF_ComprobanteMovimientos($FechaInicial,$FechaFinal,$CuentaPUC,$Tercero,$Vector) {
        $idFormato=29;
        
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        $DatosTercero=$this->obCon->DevuelveValores("proveedores", "Num_Identificacion", $Tercero);
        if($DatosTercero["RazonSocial"]==''){
            $DatosTercero=$this->obCon->DevuelveValores("clientes", "Num_Identificacion", $Tercero);
        }
        $Documento="$DatosFormatos[Nombre] del $FechaInicial al $FechaFinal";
        
        $this->PDF_Ini("ComprobanteMovimientosContables", 8, "");
           
        $this->PDF_Encabezado($DatosFormatos["Fecha"],1, $idFormato, "",$Documento);
        $html="<br><br><br><br>";
        if($Tercero<>'All'){
            $html.="Tercero: $DatosTercero[RazonSocial] $Tercero <br>"; 
            $html.="Direccion: $DatosTercero[Direccion]"; 
        }    
            $this->PDF_Write("<br>".$html);
        
        //$Condicion="WHERE Fecha>='$FechaInicial' AND Fecha<='$FechaFinal' AND Tercero_Identificacion='$Tercero' AND CuentaPUC like '$CuentaPUC%'";
        //$html=$this->HTML_Movimiento_Contable_Condicionado($Condicion,"");
        $CondicionTercero="AND Tercero_Identificacion='$Tercero'" ;
        if($Tercero=='All'){
            $CondicionTercero="" ;
        }
        $sql="SELECT Tercero_Identificacion,Tercero_Razon_Social,SUM(Debito) as Debito,SUM(Credito) as Credito,CuentaPUC, NombreCuenta FROM librodiario  "
               . "WHERE Fecha>='$FechaInicial' AND Fecha<='$FechaFinal' "
                . "AND CuentaPUC like '$CuentaPUC%' $CondicionTercero GROUP BY Tercero_Identificacion,CuentaPUC";
        //$this->PDF_Write("<br>$sql<br><br><br>");
        $html=$this->HTML_Movimientos_Resumen($sql,"");
        
        $this->PDF_Write("<br>".$html);
        $html=$this->HTML_Firmas_Documentos();
        $this->PDF_Write("<br><br><br><br>".$html);
        
        $this->PDF_Output("ComprobanteMovimientosContables_$Tercero");
    }
    //Cuenta de Cobro que envia un tercero
    //
    public function CuentaCobroTercero($idCuenta,$Vector) {
        $idFormato=30;
        $DatosEmpresa=$this->obCon->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        $DatosCuentaCobro=$this->obCon->DevuelveValores("terceros_cuentas_cobro", "ID", $idCuenta);
        $DatosConcepto=$this->obCon->DevuelveValores("conceptos", "ID", $DatosCuentaCobro["idConceptoContable"]);
        $DatosTercero=$this->obCon->DevuelveValores("proveedores", "Num_Identificacion", $DatosCuentaCobro["Tercero"]);
        if($DatosTercero["RazonSocial"]==''){
            $DatosTercero=$this->obCon->DevuelveValores("clientes", "Num_Identificacion", $Tercero);
        }
        $this->PDF_Ini("Cuenta de Cobro", 8, "");
        $html="<br><br><br><br><br><br><br><br>";
        $html.='<div style="text-align:center;"><strong>REGIMEN SIMPLIFICADO<BR>DEL IMPUESTO A LAS VENTAS<BR>'
                . "NO OBLIGADO A DECLARAR.<BR>CUENTA DE COBRO $idCuenta</strong></div>";
        $html.="<br><br><br><br><br><br><br><br>$DatosEmpresa[Ciudad], $DatosCuentaCobro[Fecha].<br><br><br><br><br>";
        //$this->PDF->Write(0, $html, '', 0, 'C', true, 0, false, false, 0);
        
        $html.='<div style="text-align:center;">'.$DatosEmpresa["RazonSocial"]."<br>$DatosEmpresa[NIT]<br>"
                . "DEBE A<BR>$DatosTercero[RazonSocial]<br>$DatosTercero[Num_Identificacion]</div><br><br><br>";
        $html.="<br><br><br><br><strong>DESCRIPCION DEL BIEN O SERVICIO PRESTADO:</strong><br>";
        $html.="<br><br>$DatosConcepto[Nombre] por $". number_format($DatosCuentaCobro["Valor"]).", $DatosCuentaCobro[Observaciones].";
        $html.="<br><br><br><br><br><br><br><br><br><br><br><br>"
                . "Declaro bajo la gravedad de juramento que se efectuó aporte a la seguridad social, "
                . "de acuerdo con lo establecido en ley 1393 de julio de 2010,<br><br>articulo 27."
                . " ASI MISMO DECLARO QUE NO ESTOY OBLIGADO A EXPEDIR FACTURA.";
        $html.="<br><br><br><br><br><br><br><br><br><br><br><br>Atentamente,<br><br><br><br><br><br><br><br><br><br><br><br>"
                . "$DatosTercero[RazonSocial]<br><br>$DatosTercero[Num_Identificacion]";
        
        $this->PDF_Write("<br>".$html);
        
        $this->PDF_Output($idCuenta."_CuentaCobro");
    }
    
   
    /**
     * Funcion para generar el PDF de una nota de devolucion
     * @param type $idNota -> id de la nota de devolucion
     * @param type $Vector -> Futuro
     */
    public function PDF_NotaDevolucion($idNota,$Vector) {
        $DatosNota=$this->obCon->DevuelveValores("factura_compra_notas_devolucion", "ID", $idNota);
        $CodigoNota="$DatosNota[ID]";
        $Documento="NOTA DE DEVOLUCION No. $CodigoNota";
        
        $this->PDF_Ini("ND_$CodigoNota", 8, "");
        $idFormato=31;
        $this->PDF_Encabezado($DatosNota["Fecha"],1, $idFormato, "",$Documento);
        $this->PDF_Encabezado_Nota_Devolucion($idNota,$DatosNota,"");
        
        
        $Position=$this->PDF->SetY(80);
        
        $html= $this->HTML_productos_devueltos_ND($idNota,"");
        $this->PDF_Write($html);
        $sql="SELECT Tercero_Identificacion,NombreCuenta,Tercero_Razon_Social ,CuentaPUC,Debito,Credito FROM librodiario "
                . "WHERE Tipo_Documento_Intero='NOTA_DEVOLUCION' AND Num_Documento_Interno='$idNota'";
        $html=$this->HTML_Movimientos_Resumen($sql, $Vector);
        $this->PDF_Write("<BR><BR><BR><strong>MOVIMIENTOS CONTABLES:</strong><BR>".$html);
                
        $this->PDF_Output("ND_$CodigoNota");
    }
    
    /**
     * Funcion para hacer el encabezado de una nota de devolucion
     * @param type $idNota ->id de la nota de devolucion
     * @param type $DatosNota -> Vector que contiene los datos de la nota
     * @param type $Vector ->Uso Futuro
     */
    public function PDF_Encabezado_Nota_Devolucion($idNota,$DatosNota,$Vector) {
        
        $DatosTercero=$this->obCon->DevuelveValores("proveedores", "Num_Identificacion", $DatosNota["Tercero"]);
        $DatosCentroCostos=$this->obCon->DevuelveValores("centrocosto","ID",$DatosNota["idCentroCostos"]);
        $DatosEmpresaPro=$this->obCon->DevuelveValores("empresapro", "idEmpresaPro", $DatosCentroCostos["EmpresaPro"]);
      
        $DatosUsuario=$this->obCon->DevuelveValores("usuarios", "idUsuarios", $DatosNota["idUser"]);
        $Comprador=$DatosUsuario["Nombre"]." ".$DatosUsuario["Apellido"];
        $tbl = <<<EOD
<table cellspacing="0" cellpadding="2" border="1">
    <tr>
        <td><strong>Tercero:</strong></td>
        <td colspan="3">$DatosTercero[RazonSocial]</td>
        
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td>
        <td colspan="3">$DatosTercero[Num_Identificacion] - $DatosTercero[DV]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dirección:</strong></td>
        <td><strong>Ciudad:</strong></td>
        <td><strong>Teléfono:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosTercero[Direccion]</td>
        <td>$DatosTercero[Ciudad]</td>
        <td>$DatosTercero[Telefono]</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Fecha:</strong> $DatosNota[Fecha]</td>
        
    </tr>
    
</table>
        
EOD;


$this->PDF->MultiCell(93, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');


////Concepto
////
////

$tbl = <<<EOD
<table cellspacing="0" cellpadding="2" border="1">
    <tr>
        <td height="42" align="center" >$DatosNota[Concepto]</td> 
    </tr>
     
</table>
<table cellspacing="0" cellpadding="2" border="1">
    <tr>
        <td align="center" ><strong>Comprador: </strong></td>
        
    </tr>
    <tr>
        <td align="center" >$Comprador</td>
        
    </tr>
     
</table>
<br>  <br><br><br>      
EOD;

$this->PDF->MultiCell(93, 25, $tbl, 0, 'R', 1, 0, '', '', true,0, true, true, 10, 'M');

    
    }
    
    /**
     * Funcion para dibujar los productos devueltos en una nota de dovolucion
     * @param type $idNota ->id de la nota
     * @param type $Vector ->Futuro
     * @return type -> retorna el html para dibujar los productos devueltos en la nota
     */
    public function HTML_productos_devueltos_ND($idNota,$Vector) {
        $tbl = "";
        

$sql="SELECT fi.idProducto,fi.Cantidad, fi.CostoUnitarioCompra, fi.SubtotalCompra, fi.ImpuestoCompra, fi.TotalCompra, fi.Tipo_Impuesto, pv.Referencia,pv.Nombre"
        . " FROM factura_compra_items_devoluciones fi INNER JOIN productosventa pv ON fi.idProducto=pv.idProductosVenta WHERE fi.idNotaDevolucion='$idNota'";
$Consulta= $this->obCon->Query($sql);
$h=1;  
if($this->obCon->NumRows($Consulta)){
    $tbl = <<<EOD
            <br>
                <h3 align="center">PRODUCTOS DEVUELTOS</h3>
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        <td align="center" ><strong>ID</strong></td>
        <td align="center" ><strong>Referencia</strong></td>
        <td align="center" colspan="3"><strong>Producto</strong></td>
        <td align="center" ><strong>Costo Unitario</strong></td>
        <td align="center" ><strong>Cantidad</strong></td>
        <td align="center" ><strong>Subtotal</strong></td>
        <td align="center" ><strong>Impuestos</strong></td>
        <td align="center" ><strong>Total</strong></td>
        <td align="center" ><strong>TipoIVA</strong></td>
    </tr>
    
         
EOD;
$GranSubtotal=0;
$GranIVA=0;
$GranTotal=0;
while($DatosItemFactura=$this->obCon->FetchArray($Consulta)){
    $GranSubtotal=$GranSubtotal+$DatosItemFactura["SubtotalCompra"];
    $GranIVA=$GranIVA+$DatosItemFactura["ImpuestoCompra"];
    $GranTotal=$GranTotal+$DatosItemFactura["TotalCompra"];
    
    $ValorUnitario=  number_format($DatosItemFactura["CostoUnitarioCompra"]);
    $SubTotalItem=  number_format($DatosItemFactura["SubtotalCompra"]);
    $Cantidad=$DatosItemFactura["Cantidad"];
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    
    $tbl .= '    
    
    <tr>
        <td align="left" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosItemFactura["idProducto"].'</td>    
        <td align="left" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosItemFactura["Referencia"].'</td>
        <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosItemFactura["Nombre"].'</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$ValorUnitario.'</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$Cantidad.'</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$SubTotalItem.'</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosItemFactura["ImpuestoCompra"]).'</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.number_format($DatosItemFactura["TotalCompra"]).'</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';">'.$DatosItemFactura["Tipo_Impuesto"].'</td>
    </tr>
        
 ';
    
}
$tbl.= '<tr>'
        . '<td align="right" colspan="7" style="border-bottom: 1px solid #ddd;background-color: white;"><strong>TOTALES</strong></td>'
        . '<td align="right" style="border-bottom: 1px solid #ddd;background-color: white;"><strong>'.number_format($GranSubtotal).'</strong></td>'
        . '<td align="right" style="border-bottom: 1px solid #ddd;background-color: white;"><strong>'.number_format($GranIVA).'</strong></td>'
        . '<td align="right" style="border-bottom: 1px solid #ddd;background-color: white;"><strong>'.number_format($GranTotal).'</strong></td>'
        . '<td align="center" style="border-bottom: 1px solid #ddd;background-color: white;"> </td>'
        . '</tr>';
$tbl.= "</table>";
        
}
        return($tbl);

    }
    
    
   //Fin Clases
}
    