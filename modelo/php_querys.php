<?php
/*
 * convierte registros en vectores y los analiza
 * 
 */
include_once 'php_conexion.php';
class php_query{
    
   //Agrupa datos de un vector
    function __construct(){
        
        $this->obCon=new db_conexion();
        
    }
    //almacena la informacion de la tabla una matriz Estilo $Registros[ID][Campo]
    public function TablaToMatriz($Tabla,$Columnas,$id,$Condicion,$Vector) {
        $Datos="";
        
        $sql="SELECT ";
        foreach($Columnas as $Campos){
           $sql .=" `$Campos`,"; 
        }
        $sql=substr($sql, 0, -1);
        
        $sql.=" FROM `$Tabla`  $Condicion";
        $Consulta=$this->obCon->Query($sql);
        while($DatosTabla=$this->obCon->FetchArray($Consulta)){
            $Cuenta=$DatosTabla[$id];
            foreach($Columnas as $Campo){
                $Registros[$Cuenta][$Campo]=$DatosTabla[$Campo];
                $Datos.='"'.$DatosTabla[$Campo].'";';
            }
            $Datos=substr($Datos, 0, -1);
            $Datos .= "\r\n";
        }
        return($Datos);
        
        
    }
    
    //obtiene los indices de una tabla
    public function Indices($Indice,$Tabla,$Condicion,$Vector) {
        $sql="SELECT `$Indice` FROM `$Tabla` $Condicion ";
        $Consulta=$this->obCon->Query($sql);
        $i=0;
        while($DatosIndice=$this->obCon->FetchArray($Consulta)){
            $IndicesRegistrados[$i]=$DatosIndice[$Indice];
            $i++;
        }
        return($IndicesRegistrados);
    }
    
    public function GenereCSV($FileName,$Datos,$Vector) {
        if($archivo = fopen($FileName, "a")){
            if(fwrite($archivo, $Datos)){
                echo "Balance creado correctament <a href='$FileName'>Abrir</a>";
            }
            else{
                echo "Ha habido un problema al crear el archivo";
            }

            fclose($archivo);
        }
    }
    
//Fin Clases
}

?>