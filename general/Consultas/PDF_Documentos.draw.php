<?php 
if(isset($_REQUEST["idDocumento"])){
    $myPage="PDF_Documentos.draw.php";
    include_once("../../modelo/php_conexion.php");
    //include_once("../../modelo/PrintPos.php");
    include_once("../clases/ClasesPDFDocumentos.class.php");
    session_start();
    $idUser=$_SESSION["idUser"];
    $obCon = new conexion($idUser);
    //$obPrint=new PrintPos($idUser);
    $obDoc = new Documento($db);
    $idDocumento=$obCon->normalizar($_REQUEST["idDocumento"]);
    
    
    switch ($idDocumento){
        case 1://Genera el PDF de una cotizacion
            
            $idCotizacion=$obCon->normalizar($_REQUEST["ID"]);
            
            $obDoc->PDF_Cotizacion($idCotizacion, "");
    
            $DatosImpresora=$obCon->DevuelveValores("config_puertos", "ID", 1);
            if($DatosImpresora["Habilitado"]=="SI"){
                $obPrint->ImprimeCotizacionPOS($idCotizacion,$DatosImpresora["Puerto"],1);
            }
        break;
        case 2://Genera el PDF de una Factura de venta
            
            $idFactura=$obCon->normalizar($_REQUEST["ID"]);
            $TipoFactura="ORIGINAL";
            if(isset($_REQUEST["TipoFactura"])){
                $TipoFactura=$obCon->normalizar($_REQUEST["TipoFactura"]);
            }
            
            $obDoc->PDF_Factura($idFactura,$TipoFactura, "");

            $DatosImpresora=$obCon->DevuelveValores("config_puertos", "ID", 1);
            if($DatosImpresora["Habilitado"]=="SI"){
                $obPrint->ImprimeFacturaPOS($idFactura,$DatosImpresora["Puerto"],1,0);
            }
        break;
        case 4: //Comprobante de ingreso
            $idIngreso=$obCon->normalizar($_REQUEST["idIngreso"]);
            $obDoc->PDF_CompIngreso($idIngreso);
            $obPrint->ComprobanteIngresoPOS($idIngreso, $DatosImpresora["Puerto"], 1);
            break;
        case 5: //Orden de Compra
            $idOC=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->OrdenCompraPDF($idOC);
            
            break;
        
        case 23: //Factura de Compra
            $idOC=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->PDF_FacturaCompra($idOC);
            
            break;
        case 25: //Comprobante de altas y bajas
            $idComprobante=$obCon->normalizar($_REQUEST["idComprobante"]);
            $obDoc->PDF_CompBajasAltas($idComprobante);     
            $obPrint->ImprimeComprobanteBajaAlta($idComprobante, "", 1, "");
            break;
        case 30: //Cuenta de cobro para un tercero
            $idCuenta=$obCon->normalizar($_REQUEST["idCuenta"]);
            $obDoc->CuentaCobroTercero($idCuenta,"");            
            break;
        case 31: //PDF de una nota de devolucion
            $idNota=$obCon->normalizar($_REQUEST["idNotaDevolucion"]);
            $obDoc->PDF_NotaDevolucion($idNota,"");            
            break;
        case 32: //PDF de un documento contable
            $idDocumento=$obCon->normalizar($_REQUEST["idDocumentoContable"]);
            $obDoc->PDF_DocumentoContable($idDocumento,"");            
            break;
        case 33: //PDF de un documento equivalente a factura para nomina
            $idDocumento=$obCon->normalizar($_REQUEST["idDocEqui"]);
            $obDoc->NominaPDFDocumentoEquivalente($idDocumento,"");            
            break;
        case 34: //PDF de un certificado de retenciones
            $FechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbCentroCosto=$obCon->normalizar($_REQUEST["CmbCentroCosto"]);
            $CmbEmpresa=$obCon->normalizar($_REQUEST["CmbEmpresa"]);
            $CmbTercero=$obCon->normalizar($_REQUEST["CmbTercero"]);
            $CmbCiudadRetencion=$obCon->normalizar($_REQUEST["CmbCiudadRetencion"]);
            $CmbCiudadPago="";
            $CmbCiudadPago=$obCon->normalizar($_REQUEST["CmbCiudadPago"]);
            $obDoc->PDF_Certificado_Retenciones($FechaInicial, $TxtFechaFinal, $CmbCentroCosto, $CmbEmpresa, $CmbTercero, $CmbCiudadRetencion, $CmbCiudadPago, "");         
            break;//Fi caso 34
        
        case 35: //PDF de un comprobante de prestamo
            $idPrestamo=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->ComprobantePrestamoPDF($idPrestamo,"");            
            break;//Fin caso 35
        
        case 2000: //PDF para un reporte de asistencia
            $idCita=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->ReporteAsistenciaPDF($idCita,"");            
        break;//Fin caso 2000
    
        case 2001: //PDF de una factura basante
            $idFactura=$obCon->normalizar($_REQUEST["idFactura"]);
            $obDoc->FacturaBasantePDF($idFactura,"");            
        break;//Fin caso 2001
        
        case 2002: //PDF del Reporte de Facturación
            $Condicion=$obCon->normalizar(base64_decode(urldecode($_REQUEST["q"])));
            $FechaInicial=$obCon->normalizar($_REQUEST["FechaInicial"]);
            $FechaFinal=$obCon->normalizar($_REQUEST["FechaFinal"]);
            $idTipoFactura=$obCon->normalizar($_REQUEST["idTipoFactura"]);
            $idRegimenFactura=$obCon->normalizar($_REQUEST["idRegimenFactura"]);
            $obDoc->RelacionFacturaBasante($Condicion,$idTipoFactura,$idRegimenFactura,$FechaInicial,$FechaFinal);            
        break;//Fin caso 2001
    }
}else{
    print("No se recibió parametro de documento");
}

?>