<?php
//Se incluyen la las clases que manejaran la conexion a la base de datos
include_once 'php_mysql_i.php';
/**
 * Clase que se conectará a la base de datos para funciones especiales
 */
class conexion extends db_conexion{
    
    private $idUser;        //id del usuario que ingresa
    private $NombreUser;    //nombre del usuario que inicia sesion
    private $TipoUser;      //tipo de usuario que inicia sesion
    /**
     * Clase constructora que consulta la tabla usuarios y se traen los datos relevantes
     * @param type $idUser -> id del usuario
     */
    function __construct($idUser){
        $idUser=$this->normalizar($idUser); //se quitan posibles inyecciones de sql		
        $consulta =$this->Query("SELECT Nombre, TipoUser FROM usuarios WHERE idUsuarios='$idUser'");
        $Datos = $this->FetchArray($consulta);
        $this->NombreUser = $Datos['Nombre'];
        $this->idUser=$idUser;
        $this->TipoUser=$Datos['TipoUser'];

    }
        
    /**
     * Verifica los permisos de los usuarios
     * @param type $VectorPermisos -> Trae la informacion con el nombre de la pagina que está visitando el usuario para verificar si está autorizado
     * @return boolean -> retorna falso si no está autorizado o verdadero si está autorizado 
     */
    public function VerificaPermisos($VectorPermisos) {
        if($this->TipoUser<>"administrador"){
            $Page=$VectorPermisos["Page"];
            $Consulta=  $this->ConsultarTabla("paginas_bloques", " WHERE Pagina='$Page' AND TipoUsuario='$this->TipoUser' AND Habilitado='SI'");
            $PaginasUser=  $this->FetchArray($Consulta);
            if($PaginasUser["Pagina"]==$Page){
                return true;
            }
            return false;
        }
        return true;
    }

   //Funcion para Crear los backups
     public function CrearBackup($idServer,$VectorBackup){
        $host=$VectorBackup["LocalHost"];
        $user=$VectorBackup["User"];
        $pw=$VectorBackup["PW"];
        $db=$VectorBackup["DB"];
        $Tabla=$VectorBackup["Tabla"];
        $AutoIncrement=$VectorBackup["AutoIncrement"];
        $sqlCrearTabla="";
        $sql="";
                        
        $VectorAS["f"]=0;
        $DatosServer=$this->DevuelveValores("servidores", "ID", $idServer); 
        $FechaSinc=date("Y-m-d H:i:s");
        //$Condicion=" WHERE ServerSincronizado='0000-00-00 00:00:00'";
        
        $CondicionUpdate=" WHERE Sync = '0000-00-00 00:00:00' OR Sync<>Updated";
        //$CondicionUpdate="";
        if($AutoIncrement<>0){
            $VectorAS["AI"]=$AutoIncrement; //Indicamos que la tabla tiene id con autoincrement
        }
        $sql=$this->ArmeSqlReplace($Tabla, $db, $CondicionUpdate,$DatosServer["DataBase"],$FechaSinc, $VectorAS);
        $Existe=  $this->DevuelveValores("plataforma_tablas", "Nombre", $Tabla);
        if(empty($Existe["Nombre"])){
            $sqlCrearTabla=$this->ArmeSQLCopiarTabla($Tabla, $db, $DatosServer["DataBase"], $VectorAS);
                    
        }
        $VectorCon["Fut"]=0;               
        
        if(empty($sql) AND !empty($Existe["Nombre"])){
            return("SA");
        }
        
        
        
        //$this->ConToServer($DatosServer["IP"], $DatosServer["Usuario"], $DatosServer["Password"], $DatosServer["DataBase"], $VectorCon);
        
        if(!empty($sqlCrearTabla)){
            $this->QueryExterno($sqlCrearTabla, $DatosServer["IP"], $DatosServer["Usuario"], $DatosServer["Password"], $DatosServer["DataBase"], "");
            //$this->Query($sqlCrearTabla);
        }
        if(!empty($sql)){
            $this->QueryExterno($sql, $DatosServer["IP"], $DatosServer["Usuario"], $DatosServer["Password"], $DatosServer["DataBase"], "");
           
            //$this->Query($sql);
        }
        //$this->ConToServer($host, $user, $pw, $db, $VectorCon); 
        $sqlUp="UPDATE $Tabla SET Sync='$FechaSinc', Updated='$FechaSinc' $CondicionUpdate";
        $this->Query($sqlUp);
        if(empty($Existe["Nombre"])){
            $sqlInsertTabla="INSERT INTO plataforma_tablas (Nombre) VALUES ('$Tabla')";
            $this->Query($sqlInsertTabla);
        }
        return("Backup Realizado a la tabla $Tabla");
        //return("<pre>$sql</pre>");
         
     }
     
