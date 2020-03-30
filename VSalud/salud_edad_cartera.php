<?php
$myPage="salud_edad_cartera.php";
include_once("../sesiones/php_control.php");
$myTitulo="Cartera por edades";
////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////

include_once("clases/Glosas.class.php");
include_once("css_construct.php");
$obGlosas = new Glosas($idUser);
print("<html>");
print("<head>");

$css =  new CssIni($myTitulo);
print("</head>");
print("<body>");
//Cabecera
$css->CabeceraIni($myTitulo); //Inicia la cabecera de la pagina

     
$css->CabeceraFin(); 

$css->CrearDiv("DivPrincipal", "container", "center", 1, 1);
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>Cartera X Edades</strong>", 1);
            $css->ColTabla("<strong>de 1 a 30</strong>", 2);
            $css->ColTabla("<strong>de 31 a 60</strong>", 2);
            $css->ColTabla("<strong>de 61 a 90</strong>", 2);
            $css->ColTabla("<strong>de 91 a 120</strong>", 2);
            $css->ColTabla("<strong>de 121 a 180</strong>", 2);
            $css->ColTabla("<strong>de 181 a 360</strong>", 2);
            $css->ColTabla("<strong>+361 Dias</strong>", 2);
            $css->ColTabla("<strong>Total</strong>", 2);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong> EPS </strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
        $css->CierraFilaTabla();
        $sql="SELECT cod_enti_administradora, nom_enti_administradora FROM vista_salud_facturas_no_pagas GROUP BY cod_enti_administradora";
        $Datos=$obGlosas->Query($sql);
        $CatidadFacturas=0;
        $TotalValor=0;
        while($DatosEPS=$obGlosas->FetchArray($Datos)){
            $idEPS=$DatosEPS["cod_enti_administradora"];
            $DiasPactadosEPS=$obGlosas->ValorActual("salud_eps", "dias_convenio", " cod_pagador_min='$idEPS'");
            $DiasPactados=$DiasPactadosEPS["dias_convenio"];
            $css->FilaTabla(14);
                $css->ColTabla("$DatosEPS[nom_enti_administradora]", 1);
                //De 1 a 30 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(1+$DiasPactados) AND DiasMora<=(30+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$DatosCartera["NumFacturas"];
                $TotalValor=$DatosCartera["Total"];
                //De 31 a 60 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(31+$DiasPactados) AND DiasMora<=(60+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 61 a 90 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(61+$DiasPactados) AND DiasMora<=(90+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 91 a 120 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(91+$DiasPactados) AND DiasMora<=(120+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 91 a 120 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(121+$DiasPactados) AND DiasMora<=(180+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 181 a 360 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(181+$DiasPactados) AND DiasMora<=(360+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // >360 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(361+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // Totales
                $css->ColTabla(number_format($CatidadFacturas), 1);
                $css->ColTabla(number_format($TotalValor), 1);
            $css->CierraFilaTabla();
        }
    $css->CerrarTabla();
$css->CerrarDiv();//Cerramos contenedor Principal
$css->Footer();
$css->AgregaJS(); //Agregamos javascripts
//$css->AgregaSubir();    
////Fin HTML  
print("</body></html>");

ob_end_flush();
?>