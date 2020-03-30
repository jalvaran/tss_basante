<?php
/* 
 * Clase donde se realizaran procesos de validaciones de la informacion.
 * Julian Alvaran
 * Techno Soluciones SAS
 */

class Validacion extends conexion{
    public function ValideCuentaRIPS($CuentaRIPS,$Vector) {
        $DatosCuentaRIPS=$this->DevuelveValores("salud_ct", "CuentaRIPS", $CuentaRIPS);
        return $DatosCuentaRIPS;
    }
    
    //Fin Clases
}