<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class Prefacturacion extends conexion{
    
    public function CalcularEdad($FechaNacimiento) {
        $FechaActual=date("Y-m-d");
        $datetime1 = new DateTime($FechaNacimiento);
        $datetime2 = new DateTime($FechaActual);
        $interval = $datetime1->diff($datetime2);
        $FechaMayor=$interval->format('%R');
        $Dias=$interval->format('%d');
        $Anios=$interval->format('%y');
        $Meses=$interval->format('%m');
        $Salte=0;
        if($Anios>0 and $Salte==0){
            $EdadCalculada=$Anios;
            $Unidad=1;
            $Salte=1;
            $NombreUnidad="AÑOS";
        }
        
        if($Meses>0 and $Salte==0){
            $EdadCalculada=$Meses;
            $Unidad=2;
            $Salte=1;
            $NombreUnidad="MESES";
        }
        
        if($Dias>0 and $Salte==0){
            $EdadCalculada=$Dias;
            $Unidad=3;
            $Salte=1;
            $NombreUnidad="DIAS";
        }
        
        $DatosEdad["Edad"]=$EdadCalculada;
        $DatosEdad["Unidad"]=$Unidad;
        $DatosEdad["NombreUnidad"]=$NombreUnidad;
        return($DatosEdad);
    }
    
    public function IngresarPagoTesoreria($Fecha,$CmbEps,$CmbBanco,$NumeroTransaccion,$TipoPago,$ValorTransaccion,$Observaciones,$Soporte,$idUser) {
        $DatosEps= $this->DevuelveValores("salud_eps", "cod_pagador_min", $CmbEps);
        $DatosBanco= $this->DevuelveValores("salud_bancos", "ID", $CmbBanco);
        $tab="salud_tesoreria";
        
        $Datos["cod_enti_administradora"]=$CmbEps;	
        $Datos["nom_enti_administradora"]=$DatosEps["nombre_completo"];	
        $Datos["fecha_transaccion"]=$Fecha;           
        $Datos["num_transaccion"]=$NumeroTransaccion;		
        $Datos["banco_transaccion"]=$DatosBanco["banco_transaccion"];		
        $Datos["num_cuenta_banco"]=$DatosBanco["num_cuenta_banco"];		
        $Datos["valor_transaccion"]=$ValorTransaccion;		
        $Datos["Soporte"]=$Soporte;			
        $Datos["observacion"]=$Observaciones;			
        $Datos["fecha_hora_registro"]=date("Y-m-d H:i:s");		
        $Datos["idUser"]=$idUser;			
        $Datos["valor_legalizar"]=$ValorTransaccion;
        $Datos["TipoPago"]=$TipoPago;	 

        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
    }
    
   
    /**
     * Fin Clase
     */
}
