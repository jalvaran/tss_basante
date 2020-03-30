<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class Glosas extends conexion{
    public function RegistreGlosa($TipoGlosa,$CodigoGlosa,$FechaReporte,$ValorEPS,$ValorAceptado,$Observaciones,$TablaOrigen,$idArchivo,$NumFactura,$idEps,$idUser,$Vector) {
        $DatosFactura= $this->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $NumFactura);
        //Miro si se recibe un archivo
        //
        if(!empty($_FILES['Soporte']['name'])){
            //echo "<script>alert ('entra foto')</script>";
            $Atras="../";
            $carpeta="SoportesSalud/SoportesGlosas/";
            opendir($Atras.$carpeta);
            $Name=$idArchivo."_".str_replace(' ','_',$_FILES['Soporte']['name']);
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES['Soporte']['tmp_name'],$Atras.$destino);
	}
        
        if($TablaOrigen=="salud_archivo_consultas"){
            $Prefijo="AC";
        }
        if($TablaOrigen=="salud_archivo_procedimientos"){
            $Prefijo="AP";
        }
        if($TablaOrigen=="salud_archivo_medicamentos"){
            $Prefijo="AM";
        }
        if($TablaOrigen=="salud_archivo_otros_servicios"){
            $Prefijo="AT";
        }
        if($TablaOrigen=="salud_archivo_facturacion_mov_generados"){
            $Prefijo="AF";
        }
    
        //////Creo la compra            
        $tab="salud_registro_glosas";
        $NumRegistros=14;

        $Columnas[0]="num_factura";		$Valores[0]=$NumFactura;
        $Columnas[1]="PrefijoArchivo";          $Valores[1]=$Prefijo;
        $Columnas[2]="idArchivo";               $Valores[2]=$idArchivo;
        $Columnas[3]="TipoGlosa";		$Valores[3]=$TipoGlosa;
        $Columnas[4]="CodigoGlosa";             $Valores[4]=$CodigoGlosa;
        $Columnas[5]="FechaReporte";            $Valores[5]=$FechaReporte;
        $Columnas[6]="GlosaEPS";                $Valores[6]=$ValorEPS;
        $Columnas[7]="GlosaAceptada";           $Valores[7]=$ValorAceptado;
        $Columnas[8]="Soporte";                 $Valores[8]=$destino;
        $Columnas[9]="Observaciones";           $Valores[9]=$Observaciones;
        $Columnas[10]="idUser";                 $Valores[10]=$idUser;
        $Columnas[11]="TablaOrigen";            $Valores[11]=$TablaOrigen;
        $Columnas[12]="fecha_factura";          $Valores[12]=$DatosFactura["fecha_factura"];
        $Columnas[13]="cod_enti_administradora";$Valores[13]=$DatosFactura["cod_enti_administradora"];
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        
    }
    
    //Informe detallado de glosas
    public function DetalleRips($idEPS,$Mes,$Year,$Vector) {
        $YearMes="$Year-$Mes";
        //Total de facturas generadas
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(valor_neto_pagar) as Total FROM salud_archivo_facturacion_mov_generados "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND tipo_negociacion='evento'";
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        $TotalesRips["RG"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RG"]["V"]=$DatosRips["Total"];
        //Total de facturas Pagadas
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(valor_neto_pagar) as Total FROM salud_archivo_facturacion_mov_generados "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND Estado='PAGADA' AND tipo_negociacion='evento'";
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        $TotalesRips["RP"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RP"]["V"]=$DatosRips["Total"];
        //SIN PAGAR NO VENCIDOS
        
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(valor_neto_pagar) as Total FROM vista_salud_facturas_no_pagas "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND (DiasMora<=1 or DiasMora IS NULL) AND tipo_negociacion='evento'";
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        $TotalesRips["RCNV"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RCNV"]["V"]=$DatosRips["Total"];
        
        //SIN PAGAR VENCIDOS
        
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(valor_neto_pagar) as Total FROM vista_salud_facturas_no_pagas "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND DiasMora>=1  AND tipo_negociacion='evento'";
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        $TotalesRips["RCV"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RCV"]["V"]=$DatosRips["Total"];
        
        //Glosa Aceptada
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(GlosaAceptada) as Total FROM salud_registro_glosas "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND TipoGlosa='3'";
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $TotalesRips["RGA"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RGA"]["V"]=$DatosRips["Total"];
        
        //Glosa Devuelta
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(GlosaAceptada) as Total FROM salud_registro_glosas "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND TipoGlosa='5'";
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $TotalesRips["RD"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RD"]["V"]=$DatosRips["Total"];
        
        //Rips Con Diferencias Sin Conciliar
       
        $sql="SELECT COUNT(num_factura) as NumFacturas, SUM(GlosaAceptada) as Total FROM salud_registro_glosas "
                . "WHERE SUBSTRING(`fecha_factura`,1,7)='$YearMes' AND TipoGlosa='4'";
        $consulta=$this->Query($sql);
        $DatosRips= $this->FetchArray($consulta);
        if($idEPS<>'ALL'){
           $sql.=" AND cod_enti_administradora='$idEPS'"; 
        }
        $TotalesRips["RDSC"]["C"]=$DatosRips["NumFacturas"];
        $TotalesRips["RDSC"]["V"]=$DatosRips["Total"];
        return($TotalesRips);
    }
    //Cartera x Edad
    public function CarteraSegunDias($idEPS,$Condicion,$Vector) {
        $sql="SELECT SUM(valor_neto_pagar) AS Total, COUNT(num_factura) AS NumFacturas FROM vista_salud_carteraxdias_v2 $Condicion";
        $Consulta=$this->Query($sql);
        $DatosCartera=$this->FetchArray($Consulta);
        return($DatosCartera);
    }
    //Fin Clases
}