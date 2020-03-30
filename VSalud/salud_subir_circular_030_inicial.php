<?php 
$myPage="salud_subir_circular_030_inicial.php";
include_once("../sesiones/php_control.php");
include_once("clases/SaludRips.class.php");
include_once("css_construct.php");
$obVenta = new conexion($idUser);
$obRips = new Rips($idUser);
//////Si recibo un cliente

	
print("<html>");
print("<head>");
$css =  new CssIni("Subir Circular 030 Inicial");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Subir Circular 030 Inicial"); //Inicia la cabecera de la pagina
    
    //////////Creamos el formulario de busqueda de remisiones
    /////
    /////
    
   
    
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
    print("<br>");
    include_once("procesadores/salud_subir_030_inicial.process.php");
    
    ///////////////Se crea el DIV que servirá de contenedor secundario
    /////
    /////
    $css->CrearDiv("Secundario", "container", "center",1,1);
   										
    
    //////////////////////////Se dibujan los campos para la anulacion de la factura
    /////
    /////
    $css->CrearNotificacionNaranja("Suba la Circular 030 inicial", 16);
    $css->CrearForm2("FrmRipsPagos", $myPage, "post", "_self");
    
        $css->CrearTabla();
            $css->FilaTabla(16);
                $css->ColTabla("<strong>Subir</strong>", 1);
                
                $css->ColTabla("<strong>Enviar</strong>", 1);
            $css->CierraFilaTabla();
            
            
            $css->FilaTabla(16);
                
                print("<td>");
                    $css->CrearUpload("UpPago");
                    
                print("</td>");
                
                print("<td>");
                    $css->CrearBotonConfirmado("BtnEnviar", "Subir");
                    
                print("</td>");
                
            $css->CierraFilaTabla();
            
        $css->CerrarTabla();
    $css->CerrarForm();   
    
    
    $css->CerrarDiv();//Cerramos contenedor Secundario
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->Footer();
    print("</body></html>");
    ob_end_flush();
?>