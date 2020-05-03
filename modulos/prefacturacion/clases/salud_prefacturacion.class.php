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
    
    public function AdjuntarDocumentoCita($idCita,$destino,$Tamano, $NombreArchivo, $Extension, $idUser) {
        
        $tab="prefactura_reservas_citas_adjuntos";
        
        $Datos["idCita"]=$idCita;
        
        $Datos["Ruta"]=$destino;    
        $Datos["NombreArchivo"]=$NombreArchivo;    
        $Datos["Extension"]=$Extension;    
        $Datos["Tamano"]=$Tamano; 
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");		
        	
        
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
    }
    
    public function CrearVistaPendientesPorFacturar($FechaInicial,$FechaFinal) {
        $sql="DROP VIEW IF EXISTS `vista_pendiente_por_facturar`;";
        $this->Query($sql);
        $Condicion="";
        if($FechaInicial<>''){
            $Condicion.=" AND t2.Fecha>='$FechaInicial'";
        }
        if($FechaFinal<>''){
            $Condicion.=" AND t2.Fecha<='$FechaFinal'";
        }
        $sql="CREATE VIEW vista_pendiente_por_facturar AS 
                SELECT t1.ID,t1.Created as FechaReserva,t1.NumeroAutorizacion,t1.idPaciente,

                (SELECT t5.TipoDocumento FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as TipoDocumento,
                (SELECT t5.NumeroDocumento FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as NumeroDocumento,
                (SELECT CONCAT(t5.PrimerNombre,' ',t5.SegundoNombre,' ',t5.PrimerApellido,' ',t5.SegundoApellido) FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as NombrePaciente,
                (SELECT t5.Telefono FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as Telefono,
                (SELECT t5.Direccion FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as Direccion    

            FROM prefactura_reservas t1 WHERE t1.Estado=3 AND EXISTS 
                (SELECT 1 FROM prefactura_reservas_citas t2 WHERE t2.idReserva=t1.ID AND t2.Estado=3 $Condicion);";
        $this->Query($sql);
    }
    
    public function CrearFactura($idFactura,$Fecha,$NumeroFactura,$idResolucion, $TipoFactura, $idRegimenFactura,$ReferenciaTutela,$idReserva,$Observaciones, $idUser) {
        
        $tab="facturas";        
        $Datos["ID"]=$idFactura;        
        $Datos["Fecha"]=$Fecha;    
        $Datos["NumeroFactura"]=$NumeroFactura;
        $Datos["idResolucion"]=$idResolucion;    
        $Datos["TipoFactura"]=$TipoFactura; 
        $Datos["idRegimenFactura"]=$idRegimenFactura; 
        $Datos["ReferenciaTutela"]=$ReferenciaTutela; 
        $Datos["idReserva"]=$idReserva;   
        $Datos["Observaciones"]=$Observaciones;
        $Datos["Estado"]=1;         
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");        
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        
    }
    
    public function AgregarItemFactura($idFactura,$idCita,$idServicio,$idColaborador, $idRecorrido, $Valor) {
        
        $tab="facturas_items";        
             
        $Datos["idFactura"]=$idFactura;    
        $Datos["idCita"]=$idCita;
        $Datos["idServicio"]=$idServicio;    
        $Datos["idColaborador"]=$idColaborador; 
        $Datos["idRecorrido"]=$idRecorrido; 
        $Datos["Valor"]=$Valor;         
        $Datos["Estado"]=1;
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        
    }
    
    /**
     * Fin Clase
     */
}
