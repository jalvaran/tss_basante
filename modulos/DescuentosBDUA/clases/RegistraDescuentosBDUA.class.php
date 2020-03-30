<?php

if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
/* 
 * Clase donde se realizaran procesos de la cartera IPS
 * Julian Alvaran
 * Techno Soluciones SAS
 * 2018-09-26
 */
        
class DescuentosBDUA extends conexion{
        
    public function RegistreDescuentoBDUA($FechaDescuento, $NumeroFactura, $Radicado, $AfiliadosIMA, $ValorDescuento,$NuevoValor,$idUser) {
        
        $Datos["Fecha"]=$FechaDescuento;
        $Datos["NumeroFactura"]=$NumeroFactura;
        $Datos["NumeroRadicado"]=$Radicado;
        $Datos["AfiliadosIMA"]=$AfiliadosIMA;
        $Datos["ValorDescuento"]=$ValorDescuento;
        $Datos["idUser"]=$idUser;
        $Datos["FechaRegistro"]=date("Y-m-d H:i:s");
        $sql=$this->getSQLInsert("descuentos_bdua", $Datos);
        $this->Query($sql);
        $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "valor_neto_pagar", $NuevoValor, "num_factura", $NumeroFactura);
            
    }
    
    //Fin Clases
}