     /*
      * Agregar una Columna a una tabla
      */
     public function AgregarColumnaTabla($Tabla,$NombreCol,$Tipo,$Predeterminado,$Atributos,$Vector){
         $sql="ALTER TABLE `$Tabla` ADD `$NombreCol` $Tipo $Atributos NOT NULL $Predeterminado";
         $this->Query($sql);
        
     }
     
     /*
      * Agregar una Columna a una tabla
      */
     public function CreeColumnasBackup($Tabla,$DataBase,$Vector){
         
        $ColumnaCol=$this->MostrarColumnas($Tabla, $DataBase);
        foreach($ColumnaCol as $NombreCol){
            if($NombreCol<>'Updated'){
                $Vector["F"]="";
                $this->AgregarColumnaTabla($Tabla, 'Updated', 'TIMESTAMP', 'CURRENT_TIMESTAMP', 'on update CURRENT_TIMESTAMP', $Vector);
            }
        }
     }
     
     //Funcion para armar un sql de los datos en una tabla de acuerdo a una condicion
    
    public function ArmeSqlInsert($Tabla,$db,$Condicion,$DataBaseDestino,$FechaSinc, $VectorAS) {
        $ai=0;
        if(isset($VectorAS["AI"])){
            $ai=1;
        }
            
        
        
        ////Armo el sql de los items
        $tb=$Tabla;
        //$tb="librodiario";
        $Columnas=  $this->MostrarColumnas($tb,$db);
        $Leng=count($Columnas);
        
        $sql=" REPLACE INTO `$DataBaseDestino`.`$tb` (";
        $i=0;
        foreach($Columnas as $NombreCol){
            if($NombreCol=="ServerSincronizado"){
                $idServerCol=$i;
            }
            $sql.="`$NombreCol`,";
            $i++;
        }
        $sql=substr($sql, 0, -1);
        $sql.=") VALUES (";
        $consulta=$this->ConsultarTabla($tb, $Condicion);
        if($this->NumRows($consulta)){
        while($Datos =  $this->FetchArray($consulta)){
            
            for ($i=0;$i<$Leng;$i++){
                $DatoN=  $this->normalizar($Datos[$i]);
                if($i==0 and $ai==1){
                   $sql.="'',"; 
                }else{
                    
                    if($i==$idServerCol){
                       $sql.="'$FechaSinc',"; 
                    }else{
                       $sql.="'$DatoN',";
                    }
                }   
               
            }
            $sql=substr($sql, 0, -1);
            $sql.="),(";
            
        }
        $sql=substr($sql, 0, -2);
        $sql.="; ";
        }else{
           $sql=""; 
        }
        
        
        return($sql);
    }
    
    //Funcion para armar un sql de los datos en una tabla de acuerdo a una condicion
    
    public function ArmeSQLCopiarTabla($Tabla,$db,$DataBaseDestino, $VectorAS) {
        $ai=0;
        if(isset($VectorAS["AI"])){
            $ai=1;
        }
        $TablaOrigen=$Tabla;
        $TablaDestino=$Tabla;
        if(isset($VectorAS["TablaDestino"])){
            $TablaDestino=$VectorAS["TablaDestino"];
        }
        
        //Armo la informacion de la tabla
        $VectorE["F"]=0;
        //$sql=" SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
          //      SET time_zone = '+00:00';\r";
        
        $sql="CREATE TABLE IF NOT EXISTS `$DataBaseDestino`.`$TablaDestino` (\r";
        $Datos=$this->MuestraEstructura($Tabla, $db, $VectorE);
        $PrimaryKey="";
        $UniqueKey="";
        while($Estructura=$this->FetchArray($Datos)){
            $Comentarios="";
            
            if(!empty($Estructura["COLUMN_COMMENT"])){
                $Comentarios=" COMMENT '$Estructura[COLUMN_COMMENT]'";
            }
            if($Estructura["COLUMN_KEY"]=="PRI"){
                $PrimaryKey="PRIMARY KEY (`$Estructura[COLUMN_NAME]`),";
            }
            $Nullable="";
            if($Estructura["IS_NULLABLE"]=="NO"){
                $Nullable="NOT NULL";
            }
            if($Estructura["COLUMN_KEY"]=="UNI"){
                $UniqueKey="UNIQUE KEY (`$Estructura[COLUMN_NAME]`),";
            }
            if($Estructura["COLUMN_KEY"]=="MUL"){
                $UniqueKey="KEY `$Estructura[COLUMN_NAME]` (`$Estructura[COLUMN_NAME]`),";
            }
            $Collaction="";
            if(!empty($Estructura["COLLATION_NAME"])){
                $Collaction="COLLATE ".$Estructura["COLLATION_NAME"];
            }
            $Defecto="";
            if(!empty($Estructura["COLUMN_DEFAULT"])){
                if($Estructura["COLUMN_DEFAULT"]=="CURRENT_TIMESTAMP"){
                    $ValorDefecto="CURRENT_TIMESTAMP";
                }else{
                    $ValorDefecto="'$Estructura[COLUMN_DEFAULT]'";
                }
                $Defecto=" DEFAULT $ValorDefecto";
            }
            
            $sql.="`$Estructura[COLUMN_NAME]` $Estructura[COLUMN_TYPE] $Nullable $Collaction $Defecto $Estructura[EXTRA] $Comentarios,";
        }
        $sql.="$PrimaryKey$UniqueKey";
        $sql=substr($sql, 0, -1);
        $sql.="\r";
        $sql.=") ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci; \r";
        return ($sql);
    }
    //Funcion para armar un sql de los datos en una tabla de acuerdo a una condicion
    
