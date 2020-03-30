<?php 
$myPage="Salud_Ingresar_Pago_Tesoreria.php";
include_once("../sesiones/php_control.php");

include_once("css_construct.php");

print("<html>");
print("<head>");
$css =  new CssIni("Ingresar Pagos Por Tesoreria");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Ingresar Pagos Por Tesoreria"); //Inicia la cabecera de la pagina
      
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    

    $css->CrearDiv("principal", "container", "center",1,1);
    include_once 'procesadores/Salud_Ingresa_Pago_Tesoreria.process.php';
    if(isset($_REQUEST["TransaccionOk"])){
        $css->CrearNotificacionVerde("Informacion Registrada Correctamente", 16);
    }
        
    $css->CrearNotificacionAzul("INGRESAR PAGO", 16);
    $css->CrearForm2("FrmGuardarPago", $myPage, "post", "_self");
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>EPS</strong>", 1);
            $css->ColTabla("<strong>BANCO</strong>", 1);
            $css->ColTabla("<strong>FECHA</strong>", 1);
            $css->ColTabla("<strong>No. TRANSACCION</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            print("<td>");
                $css->CrearTableChosen("CmbEps", "salud_eps", "", "cod_pagador_min", "sigla_nombre", "nit", "cod_pagador_min", 300, 1, "EPS", "");
               // $css->CrearSelectTable("CmbEps", "salud_eps", "", "cod_pagador_min", "nombre_completo", "cod_pagador_min", "", "", "", 1, "Seleccione la EPS");
            print("</td>");
            print("<td>");
                $css->CrearSelectTable("CmbBanco", "salud_bancos", "", "ID", "banco_transaccion", "num_cuenta_banco", "", "", "", 1, "Seleccione la cuenta");
            print("</td>");
            print("<td>");
                
                $css->CrearInputText("TxtFecha", "date", "", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
            
            print("</td>");
            print("<td>");
                $css->CrearInputText("TxtNumTransaccion", "text", "", "", "Transaccion", "", "", "", 350, 30, 0, 1);
            print("</td>");
        $css->CierraFilaTabla();
        
        $css->FilaTabla(16);
            $css->ColTabla("<strong>VALOR</strong>", 1);
            $css->ColTabla("<strong>OBSERVACIONES</strong>", 1);
            $css->ColTabla("<strong>SOPORTE</strong>", 1);
            $css->ColTabla("<strong>GUARDAR</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            print("<td>");
                $css->CrearInputNumber("TxtPago", "number", "", "", "Valor", "", "", "", 150, 30, 0, 1, 1, "", "any");
            print("</td>");
            print("<td>");
                $css->CrearTextArea("TxtObservaciones", "", "", "Observaciones", "", "", "", 200, 60, 0, 0);
            print("</td>");
            print("<td>");
                $css->CrearUpload("Soporte");
            print("</td>");
            print("<td>");
                $css->CrearBotonConfirmado("BtnGuardar", "Guardar");
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