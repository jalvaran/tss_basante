<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/SaludRips.class.php");


$obCon = new Rips($idUser);
if($_REQUEST["idAccion"]){
    $css =  new CssIni("id",0);
    $obRips = new Rips($idUser);
    switch ($_REQUEST["idAccion"]){
        case 1: //Subir el archivo, renombrarlo con la el numero del giro y guardarlo en la carpeta archivos
            $obRips->VaciarTabla("salud_pagos_temporal"); //Vacío la tabla de subida temporal
            $Separador=$obRips->normalizar($_REQUEST["CmbSeparador"]);
            $TipoGiro=$obRips->normalizar($_REQUEST["CmbTipoGiro"]);
            $FechaGiro=$obRips->normalizar($_REQUEST["TxtFechaGira"]);
            $FechaCargue=date("Y-m-d H:i:s");
            $destino="";
            
            if(!empty($_FILES['UpPago']['name'])){
                $Atras="../";
                $carpeta=$Atras."ArchivosTemporales/";
                opendir($carpeta);
                $NombreArchivo=str_replace(' ','_',$_FILES['UpPago']['name']);  
                $NombreArchivo=str_replace('%','_',$_FILES['UpPago']['name']); 
                move_uploaded_file($_FILES['UpPago']['tmp_name'],$carpeta.$NombreArchivo);
                
                $handle = fopen($carpeta.$NombreArchivo, "r");
                //print($carpeta.$NombreArchivo);
                if($Separador==1){
                    $SeparadorT=";"; 
                 }else{
                    $SeparadorT=",";  
                 }
                //$importer = new CsvImporter($carpeta.$NombreArchivo,true); 
                 //print($SeparadorT);
                $i=0;
                while (($data = fgetcsv($handle, 1000, $SeparadorT,'"', "#")) !== FALSE) {
                     //print_r($data);
                    $i++;
                    //$data[$i]=str_ireplace('"','',$data[$i]); 
                    //$data[$i]=str_replace('"','',$data[$i]); 
                    
                    if($i==2){
                        if($TipoGiro==1){
                            if(!isset($data[10])){
                                exit("El archivo no es válido");
                            }
                            $Giro=str_replace(".","",$data[10]);
                            $Giro=str_replace(",00","",$Giro);
                        }
                        if($TipoGiro==3 or $TipoGiro==2){
                            if(!isset($data[1])){
                                exit("El archivo no es válido");
                            }
                            $Giro="C1";
                        }
                       // print($Giro);
                        
                    }
                }
                fclose($handle);
                
                $NombreAR="AR".$Giro.".txt";
                $destinoAR="../archivos/".$NombreAR;
                copy($carpeta.$NombreArchivo,$destinoAR);
                unlink($carpeta.$NombreArchivo);
                
               // 
                                
            }
            
            //Verificamos si el archivo ya fue subido
            
            $DatosUploads=$obRips->DevuelveValores("salud_upload_control", "nom_cargue", $NombreAR);
            
            if($DatosUploads["id_upload_control"]==''){
                
                if(!empty($_FILES['UpSoporte']['name'])){
                    $Name='';
                    $info = new SplFileInfo($_FILES['UpSoporte']['name']);
                    $Extension=($info->getExtension());
                    $Atras="../";
                    $carpeta="SoportesSalud/SoportesAR/";
                    opendir($Atras.$Atras.$carpeta);
                    $Name=$NombreAR.".".$Extension;  
                    //$Name=str_replace('%','_',$_FILES['UpSoporte']['name']);  
                    $destino=$carpeta.$Name;
                    //print($destino);
                    move_uploaded_file($_FILES['UpSoporte']['tmp_name'],$Atras.$Atras.$destino);
                }
                
                $obRips->VaciarTabla("salud_subir_rips_pago_control");
                $Datos["ArchivoActual"]=$NombreAR;
                $Datos["idUser"]=$idUser;
                $Datos["Separador"]=$Separador;
                $Datos["Destino"]=$destino;
                $Datos["FechaGiro"]=$FechaGiro;
                $Datos["FechaCargue"]=$FechaCargue;
                $Datos["TipoGiro"]=$TipoGiro;
                $sql=$obRips->getSQLInsert("salud_subir_rips_pago_control", $Datos);
                $obRips->Query($sql);
                //print("entra a soporte1");
                
                
                print("OK");
                
            }else{
                $css->CrearNotificacionAzul("El archivo ya fue subido el $DatosUploads[fecha_cargue], por el usuario $DatosUploads[idUser]",16);
            }
            
            
        break;
        case 2: //insertar rips a tabla temporal
            $TipoGiro=$obRips->normalizar($_REQUEST["CmbTipoGiro"]);
            //print("Tipo de Giro".$TipoGiro);
            $DatosArchivoActual=$obRips->DevuelveValores("salud_subir_rips_pago_control", "ID", 1);
            if($TipoGiro==1 ){
                
                $obRips->InsertarRipsPagosAdres($DatosArchivoActual["ArchivoActual"],$DatosArchivoActual["Separador"], $DatosArchivoActual["FechaCargue"], $idUser,$DatosArchivoActual["Destino"],$DatosArchivoActual["FechaGiro"],$DatosArchivoActual["TipoGiro"], "");
                $obRips->AnaliceInsercionFacturasPagadasAdres("");
            }
            if($TipoGiro==3 or $TipoGiro==2){
               
                $obRips->InsertarRipsPagosAdresContributivoTemporal($DatosArchivoActual["ArchivoActual"],$DatosArchivoActual["Separador"], $DatosArchivoActual["FechaCargue"], $idUser,$DatosArchivoActual["Destino"],$DatosArchivoActual["FechaGiro"],$DatosArchivoActual["TipoGiro"], "");
                $sql="UPDATE salud_pagos_contributivo_temp pt INNER JOIN salud_archivo_facturacion_mov_generados mg ON mg.num_factura=pt.numero_factura 
                        SET pt.CodigoEps=mg.cod_enti_administradora, pt.FechaFactura=mg.fecha_factura, pt.FormaContratacion=mg.tipo_negociacion;";
                $obCon->Query($sql);
                $sql="INSERT INTO `salud_pagos_contributivo` SELECT * FROM `salud_pagos_contributivo_temp` as t1 
                        WHERE NOT EXISTS (SELECT 1 FROM `salud_pagos_contributivo` as t2 WHERE t1.`FechaPago`=t2.`FechaPago` and t1.`numero_factura`=t2.`numero_factura` AND t1.`NitEPS`=t2.`NitEPS`);"; 
                $obCon->Query($sql);
                
                $sql="INSERT INTO `salud_pagos_temporal` (`id_temp_rips_generados`, `Proceso`, `CodigoEPS`, `NombreEPS`, `FormaContratacion`, `Departamento`, `Municipio`, `FechaFactura`, `PrefijoFactura`, `NumeroFactura`, `ValorGiro`, `FechaPago`, `NumeroGiro`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`,`numero_factura`)
                SELECT '',Proceso,CodigoEps,NombreEPS,FormaContratacion,'','',FechaFactura,PrefijoFactura,NumeroFactura,ValorGiro,FechaPago,'','',fecha_cargue,idUser,Soporte,numero_factura FROM salud_pagos_contributivo WHERE Estado=0";
                $obCon->Query($sql);
                $obCon->update("salud_pagos_contributivo", "Estado", 1, "");
                $obCon->AjusteAutoIncrement("salud_pagos_contributivo", "ID", "");
                $obCon->VaciarTabla("salud_pagos_contributivo_temp");
                $TipoMovimiento="Contributivo";
                if($TipoGiro==2){
                    $TipoMovimiento="CuentaMaestra";
                }
                
                $obRips->AnaliceInsercionFacturasPagadasAdres("",$TipoMovimiento);
                //print("Subido");
            }
            print("OK");
            
        break;  
        case 3://Encuentre Facturas Pagadas con diferencia
            $obCon->EncuentreFacturasPagadasConDiferencia("");
            print("OK");
            
        break;
    
        case 4://Encuentre Facturas Pagas
            $obCon->EncuentreFacturasPagadas("");
            
            print("OK");
            
        break;
    
               
    }
    
}else{
    
    print("No se recibieron parametros");
}
    
    

?>