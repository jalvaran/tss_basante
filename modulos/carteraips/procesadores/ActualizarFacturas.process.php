<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/ActualizarFacturas.class.php");
include_once("../../../VSalud/clases/SaludRips.class.php");
if( !empty($_REQUEST["Accion"]) ){
    $obCon = new ActualizacionFacturas($idUser);
    $obRips = new Rips($idUser);
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Actualizar una factura
            
            $NumeroFacturaAnterior=$obCon->normalizar($_REQUEST["TxtNumeroFacturaEdit"]);
            $NumeroFacturaNueva=$obCon->normalizar($_REQUEST["TxtFacturaNueva"]);
            $Observaciones=$obCon->normalizar($_REQUEST["TxtObservacionesEdicioFactura"]);
            $sql="SELECT num_factura FROM salud_archivo_facturacion_mov_generados WHERE num_factura='$NumeroFacturaNueva'";
            $Consulta=$obCon->Query($sql);
            $DatosFactura=$obCon->FetchAssoc($Consulta);
            if($DatosFactura["num_factura"]<>''){
                exit("E1;Error: El número de Factura $NumeroFacturaNueva ya existe en el AF");
            }
                     
            $obCon->ActualizarFactura($NumeroFacturaAnterior, $NumeroFacturaNueva, $Observaciones, $idUser);
            print("OK;La Factura $NumeroFacturaAnterior Fue reemplazada por la $NumeroFacturaNueva");
            
        break; //fin caso 1
        
        case 2: //se recibe el archivo
            
            //$DatosCargas=$obCon->DevuelveValores("ips", "NIT", $CmbIPS);
            //$db=$DatosCargas["DataBase"];
            $obCon->VaciarTabla("temporal_actualizacion_facturas");
            $destino='';
            $keyArchivo="ActFacts";
            $Extension="";
            if(!empty($_FILES['UpActualizaciones']['name'])){
                
                $info = new SplFileInfo($_FILES['UpActualizaciones']['name']);
                $Extension=($info->getExtension());  
                if($Extension=='xls' or $Extension=='xlsx'){
                    $carpeta="../../../SoportesSalud/ActualizacionFacturas/";
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777);
                    }
                    opendir($carpeta);                
                    $destino=$carpeta.$keyArchivo.".".$Extension;
                    $NombreArchivo=$keyArchivo.".".$Extension;
                    move_uploaded_file($_FILES['UpActualizaciones']['tmp_name'],$destino);
                    
                }else{
                    exit("E1;Error el archivo debe ser tipo xls o xlsx");
                }
            }else{
                exit("E1;No se envió ningún archivo");
                
            }
            
            print("OK;Archivo Recibido;$destino;$Extension");   
        break;//Fin caso 2
        
        case 3://Lee el archivo y lo sube a la temporal
            
            
            $RutaArchivo=$obCon->normalizar($_REQUEST["RutaArchivo"]);
            $Extension=$obCon->normalizar($_REQUEST["Extension"]);
            
            $keyArchivo="ActFacts";
            $obCon->LeerCargarTemporal($keyArchivo,$RutaArchivo,$Extension,$idUser);
            print("OK;Archivo cargado y listo para analizar");
            
        break; //fin caso 3  
    
        case 4://Validar Datos en temporal
                     
           $sql="SELECT count(t1.FacturaAnterior) as TotalRepeticiones FROM temporal_actualizacion_facturas t1 INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t2.num_factura=t1.FacturaNueva";
           $Consulta=$obCon->Query($sql); 
           $DatosRepetidos=$obCon->FetchAssoc($Consulta);
           $TotalDuplicados=$DatosRepetidos["TotalRepeticiones"];
           if($TotalDuplicados>0){
               exit("E1;Error: En la columna B del archivo hay $TotalDuplicados Facturas que ya existen en la cartera de la IPS");
           }
           
           $sql="SELECT COUNT(*) Total FROM temporal_actualizacion_facturas GROUP BY FacturaNueva HAVING COUNT(*) > 1";
           $Consulta=$obCon->Query($sql); 
           $DatosRepetidos=$obCon->FetchAssoc($Consulta);
           $TotalDuplicados=$DatosRepetidos["Total"];
           if($TotalDuplicados>0){
               exit("E1;Error: En la columna B del archivo hay $TotalDuplicados Facturas repetidas, los registros de esta columna deben ser únicos");
           }
           print("OK;Registros validados");
        break;    
        
        case 5:// actualizar facturas
            
            $sql="SELECT * FROM temporal_actualizacion_facturas";
            $Consulta=$obCon->Query($sql);
            while($DatosFacturas=$obCon->FetchAssoc($Consulta)){
                $obCon->ActualizarFactura($DatosFacturas["FacturaAnterior"], $DatosFacturas["FacturaNueva"], $DatosFacturas["Observaciones"], $idUser);
            }
           
            print("OK;Facturas Actualizadas");
            
            
        break; //Fin caso 5
        
        case 6:// actualizar facturas
            
            $obRips->EncuentreFacturasPagadasConDiferencia("");           
            print("OK;Facturas Pagas con diferencia encontradas");
            
        break; //Fin caso 6
    
        case 7:// actualizar facturas
            
            $obRips->EncuentreFacturasPagadas("");           
            print("OK;Facturas Pagas Encontradas");
            
        break; //Fin caso 7
    
        
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>