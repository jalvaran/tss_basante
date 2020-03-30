<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class Legal extends conexion{
    public function CrearCobroPrejuridico($Fecha,$TipoCobro,$NumFact,$idEPS,$Observaciones,$idUser,$Vector) {
        if($TipoCobro==1){
            $sqlEnd=" vista_salud_facturas_no_pagas WHERE DiasMora>='1' AND EstadoCobro='' AND cod_enti_administradora='$idEPS'";
            
        }else{
            $sqlEnd=" vista_salud_facturas_no_pagas WHERE cod_enti_administradora='$idEPS' AND DiasMora>='30' AND EstadoCobro='PREJURIDICO1'";
        
        }
        $sql="SELECT id_factura_generada FROM ".$sqlEnd;
        $consulta=$this->Query($sql);
        if(!$this->NumRows($consulta)){
            return(0);
        }
        //////Creo el cobro           
        $tab="salud_cobros_prejuridicos";
        $NumRegistros=5;

        $Columnas[0]="TipoCobro";		$Valores[0]=$TipoCobro;
        $Columnas[1]="Fecha";                   $Valores[1]=$Fecha;
        $Columnas[2]="Observaciones";           $Valores[2]=$Observaciones;
        $Columnas[3]="idUser";                  $Valores[3]=$idUser;
        $Columnas[4]="Soporte";                 $Valores[4]="";
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        $id=$this->ObtenerMAX($tab, "ID", 1, "");
        
        // Con este sql ingreso los numeros de facturas a la relacion de cobros
        $sql="INSERT INTO salud_cobros_prejuridicos_relaciones (idCobroPrejuridico, num_factura) SELECT '$id',num_factura FROM $sqlEnd;"; 
            
        if($TipoCobro==1){
             // Con este sql actualizo la tabla de facturacion generada
            $sqlUP="UPDATE salud_archivo_facturacion_mov_generados t1 INNER JOIN vista_salud_facturas_no_pagas t2 ON t1.`num_factura` =t2.`num_factura`"
                . " SET t1.EstadoCobro='PREJURIDICO1' WHERE t2.DiasMora>='1' AND t2.EstadoCobro='' AND t2.cod_enti_administradora='$idEPS'";
        
        }else{
            $sqlUP="UPDATE salud_archivo_facturacion_mov_generados t1 INNER JOIN vista_salud_facturas_no_pagas t2 ON t1.`num_factura` =t2.`num_factura`"
                . " SET t1.EstadoCobro='PREJURIDICO2' WHERE t2.DiasMora>='30' AND t2.EstadoCobro='PREJURIDICO1' AND t2.cod_enti_administradora='$idEPS'";
        
        }
        
        $this->Query($sql);
        $this->Query($sqlUP);
        return($id);
        
    }
    
    //Fin Clases
}