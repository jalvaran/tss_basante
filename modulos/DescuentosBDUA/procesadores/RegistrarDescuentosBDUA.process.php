<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/RegistraDescuentosBDUA.class.php");

if( !empty($_REQUEST["Accion"]) ){
    $obCon = new DescuentosBDUA($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Registrar un descuento BDUA
            
            $FechaDescuento=$obCon->normalizar($_REQUEST["FechaDescuento"]);
            $NumeroFactura=$obCon->normalizar($_REQUEST["NumeroFactura"]);
            $Radicado=$obCon->normalizar($_REQUEST["Radicado"]);
            
            $AfiliadosIMA=$obCon->normalizar($_REQUEST["AfiliadosIMA"]);
            $ValorDescuento=$obCon->normalizar($_REQUEST["ValorDescuento"]);
            
            if($FechaDescuento==''){
                exit("E1;Debe digitar una fecha válida;FechaDescuento");
            }
            if($NumeroFactura==''){
                exit("E1;Debe digitar un Número de Factura;NumeroFactura");
            }
            if($Radicado==''){
                exit("E1;Debe digitar un Número de Radicado;Radicado");
            }
            if(!is_numeric($AfiliadosIMA) or $AfiliadosIMA<0){
                exit("E1;Debe digitar un Número de Afiliados IMA;AfiliadosIMA");
            }
            if(!is_numeric($ValorDescuento) or $ValorDescuento==0 or $ValorDescuento==''){
                exit("E1;Debe digitar un Valor de descuento Válido;ValorDescuento");
            }
            $sql="SELECT num_factura,valor_neto_pagar, tipo_negociacion FROM salud_archivo_facturacion_mov_generados WHERE num_factura='$NumeroFactura'";
            $Consulta=$obCon->Query($sql);
            $DatosFactura=$obCon->FetchAssoc($Consulta);
            if($DatosFactura["num_factura"]==''){
                exit("E1;La Factura digitada no existe en los registros del AF;NumeroFactura");
            }
            if($DatosFactura["tipo_negociacion"]<>'capita'){
                exit("E1;La Factura digitada no es de tipo cápita;NumeroFactura");
            }
            $NuevoSaldo=$DatosFactura["valor_neto_pagar"]-$ValorDescuento;
            if($NuevoSaldo<0){
                exit("E1;El Valor del Descuento No puede ser mayor al Valor Total de la Factura;ValorDescuento");
            }
            $obCon->RegistreDescuentoBDUA($FechaDescuento, $NumeroFactura, $Radicado, $AfiliadosIMA, $ValorDescuento,$NuevoSaldo,$idUser);
            
            print("OK;Fué registrado un descuento a la Fáctura $NumeroFactura");
            
        break; //fin caso 1
        
           
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>