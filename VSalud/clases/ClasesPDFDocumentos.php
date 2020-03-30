<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class Documento extends Tabla{
    
    //Comprobante de ingreso
    
     public function PDF_CobroPrejuridico($idCobro) {
        $DatosCobro= $this->obCon->DevuelveValores("salud_cobros_prejuridicos", "ID", $idCobro);
        if($DatosCobro["TipoCobro"]==1){ //Indica si es prejuridico 1 o 2
            $idFormato=27;  //Formato de calidad para el cobro prejuridico 1
            $Asunto="CLIENTE CON ALTURA DE MORA DE 1 A 29 DÍAS DE ATRASO";
        
        }else{
            $idFormato=28;   //Formato de calidad para el cobro prejuridico 2
            $Asunto="CLIENTE CON ALTURA DE MORA DE MÁS DE 29 DÍAS DE ATRASO";
        }
        
        $fecha=date("Y-m-d");
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        
        $Documento="$DatosFormatos[Nombre] No. $idCobro";
        
        $this->PDF_Ini("CobroPrejuridico", 8, ""); 
        
        $DatosUsuarios= $this->obCon->DevuelveValores("usuarios", "idUsuarios", $DatosCobro["idUser"]);
        $sql="SELECT cod_enti_administradora,SUM(`valor_neto_pagar`) AS TotalFacturas FROM salud_cobros_prejuridicos_relaciones p"
                . " INNER JOIN salud_archivo_facturacion_mov_generados f ON f.num_factura=p.num_factura "
                . "WHERE p.idCobroPrejuridico='$idCobro' LIMIT 1"; //Busco una factura que corresponda al cobro
        $consulta=$this->obCon->Query($sql);
        $DatosFacturas=$this->obCon->FetchArray($consulta);
        $DatosEPS=$this->obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosFacturas["cod_enti_administradora"]); 
        $DatosEmpresaPro=$this->obCon->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $this->PDF_Encabezado($fecha,1, $idFormato, "",$Documento); //encabezado del formato de calidad
        $this->PDF->SetMargins(20, PDF_MARGIN_TOP, 20);
        $this->PDF->SetFont('helvetica', '', 10);
        $html="<br>";
        $html.="
<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>$DatosEmpresaPro[Ciudad], $DatosCobro[Fecha]</span></span></strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;></span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>Se&ntilde;or</span></span></strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>(a)</span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><span style=font-size:10.0pt><span style=background-color:yellow><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>$DatosEPS[nombre_completo]</span></span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=background-color:yellow><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>DIRECCI&Oacute;N</span></span></span></strong><span style=font-size:10.0pt><span style=background-color:yellow><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>:</span></span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>REF</span></span></strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>:&nbsp;&nbsp;&nbsp;$Asunto</span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>FACTURA(S) No</span></span></strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>:&nbsp;<span style=background-color:yellow>SEGÚN RELACIÓN ADJUNTA</span></span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify><span style=font-size:11pt><span style=font-family:&quot;Calibri&quot;,&quot;sans-serif&quot;><strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>POR TOTAL DE: </span></span></strong><span style=font-size:10.0pt><span style=font-family:&quot;Arial&quot;,&quot;sans-serif&quot;>&nbsp;<span style=background-color:yellow>$ ".number_format($DatosFacturas["TotalFacturas"])."</span></span></span></span></span></p>

<p style=margin-left:0cm; margin-right:0cm; text-align:justify>&nbsp;</p>

";
        
$html.= utf8_encode($DatosFormatos["CuerpoFormato"]); 
        $this->PDF->writeHTML($html, true, 0, true, true);
        //$this->PDF_Write("<br>".$html);
        
        
        $this->PDF_Output("CobroPrejuridico_$idCobro");
    }
    /**
     * Genera el PDF de un reporte
     * @param type $idReporte
     */
    public function Reportes_PDF($idReporte,$query,$st,$idUser,$Vector) {
        $DatosFormatos= $this->obCon->DevuelveValores("formatos_calidad", "ID", 32);
        if($idReporte==1){
            $Documento="Glosas Pendientes por Conciliar";
            $html= $this->ReportesHTML_PendientesXConciliar($st);
        }
        if($idReporte==2){
            $Documento="Glosas Pendientes por Contestar";
            $html= $this->ReportesHTML_PendientesXConciliar($st);
        }
        if($idReporte==3){
            $Documento="Porcentaje de valor Glosado definitivo X EPS";
            $html= $this->ReportesHTML_PorcentajesGlosados($st,$query);
        }
        if($idReporte==4){
            $Documento="Porcentaje de valor Glosado definitivo X IPS";
            $html=$this->ReportesHTML_PorcentajesGlosadosIPS($st,$query);
        }
        if($idReporte==5){
            $Documento="Reporte 2193";
            $html=$this->ReportesHTML_Reporte_2193($st);
        }
        if($idReporte==6){
            $Documento="Reporte de concepto de glosas mas frecuente";
            $html=$this->ReportesHTML_GlosasFrecuentes($st);
        }
        
        $this->PDF_Ini($Documento, 8,""); 
        $this->PDF_Encabezado(date("Y-m-d"),1, 32, "",$Documento); 
        $this->PDF_Write("<br><br><h3 style='text-align:center'>$Documento</h3>");
        
        $Position=$this->PDF->SetY(60);
        $this->PDF_Write($html);
        
        $Position=$this->PDF->GetY();
        if($Position>240){
          $this->PDF_Add();
        }
        $html= $this->FooterHTMLReportes($idUser);
        $this->PDF->SetY(282);
        $this->PDF_Write($html);
        $this->PDF_Output($Documento);
    }
    /**
     * Arma el HTML para visualizar los reportes de glosas pendientes por conciliar
     * @param type $st
     */
    public function ReportesHTML_PendientesXConciliar($st) {
        
        $Back="#CEE3F6";
        $html='<table cellspacing="1" cellpadding="2" border="0"  align="left" >';
            $html.="<tr>";
                $html.='<td>';
                $html.="<strong>CUENTA</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>ENTIDAD</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>PRESTADOR</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>FÁCTURA</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>ACTIVIDAD</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>CÓDIGO GLOSA</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>IDENTIFICACIÓN</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>ESTADO</strong>";
                $html.="</td>";
            $html.="</tr>";
            
            $query="SELECT cuenta,cod_actividad,cod_glosa_inicial,factura,nombre_administrador,fecha_factura,cod_prestador, identificacion, descripcion_estado ";
            $consulta= $this->obCon->Query("$query FROM $st ORDER BY fecha_factura");
            $h=0;
            while($DatosRespuestas=$this->obCon->FetchAssoc($consulta)){
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                }
                
                $html.='<tr align="left" border="1" style="border-bottom: 2px solid #ddd;background-color: '.$Back.';"> ';
                    $html.="<td>";
                    $html.=$DatosRespuestas["cuenta"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.= utf8_encode($DatosRespuestas["nombre_administrador"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["cod_prestador"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["factura"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["cod_actividad"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["cod_glosa_inicial"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["identificacion"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["descripcion_estado"];
                    $html.="</td>";
                $html.="</tr>";
            }
        $html.="</tabla>";
        return($html);
    }
    /**
     * HTML porcentajes Glosados
     * @param type $st
     * @return type
     */
    public function ReportesHTML_PorcentajesGlosados($st,$query) {
        
        $Back="#CEE3F6";
        $html='<table cellspacing="1" cellpadding="2" border="0"  align="left" >';
            $html.="<tr>";
                $html.='<td>';
                $html.="<strong>CODIGO ENTIDAD</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>NOMBRE ENTIDAD</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>NIT</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL FACTURADO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL GLOSADO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>PORCENTAJE</strong>";
                $html.="</td>";
            $html.="</tr>";
            /*
            $query="SELECT cod_administrador,nombre_administrador,nit_administrador,"
                   . " (SELECT SUM(valor_neto_pagar) FROM salud_archivo_facturacion_mov_generados WHERE cod_enti_administradora=cod_administrador) AS TotalFacturado,"
                   . "SUM(ValorGlosado - ValorLevantado) AS TotalGlosado ";
             * 
             */
            $consulta= $this->obCon->Query("$query FROM $st GROUP BY cod_administrador");
            $h=0;
            while($DatosRespuestas=$this->obCon->FetchAssoc($consulta)){
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                }
                $TotalFactura=$DatosRespuestas["TotalFacturado"];
                if($DatosRespuestas["TotalFacturado"]==0){
                    $TotalFactura=1;
                }
                $Porcentaje=ROUND((100/$TotalFactura)*$DatosRespuestas["TotalGlosado"],4);
                               
                $html.='<tr align="left" border="1" style="border-bottom: 2px solid #ddd;background-color: '.$Back.';"> ';
                    $html.="<td>";
                    $html.=$DatosRespuestas["cod_administrador"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.= utf8_encode($DatosRespuestas["nombre_administrador"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["nit_administrador"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=number_format($DatosRespuestas["TotalFacturado"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.= number_format($DatosRespuestas["TotalGlosado"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$Porcentaje."%";
                    $html.="</td>";
                $html.="</tr>";
            }
        $html.="</tabla>";
        return($html);
    }
    
    /**
     * HTML porcentajes Glosados
     * @param type $st
     * @return type
     */
    public function ReportesHTML_PorcentajesGlosadosIPS($st,$query) {
        
        $Back="#CEE3F6";
        $html='<table cellspacing="1" cellpadding="2" border="0"  align="left" >';
            $html.="<tr>";
                $html.='<td>';
                $html.="<strong>ENTIDAD PRESTADORA</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>NOMBRE ENTIDAD</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>NIT</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL FACTURADO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL GLOSADO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>PORCENTAJE</strong>";
                $html.="</td>";
            $html.="</tr>";
            /*
            $query="SELECT cod_prestador,nombre_prestador,nit_prestador,"
                    . " (SELECT SUM(valor_neto_pagar) FROM salud_archivo_facturacion_mov_generados WHERE cod_prest_servicio=cod_prestador) AS TotalFacturado,"
                    . "SUM(ValorGlosado - ValorLevantado) AS TotalGlosado ";
             * 
             */
            $consulta= $this->obCon->Query("$query FROM $st GROUP BY cod_prestador");
            $h=0;
            while($DatosRespuestas=$this->obCon->FetchAssoc($consulta)){
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                }
                $TotalFactura=$DatosRespuestas["TotalFacturado"];
                if($DatosRespuestas["TotalFacturado"]==0){
                    $TotalFactura=1;
                }
                $Porcentaje=ROUND((100/$TotalFactura)*$DatosRespuestas["TotalGlosado"],4);
                               
                $html.='<tr align="left" border="1" style="border-bottom: 2px solid #ddd;background-color: '.$Back.';"> ';
                    $html.="<td>";
                    $html.=$DatosRespuestas["cod_prestador"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.= utf8_encode($DatosRespuestas["nombre_prestador"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$DatosRespuestas["nit_prestador"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.=number_format($DatosRespuestas["TotalFacturado"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.= number_format($DatosRespuestas["TotalGlosado"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=$Porcentaje."%";
                    $html.="</td>";
                $html.="</tr>";
            }
        $html.="</tabla>";
        return($html);
    }
    
    /**
     * HTML reporte 2193
     * * @param type $st
     * @return type
     */
    public function ReportesHTML_Reporte_2193($st) {
        
        $Back="#CEE3F6";
        $html='<table cellspacing="1" cellpadding="2" border="0"  align="left" >';
            $html.="<tr>";
                $html.='<td>';
                $html.="<strong>RÉGIMEN</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL GLOSADO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>TOTAL GLOSADO DEFINITIVO</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>DIFERENCIA</strong>";
                $html.="</td>";
                
            $html.="</tr>";
            
            $query="SELECT regimen_eps,"
                    . "SUM(ValorGlosado) AS TotalGlosado,"
                    . "SUM(ValorGlosado - ValorLevantado) AS TotalGlosadoDefinitivo ";
            $consulta= $this->obCon->Query("$query FROM $st GROUP BY regimen_eps");
            $h=0;
            while($DatosRespuestas=$this->obCon->FetchAssoc($consulta)){
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                }
                               
                $html.='<tr align="left" border="1" style="border-bottom: 2px solid #ddd;background-color: '.$Back.';"> ';
                    $html.="<td>";
                    $html.=$DatosRespuestas["regimen_eps"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.= number_format($DatosRespuestas["TotalGlosado"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=number_format($DatosRespuestas["TotalGlosadoDefinitivo"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=number_format($DatosRespuestas["TotalGlosado"]-$DatosRespuestas["TotalGlosadoDefinitivo"]);
                    $html.="</td>";
                    
                $html.="</tr>";
            }
        $html.="</tabla>";
        return($html);
    }
    
    
    /**
     * HTML reporte Glosas Frecuentes
     * * @param type $st
     * @return type
     */
    public function ReportesHTML_GlosasFrecuentes($st) {
        
        $Back="#CEE3F6";
        $html='<table cellspacing="1" cellpadding="2" border="0"  align="left" >';
            $html.="<tr>";
                $html.='<td>';
                $html.="<strong>CÓDIGO GLOSA</strong>";
                $html.="</td>";
                $html.="<td>";
                $html.="<strong>DESCRIPCIÓN</strong>";
                $html.="</td>";
                $html.="<td style='text-align:center'>";
                $html.="<strong>TOTAL</strong>";
                $html.="</td>";
                
                
            $html.="</tr>";
            
            $query="SELECT CodigoGlosa, DescripcionGlosa,"
                    . "COUNT(CodigoGlosa) AS Cantidad ";
            $consulta= $this->obCon->Query("$query FROM $st GROUP BY CodigoGlosa ORDER BY (COUNT(CodigoGlosa)) DESC");
            $h=0;
            while($DatosRespuestas=$this->obCon->FetchAssoc($consulta)){
                if($h==0){
                    $Back="#f2f2f2";
                    $h=1;
                }else{
                    $Back="white";
                    $h=0;
                }
                               
                $html.='<tr align="left" border="1" style="border-bottom: 2px solid #ddd;background-color: '.$Back.';"> ';
                    $html.="<td>";
                    $html.=$DatosRespuestas["CodigoGlosa"];
                    $html.="</td>";
                    $html.="<td>";
                    $html.= utf8_encode($DatosRespuestas["DescripcionGlosa"]);
                    $html.="</td>";
                    $html.="<td>";
                    $html.=number_format($DatosRespuestas["Cantidad"]);
                    $html.="</td>";
                                        
                $html.="</tr>";
            }
        $html.="</tabla>";
        return($html);
    }
    public function FooterHTMLReportes($idUser) {
        $DatosUsuario= $this->obCon->DevuelveValores("usuarios","idUsuarios",$idUser);
        $Fecha=date("Y-m-d H:i:s");
        $html="Impreso por $DatosUsuario[Nombre], Fecha: $Fecha";
        return($html);
    }
   //Fin Clases
}
    