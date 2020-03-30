<?php 
$myPage="LegalizarPagos.php";
include_once("../sesiones/php_control.php");
include_once("css_construct.php");

print("<html>");
print("<head>");
$css =  new CssIni("Legalizar Pagos de Tesoreria");

print("</head>");
print("<body>");
$css->CabeceraIni("Legalizar Pagos de Tesoreria"); //Inicia la cabecera de la pagina

$css->CabeceraFin(); 
$css->CrearDiv("principal", "container", "center",1,1);
include_once("procesadores/LegalizarPagos.process.php");

if($_REQUEST["ID"]){
    
    $obCon=new conexion($idUser);
    $idTesoreria=$obCon->normalizar($_REQUEST["ID"]);
    $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idTesoreria);
    
    print("<p>Va a legalizar el pago de la EPS: <strong>$DatosPago[nom_enti_administradora] $DatosPago[cod_enti_administradora]</strong>."
            . "<br>Por Valor de: <strong>". number_format($DatosPago["valor_transaccion"])."</strong>"
            . "<br>En la transacción: <strong>". ($DatosPago["num_transaccion"])."</strong>"
            . "<br>En la Fecha: <strong>". ($DatosPago["fecha_transaccion"])."</strong></p>");
    $css->CrearForm2("FrmLegalizar", $myPage, "post", "_self");
        $css->CrearInputText("idPago", "hidden", "", $idTesoreria, "", "", "", "", "", "", 0, 0);
        $css->CrearInputText("ValorTransaccion", "hidden", "", $DatosPago["valor_transaccion"], "", "", "", "", "", "", 0, 0);
        $css->CrearTabla();
           $css->FilaTabla(16);
                $css->ColTabla("<strong>Observaciones</strong>", 1);
                $css->ColTabla("<strong>Valor Legalizado</strong>", 1);
                $css->ColTabla("<strong>Actualizar</strong>", 1);
           $css->CierraFilaTabla();
           $css->FilaTabla(16);
                print("<td>");
                    $css->CrearTextArea("TxtObservaciones", "", $DatosPago["observaciones_cartera"], "Observaciones", "", "", "", 400, 60, 0, 1);
                print("</td>");
                print("<td>");
                    $css->CrearInputNumber("TxtValorLegalizado", "number", "", $DatosPago["valor_legalizado"], "Valor legalizado", "", "", "", 200, 30, 0, 1, 1, $DatosPago["valor_transaccion"], "any");
                print("</td>");
                print("<td>");
                    $css->CrearBotonConfirmado("BtnLegalizar", "Enviar");
                print("</td>");
           $css->CierraFilaTabla();
        $css->CerrarTabla();
    $css->CerrarForm();
   
}  
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>