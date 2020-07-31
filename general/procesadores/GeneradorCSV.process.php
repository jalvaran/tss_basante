<?php 
session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}

if(isset($_REQUEST["Opcion"])){
    $myPage="GeneradorCSV.php";
    include_once("../clases/administrador.class.php");
    
       
    $idUser=$_SESSION['idUser'];
    $obCon = new Administrador($idUser);
    
    $DatosRuta=$obCon->DevuelveValores("configuracion_general", "ID", 10000);
    $OuputFile=$DatosRuta["Valor"];
    //$Link1=substr($OuputFile, -17);
    $Link=$OuputFile;
    //print($Link);
    $a='"';
    $Enclosed=" ENCLOSED BY '$a' ";
    $Opcion=$_REQUEST["Opcion"];
    
    switch ($Opcion){
        case 1: //Exportar Facturas Pagadas
            if(file_exists($Link)){
                unlink($Link);
            }else{
                //print($Link." No existe");
            }
            
            
            $condicion=$obCon->normalizar(urldecode($_REQUEST["c"]));
            $Separador=$obCon->normalizar($_REQUEST["sp"]);
            if($Separador==1){
                $Separador=';';
            }else{
                $Separador=',';
            }
            $sqlColumnas="SELECT 'ID','Fecha','Documento','Nombre','idServicio','Descripcion',"
                    . "'idRecorrido','Valor'";
            $CamposShow=" t1.ID,t1.Fecha,t1.Documento,t1.Nombre,t1.idServicio,t1.Descripcion,"
                    . "t1.idRecorrido,ROUND(t1.Valor) ";
            $sqlColumnas.=" UNION ALL ";
            
            //print($Indice);
            $sql=$sqlColumnas."SELECT $CamposShow FROM vista_liquidacion_colaboradores t1 $condicion;";
            $Consulta=$obCon->Query($sql);
            if($archivo = fopen($Link, "a")){
                $mensaje="";
                $r=0;
                while($DatosExportacion= $obCon->FetchArray($Consulta)){
                    $r++;
                    for ($i=0;$i<count($DatosExportacion);$i++){
                        $Dato="";
                        if(isset($DatosExportacion[$i])){
                            $Dato=$DatosExportacion[$i];
                        }
                        $mensaje.='"'.str_replace(";", "", $Dato).'";'; 
                    }
                    $mensaje=substr($mensaje, 0, -1);
                    $mensaje.="\r\n";
                    if($r==1000){
                        $r=0;
                        fwrite($archivo, $mensaje);
                        $mensaje="";
                    }
                }
                

                fwrite($archivo, $mensaje);
                fclose($archivo);
                unset($mensaje);
                unset($DatosExportacion);
            }
            print("<a href='$Link' target='_top'><img src='../../images/download.gif'>Download</img></a>");
            break;
        }
}else{
    print("No se recibió parametro de opcion");
}

?>