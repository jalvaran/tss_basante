<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class SIHO extends conexion{
    public function CrearSIHO($FechaCorte,$Separador,$Vector) {
        
        $Salto="\r\n";
        $CodEps='';
        
        //Calculo fecha actual y fecha anterior un mes antes en formato yyyy-mm
        //$FechaConsulta=substr($FechaCorte,0,-3); //2018-09-09
        //$FechaConsultaAnterior = date('Y-m-d', strtotime("$FechaCorte - 1 month"));
        //$FechaConsultaAnterior=substr($FechaConsultaAnterior,0,-3); //2018-09-09
        
        //realizo las consultas necesarias 
        
        $sql="SELECT * FROM vista_SIHO WHERE fecha_radicado < '$FechaCorte'";        
        $Datos=$this->Query($sql);
        while($DatosFactura=$this->FetchArray($Datos)){
            $CodEps=$DatosFactura['cod_enti_administradora'];
            if($DatosFactura['cod_enti_administradora']==''){
                $CodEps='SinEps';
            }
            
            if(!isset($Cartera[$CodEps]['Codigo'])){
                $Cartera[$CodEps]['SaldoAnterior']=0;
                $Cartera[$CodEps]['SaldoPeriodo']=0;
                $Cartera[$CodEps]['Codigo']=$CodEps;
                $Cartera[$CodEps]['NombreEntidad']=$DatosFactura['nom_enti_administradora'];
            }
            
            $Cartera[$CodEps]['SaldoAnterior']=$Cartera[$CodEps]['SaldoAnterior']+$DatosFactura['valor_neto_pagar'];
            
        }
        
        
        return($Cartera);
        
        
    }
    
    //Fin Clases
}