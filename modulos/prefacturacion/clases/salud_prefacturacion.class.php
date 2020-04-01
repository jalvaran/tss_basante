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
    
    public function AgregarCitaReserva($idReserva, $idHospital, $Fecha, $Hora,$Observaciones, $idUser) {
        
        $tab="prefactura_reservas_citas";
        
        $Datos["idReserva"]=$idReserva;	
        $Datos["idHospital"]=$idHospital;	
        $Datos["Fecha"]=$Fecha;           
        $Datos["Hora"]=$Hora;	
        $Datos["Observaciones"]=$Observaciones;	
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");		
        $Datos["Estado"]=1;		
        
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
    }
    
    public function ValidarReserva($idReserva,$Fecha,$Observaciones,$destino,$Tamano, $NombreArchivo, $Extension, $idUser) {
        
        $tab="prefactura_reservas_validacion";
        
        $Datos["idReserva"]=$idReserva;
        $Datos["Fecha"]=$Fecha;    
        $Datos["Observaciones"]=$Observaciones;	
        $Datos["Ruta"]=$destino;    
        $Datos["NombreArchivo"]=$NombreArchivo;    
        $Datos["Extension"]=$Extension;    
        $Datos["Tamano"]=$Tamano; 
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");		
        	
        
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
    }
    
   
    /**
     * Fin Clase
     */
}
