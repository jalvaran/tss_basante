<?php 
$myPage="SaludInformeEstadoRips.php";
include_once("../sesiones/php_control.php");
include_once("clases/Glosas.class.php");
include_once("css_construct.php");

function DibujeDetalles($Mes,$Year,$DetalleRIPS) {
    $css =  new CssIni("");
    $css->CrearTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>$Mes $Year</strong>", 3);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>RIPS</strong>", 1);
            $css->ColTabla("<strong>CANTIDAD</strong>", 1);
            $css->ColTabla("<strong>VALOR</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>GENERADOS</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RG"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RG"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>VALIDADOS</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RP"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RP"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>NO VENCIDOS SIN PAGAR</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RCNV"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RCNV"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>VENCIDOS SIN PAGAR</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RCV"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RCV"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>CON GLOSA ACEPTADA</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RGA"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RGA"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>DEVUELTOS</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RD"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RD"]["V"]), 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>SIN CONCILIAR</strong>", 1);
            $css->ColTabla(number_format($DetalleRIPS["RDSC"]["C"]), 1);
            $css->ColTabla(number_format($DetalleRIPS["RDSC"]["V"]), 1);
        $css->CierraFilaTabla();
    $css->CerrarTabla();
}

$obGlosas = new Glosas($idUser);
//////Si recibo un cliente
$ActualYear=date("Y");
$Year=$ActualYear;
$idEps="ALL";
if(isset($_REQUEST["CmbYear"])){
    $Year=$_REQUEST["CmbYear"];
}
if(isset($_REQUEST["CmbEps"])){
    $idEps=$_REQUEST["CmbEps"];
}
print("<html>");
print("<head>");
$css =  new CssIni("Informe RIPS");

print("</head>");
print("<body>");
    
    include_once("procesadores/SaludGlosas.process.php");
    
    $css->CabeceraIni("Informe RIPS"); //Inicia la cabecera de la pagina
      
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
    
    $css->CrearForm2("FrmSelector", $myPage, "post", "_self");
        $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>EPS</strong>", 1);
            $css->ColTabla("<strong>YEAR</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        print("<td>");
        $VarSelect["Ancho"]="300";
        $VarSelect["PlaceHolder"]="EPS";
        $VarSelect["Required"]=1;
        $css->CrearSelectChosen("CmbEps", $VarSelect);
        $css->CrearOptionSelect("ALL", "Todas" , 1);
            $sql="SELECT * FROM salud_eps ORDER BY nombre_completo";
            $Consulta=$obGlosas->Query($sql);
            while($DatosEps=$obGlosas->FetchArray($Consulta)){
                if($idEps==$DatosEps[cod_pagador_min]){
                    $sel=1;
                }else{
                    $sel=0;
                }
                $css->CrearOptionSelect("$DatosEps[cod_pagador_min]", "$DatosEps[nombre_completo] / $DatosEps[cod_pagador_min]" , $sel);
            }

        $css->CerrarSelect();
        print("</td>");
        print("<td>");
            $css->CrearSelect("CmbYear", "EnviaForm('FrmSelector');");
                for($i=2010;$i<=$ActualYear;$i++){
                    if($Year==$i){
                        $sel=1;
                    }else{
                        $sel=0;
                    }
                    $css->CrearOptionSelect($i, "$i", $sel);
                }
            $css->CerrarSelect();
        print("</td>");
        $css->CierraFilaTabla();
        $css->CerrarTabla();
    $css->CerrarForm();
    
    
        $AnchoGrid=32;
        $AltoGrid=64;
        $css->DivGrid("DivRips1", "", "left", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "01", $Year, ""); //Mes de enero
            DibujeDetalles("ENERO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips2", "", "center", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "02", $Year, ""); //Mes de enero
            DibujeDetalles("FEBRERO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips3", "", "rigth", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "03", $Year, ""); //Mes de enero
            DibujeDetalles("MARZO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips4", "", "left", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "04", $Year, ""); //Mes de enero
            DibujeDetalles("ABRIL", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips5", "", "center", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "05", $Year, ""); //Mes de enero
            DibujeDetalles("MAYO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips6", "", "rigth", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "06", $Year, ""); //Mes de enero
            DibujeDetalles("JUNIO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips7", "", "left", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "07", $Year, ""); //Mes de enero
            DibujeDetalles("JULIO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips8", "", "center", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "08", $Year, ""); //Mes de enero
            DibujeDetalles("AGOSTO", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips9", "", "rigth", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "09", $Year, ""); //Mes de enero
            DibujeDetalles("SEPTIEMBRE", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips10", "", "left", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "10", $Year, ""); //Mes de enero
            DibujeDetalles("OCTUBRE", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips11", "", "center", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "11", $Year, ""); //Mes de enero
            DibujeDetalles("NOVIEMBRE", $Year, $DetalleRIPS);
        $css->CerrarDiv();
        $css->DivGrid("DivRips12", "", "rigth", 1, 1, 3, $AltoGrid, $AnchoGrid,5,"#E6E6E6");
            $DetalleRIPS=$obGlosas->DetalleRips($idEps, "12", $Year, ""); //Mes de enero
            DibujeDetalles("DICIEMBRE", $Year, $DetalleRIPS);
        $css->CerrarDiv();

    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AnchoElemento("CmbEps_chosen", 350);
    $css->AgregaSubir();
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>