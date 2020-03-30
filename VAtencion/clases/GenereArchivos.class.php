<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class GenereArchivos extends conexion{
    public function ExportarTablaToCSV($Tabla,$statement,$Carpeta,$NombreArchivo,$Extension,$Separador,$Vector) {
        
        
        $nombre_archivo = $Carpeta.$NombreArchivo.$Extension;
        //Si existe el archivo lo borro
        if(file_exists($nombre_archivo)){
            unlink($nombre_archivo);
        }
        //Creo y abro el archivo
        if($archivo = fopen($nombre_archivo, "a")){
            $CamposShow=""; //Determina los campos que se mostraran
            $NumCampos=0;
            $Columnas=$this->ShowColums($Tabla);
            $Indice=$Columnas["Field"][0];
            $mensaje="";
            
            foreach($Columnas["Field"] as $Campo){
                $consulta= $this->ConsultarTabla("tablas_campos_control", "WHERE NombreTabla='$Tabla' AND Campo='$Campo'");
                $DatosCampo=$this->FetchArray($consulta);
                //$CamposShow="";
                if($DatosCampo["Visible"]=='' or $DatosCampo["Visible"]=='1'){
                    $mensaje.=$Campo.$Separador;
                    $CamposShow.="`$Campo`,";
                    $NumCampos++;
                }

            }
            fwrite($archivo,$mensaje."\n");
            $CamposShow=substr($CamposShow, 0, -1);
            $sql="SELECT $CamposShow FROM $statement ORDER BY $Indice DESC";
            $consulta=$this->Query($sql);
            while($DatosSentencia= $this->FetchArray($consulta)){
                $mensaje="";
                for($i=0;$i<$NumCampos;$i++){
                    $Dato=str_replace($Separador, " ", $DatosSentencia[$i]);
                    $mensaje.=$Dato.$Separador;
                }

                $mensaje=substr($mensaje, 0, -1);
                fwrite($archivo,$mensaje."\n");
            }
            
            fclose($archivo);
        }
       
    }
    
    //Fin Clases
}