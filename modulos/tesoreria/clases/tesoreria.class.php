<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class Tesoreria extends conexion{
    
    
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
    
    public function EditarPagoTesoreria($idPago,$Fecha,$CmbEps,$CmbBanco,$NumeroTransaccion,$TipoPago,$ValorTransaccion,$ValorXLegalizar,$Observaciones,$idUser) {
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
        		
        $Datos["observacion"]=$Observaciones;			
        $Datos["fecha_hora_registro"]=date("Y-m-d H:i:s");		
        $Datos["idUser"]=$idUser;			
        $Datos["valor_legalizar"]=$ValorXLegalizar;
        $Datos["TipoPago"]=$TipoPago;	 

        $sql=$this->getSQLUpdate($tab, $Datos);
        $sql.=" WHERE ID='$idPago'";
        $this->Query($sql);
    }
    
   
    /**
     * Fin Clase
     */
}
