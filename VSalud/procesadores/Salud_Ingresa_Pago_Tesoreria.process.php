<?php 
$obVenta=new conexion($idUser);
// si se requiere guardar y cerrar
if(isset($_REQUEST["BtnGuardar"])){
    
    $idEPS=$obVenta->normalizar($_REQUEST["CmbEps"]);
    $idBanco=$obVenta->normalizar($_REQUEST["CmbBanco"]);
    $Fecha=$obVenta->normalizar($_REQUEST["TxtFecha"]);
    $NumTransaccion=$obVenta->normalizar($_REQUEST["TxtNumTransaccion"]);
    $Pago=$obVenta->normalizar($_REQUEST["TxtPago"]);
    $Observaciones=$obVenta->normalizar($_REQUEST["TxtObservaciones"]);
    
    $DatosEPS=$obVenta->DevuelveValores("salud_eps", "cod_pagador_min", $idEPS);
    $DatosBanco=$obVenta->DevuelveValores("salud_bancos", "ID", $idBanco);
    $destino="";
    //echo "<script>alert ('entra')</script>";
    if(!empty($_FILES['Soporte']['name'])){
        //echo "<script>alert ('entra foto')</script>";
            $Atras="../";
            $carpeta="SoportesSalud/SoportesPagosTesoreria/";
            opendir($Atras.$carpeta);
            $Name=str_replace(' ','_',$_FILES['Soporte']['name']);  
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES['Soporte']['tmp_name'],$Atras.$destino);
    }   
    
    $tab="salud_tesoreria";
    $NumRegistros=12;  


    $Columnas[0]="cod_enti_administradora";	$Valores[0]=$idEPS;
    $Columnas[1]="nom_enti_administradora";	$Valores[1]=$DatosEPS["nombre_completo"];
    $Columnas[2]="fecha_transaccion";           $Valores[2]=$Fecha;
    $Columnas[3]="num_transaccion";		$Valores[3]=$NumTransaccion;
    $Columnas[4]="banco_transaccion";		$Valores[4]=$DatosBanco["banco_transaccion"];
    $Columnas[5]="num_cuenta_banco";		$Valores[5]=$DatosBanco["num_cuenta_banco"];
    $Columnas[6]="valor_transaccion";		$Valores[6]=$Pago;
    $Columnas[7]="Soporte";			$Valores[7]=$destino;
    $Columnas[8]="observacion";			$Valores[8]=$Observaciones;
    $Columnas[9]="fecha_hora_registro";		$Valores[9]=date("Y-m-d H:i:s");
    $Columnas[10]="idUser";			$Valores[10]=$idUser;
    $Columnas[11]="valor_legalizar";	        $Valores[11]=$Pago;
    
    $obVenta->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
    
    header("location:$myPage?TransaccionOk=1");
    
}


///////////////fin
?>