    public function ArmeSqlReplace($Tabla,$db,$Condicion,$DataBaseDestino,$FechaSinc, $VectorAS) {
        $ai=0;
        if(isset($VectorAS["AI"])){
            $ai=1;
        }
        $TablaOrigen=$Tabla;
        $TablaDestino=$Tabla;
        if(isset($VectorAS["TablaDestino"])){
            $TablaDestino=$VectorAS["TablaDestino"];
        }
        
        
        $Columnas=  $this->MostrarColumnas($TablaOrigen,$db);
        $Leng=count($Columnas);
        
        $sql="\r REPLACE INTO `$DataBaseDestino`.`$TablaDestino` (";
        $i=0;
        foreach($Columnas as $NombreCol){
            if($NombreCol=="Sync"){
                $idServerCol=$i;
            }
            $sql.="`$NombreCol`,";
            $i++;
        }
        $sql=substr($sql, 0, -1);
        $sql.=") VALUES (";
        $ConsultaParcial=$sql;
        $consulta=$this->ConsultarTabla($TablaOrigen, $Condicion);
        if($this->NumRows($consulta)){
            $z=0;
        while($Datos =  $this->FetchArray($consulta)){
            $z++;
            for ($i=0;$i<$Leng;$i++){
                $DatoN=  $this->normalizar($Datos[$i]);
                if($i==0 and $ai==1){
                   $sql.="'',"; 
                }else{
                    
                    if($i==$idServerCol){
                       $sql.="'$FechaSinc',"; 
                    }else{
                       $sql.="'$DatoN',";
                    }
                }   
               
            }
            $sql=substr($sql, 0, -1);
            $sql.="),(";
            //if($z==500){
            //    $sql=substr($sql, 0, -2);
            //    $sql.="; ";
            //    $sql.=$ConsultaParcial;
            //    $z=0;
            //}    
        }
        $sql=substr($sql, 0, -2);
        $sql.="; ";
        }else{
           $sql=""; 
        }
        
        
        return($sql);
    }
    
    /*
      * Muestra la estructura de una tabla
      */
     public function MuestraEstructura($Tabla,$DataBase,$Vector){
         
         $sql="SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$DataBase' AND TABLE_NAME = '$Tabla' ";
         $Datos=$this->Query($sql);
         //$Tablas=$this->FetchArray($Datos);
         return ($Datos);
     }
     /**
      * Funcion para calcular la diferencia entre dos fechas
      * @param type $FechaInicial
      * @param type $FechaFinal
      * @param type $Vector
      * @return type
      */
     public function CalculeDiferenciaFechas($FechaInicial,$FechaFinal,$Vector) {
        $datetime1 = date_create($FechaInicial);
        $datetime2 = date_create($FechaFinal);
        $interval = date_diff($datetime1, $datetime2);
        $Resultados["Dias"]=$interval->format('%a');
        
        return($Resultados);
    }
    
    //Quitar acentos y eñes
    public function QuitarAcentos($str) {
        $no_permitidas= array ('"',"`","´","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("","","","a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$str);
        return $texto;
        
    }
    
    public function getUniqId($prefijo='') {
        return (str_replace(".","",uniqid($prefijo, true)));
    }
    
    public function SumeDiasFecha($Fecha,$Dias){		
        
        $nuevafecha = date('Y-m-d', strtotime($Fecha) + 86400);
        $nuevafecha = date('Y-m-d', strtotime("$Fecha + $Dias day"));

        return($nuevafecha);

    }
    
}
?>