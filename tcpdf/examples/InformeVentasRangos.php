<?php

include("../../modelo/php_conexion.php");
include("../../modelo/php_tablas.php");

////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////
$VerFacturas=0;
$obVenta = new conexion(1);
$obTabla = new Tabla($db);
$fecha=date("Y-m-d");
$FechaIni = $obVenta->normalizar($_POST["TxtFechaIniRango"]);
$FechaFinal = $obVenta->normalizar($_POST["TxtFechaFinRango"]);
$FechaCorte = $obVenta->normalizar($_POST["TxtFechaCorteRangos"]);
$Nivel=$obVenta->normalizar($_POST["CmbNivel"]);

$TipoReporte=$_POST["CmbTipoReporteRangos"];

$idFormatoCalidad=16;

if($TipoReporte=="Corte"){
    $CondicionFecha1=" FechaFactura <= '$FechaCorte' ";
    $CondicionFecha2=" Fecha <= '$FechaCorte' ";
    $Rango="Corte a $FechaCorte";
}else{
    $CondicionFecha1=" FechaFactura >= '$FechaIni' AND FechaFactura <= '$FechaFinal' ";
    $CondicionFecha2=" Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ";
    $Rango="De $FechaIni a $FechaFinal";
}



$CondicionItems=" AND ".$CondicionFecha1;
$CondicionFacturas=$CondicionFecha2;
        
$Documento="<strong>Informe De Ventas $Rango</strong>";
require_once('Encabezado.php');

$nombre_file=$fecha."_Reporte_Ventas_Nivel_$Nivel";
		   
switch ($Nivel){
    case 1: $sql="SELECT dp.idDepartamentos as idDepartamentos, dp.Nombre as Departamento FROM  prod_departamentos dp";    
    break;
    case 2: $sql="SELECT dp.idDepartamentos as idDepartamentos, sub1.idSub1 as idSub1, dp.Nombre as Departamento, sub1.NombreSub1 as NombreSub1 FROM prod_sub1 sub1 INNER JOIN prod_departamentos dp"
        . " ON sub1.idDepartamento=dp.idDepartamentos";    
    break;
    case 3: $sql="SELECT dp.idDepartamentos as idDepartamentos, sub1.idSub1 as idSub1, dp.Nombre as Departamento, sub1.NombreSub1 as NombreSub1,sub2.NombreSub2,sub2.idSub2 "
            . " FROM prod_sub1 sub1 INNER JOIN prod_departamentos dp"
        . " ON sub1.idDepartamento=dp.idDepartamentos INNER JOIN prod_sub2 sub2 ON sub2.idSub1=sub1.idSub1";    
    break;
    case 4: $sql="SELECT dp.idDepartamentos as idDepartamentos, sub1.idSub1 as idSub1, dp.Nombre as Departamento, sub1.NombreSub1 as NombreSub1,sub2.NombreSub2,sub2.idSub2 "
            . ",sub3.NombreSub3,sub3.idSub3 "
            . " FROM prod_sub1 sub1 INNER JOIN prod_departamentos dp"
        . " ON sub1.idDepartamento=dp.idDepartamentos INNER JOIN prod_sub2 sub2 ON sub2.idSub1=sub1.idSub1"
            . " INNER JOIN prod_sub3 sub3 ON sub3.idSub2=sub2.idSub2";    
    break;
    case 5: $sql="SELECT dp.idDepartamentos as idDepartamentos, sub1.idSub1 as idSub1, dp.Nombre as Departamento, sub1.NombreSub1 as NombreSub1,sub2.NombreSub2,sub2.idSub2 "
            . ",sub3.NombreSub3,sub3.idSub3,sub4.NombreSub4,sub4.idSub4 "
            . " FROM prod_sub1 sub1 INNER JOIN prod_departamentos dp"
        . " ON sub1.idDepartamento=dp.idDepartamentos INNER JOIN prod_sub2 sub2 ON sub2.idSub1=sub1.idSub1"
            . " INNER JOIN prod_sub3 sub3 ON sub3.idSub2=sub2.idSub2 "
            . " INNER JOIN prod_sub4 sub4 ON sub4.idSub3=sub3.idSub3 ";    
    break;
    case 6: $sql="SELECT dp.idDepartamentos as idDepartamentos, sub1.idSub1 as idSub1, dp.Nombre as Departamento, sub1.NombreSub1 as NombreSub1,sub2.NombreSub2,sub2.idSub2 "
            . ",sub3.NombreSub3,sub3.idSub3,sub4.NombreSub4,sub4.idSub4, sub5.NombreSub5,sub5.idSub5 "
            . " FROM prod_sub1 sub1 INNER JOIN prod_departamentos dp"
        . " ON sub1.idDepartamento=dp.idDepartamentos INNER JOIN prod_sub2 sub2 ON sub2.idSub1=sub1.idSub1"
            . " INNER JOIN prod_sub3 sub3 ON sub3.idSub2=sub2.idSub2 "
            . " INNER JOIN prod_sub4 sub4 ON sub4.idSub3=sub3.idSub3 "
            . " INNER JOIN prod_sub5 sub5 ON sub5.idSub4=sub4.idSub4 ";    
    break;
}


