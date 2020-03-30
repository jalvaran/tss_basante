<?php 
$obVenta=new conexion($idUser);
// si se requiere guardar y cerrar
if(isset($_REQUEST["BtnRadicar"])){
    
    $idEPS=$obVenta->normalizar($_REQUEST["CmbEPS"]);
    $FechaRadicado=$obVenta->normalizar($_REQUEST["TxtFechaRadicado"]);
    $NumeroRadicado=$obVenta->normalizar($_REQUEST["TxtNumeroRadicado"]);
    $FechaInicial=$obVenta->normalizar($_REQUEST["TxtFechaInicial"]);
    $FechaFinal=$obVenta->normalizar($_REQUEST["TxtFechaFinal"]);
    $DatosEPS=$obVenta->DevuelveValores("salud_eps", "cod_pagador_min", $idEPS);
    $Dias=$DatosEPS["dias_convenio"];
    $destino="";
    //echo "<script>alert ('entra')</script>";
    if(!empty($_FILES['Soporte']['name'])){
        //echo "<script>alert ('entra foto')</script>";
            $Atras="../";
            $carpeta="SoportesSalud/SoportesRadicados/";
            opendir($Atras.$carpeta);
            $Name=str_replace(' ','_',$_FILES['Soporte']['name']);  
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES['Soporte']['tmp_name'],$Atras.$destino);
    }   
    
    $sql="UPDATE salud_archivo_facturacion_mov_generados SET eps_radicacion='$idEPS', dias_pactados='$Dias',"
            . " fecha_radicado='$FechaRadicado', numero_radicado='$NumeroRadicado',Soporte='$destino',estado='RADICADO' "
            . " WHERE cod_enti_administradora='$idEPS' AND fecha_factura>='$FechaInicial' AND fecha_factura<='$FechaFinal' "
            . "AND numero_radicado=''";
    $obVenta->Query($sql);
    header("location:$myPage");
    
}

// si se radica por numeros
if(isset($_REQUEST["BtnRadicarXNumero"])){
    
    $idEPS=$obVenta->normalizar($_REQUEST["idEps"]);
    $FechaRadicado=$obVenta->normalizar($_REQUEST["TxtFechaRadicado"]);
    $NumeroRadicado=$obVenta->normalizar($_REQUEST["TxtNumeroRadicado"]);
    $NumeroInicial=$obVenta->normalizar($_REQUEST["TxtNumFactInicial"]);
    $NumeroFinal=$obVenta->normalizar($_REQUEST["TxtNumFactFinal"]);
    $DatosEPS=$obVenta->DevuelveValores("salud_eps", "cod_pagador_min", $idEPS);
    $Dias=$DatosEPS["dias_convenio"];
    $destino="";
    //echo "<script>alert ('entra')</script>";
    if(!empty($_FILES['Soporte']['name'])){
        //echo "<script>alert ('entra foto')</script>";
            $Atras="../";
            $carpeta="SoportesSalud/SoportesRadicados/";
            opendir($Atras.$carpeta);
            $Name=str_replace(' ','_',$_FILES['Soporte']['name']);  
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES['Soporte']['tmp_name'],$Atras.$destino);
    }   
    //Si se selecciona un rango de numeros
    if($NumeroInicial<>'' AND $NumeroFinal<>''){
        $CondicionAdicional="AND REPLACE(REPLACE(REPLACE(`num_factura`,'-',''),'FV',''),' ','')>='$NumeroInicial' "
            . "AND REPLACE(REPLACE(REPLACE(`num_factura`,'-',''),'FV',''),' ','')<='$NumeroFinal'";
        $sql="UPDATE salud_archivo_facturacion_mov_generados SET eps_radicacion='$idEPS', dias_pactados='$Dias',"
            . " fecha_radicado='$FechaRadicado', numero_radicado='$NumeroRadicado',Soporte='$destino',estado='RADICADO' "
            . " WHERE cod_enti_administradora='$idEPS' $CondicionAdicional AND numero_radicado=''";
        $obVenta->Query($sql);
    }
    
    //Se revisa la tabla de preradicados y se actualiza
    
   $sql="SELECT id_fac_mov_generados,num_factura FROM salud_facturas_radicacion_numero fr "
                . "INNER JOIN salud_archivo_facturacion_mov_generados fg ON fr.idFactura=fg.id_fac_mov_generados"
                . " WHERE fr.idUser='$idUser' ";
    
   $consulta=$obVenta->Query($sql);
   while($DatosFacturas=$obVenta->FetchArray($consulta)){
       $NumeroFactura=$DatosFacturas["num_factura"];
       $sql="UPDATE salud_archivo_facturacion_mov_generados SET eps_radicacion='$idEPS', dias_pactados='$Dias',"
            . " fecha_radicado='$FechaRadicado', numero_radicado='$NumeroRadicado',Soporte='$destino',estado='RADICADO' "
            . " WHERE num_factura='$NumeroFactura' AND numero_radicado=''";
        $obVenta->Query($sql);
        
   }
   $obVenta->BorraReg("salud_facturas_radicacion_numero", "idUser", $idUser);
   header("location:$myPage");
    
}
///////////////fin
?>