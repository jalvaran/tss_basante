<?php

/* 
 * Este archivo se encarga de escuchar las peticiones para editar un registro
 */

if(!empty($_REQUEST["BtnEditarRegistro"])){
    include_once("../../modelo/php_tablas.php");  //Clases de donde se escribirán las tablas
    session_start();
    $idUser=$_SESSION['idUser'];
    $obTabla = new Tabla($db);
    $obVenta = new conexion($idUser);
    $stament=$_REQUEST["TxtStament"];
    $tab=$_REQUEST["TxtTablaEdit"];
    if(isset($_REQUEST["TxtTabla"])){
        $tab=$_REQUEST["TxtTabla"];  
    }
    $myPage1=explode(".",$_REQUEST["TxtMyPage"]);
    $myPage=$myPage1[0];
    $IDEdit=$_REQUEST["TxtIDEdit"];
    $Vector["Tabla"]=$tab;
    $NombresColumnas=$obTabla->Columnas($Vector);
    $sql="UPDATE $tab SET ";
    //$NumCols=Count($NombresColumnas);
    $i=1;
    foreach($NombresColumnas as $NombreCol){
        if(!empty($_FILES[$NombreCol]['name'])){
            $dir= "../../";
            if($tab=="productosventa"){
                $carpeta="ImagenesProductos/";
            }else if($tab=="empresapro"){
                $carpeta="LogosEmpresas/";
            
            }else if($tab=="egresos"){
                $carpeta="SoportesEgresos/";
            
            }else if($tab=="clientes" or $tab=="proveedores"){
                $carpeta="SoportesTS5/Terceros/";
            }
            opendir($dir.$carpeta);
            $Name=str_replace(' ','_',$_FILES[$NombreCol]['name']);  
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES[$NombreCol]['tmp_name'],$dir.$destino);
            $obVenta->ActualizaRegistro($tab, $NombreCol, $destino, $NombresColumnas[0], $IDEdit,0);
        }
        $NumCar=strlen($_REQUEST[$NombreCol]);
        if(isset($_REQUEST[$NombreCol]) && $NumCar>0){
            $Edicion=$_REQUEST[$NombreCol];
            if($NombreCol=='FormaPago' && $tab=="facturas" && $_REQUEST[$NombreCol]<>"Contado"){
                $Edicion="Credito a $_REQUEST[$NombreCol] dias";
                
            }
            
            $obVenta->ActualizaRegistro($tab, $NombreCol, $Edicion, $NombresColumnas[0], $IDEdit,0);
        }
       $i++;
    }
    //$sql=substr($sql, 0, -1);
    
    if($tab=="facturas"){
       
        $DatosFactura=$obVenta->DevuelveValores("facturas", "idFacturas", $IDEdit);
        
        if($_REQUEST["FormaPago"]<>"Contado"){
            $Datos["Fecha"]=$DatosFactura["Fecha"];                
            $Datos["Dias"]=$_REQUEST["FormaPago"];
            $FechaVencimiento=$obVenta->SumeDiasFecha($Datos);
            $Datos["idFactura"]=$IDEdit; 
            $Datos["FechaFactura"]=$DatosFactura["Fecha"]; 
            $Datos["FechaVencimiento"]=$FechaVencimiento;
            $Datos["idCliente"]=$DatosFactura["Clientes_idClientes"];
            $obVenta->InsertarFacturaEnCartera($Datos);///Inserto La factura en la cartera

            $sql="UPDATE `librodiario` SET `CuentaPUC`='130505',`NombreCuenta`='CLIENTES NACIONALES' WHERE `Num_Documento_Interno`='$IDEdit' AND `CuentaPUC` LIKE '11%'";
            $obVenta->Query($sql);
        }
        
        $DatosTercero=$obVenta->DevuelveValores("clientes", "idClientes", $DatosFactura["Clientes_idClientes"]);
        $obVenta->ActualiceTerceroLibroDiario("FACTURA", $IDEdit, "clientes", $DatosTercero["Num_Identificacion"]);
        $obVenta->ActualiceClienteCartera($IDEdit, "clientes", $DatosTercero["Num_Identificacion"]);
    }
    
   $Fecha=date("Y-m-d H:i:s");
   $obVenta->ActualizaRegistro($tab, "Updated", $Fecha, $NombresColumnas[0], $IDEdit);
    
    $PageReturn=  substr($myPage, 0, 21);
    if($PageReturn=="productosventa_bodega"){
        $myPage="bodegas_externas.php?CmbBodega=$Vector[Tabla]&TxtStament=$stament";
        header("location:../$myPage");
        exit();
    }
    
    if($tab=="usuarios"){
        
        $Vector["Tabla"]="usuarios";        
        $DatosUsuario=$obVenta->ValorActual($tab, "Password", " idUsuarios='$IDEdit'");        
        $obVenta->ActualizaRegistro($tab, "Password", md5($DatosUsuario["Password"]), "idUsuarios", $IDEdit);
        
    }
    
    //$obVenta->ActualizaRegistro($tab, "Updated", date("Y-m-d H:i:s"), $NombresColumnas[0], $IDEdit);
    
    header("location:../$myPage.php");
}


?>
