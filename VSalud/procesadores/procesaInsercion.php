<?php
//include_once("../../modelo/php_tablas.php");  //Clases de donde se escribirán las tablas
/* 
 * Este archivo se encarga de eschuchar las peticiones para guardar un archivo
 */

/* 
 * Si se Solicita Guardar un Registro
 */
if(!empty($_REQUEST["BtnGuardarRegistro"])){
    include_once("../../modelo/php_tablas.php");  //Clases de donde se escribirán las tablas
    $obTabla = new Tabla($db);
    $obVenta = new conexion(1);
    $tab=$_REQUEST["TxtTablaInsert"];
    $Vector["Tabla"]=$tab;
    $NombresColumnas=$obTabla->Columnas($Vector);
    
    $i=0;   
    foreach($NombresColumnas as $NombreCol){
        if($NombreCol=="RutaImagen"){
               $destino="";
                //echo "<script>alert ('entra a las columnas $NombreCol')</script>"; 
		if(!empty($_FILES['RutaImagen']['name'])){
                    //echo "<script>alert ('entra foto')</script>";
                        $dir= "../../";
                        if($tab=="productosventa"){
                            
                            $carpeta="ImagenesProductos/";
                        }else{
                            $carpeta="LogosEmpresas/";
                        }
			opendir($dir.$carpeta);
                        $Name=str_replace(' ','_',$_FILES['RutaImagen']['name']);  
			$destino=$carpeta.$Name;
			move_uploaded_file($_FILES['RutaImagen']['tmp_name'],$dir.$destino);
		}
                $Columnas[$i]=$NombreCol;  $Valores[$i]=$destino;
                $i++;
           }
        if(isset($_REQUEST[$NombreCol])){
            //echo "<script>alert ('entra a las columnas $NombreCol')</script>"; 
            if($NombreCol=="Updated"){
                $Valor=date("Y-m-d H:i:s");
            }else{
                $Valor=$_REQUEST[$NombreCol];
            }
                $Columnas[$i]=$NombreCol;  $Valores[$i]=$Valor;
            $i++;
        }
       
    }
        
    $obVenta->InsertarRegistro($tab,$i,$Columnas,$Valores);
    if($tab=="productosventa"){
        $Vector["Tabla"]="productosventa";
        $ID=$obTabla->ObtengaAutoIncrement($Vector);
        $ID=$ID-1;
        $obVenta->ActualizaRegistro("productosventa", "CodigoBarras", $ID, "idProductosVenta", $ID);
        if(empty($_REQUEST["Referencia"])){
            $obVenta->ActualizaRegistro("productosventa", "Referencia", "REF".$ID, "idProductosVenta", $ID);
        }
        //Buscamos si hay mas bodegas para insertar los valores en cada una
        $SqlCB="SELECT CodigoBarras FROM prod_codbarras WHERE ProductosVenta_idProductosVenta='$ID' LIMIT 1";
        $DatosCodigo=$obVenta->Query($SqlCB);
        $DatosCodigo=$obVenta->FetchArray($DatosCodigo);
        $Datos=$obVenta->ConsultarTabla("bodega", "");
        
        while($DatosBodegas=$obVenta->FetchArray($Datos)){
            $tabBodegas="productosventa_bodega_$DatosBodegas[0]";
            
            $Vector["Tabla"]=$tabBodegas;
            $ID=$obTabla->ObtengaAutoIncrement($Vector);
            $ID=$ID;
                        
            if(empty($_REQUEST["Referencia"])){
                $Valores[0]="REF".$ID;
            }
            
            $obVenta->InsertarRegistro($tabBodegas,$i,$Columnas,$Valores);
            
            $obVenta->ActualizaRegistro($tabBodegas, "CodigoBarras", $ID, "idProductosVenta", $ID);
            $tabBodegas="prod_codbarras_bodega_$DatosBodegas[0]";
            $Columnas2[0]="ProductosVenta_idProductosVenta";    $Valores2[0]=$ID;
            $Columnas2[1]="CodigoBarras";                       $Valores2[1]=$DatosCodigo["CodigoBarras"];
            $obVenta->InsertarRegistro($tabBodegas, 2, $Columnas2, $Valores2);
        }
        
    }
    
    
    if($tab=="servicios"){
        
        
        if(empty($_REQUEST["Referencia"])){
            $Vector["Tabla"]="servicios";
            $ID=$obTabla->ObtengaAutoIncrement($Vector);
            $ID=$ID-1;
            $obVenta->ActualizaRegistro($tab, "Referencia", "REFSER".$ID, "idProductosVenta", $ID);
        }
        
        
    }
    
    if($tab=="productosalquiler"){
        
        
        if(empty($_REQUEST["Referencia"])){
            $Vector["Tabla"]="productosalquiler";
            $ID=$obTabla->ObtengaAutoIncrement($Vector);
            $ID=$ID-1;
            $obVenta->ActualizaRegistro($tab, "Referencia", "REFPQ".$ID, "idProductosVenta", $ID);
        }
        
        
    }
    
    /*
     * Si se crea una sucursal
     */
    if($tab=="bodega"){
      $Vector["Tabla"]=$tab;
      $ID=$obTabla->ObtengaAutoIncrement($Vector);
      $ID=$ID-1;
      $VectorB["F"]=0;
      
      $obVenta->CrearTablaBodegaSucursal($ID, $VectorB);   
        
    }
    
    /*
     * Si se crea una promocion
     */
    if($tab=="titulos_promociones"){
      $Vector["Tabla"]=$tab;
      $ID=$obTabla->ObtengaAutoIncrement($Vector);
      $ID=$ID-1;
      $obVenta->CrearTablaListadoTitulos($ID, "");   
        
    }
   header("location:../$tab.php");
}
?>