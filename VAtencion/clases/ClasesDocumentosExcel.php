<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class TS5_Excel extends Tabla{
    
    // Clase para generar excel de un balance de comprobacion
    
    public function GenerarBalanceComprobacionExcel($TipoReporte,$FechaInicial,$FechaFinal,$FechaCorte,$idEmpresa,$CentroCosto,$Vector) {
        require_once '../librerias/Excel/PHPExcel.php';
        $Condicion=" WHERE ";
        $Condicion2=" WHERE ";
        if($TipoReporte=="Corte"){
            $Condicion.=" Fecha <= '$FechaCorte' ";
            $Condicion2.=" Fecha > '5000-01-01' AND  ";
            $Rango="Corte a $FechaCorte";
        }else{
            $Condicion.=" Fecha >= '$FechaInicial' AND Fecha <= '$FechaFinal' "; 
            $Condicion2.= " Fecha < '$FechaInicial' AND ";
            $Rango="De $FechaInicial a $FechaFinal";
        }
        if($CentroCosto<>"ALL"){
                $Condicion.="  AND idCentroCosto='$CentroCosto' ";
                $Condicion2.="  AND idCentroCosto='$CentroCosto' AND ";
        }
        if($idEmpresa<>"ALL"){
                $Condicion.="  AND idEmpresa='$idEmpresa' ";
                $Condicion2.="  AND idEmpresa='$idEmpresa' AND ";
        }
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode('#');
        $objPHPExcel->getActiveSheet()->getStyle('C:F')->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("A:F")->getFont()->setSize(10);
        
        $f=1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"BALANCE DE COMPROBACION $Rango")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"CUENTA")
            ->setCellValue($this->Campos[1].$f,"NOMBRE")
            ->setCellValue($this->Campos[2].$f,"SALDO ANTERIOR")
            ->setCellValue($this->Campos[3].$f,"DEBITO")
            ->setCellValue($this->Campos[4].$f,"CREDITO")
            ->setCellValue($this->Campos[5].$f,"NUEVO SALDO")
            
            ;
            
   $sql="SELECT SUBSTRING(`CuentaPUC`,1,1) AS Clase ,sum(`Debito`) as Debitos, sum(`Credito`) as Creditos, sum(`Neto`) as Neto, (SELECT SUM(`Neto`) as SaldoTotal FROM `librodiario` $Condicion2  SUBSTRING(`CuentaPUC`,1,1)=Clase) AS Total FROM `librodiario` $Condicion GROUP BY SUBSTRING(`CuentaPUC`,1,1)";
        $Consulta=$this->obCon->Query($sql);
        $i=0;
        $DebitosGeneral=0;
        $CreditosGeneral=0;
        $TotalDebitos=0;
        $TotalCreditos=0;
        $Total=0;
        while($ClaseCuenta=$this->obCon->FetchArray($Consulta)){
            $DebitosGeneral=$DebitosGeneral+$ClaseCuenta["Debitos"];
            $CreditosGeneral=$CreditosGeneral+$ClaseCuenta["Creditos"];
            $TotalDebitos=$TotalDebitos+$ClaseCuenta["Debitos"];
            $TotalCreditos=$TotalCreditos+$ClaseCuenta["Creditos"];
            $Total=$Total+$ClaseCuenta["Total"];
            $i++;
            $Clase=$ClaseCuenta["Clase"];
            $NoClasesCuentas[$i]=$ClaseCuenta["Clase"];
            $DatosCuenta=  $this->obCon->DevuelveValores("clasecuenta", "PUC", $Clase);
            $Balance["ClaseCuenta"][$Clase]["Nombre"]=$DatosCuenta["Clase"];
            $Balance["ClaseCuenta"][$Clase]["Clases"]=$ClaseCuenta["Clase"];
            $Balance["ClaseCuenta"][$Clase]["Debitos"]=$ClaseCuenta["Debitos"];
            $Balance["ClaseCuenta"][$Clase]["Creditos"]=$ClaseCuenta["Creditos"];
            $Balance["ClaseCuenta"][$Clase]["NuevoSaldo"]=$ClaseCuenta["Debitos"]-$ClaseCuenta["Creditos"]+$ClaseCuenta["Total"];
            $Balance["ClaseCuenta"][$Clase]["SaldoAnterior"]=$ClaseCuenta["Total"];
        }
        $Diferencia=$TotalDebitos-$TotalCreditos;
        //Guardo en un Vector los resultados de la consulta por Grupo
        
        $sql="SELECT SUBSTRING(`CuentaPUC`,1,2) AS Grupo ,sum(`Debito`) as Debitos, sum(`Credito`) as Creditos,(SELECT SUM(`Neto`) as SaldoTotal FROM `librodiario` $Condicion2  SUBSTRING(`CuentaPUC`,1,2)=Grupo) AS Total FROM `librodiario` $Condicion GROUP BY SUBSTRING(`CuentaPUC`,1,2)";
        $Consulta=$this->obCon->Query($sql);
        $i=0;
        
        while($ClaseCuentaGrupo=$this->obCon->FetchArray($Consulta)){
            $i++;
            $Grupo=$ClaseCuentaGrupo["Grupo"];
            $NoGrupos[$i]=$ClaseCuentaGrupo["Grupo"];
            $DatosCuenta=  $this->obCon->DevuelveValores("gupocuentas", "PUC", $Grupo);
            $Balance["GrupoCuenta"][$Grupo]["Nombre"]=$DatosCuenta["Nombre"];
            $Balance["GrupoCuenta"][$Grupo]["Grupos"]=$ClaseCuentaGrupo["Grupo"];
            $Balance["GrupoCuenta"][$Grupo]["Debitos"]=$ClaseCuentaGrupo["Debitos"];
            $Balance["GrupoCuenta"][$Grupo]["Creditos"]=$ClaseCuentaGrupo["Creditos"];
            $Balance["GrupoCuenta"][$Grupo]["NuevoSaldo"]=$ClaseCuentaGrupo["Debitos"]-$ClaseCuentaGrupo["Creditos"]+$ClaseCuentaGrupo["Total"];
            $Balance["GrupoCuenta"][$Grupo]["SaldoAnterior"]=$ClaseCuentaGrupo["Total"];
        }
        
        //Guardo en un Vector los resultados de la consulta por Cuenta
        
        $sql="SELECT SUBSTRING(`CuentaPUC`,1,4) AS Cuenta ,sum(`Debito`) as Debitos, sum(`Credito`) as Creditos,(SELECT SUM(`Neto`) as SaldoTotal FROM `librodiario` $Condicion2  SUBSTRING(`CuentaPUC`,1,4)=Cuenta) as Total FROM `librodiario` $Condicion GROUP BY SUBSTRING(`CuentaPUC`,1,4)";
        $Consulta=$this->obCon->Query($sql);
        $i=0;
        
        while($ClaseCuentaCuenta=$this->obCon->FetchArray($Consulta)){
            $i++;
            $Cuenta=$ClaseCuentaCuenta["Cuenta"];
            $NoCuentas[$i]=$ClaseCuentaCuenta["Cuenta"];
            $DatosCuenta=  $this->obCon->DevuelveValores("cuentas", "idPUC", $Cuenta);
            $Balance["Cuenta"][$Cuenta]["Nombre"]=$DatosCuenta["Nombre"];
            $Balance["Cuenta"][$Cuenta]["Cuentas"]=$ClaseCuentaCuenta["Cuenta"];
            $Balance["Cuenta"][$Cuenta]["Debitos"]=$ClaseCuentaCuenta["Debitos"];
            $Balance["Cuenta"][$Cuenta]["Creditos"]=$ClaseCuentaCuenta["Creditos"];
            $Balance["Cuenta"][$Cuenta]["NuevoSaldo"]=$ClaseCuentaCuenta["Debitos"]-$ClaseCuentaCuenta["Creditos"]+$ClaseCuentaCuenta["Total"];
            $Balance["Cuenta"][$Cuenta]["SaldoAnterior"]=$ClaseCuentaCuenta["Total"];
        }
        
        //Guardo en un Vector los resultados de la consulta por SubCuenta
        
        $sql="SELECT `CuentaPUC` AS Subcuenta , sum(`Debito`) as Debitos, sum(`Credito`) as Creditos, sum(`Neto`) as NuevoSaldo,(SELECT SUM(`Neto`) as SaldoTotal FROM `librodiario` $Condicion2  `CuentaPUC` = Subcuenta) as Total FROM `librodiario` $Condicion AND LENGTH(`CuentaPUC`)>=5 GROUP BY `CuentaPUC` ";
        $Consulta=$this->obCon->Query($sql);
        $i=0;
        
        while($ClaseCuentaSub=$this->obCon->FetchArray($Consulta)){
            $i++;
            $SubCuenta=$ClaseCuentaSub["Subcuenta"];
            $NoSubCuentas[$i]=$ClaseCuentaSub["Subcuenta"];
            $sql="SELECT NombreCuenta FROM librodiario WHERE CuentaPUC='$SubCuenta' LIMIT 1";
            $Datos=  $this->obCon->Query($sql);
            $DatosCuenta=$this->obCon->FetchArray($Datos);
            //$DatosCuenta=  $this->obCon->DevuelveValores("subcuentas", "PUC", `$SubCuenta`);
            $Balance["SubCuenta"][$SubCuenta]["Nombre"]=$DatosCuenta["NombreCuenta"];
            $Balance["SubCuenta"][$SubCuenta]["Subcuenta"]=$ClaseCuentaSub["Subcuenta"];
            $Balance["SubCuenta"][$SubCuenta]["Debitos"]=$ClaseCuentaSub["Debitos"];
            $Balance["SubCuenta"][$SubCuenta]["Creditos"]=$ClaseCuentaSub["Creditos"];
            $Balance["SubCuenta"][$SubCuenta]["NuevoSaldo"]=$ClaseCuentaSub["Debitos"]-$ClaseCuentaSub["Creditos"]+$ClaseCuentaSub["Total"];
            $Balance["SubCuenta"][$SubCuenta]["SaldoAnterior"]=$ClaseCuentaSub["Total"];
        }
        $f=3;
        foreach ($NoClasesCuentas as $Clase){
            if($Balance["ClaseCuenta"][$Clase]["Debitos"]<>0 OR $Balance["ClaseCuenta"][$Clase]["Creditos"]<>0 OR $Balance["ClaseCuenta"][$Clase]["NuevoSaldo"]<>0 ){
            //Se digitan las clases
            $objPHPExcel->getActiveSheet()->getStyle("A$f:F$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00fadbd8');    
            $objPHPExcel->getActiveSheet()->getStyle("A$f:F$f")->getFont()->setSize(16);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($this->Campos[0].$f,$Clase)
                ->setCellValue($this->Campos[1].$f,$Balance["ClaseCuenta"][$Clase]["Nombre"])
                ->setCellValue($this->Campos[2].$f,$Balance["ClaseCuenta"][$Clase]["SaldoAnterior"])
                ->setCellValue($this->Campos[3].$f,$Balance["ClaseCuenta"][$Clase]["Debitos"])
                ->setCellValue($this->Campos[4].$f,$Balance["ClaseCuenta"][$Clase]["Creditos"])
                ->setCellValue($this->Campos[5].$f,$Balance["ClaseCuenta"][$Clase]["NuevoSaldo"])
                ;
            
           foreach($NoGrupos as $GruposCuentas){
               
                   if(substr($Balance["GrupoCuenta"][$GruposCuentas]["Grupos"], 0, 1)==$Clase){
                       
                       $f++;
                       $objPHPExcel->getActiveSheet()->getStyle("A$f:F$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00E6E6E6');    
                       $objPHPExcel->getActiveSheet()->getStyle("A$f:F$f")->getFont()->setSize(12);
                        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($this->Campos[0].$f,$GruposCuentas)
                    ->setCellValue($this->Campos[1].$f,$Balance["GrupoCuenta"][$GruposCuentas]["Nombre"])
                    ->setCellValue($this->Campos[2].$f,$Balance["GrupoCuenta"][$GruposCuentas]["SaldoAnterior"])
                    ->setCellValue($this->Campos[3].$f,$Balance["GrupoCuenta"][$GruposCuentas]["Debitos"])
                    ->setCellValue($this->Campos[4].$f,$Balance["GrupoCuenta"][$GruposCuentas]["Creditos"])
                    ->setCellValue($this->Campos[5].$f,$Balance["GrupoCuenta"][$GruposCuentas]["NuevoSaldo"])
                    ;                      
                   
                   //Consulto los valores dentro de la Cuenta
                   
                   foreach($NoCuentas as $Cuentas){
                       
                    if(substr($Balance["Cuenta"][$Cuentas]["Cuentas"], 0, 2)==$GruposCuentas){
                        $f++;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($this->Campos[0].$f,$Cuentas)
                            ->setCellValue($this->Campos[1].$f,$Balance["Cuenta"][$Cuentas]["Nombre"])
                            ->setCellValue($this->Campos[2].$f,$Balance["Cuenta"][$Cuentas]["SaldoAnterior"])
                            ->setCellValue($this->Campos[3].$f,$Balance["Cuenta"][$Cuentas]["Debitos"])
                            ->setCellValue($this->Campos[4].$f,$Balance["Cuenta"][$Cuentas]["Creditos"])
                            ->setCellValue($this->Campos[5].$f,$Balance["Cuenta"][$Cuentas]["NuevoSaldo"])
                            ;    
                         //Consulto los valores dentro de la Cuenta
                   
                   foreach($NoSubCuentas as $SubCuentas){
                       
                    if(substr($Balance["SubCuenta"][$SubCuentas]["Subcuenta"], 0, 4)==$Cuentas){
                        $f++;
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($this->Campos[0].$f,$Balance["SubCuenta"][$SubCuentas]["Subcuenta"])
                            ->setCellValue($this->Campos[1].$f,$Balance["SubCuenta"][$SubCuentas]["Nombre"])
                            ->setCellValue($this->Campos[2].$f,$Balance["SubCuenta"][$SubCuentas]["SaldoAnterior"])
                            ->setCellValue($this->Campos[3].$f,$Balance["SubCuenta"][$SubCuentas]["Debitos"])
                            ->setCellValue($this->Campos[4].$f,$Balance["SubCuenta"][$SubCuentas]["Creditos"])
                            ->setCellValue($this->Campos[5].$f,$Balance["SubCuenta"][$SubCuentas]["NuevoSaldo"])
                            ;  
                    }
                  }
                         
                 }
                }
              }
             }
             $f++; //Salto de linea para clase cuenta
            } 
            
        }
    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[2].$f,"Totales")
            ->setCellValue($this->Campos[3].$f,$DebitosGeneral)
            ->setCellValue($this->Campos[4].$f,$CreditosGeneral)
            ->setCellValue($this->Campos[5].$f,"Diferencia")
            ->setCellValue($this->Campos[6].$f,$DebitosGeneral-$CreditosGeneral);
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Balance Comprobacion")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Balance Comprobacion");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Balance_Comprobacion".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
   
    }
    
    //resumen de ventas y comisiones en excel
    // Clase para generar excel de un balance de comprobacion
    
    public function InformeComisionesXVentas($Tipo,$idCierre,$FechaInicial,$FechaFinal,$Vector) {
        
        require_once '../../librerias/Excel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('B:F')->getNumberFormat()->setFormatCode('#');
        
        $objPHPExcel->getActiveSheet()->getStyle("A:F")->getFont()->setSize(10);
        
        $f=1;
        $Rango="DE $FechaInicial A $FechaFinal";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"INFORME DE COMISIONES $Rango")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"PRODUCTO")
            ->setCellValue($this->Campos[1].$f,"CANTIDAD")
            ->setCellValue($this->Campos[2].$f,"VALOR TOTAL")
            ->setCellValue($this->Campos[3].$f,"COMISION 1")
            ->setCellValue($this->Campos[4].$f,"COMISION 2")
            ->setCellValue($this->Campos[5].$f,"COMISION 3")            
            ;
        $sql="SELECT `idCierre` as Cierre,`Referencia`,Nombre,SUM(`Cantidad`) AS CantidadTotal, "
                . "round(SUM( `TotalItem`+`ValorOtrosImpuestos`)) AS ValorTotal, "
                . "(SUM(`Cantidad`)*(SELECT ValorComision1 FROM productosventa WHERE productosventa.Referencia=`facturas_items`.`Referencia`) ) AS Comision1, "
                . "(SUM(`Cantidad`)*(SELECT ValorComision2 FROM productosventa WHERE productosventa.Referencia=`facturas_items`.`Referencia`)) AS Comision2, "
                . "(SUM(`Cantidad`)*(SELECT ValorComision3 FROM productosventa WHERE productosventa.Referencia=`facturas_items`.`Referencia`) ) AS Comision3"
                . " FROM `facturas_items`WHERE idCierre='$idCierre' GROUP BY `Referencia` ";
        
        $Consulta=$this->obCon->Query($sql);
        $f=3;
        $TotalCantidad=0;
        $TotalValor=0;
        $TotalComision1=0;
        $TotalComision2=0;
        $TotalComision3=0;
        while($DatosComisiones= $this->obCon->FetchArray($Consulta)){
            $TotalCantidad=$TotalCantidad+$DatosComisiones["CantidadTotal"];
            $TotalValor=$TotalValor+$DatosComisiones["ValorTotal"];
            $TotalComision1=$TotalComision1+$DatosComisiones["Comision1"];
            $TotalComision2=$TotalComision2+$DatosComisiones["Comision2"];
            $TotalComision3=$TotalComision3+$DatosComisiones["Comision3"];
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosComisiones["Nombre"])
            ->setCellValue($this->Campos[1].$f,$DatosComisiones["CantidadTotal"])
            ->setCellValue($this->Campos[2].$f,$DatosComisiones["ValorTotal"])
            ->setCellValue($this->Campos[3].$f,$DatosComisiones["Comision1"])
            ->setCellValue($this->Campos[4].$f,$DatosComisiones["Comision2"])
            ->setCellValue($this->Campos[5].$f,$DatosComisiones["Comision3"])            
            ;
            $f++;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"TOTALES")
            ->setCellValue($this->Campos[1].$f,$TotalCantidad)
            ->setCellValue($this->Campos[2].$f,$TotalValor)
            ->setCellValue($this->Campos[3].$f,$TotalComision1)
            ->setCellValue($this->Campos[4].$f,$TotalComision2)
            ->setCellValue($this->Campos[5].$f,$TotalComision3)            
            ;
        //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Informe de Comisiones")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes Ventas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Informe_Comisiones".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
        
    }
    
    // Clase para generar excel de un balance de comprobacion
    
    public function AcumuladoXTerceroExcel($FechaInicial,$FechaFinal,$CuentaPUC,$Tercero,$Vector) {
        
        require_once '../librerias/Excel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('E:F')->getNumberFormat()->setFormatCode('#');
        
        $objPHPExcel->getActiveSheet()->getStyle("A:F")->getFont()->setSize(10);
        
        $f=1;
        $Rango="DE $FechaInicial A $FechaFinal";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"ACUMULADO POR TERCERO $Rango")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"TERCERO")
            ->setCellValue($this->Campos[1].$f,"RAZON SOCIAL")
            ->setCellValue($this->Campos[2].$f,"CUENTA PUC")
            ->setCellValue($this->Campos[3].$f,"NOMBRE CUENTA")
            ->setCellValue($this->Campos[4].$f,"DEBITOS")
            ->setCellValue($this->Campos[5].$f,"CREDITOS")            
            ;
        $CondicionTercero="AND Tercero_Identificacion='$Tercero'" ;
        if($Tercero=='All'){
            $CondicionTercero="" ;
        }
        $sql="SELECT Tercero_Identificacion,Tercero_Razon_Social,SUM(Debito) as Debito,SUM(Credito) as Credito,CuentaPUC, NombreCuenta FROM librodiario  "
               . "WHERE Fecha>='$FechaInicial' AND Fecha<='$FechaFinal' "
                . "AND CuentaPUC like '$CuentaPUC%' $CondicionTercero GROUP BY Tercero_Identificacion,CuentaPUC";
        $Consulta=$this->obCon->Query($sql);
        $f=3;
        $TotalCreditos=0;
        $TotalDebitos=0;
        
        while($DatosLibro= $this->obCon->FetchArray($Consulta)){
            $TotalDebitos=$TotalDebitos+$DatosLibro["Debito"];
            $TotalCreditos=$TotalCreditos+$DatosLibro["Credito"];;
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosLibro["Tercero_Identificacion"])
            ->setCellValue($this->Campos[1].$f,$DatosLibro["Tercero_Razon_Social"])
            ->setCellValue($this->Campos[2].$f,$DatosLibro["CuentaPUC"])
            ->setCellValue($this->Campos[3].$f,$DatosLibro["NombreCuenta"])
            ->setCellValue($this->Campos[4].$f,$DatosLibro["Debito"])
            ->setCellValue($this->Campos[5].$f,$DatosLibro["Credito"])            
            ;
            $f++;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"")
            ->setCellValue($this->Campos[1].$f,"")
            ->setCellValue($this->Campos[2].$f,"")
            ->setCellValue($this->Campos[3].$f,"TOTALES")
            ->setCellValue($this->Campos[4].$f,$TotalDebitos)
            ->setCellValue($this->Campos[5].$f,$TotalCreditos)            
            ;
        //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Acumulado por terceros")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes Ventas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Acumulado".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
        
    }
    //Estilos alineacion
    public function EstilosAlineacion($Vector) {
        $Estilos=array('HG'=>PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
                'HL'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'HR'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'HC'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'HCC'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS,
                'HJ'=>PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                'VB'=>PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'VT'=>PHPExcel_Style_Alignment::VERTICAL_TOP,
                'VC'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'VJ'=>PHPExcel_Style_Alignment::VERTICAL_JUSTIFY,
                
                );
        return($Estilos);
    }
    
    //Estilos desde array
    public function EstilosDeBordes($E,$Vector) {
        switch ($E) {
            case 1:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_NONE;//Sin Borde
                break;
            case 2:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_DASHDOT;//punteado
                break;
            case 3:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_DASHDOTDOT;//punteado
                break;
            case 4:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_DASHED;//punteado
                break;
            case 5:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_DOTTED;//punteado
                break;
            case 6:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_DOUBLE;//Doble continuo
                break;
            case 7:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_HAIR;//punteado leve
                break;
            case 8:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_MEDIUM;//Borde de cuadro grueso continuo
                break;
            case 9:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_MEDIUMDASHDOT;//Grueso punteado
                break;
            case 10:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_MEDIUMDASHDOTDOT;//Grueso punteado
                break;
            case 11:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_MEDIUMDASHED;//Grueso punteado
                break;
            case 12:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_SLANTDASHDOT;//punteado grueso rectangulos
                break;
            case 13:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THICK;//Grueso incluyendo las de adentro
                break;
            case 14:
                $Estyle['borders']['allborders']['style']=PHPExcel_Style_Border::BORDER_THIN;//Con Borde
                break;

        }
        return($Estyle);
    }
    // Clase para generar una factura equivalente
    
    public function DocumentoEquivalenteExcel($idDocumento,$Vector) {
        $DiaSem=array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabados','Domingos');
        $Mes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        require_once '../../librerias/Excel/PHPExcel.php';
        $Estilos=$this->EstilosAlineacion("");
        
        $objPHPExcel = new PHPExcel();  
        $DatosEmpresa=$this->obCon->DevuelveValores("empresapro","idEmpresaPro",1);
        $DatosDocumento=$this->obCon->DevuelveValores("documento_equivalente","ID",$idDocumento);
        $DatosTercero=$this->obCon->DevuelveValores("proveedores","Num_Identificacion",$DatosDocumento["Tercero"]);
        
        $f=1;
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(14)->setBold(true); //pongo la celda A1 en Negrita  con tamaño 14
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1'); //Se combinan las celdas A1 a la F1
        
        //Escribo la razon social de la empresa propietaria
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosEmpresa["RazonSocial"])
                        
            ;
        //Coloco el NIT
        $f=2;
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setWrapText(true); // texto envolvente
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('13');//Ancho de la Columna
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getNumberFormat()->setFormatCode('#');
        $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->getFont()->setSize(12)->setBold(true); //Tamaño 12
        //Escribo la razon social de la empresa propietaria
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[2].$f,"NIT:")
            ->setCellValue($this->Campos[3].$f,$DatosEmpresa["NIT"])            
            ;
        //Coloco la direccion
        $objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(10)->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:F3'); //Se combinan las celdas A3 a la F3
        $f=3;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,$DatosEmpresa["Direccion"])            
            ;
        
        //Regimen
        
        $objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setSize(10)->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:F4'); //Se combinan las celdas A3 a la F3
        $f=4;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"RESPONSABLE DE IVA REGIMEN COMÚN")            
            ;
        
        //informacion de retenciones
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setSize(10)->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A5:E5'); //Se combinan las celdas A3 a la F3
        $f=5;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"AGENTE DE RETENCION EN LA FUENTE") 
            ->setCellValue($this->Campos[5].$f,"NO")    
            ;
        
        //Numeracion del documento
        $EstiloBordes=$this->EstilosDeBordes(8, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true); // texto envolvente
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15.7');//Ancho de la Columna
        $objPHPExcel->getActiveSheet()->getStyle("G2:G4")->applyFromArray($EstiloBordes);//Aplico bordes
        $objPHPExcel->getActiveSheet()->getStyle("G2")->getFont()->setSize(12)->setBold(true);
        //$objPHPExcel->getActiveSheet()->getStyle("G4")->getFont()->setSize(14)->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('G2:G4')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:G4'); //Se combinan las celdas A3 a la F3
        
        $f=2;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[6].$f++,"FACTURA\r\nEQUIVALENTE\r\n".$idDocumento) 
            
            ;
        //Ciudad y fecha
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A7:G7")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle('A7:G7')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        
        $objPHPExcel->getActiveSheet()->getStyle("A7")->getFont()->setSize(10)->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:G7'); //Se combinan las celdas A3 a la F3
        $f=7;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"CIUDAD Y FECHA DE LA OPERACIÓN") 
            
            ;
        
        //Ciudad y fecha
        
        $NumDia=date("N",strtotime($DatosDocumento["Fecha"]));
        $NumFecha=date("j",strtotime($DatosDocumento["Fecha"]));
        $NumMes=date("n",strtotime($DatosDocumento["Fecha"]));
        $Anio=date("Y",strtotime($DatosDocumento["Fecha"]));
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A8:G8")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle('A8:G8')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        
        //$objPHPExcel->getActiveSheet()->getStyle("A8")->getFont()->setSize(10)->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:B8'); 
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C8:G8'); 
        $f=8;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,$DatosEmpresa["Ciudad"]) 
            ->setCellValue($this->Campos[2].$f,$DiaSem[$NumDia]." ".$NumFecha." de ".$Mes[$NumMes]." del ".$Anio) 
            ;
        
        //Titulo tercero
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A10:G10")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle("A10:G10")->getFont()->setSize(10)->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A10:G10')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A10:F10'); //Se combinan las celdas A3 a la F3
        $f=10;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"PERSONA NATURAL DE QUIEN SE ADQUIEREN LOS BIENES Y/O SERVICIOS")
            ->setCellValue($this->Campos[6].$f,"NIT ó CC")
            ;
        
        //Informacion tercero
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A11:G11")->applyFromArray($EstiloBordes);
        //$objPHPExcel->getActiveSheet()->getStyle("A10:G10")->getFont()->setSize(10)->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:F11'); //Se combinan las celdas A3 a la F3
        $f=11;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,$DatosTercero["RazonSocial"])
            ->setCellValue($this->Campos[6].$f,$DatosTercero["Num_Identificacion"])
            ;
        
        //Cabecera articulos
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A13:G13")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle("A13:G13")->getFont()->setSize(10)->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A13:G13')->getAlignment()->setHorizontal($Estilos['VC']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B13:E13'); //Se combinan las celdas A3 a la F3
        $f=13;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"CANT")
            ->setCellValue($this->Campos[1].$f,"DESCRIPCION")
            ->setCellValue($this->Campos[5].$f,"VR. UNIT")
            ->setCellValue($this->Campos[6].$f,"VR. PARCIAL")
            ;
        
        //Articulos
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A14:G14")->applyFromArray($EstiloBordes);
        
        $objPHPExcel->getActiveSheet()->getStyle('F14:G14')->getAlignment()->setHorizontal($Estilos['HR']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B14:E14'); //Se combinan las celdas A3 a la F3
        $f=14;
        $sql="SELECT * FROM documento_equivalente_items WHERE idDocumento=$idDocumento LIMIT 6";
        $Consulta=$this->obCon->Query($sql);
        $Total=0;
        $z=0;
        while($DatosItems= $this->obCon->FetchArray($Consulta)){ 
            $z++;
            $Total=$Total+$DatosItems["Total"];
            $objPHPExcel->setActiveSheetIndex(0)
                     
                ->setCellValue($this->Campos[0].$f,$DatosItems["Cantidad"])
                ->setCellValue($this->Campos[1].$f,$DatosItems["Descripcion"])
                ->setCellValue($this->Campos[5].$f,$DatosItems["ValorUnitario"])
                ->setCellValue($this->Campos[6].$f,$DatosItems["Total"])
                ;
            $f++;
        }
        $objPHPExcel->getActiveSheet()->getStyle("A15:G20")->applyFromArray($EstiloBordes);
        for($i=15;$i<=19;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$i.':E'.$i);
        }
        //Total
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("G20")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle("A20:G20")->getFont()->setSize(11)->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A20')->getAlignment()->setHorizontal($Estilos['HR']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A20:F20'); //Se combinan las celdas A3 a la F3
        $f=20;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"TOTAL FACTURA")
            ->setCellValue($this->Campos[6].$f,$Total)
           
            ;
        
        //Obervaciones y firma
        $EstiloBordes=$this->EstilosDeBordes(14, $Vector);//Borde continuo normal
        $objPHPExcel->getActiveSheet()->getStyle("A21:G24")->applyFromArray($EstiloBordes);
        $objPHPExcel->getActiveSheet()->getStyle('A21')->getAlignment()->setWrapText(true); // texto envolvente
        $objPHPExcel->getActiveSheet()->getStyle('A21:G24')->getAlignment()->setHorizontal($Estilos['VB']); //se centra el texto
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A21:G24'); //Se combinan las celdas A3 a la F3
        $f=21;
        $objPHPExcel->setActiveSheetIndex(0)            
            ->setCellValue($this->Campos[0].$f,"                                                     _______________________"
                    . "\r\n                                                          FIRMA DEL VENDEDOR")
                      
            ;
        //Informacion del excel
        $objPHPExcel->
         getProperties()
             ->setCreator("www.technosoluciones.com.co")
             ->setLastModifiedBy("www.technosoluciones.com.co")
             ->setTitle("Acumulado por terceros")
             ->setSubject("Informe")
             ->setDescription("Documento generado con PHPExcel")
             ->setKeywords("techno soluciones sas")
             ->setCategory("Informes Ventas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Equivalente_$idDocumento".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
        
    }
    
    /**
     * Genera el excel para un auxiliar por documento
     * @param type $FechaInicial Fecha inicial del reporte
     * @param type $FechaFinal Fecha final del reporte
     * @param type $Vector uso futuro
     */
    public function AuxiliarXDocumento($FechaInicial,$FechaFinal,$Vector) {
        
        require_once '../librerias/Excel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('D:I')->getNumberFormat()->setFormatCode('#');
        
        $objPHPExcel->getActiveSheet()->getStyle("A:I")->getFont()->setSize(10);
        
        $f=1;
        $Rango="DE $FechaInicial A $FechaFinal";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"AUXILIAR POR DOCUMENTO $Rango")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"FECHA")
            ->setCellValue($this->Campos[1].$f,"DOC")
            ->setCellValue($this->Campos[2].$f,"NUMERO")
            ->setCellValue($this->Campos[3].$f,"TERCERO")
            ->setCellValue($this->Campos[4].$f,"RAZON SOCIAL")
            ->setCellValue($this->Campos[5].$f,"CUENTA")
            ->setCellValue($this->Campos[6].$f,"NOMBRE") 
            ->setCellValue($this->Campos[7].$f,"DEBITOS")
            ->setCellValue($this->Campos[8].$f,"CREDITOS")
            ;
        
        $sql="SELECT `Fecha`,`Tipo_Documento_Intero`,`NumDocumento`,`Tercero_Identificacion`,`Tercero_Razon_Social`,"
               . "`CuentaPUC`,`NombreCuenta`,`Debito`,`Credito` FROM `vista_libro_diario`  "
               . "WHERE Fecha>='$FechaInicial' AND Fecha<='$FechaFinal' ORDER BY `Tipo_Documento_Intero`,`Fecha` ";
                
        $Consulta=$this->obCon->Query($sql);
        $f=3;
        $TotalCreditos=0;
        $TotalDebitos=0;
        $TotalDebitosDoc=0;
        $TotalCreditosDoc=0;
        $TotalDebitosNumDoc=0;
        $TotalCreditosNumDoc=0;
        $Doc="";
        $NumDoc="";
        while($DatosLibro= $this->obCon->FetchArray($Consulta)){
            
            if($NumDoc<>'' and $NumDoc<>$DatosLibro["NumDocumento"]){
                $Color='00E6E6E6';
                if($TotalDebitosNumDoc<>$TotalCreditosNumDoc){
                    $Color='00fa8580';
                }
                $objPHPExcel->getActiveSheet()->getStyle("A$f:I$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB($Color);
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($this->Campos[0].$f,"")
                ->setCellValue($this->Campos[1].$f,"TOTALES $Doc $NumDoc")
                ->setCellValue($this->Campos[2].$f,"")
                ->setCellValue($this->Campos[3].$f,"")
                ->setCellValue($this->Campos[4].$f,"")
                ->setCellValue($this->Campos[5].$f,"") 
                ->setCellValue($this->Campos[6].$f,"")
                ->setCellValue($this->Campos[7].$f,$TotalDebitosNumDoc)
                ->setCellValue($this->Campos[8].$f,$TotalCreditosNumDoc)      
                ;
                $TotalDebitosNumDoc=0;
                $TotalCreditosNumDoc=0;
                $f++;
            }
            
            if($Doc<>'' and $Doc<>$DatosLibro["Tipo_Documento_Intero"]){
                $objPHPExcel->getActiveSheet()->getStyle("A$f:I$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00ffe9ae');
               
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($this->Campos[0].$f,"")
                ->setCellValue($this->Campos[1].$f,"")
                ->setCellValue($this->Campos[2].$f,"")
                ->setCellValue($this->Campos[3].$f,"")
                ->setCellValue($this->Campos[4].$f,"")
                ->setCellValue($this->Campos[5].$f,"") 
                ->setCellValue($this->Campos[6].$f,"TOTALES $Doc")
                ->setCellValue($this->Campos[7].$f,$TotalDebitosDoc)
                ->setCellValue($this->Campos[8].$f,$TotalCreditosDoc)      
                ;
                $TotalDebitosDoc=0;
                $TotalCreditosDoc=0;
                $f++;
            }
            
            $NumDoc=$DatosLibro["NumDocumento"];
            $Doc=$DatosLibro["Tipo_Documento_Intero"];
            $TotalDebitos=$TotalDebitos+$DatosLibro["Debito"];
            $TotalCreditos=$TotalCreditos+$DatosLibro["Credito"];
            $TotalDebitosDoc=$TotalDebitosDoc+$DatosLibro["Debito"];
            $TotalCreditosDoc=$TotalCreditosDoc+$DatosLibro["Credito"];
            $TotalDebitosNumDoc=$TotalDebitosNumDoc+$DatosLibro["Debito"];
            $TotalCreditosNumDoc=$TotalCreditosNumDoc+$DatosLibro["Credito"];
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosLibro["Fecha"])
            ->setCellValue($this->Campos[1].$f,$DatosLibro["Tipo_Documento_Intero"])
            ->setCellValue($this->Campos[2].$f,$DatosLibro["NumDocumento"])
            ->setCellValue($this->Campos[3].$f,$DatosLibro["Tercero_Identificacion"])
            ->setCellValue($this->Campos[4].$f,$DatosLibro["Tercero_Razon_Social"])
            ->setCellValue($this->Campos[5].$f,$DatosLibro["CuentaPUC"])  
            ->setCellValue($this->Campos[6].$f,$DatosLibro["NombreCuenta"])
            ->setCellValue($this->Campos[7].$f,$DatosLibro["Debito"])
            ->setCellValue($this->Campos[8].$f,$DatosLibro["Credito"])          
            ;
            $f++;
        }
        $objPHPExcel->getActiveSheet()->getStyle("A$f:I$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00ffe9ae');
               
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($this->Campos[0].$f,"")
                ->setCellValue($this->Campos[1].$f,"")
                ->setCellValue($this->Campos[2].$f,"")
                ->setCellValue($this->Campos[3].$f,"")
                ->setCellValue($this->Campos[4].$f,"")
                ->setCellValue($this->Campos[5].$f,"") 
                ->setCellValue($this->Campos[6].$f,"TOTALES $Doc")
                ->setCellValue($this->Campos[7].$f,$TotalDebitosDoc)
                ->setCellValue($this->Campos[8].$f,$TotalCreditosDoc)      
                ;
                $TotalDebitosDoc=0;
                $TotalCreditosDoc=0;
                $f++;
        $objPHPExcel->getActiveSheet()->getStyle("A$f:I$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00defa80');
                
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"")
            ->setCellValue($this->Campos[1].$f,"")
            ->setCellValue($this->Campos[2].$f,"")
            ->setCellValue($this->Campos[3].$f,"")
            ->setCellValue($this->Campos[4].$f,"")
            ->setCellValue($this->Campos[5].$f,"") 
            ->setCellValue($this->Campos[6].$f,"TOTALES")
            ->setCellValue($this->Campos[7].$f,$TotalDebitos)
            ->setCellValue($this->Campos[8].$f,$TotalCreditos)      
            ;
        //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Auxiliar por Documento")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes Ventas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."AuxiliarXDocumento".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
        
    }
    
     /**
     * Genera el excel para un auxiliar por terceros
     * @param type $FechaInicial Fecha inicial del reporte
     * @param type $FechaFinal Fecha final del reporte
     * @param type $Vector uso futuro
     */
    public function AuxiliarXTercero($FechaInicial,$FechaFinal,$Vector) {
        
        require_once '../librerias/Excel/PHPExcel.php';
        $objPHPExcel = new PHPExcel();  
        
        $objPHPExcel->getActiveSheet()->getStyle('C:H')->getNumberFormat()->setFormatCode('#');
        
        $objPHPExcel->getActiveSheet()->getStyle("A:H")->getFont()->setSize(10);
        
        $f=1;
        $Rango="DE $FechaInicial A $FechaFinal";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[1].$f,"AUXILIAR POR TERCERO $Rango")
                        
            ;
        $f=2;
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"CUENTA")
            ->setCellValue($this->Campos[1].$f,"NOMBRE")
            ->setCellValue($this->Campos[2].$f,"TERCERO")
            ->setCellValue($this->Campos[3].$f,"RAZON SOCIAL")
            ->setCellValue($this->Campos[4].$f,"SALDO ANTERIOR")
            ->setCellValue($this->Campos[5].$f,"DEBITOS")
            ->setCellValue($this->Campos[6].$f,"CREDITOS") 
            ->setCellValue($this->Campos[7].$f,"NUEVO SALDO")
            ;
        
        $sql="SELECT SUBSTRING(`CuentaPUC`,1,4) AS Cuenta,`CuentaPUC` AS CuentaPUC,`NombreCuenta`,
            SUM(`Debito`) AS Debitos,SUM(`Credito`) AS Creditos, `Tercero_Identificacion` AS NIT,
            `Tercero_Razon_Social`, (SELECT SUM(Neto) FROM librodiario WHERE CuentaPUC=(SELECT CuentaPUC)
            AND Tercero_Identificacion=(SELECT NIT) AND `Fecha`<'$FechaInicial') AS SaldoAnterior 
            FROM `librodiario` WHERE `Fecha`>='$FechaInicial' AND `Fecha`<='$FechaFinal' 
            GROUP BY `Tercero_Identificacion` ORDER BY SUBSTRING(`CuentaPUC`,1,4), LENGTH(`CuentaPUC`),`CuentaPUC`;";
                
        $Consulta=$this->obCon->Query($sql);
        $f=3;
        $TotalCreditos=0;
        $TotalDebitos=0;
        $TotalDebitosCuenta=0;
        $TotalCreditosCuenta=0;    
        $TotalSaldoAnterior=0;
        $TotalNuevoSaldo=0;
        $TotalSaldoAnteriorCuenta=0;
        $TotalNuevoSaldoCuenta=0;
        $Cuenta="";
        
        while($DatosLibro= $this->obCon->FetchArray($Consulta)){
            
            if($Cuenta<>'' and $Cuenta<>$DatosLibro["CuentaPUC"]){
                $Color='0080e9fa';
                
                $objPHPExcel->getActiveSheet()->getStyle("A$f:H$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB($Color);
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($this->Campos[0].$f,"")
                ->setCellValue($this->Campos[1].$f,"TOTALES CUENTA $Cuenta")
                ->setCellValue($this->Campos[2].$f,"")
                ->setCellValue($this->Campos[3].$f,"")
                ->setCellValue($this->Campos[4].$f,$TotalSaldoAnterior) 
                ->setCellValue($this->Campos[5].$f,$TotalDebitosCuenta)
                ->setCellValue($this->Campos[6].$f,$TotalCreditosCuenta)
                ->setCellValue($this->Campos[7].$f,$TotalNuevoSaldo)      
                ;
                $TotalDebitosCuenta=0;
                $TotalCreditosCuenta=0;
                $TotalSaldoAnterior=0;
                $TotalNuevoSaldo=0;
                $f++;
            }
            
            
            $Cuenta=$DatosLibro["CuentaPUC"];
            $TotalSaldoAnterior=$TotalSaldoAnterior+$DatosLibro["SaldoAnterior"];
            $TotalDebitos=$TotalDebitos+$DatosLibro["Debitos"];
            $TotalCreditos=$TotalCreditos+$DatosLibro["Creditos"];
            $TotalNuevoSaldo=$TotalNuevoSaldo+($TotalSaldoAnterior+$TotalDebitos-$TotalCreditos);
            $TotalDebitosCuenta=$TotalDebitosCuenta+$DatosLibro["Debitos"];
            $TotalCreditosCuenta=$TotalCreditosCuenta+$DatosLibro["Creditos"];
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,$DatosLibro["CuentaPUC"])
            ->setCellValue($this->Campos[1].$f,$DatosLibro["NombreCuenta"])
            ->setCellValue($this->Campos[2].$f,$DatosLibro["NIT"])
            ->setCellValue($this->Campos[3].$f,$DatosLibro["Tercero_Razon_Social"])
            ->setCellValue($this->Campos[4].$f,$DatosLibro["SaldoAnterior"])
            ->setCellValue($this->Campos[5].$f,$DatosLibro["Debitos"])  
            ->setCellValue($this->Campos[6].$f,$DatosLibro["Creditos"])
            ->setCellValue($this->Campos[7].$f,$DatosLibro["SaldoAnterior"]+$DatosLibro["Debitos"]-$DatosLibro["Creditos"])
               
            ;
            $f++;
        }
       
        $Color='0080e9fa';
                
        $objPHPExcel->getActiveSheet()->getStyle("A$f:H$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB($Color);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"")
            ->setCellValue($this->Campos[1].$f,"TOTALES CUENTA $Cuenta")
            ->setCellValue($this->Campos[2].$f,"")
            ->setCellValue($this->Campos[3].$f,"")
            ->setCellValue($this->Campos[4].$f,$TotalSaldoAnterior) 
            ->setCellValue($this->Campos[5].$f,$TotalDebitosCuenta)
            ->setCellValue($this->Campos[6].$f,$TotalCreditosCuenta)
            ->setCellValue($this->Campos[7].$f,$TotalNuevoSaldo)      
            ;
        $TotalDebitosCuenta=0;
        $TotalCreditosCuenta=0;
        $TotalSaldoAnterior=0;
        $TotalNuevoSaldo=0;
        $f++;
        
        $objPHPExcel->getActiveSheet()->getStyle("A$f:H$f")->getFill() ->setFillType(PHPExcel_Style_Fill::FILL_SOLID) ->getStartColor()->setARGB('00defa80');
                
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($this->Campos[0].$f,"")
            ->setCellValue($this->Campos[1].$f,"")
            ->setCellValue($this->Campos[2].$f,"")
            ->setCellValue($this->Campos[3].$f,"")
            ->setCellValue($this->Campos[4].$f,"TOTALES")
            ->setCellValue($this->Campos[5].$f,$TotalDebitos)
            ->setCellValue($this->Campos[6].$f,$TotalCreditos)
               
            ;
        //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("www.technosoluciones.com.co")
        ->setLastModifiedBy("www.technosoluciones.com.co")
        ->setTitle("Auxiliar por Documento")
        ->setSubject("Informe")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("techno soluciones sas")
        ->setCategory("Informes Ventas");    
 
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."AuxiliarXTercero".'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
    $objWriter->save('php://output');
    exit; 
        
    }
    
   //Fin Clases
}
    