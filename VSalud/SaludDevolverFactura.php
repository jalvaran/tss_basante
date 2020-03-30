<?php 
$myPage="SaludDevolverFactura.php";
include_once("../sesiones/php_control.php");
include_once("clases/Glosas.class.php");
include_once("css_construct.php");
$obGlosas = new Glosas($idUser);
//////Si recibo un id de Factura
if(!empty($_REQUEST['idFactura'])){

    $idFactura=$obGlosas->normalizar($_REQUEST['idFactura']);
}

	
print("<html>");
print("<head>");
$css =  new CssIni("Devolucion Factura");

print("</head>");
print("<body>");
    
    include_once("procesadores/SaludDevolverFactura.process.php");
    
    $css->CabeceraIni("Marcar Factura como Devuelta"); //Inicia la cabecera de la pagina
    
    //////////Creamos el formulario de busqueda de remisiones
    /////
    /////
    
   
    
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("principal", "container", "center",1,1);
    print("<br>");
    ///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
    $css->CrearImageLink("vista_salud_facturas_no_pagas.php", "../images/anular.png", "_self",200,200);
    
    ///////////////Si se crea una devolucion o una factura
    /////
    /////
    if(!empty($_REQUEST["TxtidComprobante"])){
        $css->CrearTabla();
        $css->CrearFilaNotificacion("Se ha marcado la factura como devuelta</a>",16);
        $css->CerrarTabla();
    }
    ///////////////Se crea el DIV que servirá de contenedor secundario
    /////
    /////
    $css->CrearDiv("Secundario", "container", "center",1,1);
   										
    
    //////////////////////////Se dibujan los campos para la anulacion de la factura
    /////
    /////
    if(!empty($idFactura)){
        
        $css->CrearTabla();
            
            $DatosFactura=$obGlosas->DevuelveValores("salud_archivo_facturacion_mov_generados", "id_fac_mov_generados", $idFactura);
            //$DatosCliente=$obVenta->DevuelveValores("clientes", "idClientes", $DatosFactura["Clientes_idClientes"]);
                if($DatosFactura["estado"]=="DEVUELTA"){
                    $css->CrearNotificacionRoja("Error! esta factura ya fue anulada", 16);
                    exit();
                }
            $css->CrearNotificacionAzul("Datos de la factura", 18);
            $css->FilaTabla(14);
            
            $css->ColTabla("<strong>Numero</strong>", 1);
            $css->ColTabla("<strong>EPS</strong>", 1);
            $css->ColTabla("<strong>Fecha</strong>", 1);
            $css->ColTabla("<strong>Total</strong>", 1);
            
            
            $css->CierraFilaTabla();
            
            $css->FilaTabla(14);
            
            $css->ColTabla($DatosFactura["num_factura"], 1);
            $css->ColTabla($DatosFactura["cod_enti_administradora"]." ".$DatosFactura["nom_enti_administradora"], 1);
            $css->ColTabla($DatosFactura["fecha_factura"], 1);
            
            $css->ColTabla($DatosFactura["valor_neto_pagar"], 1);
                        
            $css->CierraFilaTabla();
            
        $css->CerrarTabla();
        $css->CrearForm2("FrmRegistraDevolucion", $myPage, "post", "_self");
        $css->CrearInputText("idFactura", "hidden", "", $idFactura, "", "", "", "", "", "", "", "");
        $css->CrearTabla();
        $css->CrearNotificacionNaranja("Datos Para Realizar la Devolucion", 16);
        print("<td style='text-align:center'>");
        $css->CrearInputFecha("Fecha:<br>", "TxtFechaDevolucion", date("Y-m-d"), 100, 30, "");
        //$css->CrearInputText("TxtFechaAnulacion", "text", "Fecha de Anulacion: <br>", date("Y-m-d"), "Fecha", "black", "", "", 100, 30, 0, 1);
        
        $VarSelect["Ancho"]="200";
        $VarSelect["PlaceHolder"]="Conceptos Glosas";
        $VarSelect["Required"]=1;
        $css->CrearSelectChosen("CmbGlosas", $VarSelect);
        $css->CrearOptionSelect("", "Concepto de Glosa" , 1);
            $sql="SELECT * FROM salud_archivo_conceptos_glosas";
            $Consulta=$obGlosas->Query($sql);
            while($DatosGlosas=$obGlosas->FetchArray($Consulta)){

                   $css->CrearOptionSelect("$DatosGlosas[cod_glosa]", "$DatosGlosas[cod_glosa] / $DatosGlosas[aplicacion]" , 0);
               }

        $css->CerrarSelect();
       
        print("<br>");
        $css->CrearTextArea("TxtObservaciones", "", "", "Escriba el por qué se devolvió la factura", "black", "", "", 200, 100, 0, 1);
        
        print("<br>");
        $css->CrearUpload("Soporte");
        print("<br>");
        $css->CrearBotonConfirmado("BtnDevolver","Devolver");	
            
        print("</td>");
        
        $css->CerrarTabla();
        $css->CerrarForm();
        
        
    }else{
        $css->CrearTabla();
        $css->CrearFilaNotificacion("Por favor busque y asocie un abono",16);
        $css->CerrarTabla();
    }
    $css->CerrarDiv();//Cerramos contenedor Secundario
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->Footer();
    print("</body></html>");
    ob_end_flush();
?>