<?php 
$myPage="CreePDFFromTabla.php";
include_once("../sesiones/php_control.php");
include_once("procesadores/ExportarToPDF.Conf.php");
$obVenta = new conexion($idUser);
$obTabla = new Tabla($db);
$Fecha="Y-m-d";
if(isset($_REQUEST["BtnVerPDF"])){
    $statement= urldecode($_REQUEST["TxtL"]);
    $Tabla=base64_decode($_REQUEST["TxtT"]);
    $TablaConfig=$Tabla;
    $Tabla2=substr($Tabla, 0, 26);
    $TituloActa="";
    $FirmasActa="";
    if($Tabla2=="titulos_listados_promocion"){
        $TablaConfig=$Tabla2;
        $TituloActa="<br>
<pre>                                               <strong>ACTA DE ENTREGA DE TITULOS</strong>

Por medio del presente documento se realiza la entrega de los títulos que se relacionan a continuacion:  
</pre>";
        $FirmasActa="<br><br><pre>
        Recibe                                                      Entrega
        
        Nombre: ____________________________                        Nombre:    ____________________________
        
        Cedula: ____________________________                        Cedula:    ____________________________
</pre>";
    }
    $Back="#CEE3F6";
    $html='
            <table cellspacing="1" cellpadding="2" border="0"  align="center" >
            <tr style="border-bottom: 1px solid #ddd;background-color: '.$Back.';"> 
        ';
    
    $Vector["Tabla"]=$Tabla;
    $Columnas=$obTabla->ColumnasInfo($Vector);
    foreach ($Columnas["Field"] as $NombreCol){
        $consulta=$obVenta->ConsultarTabla("tablas_campos_control", "WHERE NombreTabla='$Tabla' AND Campo='$NombreCol'");
        $DatosCampo=$obVenta->FetchArray($consulta);
        if($DatosCampo["Habilitado"]=='' or $DatosCampo["Habilitado"]=='1'){
            if($DatosCampo["Visible"]=='' or $DatosCampo["Visible"]=='1'){
                if(!isset($Vector[$TablaConfig]["Excluir"][$NombreCol])){
                    if($NombreCol<>"Updated" and $NombreCol<>"Sync"){
                        $html.="<td><strong>$NombreCol </strong></td>";
                    }
                } 
            }
        }
            
    }
    $html.="</tr>";
    $sql="SELECT * FROM $statement LIMIT 500";
    $Consulta=$obVenta->Query($sql);
    
    $h=1;
    while($DatosTabla=$obVenta->FetchArray($Consulta)){
        if($h==0){
            $Back="#f2f2f2";
            $h=1;
        }else{
            $Back="white";
            $h=0;
        }
        $html.='<tr align="rigth" border="0" style="border-bottom: 1px solid #ddd;background-color: '.$Back.';"> ';
        
        foreach ($Columnas["Field"] as $NombreCol){
            $consulta=$obVenta->ConsultarTabla("tablas_campos_control", "WHERE NombreTabla='$Tabla' AND Campo='$NombreCol'");
            $DatosCampo=$obVenta->FetchArray($consulta);
            if($DatosCampo["Habilitado"]=='' or $DatosCampo["Habilitado"]=='1'){
                if($DatosCampo["Visible"]=='' or $DatosCampo["Visible"]=='1'){
                    if(!isset($Vector[$TablaConfig]["Excluir"][$NombreCol])){
                        if( $NombreCol<>"Updated" and $NombreCol<>"Sync"){
                            $html.="<td>$DatosTabla[$NombreCol] </td>";
                        }
                    } 
                }
            }
               
        }
                
        $html.="</tr>";      
        
    }
    
     
    
    $html.="</table>";
    //print($html);
    $obTabla->PDF_Ini("Export",7,"");
    $obTabla->PDF_Encabezado($Fecha,1,21,"");
    $obTabla->PDF_Write("<br><br>");
    if($TituloActa==""){
        $obTabla->PDF_Write("<div><h2>Datos de $Tabla</h2></div>");
    }
    
    $obTabla->PDF_Write($TituloActa."".$html."".$FirmasActa);
    $obTabla->PDF_Output("DatosExportados");
}

?>