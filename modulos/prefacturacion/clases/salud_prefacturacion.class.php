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
    
    
    public function CrearVistaFacturas() {
        $sql="DROP VIEW IF EXISTS `vista_facturas_basante`;";
        $this->Query($sql);
        
        $sql="CREATE VIEW vista_facturas_basante AS 
                SELECT t2.*,t1.NumeroAutorizacion,
                (SELECT IFNULL((SELECT COUNT(idCita) FROM facturas_items t3 WHERE t3.idFactura=t2.ID),0)) as CitasFacturadas,
                (SELECT IFNULL((SELECT SUM(Valor) FROM facturas_items t3 WHERE t3.idFactura=t2.ID),0)) as TotalFactura,
                (SELECT IFNULL((SELECT TotalFactura)/(SELECT CitasFacturadas),0)) as Subtotal,
                (SELECT t5.TipoDocumento FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as TipoDocumento,
                (SELECT t5.NumeroDocumento FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as NumeroDocumento,
                (SELECT CONCAT(t5.PrimerNombre,' ',t5.SegundoNombre,' ',t5.PrimerApellido,' ',t5.SegundoApellido) FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as NombrePaciente,
                (SELECT t5.Telefono FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as Telefono,
                (SELECT t5.Direccion FROM prefactura_paciente t5 WHERE t5.ID=t1.idPaciente) as Direccion,
                (SELECT t6.TipoFactura FROM facturas_tipo t6 WHERE t6.ID=t2.TipoFactura) as NombreTipoFactura,
                (SELECT t7.RegimenFactura FROM facturas_regimen t7 WHERE t7.ID=t2.idRegimenFactura) as NombreResolucionFactura  
                
            FROM facturas t2 INNER JOIN prefactura_reservas t1 ON t2.idReserva=t1.ID ORDER BY t2.Created DESC;";
        $this->Query($sql);
    }
    
    public function CrearConsecutivoRips($idUser) {
        $tab="rips_consecutivos";    
        $sql="SELECT * FROM $tab WHERE Estado=0 LIMIT 1";
        $DatosRIPS=$this->FetchAssoc($this->Query($sql));
        if($DatosRIPS["CuentaRIPS"]<>''){
            return($DatosRIPS["CuentaRIPS"]);
        }     
        $Datos["Created"]=date("Y-m-d H:i:s");    
        
        $Datos["idUser"]=$idUser;    
        
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        $CuentaRIPS=$this->ObtenerMAX($tab, "CuentaRIPS", "1", "");
        return($CuentaRIPS);
    }
    
    public function GenereRIPSAF($CuentaRIPS,$Condicion,$FechaInicio,$FechaFin) {
        $DatosIPS=$this->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $CodPrestador=$DatosIPS["CodigoPrestadora"];
        $RazonSocialIPS=$DatosIPS["RazonSocial"];
        $NIT=$DatosIPS["NIT"];
        $NombreRIPS="AF".$CuentaRIPS.".txt";
        
        $FechaInicio=date("d/m/Y", strtotime($FechaInicio));
        $FechaFin=date("d/m/Y", strtotime($FechaFin));
        $path="../../../RIPS/$CuentaRIPS/";
        $nombre_archivo = $path.$NombreRIPS;
        
        if(!file_exists($path)){           
            mkdir($path,'0777');
        }
        
        $sql="SELECT NumeroFactura,
                (SELECT DATE_FORMAT(Fecha, '%d/%m/%Y')) as Fecha,
                
                (SELECT idPaciente FROM prefactura_reservas t2 WHERE t2.ID=t1.idReserva LIMIT 1)  as idPaciente,
                (SELECT CodEPS FROM prefactura_paciente t4 WHERE t4.ID=(SELECT idPaciente)) as CodEPS,
                
                (SELECT nombre_completo FROM salud_eps t5 WHERE t5.cod_pagador_min=(SELECT CodEPS)) as RazonSocialEPS,
                (SELECT ROUND(SUM(Valor),2) FROM facturas_items t3 WHERE t3.idFactura=t1.ID ) as TotalFactura
               
                    FROM facturas t1 $Condicion";
        
        $Consulta=$this->Query($sql);
        $Total=0;
        if($archivo = fopen($nombre_archivo, "w")){
            
            $mensaje="";
            $i=0;
            while($DatosConsulta=$this->FetchAssoc($Consulta)){
                $i=$i+1;
                $Total=$Total+$DatosConsulta["TotalFactura"];
                $mensaje.=$CodPrestador.",";
                $mensaje.=$RazonSocialIPS.",";
                $mensaje.="NI,";
                $mensaje.=$NIT.",";
                $mensaje.=$DatosConsulta["NumeroFactura"].",";
                $mensaje.=$DatosConsulta["Fecha"].",";
                $mensaje.=$FechaInicio.",";
                $mensaje.=$FechaFin.",";
                $mensaje.=$DatosConsulta["CodEPS"].",";
                $mensaje.=substr(str_replace(",","",$this->QuitarAcentos(($DatosConsulta["RazonSocialEPS"]))), 0, 29).",";
                $mensaje.="999999,";
                $mensaje.=",";
                $mensaje.=",";
                $mensaje.="0.00,";
                $mensaje.="0.00,";
                $mensaje.="0.00,";
                $mensaje.=$DatosConsulta["TotalFactura"];                
                $mensaje.="\r\n";
            }
            
            $mensaje=substr($mensaje, 0, -2);
            fwrite($archivo,$mensaje);
            fclose($archivo);
        }
        
        $this->ActualizaRegistro("rips_consecutivos", "AF", $i, "CuentaRIPS", $CuentaRIPS);
        $this->ActualizaRegistro("rips_consecutivos", "Valor", $Total, "CuentaRIPS", $CuentaRIPS);
        return($i);
        
    }
    
    public function GenereRIPSAD($CuentaRIPS,$Condicion,$FechaInicio,$FechaFin) {
        $DatosIPS=$this->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $CodPrestador=$DatosIPS["CodigoPrestadora"];
        $NombreRIPS="AD".$CuentaRIPS.".txt";
        $path="../../../RIPS/$CuentaRIPS/";
        $nombre_archivo = $path.$NombreRIPS;
        
        if(!file_exists($path)){           
            mkdir($path,'0777');
        }
        
        $sql="SELECT NumeroFactura,CitasFacturadas,Subtotal,TotalFactura 
                
                    FROM vista_facturas_basante t1 $Condicion";
        
        $Consulta=$this->Query($sql);
        if($archivo = fopen($nombre_archivo, "w")){
            
            $mensaje="";
            $i=0;
            while($DatosConsulta=$this->FetchAssoc($Consulta)){
                $i=$i+1;
                $mensaje.=$DatosConsulta["NumeroFactura"].",";
                $mensaje.=$CodPrestador.",";                
                $mensaje.="14,";                
                $mensaje.=($DatosConsulta["CitasFacturadas"]).",";
                $mensaje.=round($DatosConsulta["Subtotal"],2).",";
                $mensaje.=round($DatosConsulta["TotalFactura"],2);                          
                $mensaje.="\r\n";
            }
            
            $mensaje=substr($mensaje, 0, -2);
            fwrite($archivo,$mensaje);
            fclose($archivo);
        }
        $this->ActualizaRegistro("rips_consecutivos", "AD", $i, "CuentaRIPS", $CuentaRIPS);
        return($i);
        
    }
    
    public function GenereRIPSAT($CuentaRIPS,$Condicion,$FechaInicio,$FechaFin) {
        $DatosIPS=$this->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $CodPrestador=$DatosIPS["CodigoPrestadora"];
        $NombreRIPS="AT".$CuentaRIPS.".txt";
        $path="../../../RIPS/$CuentaRIPS/";
        $nombre_archivo = $path.$NombreRIPS;
        
        if(!file_exists($path)){           
            mkdir($path,'0777');
        }
        
        $sql="SELECT NumeroFactura,TipoDocumento,NumeroDocumento,NumeroAutorizacion,
                    (SELECT idServicio FROM facturas_items t2 WHERE t2.idFactura=t1.ID LIMIT 1) as idServicio,
                    (SELECT Descripcion FROM catalogo_servicios t3 WHERE t3.CUPS=(SELECT idServicio) LIMIT 1) as DescripcionServicio,
                    CitasFacturadas,Subtotal,TotalFactura 
                
                    FROM vista_facturas_basante t1 $Condicion";
        
        $Consulta=$this->Query($sql);
        if($archivo = fopen($nombre_archivo, "w")){
            
            $mensaje="";
            $i=0;
            while($DatosConsulta=$this->FetchAssoc($Consulta)){
                $i=$i+1;
                $mensaje.=$DatosConsulta["NumeroFactura"].",";
                $mensaje.=$CodPrestador.",";                
                $mensaje.=$DatosConsulta["TipoDocumento"].","; 
                $mensaje.=$DatosConsulta["NumeroDocumento"].","; 
                $mensaje.=$DatosConsulta["NumeroAutorizacion"].","; 
                $mensaje.="2,"; 
                $mensaje.=$DatosConsulta["idServicio"].","; 
                $mensaje.=$DatosConsulta["DescripcionServicio"].",";                
                $mensaje.=($DatosConsulta["CitasFacturadas"]).",";
                $mensaje.=round($DatosConsulta["Subtotal"],2).",";
                $mensaje.=round($DatosConsulta["TotalFactura"],2);                          
                $mensaje.="\r\n";
            }
            
            $mensaje=substr($mensaje, 0, -2);
            fwrite($archivo,$mensaje);
            fclose($archivo);
        }
        $this->ActualizaRegistro("rips_consecutivos", "AT", $i, "CuentaRIPS", $CuentaRIPS);
        return($i);
        
    }
    
    public function GenereRIPSUS($CuentaRIPS,$Condicion,$FechaInicio,$FechaFin) {
        $DatosIPS=$this->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $CodPrestador=$DatosIPS["CodigoPrestadora"];
        $NombreRIPS="US".$CuentaRIPS.".txt";
        $path="../../../RIPS/$CuentaRIPS/";
        $nombre_archivo = $path.$NombreRIPS;
        
        if(!file_exists($path)){           
            mkdir($path,'0777');
        }
       
        $sql="SELECT t1.* FROM prefactura_paciente t1 
                    WHERE EXISTS (SELECT 1 FROM prefactura_reservas t2 WHERE t2.idPaciente=t1.ID AND 
                          EXISTS (SELECT 1 FROM facturas t3 $Condicion AND t3.idReserva=t2.ID)
                        )
                
                    ";
        
        $Consulta=$this->Query($sql);
        if($archivo = fopen($nombre_archivo, "w")){
            
            $mensaje="";
            $i=0;
            while($DatosConsulta=$this->FetchAssoc($Consulta)){
                $i=$i+1;   
                $Edad=$this->CalcularEdad($DatosConsulta["FechaNacimiento"]);
                $mensaje.=$DatosConsulta["TipoDocumento"].","; 
                $mensaje.=$DatosConsulta["NumeroDocumento"].","; 
                $mensaje.=$DatosConsulta["CodEPS"].","; 
                $mensaje.=$DatosConsulta["idRegimenPaciente"].","; 
                $mensaje.=$DatosConsulta["PrimerApellido"].","; 
                $mensaje.=$DatosConsulta["SegundoApellido"].",";                
                $mensaje.=($DatosConsulta["PrimerNombre"]).",";
                $mensaje.=($DatosConsulta["SegundoNombre"]).",";
                $mensaje.=$Edad["Edad"].",";
                $mensaje.=$Edad["Unidad"].",";
                $mensaje.=$DatosConsulta["Sexo"].",";
                $mensaje.=$DatosConsulta["Departamento"].",";
                $mensaje.=$DatosConsulta["Municipio"].",";
                $mensaje.=$DatosConsulta["ZonaResidencial"];
                $mensaje.="\r\n";
            }
            
            $mensaje=substr($mensaje, 0, -2);
            fwrite($archivo,$mensaje);
            fclose($archivo);
        }
        $this->ActualizaRegistro("rips_consecutivos", "US", $i, "CuentaRIPS", $CuentaRIPS);
        return($i);
        
    }
    
    
    public function GenereRIPSCT($CuentaRIPS) {
        $DatosIPS=$this->DevuelveValores("empresapro", "idEmpresaPro", 1);
        $CodPrestador=$DatosIPS["CodigoPrestadora"];
        $NombreRIPS="CT".$CuentaRIPS.".txt";
        $path="../../../RIPS/$CuentaRIPS/";
        $nombre_archivo = $path.$NombreRIPS;
        $FechaRemision=date("d/m/Y");
        if(!file_exists($path)){           
            mkdir($path,'0777');
        }
        
        $sql="SELECT AF,AD,AT,US 
                    FROM rips_consecutivos t1 WHERE CuentaRIPS='$CuentaRIPS'";
        
        $Consulta=$this->Query($sql);
        if($archivo = fopen($nombre_archivo, "w")){
            
            $mensaje="";
            $DatosConsulta=$this->FetchAssoc($Consulta);
            foreach ($DatosConsulta as $key => $value) {
                if($value>0){
                    $mensaje.=$CodPrestador.",";                
                    $mensaje.=$FechaRemision.",";                
                    $mensaje.=$key.$CuentaRIPS.",";
                    $mensaje.=$value;       
                    $mensaje.="\r\n";
                }
                
            }
            
            
            $mensaje=substr($mensaje, 0, -2);
            fwrite($archivo,$mensaje);
            fclose($archivo);
        }
        
        
        
        
    }
    
    
    public function ComprimaRIPS($CuentaRIPS,$NombreZIP) {
        //creamos una instancia de ZipArchive
        $zip = new ZipArchive();

        /*directorio a comprimir
         * la barra inclinada al final es importante
         * la ruta debe ser relativa no absoluta
         */
        $dir="../../../RIPS/$CuentaRIPS/";
        
        //ruta donde guardar los archivos zip, ya debe existir
        $rutaFinal = "../../../RIPS/$CuentaRIPS/zip/";

        if(!file_exists($rutaFinal)){
          mkdir($rutaFinal);
        }

        $archivoZip = $NombreZIP;
        $Archivos[0]="AF$CuentaRIPS.txt";
        $Archivos[1]="AT$CuentaRIPS.txt";
        $Archivos[2]="US$CuentaRIPS.txt";
        $Archivos[3]="AD$CuentaRIPS.txt";
        $Archivos[4]="CT$CuentaRIPS.txt";
        if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
            //$directorio = opendir($dir); //ruta actual
            //while ($archivo = readdir($directorio)){  //obtenemos un archivo y luego otro sucesivamente
              //  print($archivo."<br>");     
              foreach ($Archivos as $key => $value) {
                  $zip->addFile($dir.$value, $value);
              }
                
                
            //}
            $zip->close();

          //Muevo el archivo a una ruta
          //donde no se mezcle los zip con los demas archivos
          $zipRips= $rutaFinal.$archivoZip;
          rename($archivoZip, $zipRips);

          //Hasta aqui el archivo zip ya esta creado
          //Verifico si el archivo ha sido creado
          if (file_exists($zipRips)) {
                $this->ActualizaRegistro("rips_consecutivos", "Ruta", $zipRips, "CuentaRIPS", $CuentaRIPS);
                return("OK");
          } else {
                return("Error");
          }
        }
    }
    
    /**
     * Fin Clase
     */
}
