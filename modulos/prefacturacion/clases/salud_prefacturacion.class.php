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
        $EdadCalculada=0;
        $Unidad=3;
        $NombreUnidad="DIAS";
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
    
    public function CrearFactura($idFactura,$Fecha,$NumeroFactura,$idResolucion, $TipoFactura, $idRegimenFactura,$ReferenciaTutela,$idTraza,$idReserva,$Observaciones, $idUser) {
        
        $tab="facturas";        
        $Datos["ID"]=$idFactura;        
        $Datos["Fecha"]=$Fecha;    
        $Datos["NumeroFactura"]=$NumeroFactura;
        $Datos["idResolucion"]=$idResolucion;    
        $Datos["TipoFactura"]=$TipoFactura; 
        $Datos["idRegimenFactura"]=$idRegimenFactura; 
        $Datos["ReferenciaTutela"]=$ReferenciaTutela; 
        $Datos["idTraza"]=$idTraza; 
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
        
        $sql="SELECT NumeroFactura,TipoDocumento,NumeroDocumento,idTraza,
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
                $mensaje.=$DatosConsulta["idTraza"].","; 
                $mensaje.="2,"; 
                $mensaje.=$DatosConsulta["idServicio"].","; 
                $mensaje.= substr(str_replace(")","", str_replace("(","", $DatosConsulta["DescripcionServicio"])),0,50).",";                
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
    
    public function AnularFactura($idFactura,$TipoAnulacion,$Observaciones,$idUser) {
        $tab="facturas";
        if($TipoAnulacion=="Anulada"){
            $Estado=10;
        }else{
            $Estado=11;
        }
        $this->ActualizaRegistro($tab, "Estado", $Estado, "ID", $idFactura);
        $sql="UPDATE prefactura_reservas_citas t1 INNER JOIN facturas_items t2 set t1.Estado='3' WHERE t2.idFactura='$idFactura' AND t2.idCita=t1.ID";
        $this->Query($sql);
        
        $Datos["Observaciones"]=$Observaciones;
        $Datos["TipoMovimiento"]=$TipoAnulacion;
        $Datos["idUser"]=$idUser;
        $Datos["idFactura"]=$idFactura;
        $Datos["Created"]=date("Y-m-d H:i:s");
        $sql= $this->getSQLInsert("facturas_anulaciones", $Datos);
        $this->Query($sql);
        
        
    }
    
    public function AnularReserva($idReserva,$Observaciones,$idUser) {
        $tab="prefactura_reservas";
        
        $Estado=10;
        
        $this->ActualizaRegistro($tab, "Estado", $Estado, "ID", $idReserva);
                
        $Datos["Observaciones"]=$Observaciones;        
        $Datos["idUser"]=$idUser;
        $Datos["idReserva"]=$idReserva;
        $Datos["Created"]=date("Y-m-d H:i:s");
        $sql= $this->getSQLInsert("prefactura_reservas_anulaciones", $Datos);
        $this->Query($sql);
        
        
    }
    
    function cree_documento_electronico_desde_factura($factura_id){
        $usuario_id=$_SESSION["idUser"];
        $datos_factura=$this->DevuelveValores("facturas", "ID", $factura_id);
        $datos_reserva=$this->DevuelveValores("prefactura_reservas", "ID", $datos_factura["idReserva"]);
        $datos_paciente=$this->DevuelveValores("prefactura_paciente", "ID", $datos_reserva["idPaciente"]);
        $datos_eps=$this->DevuelveValores("salud_eps", "cod_pagador_min", $datos_paciente["CodEPS"]);
        $datos_tercero=$this->DevuelveValores("terceros", "identificacion", $datos_eps["nit"]);
        $empresa_db=DB;
        $datos_resolucion=$this->DevuelveValores("empresa_resoluciones", "ID", 1);
        
        if($datos_resolucion["estado"]==2){
            exit("E1;La resolución seleccionada ya fué completada");
        }
        if($datos_resolucion["estado"]==3){
            exit("E1;La resolución seleccionada ya está vencida");
        }
        $prefijo_resolucion=$datos_resolucion["prefijo"];
        $sql="SELECT MAX(numero) as numero FROM $empresa_db.documentos_electronicos WHERE tipo_documento_id='1' and resolucion_id='1' and prefijo='$prefijo_resolucion'";
        $datos_validacion=$this->FetchAssoc($this->Query($sql));
        if($datos_validacion["numero"]=='' or $datos_validacion["numero"]==0){
            $datos_validacion["numero"]=$datos_resolucion["proximo_numero_documento"]-1;
        }
        $numero=$datos_validacion["numero"]+1;
        if($numero>$datos_resolucion["hasta"]){
            exit("E1;la resolución no ya fué completada");
        }
        $notas=$this->limpiar_cadena($datos_factura["Observaciones"]);
        $orden_compra="";
        
        $this->crear_items_inventario_desde_items_factura($factura_id);
        
        $documento_electronico_id=$this->registra_documento_electronico(DB, 1, 1, $datos_resolucion["prefijo"],$numero,$datos_tercero["ID"],$usuario_id,$notas,$orden_compra,2,"");
        $this->copiar_items_factura_items_documento(DB, $factura_id, $documento_electronico_id);
    }
   
    
    public function copiar_items_factura_items_documento($db,$factura_id,$documento_electronico_id) {
        $usuario_id=$_SESSION["idUser"];
        $sql="INSERT INTO $db.documentos_electronicos_items (`documento_electronico_id`,`item_id`,`valor_unitario`,`cantidad`,`subtotal`,`impuestos`,`total`,`porcentaje_iva_id`,`usuario_id`) 
                SELECT '$documento_electronico_id',(SELECT ID FROM inventario_items_general t2 WHERE t2.Referencia=t1.idServicio LIMIT 1),t1.Valor,'1',t1.Valor,'0',t1.Valor,'1','$usuario_id' 
                FROM $db.facturas_items t1 WHERE idFactura='$factura_id' 
                
                ";
        $this->Query($sql);
    }
    
    
    function crear_items_inventario_desde_items_factura($factura_id){
        $usuario_id=$_SESSION["idUser"];
        $sql="SELECT t1.* FROM catalogo_servicios t1 INNER JOIN facturas_items t2 ON t1.CUPS=t2.idServicio WHERE t2.idFactura='$factura_id' AND NOT EXISTS (SELECT 1 FROM inventario_items_general t3 WHERE t3.Referencia=t1.CUPS) GROUP BY t2.idServicio;";
        $Consulta= $this->Query($sql);
        
        while($datos_consulta=$this->FetchAssoc($Consulta)){
            $this->crear_item_general($datos_consulta["ID"], $datos_consulta["CUPS"], $datos_consulta["Descripcion"], $datos_consulta["TarifaSencilla"], 1, $usuario_id);
        }
    }
    
    function crear_item_general($ID,$Referencia,$Descripcion,$Precio,$PorcentajeIVA,$usuario_id){
        $Datos["ID"]=$ID;        
        $Datos["Referencia"]=$Referencia;
        $Datos["Descripcion"]=$Descripcion;
        $Datos["Precio"]=$Precio;
        $Datos["porcentajes_iva_id"]=$PorcentajeIVA;
        $Datos["usuario_id"]=$usuario_id;
         
        $sql= $this->getSQLInsert("inventario_items_general", $Datos);
        $this->Query($sql);
    }
    
    /*
     * Clases de facturacion electronica
     */
    
    
    public function limpiar_cadena($string) {
        $string = htmlentities($string);
        $string = preg_replace('/\&(.)[^;]*;/', '', $string);
        $string = str_replace('\n', '', $string);
        $string = trim(preg_replace('/[\r\n|\n|\r]+/', '', $string));
        return $string;
    }
            
    public function registra_documento_electronico($db,$resolucion_id,$tipo_documento_id,$prefijo,$numero,$tercero_id,$usuario_id,$notas,$orden_compra,$forma_pago,$documento_asociado_id) {
        $tab="$db.documentos_electronicos";
        if($tipo_documento_id==1){
            $prefijo_llave="fv_";
        }
        if($tipo_documento_id==5){
            $prefijo_llave="nc_";
        }
        if($tipo_documento_id==6){
            $prefijo_llave="nd_";
        }
        
        $documento_electronico_id=$this->getUniqId($prefijo_llave);
        $Datos["documento_electronico_id"]=$documento_electronico_id;
        $Datos["fecha"]=date("Y-m-d");
        $Datos["hora"]=date("H:i:s");
        $Datos["tipo_documento_id"]=$tipo_documento_id;
        $Datos["resolucion_id"]=$resolucion_id;
        $Datos["prefijo"]=$prefijo;
        $Datos["numero"]=$numero;
        $Datos["tercero_id"]=$tercero_id;
        $Datos["usuario_id"]=$usuario_id;
        $Datos["notas"]=$notas;  
        $Datos["orden_compra"]=$orden_compra;  
        $Datos["forma_pago"]=$forma_pago;  
        $Datos["documento_asociado_id"]=$documento_asociado_id;  
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        return($documento_electronico_id);
    }
    
    
    public function crear_vista_documentos_electronicos($db) {
        $principalDb=DB;
        $sql="DROP VIEW IF EXISTS `vista_documentos_electronicos`;";
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
        
        $sql="CREATE VIEW vista_documentos_electronicos AS
                SELECT t1.*, 
                    
                    (SELECT SUM(subtotal) from documentos_electronicos_items t4 where t4.documento_electronico_id=t1.documento_electronico_id LIMIT 1 ) AS subtotal_documento,
                    (SELECT SUM(impuestos) from documentos_electronicos_items t4 where t4.documento_electronico_id=t1.documento_electronico_id LIMIT 1 ) AS impuestos_documento,
                    (SELECT SUM(total) from documentos_electronicos_items t4 where t4.documento_electronico_id=t1.documento_electronico_id LIMIT 1 ) AS total_documento,
                    (SELECT name FROM $principalDb.api_fe_tipo_documentos t3 WHERE t3.ID=t1.tipo_documento_id LIMIT 1) AS nombre_tipo_documento,
                    (SELECT razon_social FROM terceros t4 WHERE t4.ID=t1.tercero_id LIMIT 1) AS nombre_tercero, 
                    (SELECT identificacion FROM terceros t4 WHERE t4.ID=t1.tercero_id LIMIT 1) AS nit_tercero,
                    (SELECT CONCAT(Nombre,' ',Apellido) FROM $principalDb.usuarios t5 WHERE t5.idUsuarios=t1.usuario_id LIMIT 1) AS nombre_usuario,
                    (SELECT CONCAT(prefijo,'-',numero) from documentos_electronicos t5 where t5.documento_electronico_id=t1.documento_asociado_id LIMIT 1 ) AS documento_asociado,
                    (SELECT GROUP_CONCAT(t5.Descripcion) from inventario_items_general t5 where exists (SELECT 1 FROM documentos_electronicos_items t7 WHERE t7.documento_electronico_id=t1.documento_electronico_id and t7.item_id=t5.ID) ) as nombre_items  
                    
                FROM `documentos_electronicos` t1 ORDER BY updated DESC ";
        
        $this->QueryExterno($sql, HOST, USER, PW, $db, "");
    }
    
    /**
     * Fin Clase
     */
}