///////////////////////////////////////////////////////
//////////////tabla con los datos de ventas por rango y departamento//////////////////
////////////////////////////////////////////////////////

$Consulta=$obVenta->Query($sql);
while($DatosDepartamento=$obVenta->FetchArray($Consulta)){
    $id=$DatosDepartamento["idDepartamentos"];
    switch ($Nivel){
        case 1:
            $Titulo="Reporte de $DatosDepartamento[Departamento]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' ";
            break;
        case 2:
            $idSub1=$DatosDepartamento["idSub1"];
            $Titulo="Reporte de $DatosDepartamento[Departamento] -> $DatosDepartamento[NombreSub1]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' AND SubGrupo1='$idSub1'";
            break;
        case 3:
            $idSub1=$DatosDepartamento["idSub1"];
            $idSub2=$DatosDepartamento["idSub2"];
            $Titulo="Reporte de $DatosDepartamento[Departamento] -> $DatosDepartamento[NombreSub1] -> $DatosDepartamento[NombreSub2]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' AND SubGrupo1='$idSub1' AND SubGrupo2='$idSub2'";
            break;
        case 4:
            $idSub1=$DatosDepartamento["idSub1"];
            $idSub2=$DatosDepartamento["idSub2"];
            $idSub3=$DatosDepartamento["idSub3"];
            $Titulo="Reporte de $DatosDepartamento[Departamento] -> $DatosDepartamento[NombreSub1] -> $DatosDepartamento[NombreSub2] -> $DatosDepartamento[NombreSub3]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' AND SubGrupo1='$idSub1' AND SubGrupo2='$idSub2' AND SubGrupo3='$idSub3'";
            break;
        case 5:
            $idSub1=$DatosDepartamento["idSub1"];
            $idSub2=$DatosDepartamento["idSub2"];
            $idSub3=$DatosDepartamento["idSub3"];
            $idSub4=$DatosDepartamento["idSub4"];
            $Titulo="Reporte de $DatosDepartamento[Departamento] -> $DatosDepartamento[NombreSub1] -> $DatosDepartamento[NombreSub2] -> $DatosDepartamento[NombreSub3]"
                    . " -> $DatosDepartamento[NombreSub4]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' AND SubGrupo1='$idSub1' AND SubGrupo2='$idSub2' AND SubGrupo3='$idSub3' AND SubGrupo4='$idSub4'";
            break;
        case 6:
            $idSub1=$DatosDepartamento["idSub1"];
            $idSub2=$DatosDepartamento["idSub2"];
            $idSub3=$DatosDepartamento["idSub3"];
            $idSub4=$DatosDepartamento["idSub4"];
            $idSub5=$DatosDepartamento["idSub5"];
            $Titulo="Reporte de $DatosDepartamento[Departamento] -> $DatosDepartamento[NombreSub1] -> $DatosDepartamento[NombreSub2] -> $DatosDepartamento[NombreSub3]"
                    . " -> $DatosDepartamento[NombreSub4] -> $DatosDepartamento[NombreSub5]";
            $CondicionReporte= $CondicionItems." AND Departamento='$id' AND SubGrupo1='$idSub1' AND SubGrupo2='$idSub2' AND SubGrupo3='$idSub3' AND SubGrupo4='$idSub4' AND SubGrupo5='$idSub5'";
            break;
    }
     
    
    $tbl=$obTabla->ArmeTablaVentaRangos($Titulo,$CondicionReporte,"");
    $pdf->writeHTML($tbl, false, false, false, false, '');
}


//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>