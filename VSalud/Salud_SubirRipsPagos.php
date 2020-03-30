<?php 
$myPage="Salud_SubirRipsPagos.php";
include_once("../sesiones/php_control.php");
include_once("clases/SaludRips.class.php");
include_once("css_construct.php");
$obVenta = new conexion($idUser);
$obRips = new Rips($idUser);
//////Si recibo un cliente

	
print("<html>");
print("<head>");
$css =  new CssIni("Subir Rips");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Subir RIPS de Pagos"); //Inicia la cabecera de la pagina
    
    //////////Creamos el formulario de busqueda de remisiones
    /////
    /////
    
   
    
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
    
    print("<br>");
    include_once("procesadores/Salud_SubirRipsPagos.process.php");
    
    ///////////////Se crea el DIV que servirá de contenedor secundario
    /////
    /////
    $css->CrearDiv("Secundario", "container", "center",1,1);
   										
    
    //////////////////////////Se dibujan los campos para la anulacion de la factura
    /////
    /////
    $css->CrearNotificacionRoja("Suba los Archivos de Relacion de Pagos", 16);
    $css->CrearDiv("DivMensajes","","center",1,1);
    $css->CerrarDiv();
    $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    
    //$css->CrearForm2("FrmRipsPagos", $myPage, "post", "_self");
    print("<strong>Separador de Archivo</strong><br>");
    $css->CrearSelect("CmbSeparador", "");
        $css->CrearOptionSelect("", "Selecciones el Separador de los archivos", 0);
        $css->CrearOptionSelect(1, "punto y coma (;)", 0);
        $css->CrearOptionSelect(2, "Coma (,)", 1);
    $css->CerrarSelect();
    print("<br><strong>Tipo de Giro</strong><br>");
    $css->CrearSelect("CmbTipoGiro", "",200);
        $css->CrearOptionSelect("", "Tipo de Giro", 0);
        $css->CrearOptionSelect(1, "Giro Directo Subsidiado", 1);
        $css->CrearOptionSelect(2, "Cuenta Maestra (Tesoreria)", 0);
        $css->CrearOptionSelect(3, "Giro Directo Contributivo", 0);
    $css->CerrarSelect();
        $css->CrearTabla();
            $css->FilaTabla(16);
                $css->ColTabla("<strong>Pagos (AR)</strong>", 1);
                $css->ColTabla("<strong>Fecha de Giro</strong>", 1);
                $css->ColTabla("<strong>Soporte de Pago</strong>", 1);
                $css->ColTabla("<strong>Enviar</strong>", 1);
            $css->CierraFilaTabla();
            
            
            $css->FilaTabla(16);
                
                print("<td>");
                    $css->CrearUpload("UpPago");
                    
                print("</td>");
                print("<td>");
                    $css->CrearInputText("TxtFechaGira", "date", "", date("Y-m-d"), "", "", "", "", 200, 30, 0, 1);
                    
                print("</td>");
                print("<td>");
                    $css->CrearUpload("UpSoporte");
                    
                print("</td>");
                print("<td>");
                    $css->CrearBotonEvento("BtnEnviar", "Subir", 1, "onClick", "CargarAR()", "rojo", "");
                    //$css->CrearBotonConfirmado("BtnEnviar", "Subir");
                    
                print("</td>");
                
            $css->CierraFilaTabla();
            
        $css->CerrarTabla();
    //$css->CerrarForm();   
    
    
    $css->CerrarDiv();//Cerramos contenedor Secundario
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    print('<script type="text/javascript" src="jsPages/Salud_SubirRipsPago.js"></script>');
    $css->AgregaSubir();
    $css->Footer();
    print("</body></html>");
    ob_end_flush();
?>