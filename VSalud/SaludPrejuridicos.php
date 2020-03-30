<?php 
$myPage="SaludPrejuridicos.php";
include_once("../sesiones/php_control.php");
include_once("clases/Legal.class.php");
include_once("css_construct.php");

print("<html>");
print("<head>");
$css =  new CssIni("Generar Cobros Prejuridicos");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Generar Cobros Prejuridicos"); //Inicia la cabecera de la pagina
      
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    

    $css->CrearDiv("principal", "container", "center",1,1);
    include_once("procesadores/SaludPrejuridicos.process.php");
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>DATOS</strong>", 1);
            $css->ColTabla("<strong>FACTURAS</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        print("<td>");
        $css->CrearForm2("FrmGenerarPrejuridico", $myPage, "post", "_SELF"); 
            $css->CrearDiv2("DivF", "container", "center", 2, 1, 130, 200, 1, 1, "", "");
                $Page="Consultas/FacturasPrejuridicos.php";
                $FuncionJS="EnvieObjetoConsulta2(`$Page`,`CmbCobro`,`DivFacturasDif`,`3`);return false ;";
                $css->CrearSelect("CmbCobro", $FuncionJS,220);
                    
                    $css->CrearOptionSelect("", "Seleccione un Tipo de Cobro", 0);
                    $css->CrearOptionSelect(1, "Prejuridico 1", 0);
                    $css->CrearOptionSelect(2, "Prejuridico 2", 0);
                $css->CerrarSelect();
                $Page="Consultas/FacturasPrejuridicos.php?idFactura=";
                $css->CrearInputText("TxtBuscarFact","text","","","Buscar Factura","black","onchange","EnvieObjetoConsulta(`$Page`,`TxtBuscarFact`,`DivFacturasDif`,`5`);return false;",200,30,0,0);
        
                $Page="Consultas/FacturasPrejuridicos.php";
                $FuncionJS="EnvieObjetoConsulta2(`$Page`,`idEps`,`DivFacturasDif`,`3`);return false ;";
                $css->CrearSelectTable("idEps", "salud_eps", " ORDER BY nombre_completo", "cod_pagador_min", "nombre_completo", "cod_pagador_min", "onchange", $FuncionJS, "", 1,"Seleccione una EPS");
                $css->CrearTextArea("TxtObservaciones", "", "", "Observaciones", "", "", "", 220, 100, 0, 1);
                $css->CrearBotonConfirmado("BtnGenerar", "Generar");
            $css->CerrarDiv();
        $css->CerrarForm();      
        print("</td>"); 
        print("<td>");
            $css->CrearDiv2("DivF", "container", "center", 2, 1, 500, 500, 1, 1, "", "");
            $css->DivGrid("DivFacturasDif", "container", "center", 1, 1, 3, 90, 175,5,"transparent");
            $css->CerrarDiv();
            $css->CerrarDiv();
        print("</td>");
       $css->CierraFilaTabla();
    $css->CerrarTabla();
            
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>