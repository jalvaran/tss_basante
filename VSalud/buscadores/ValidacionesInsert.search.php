<?php
include_once("../../modelo/php_conexion.php");

$obCon=new conexion(1);

if(isset($_REQUEST['idAccion'])){
    $idAccion=$obCon->normalizar($_REQUEST['idAccion']);
    
    switch ($idAccion) {
        case 1:
            $key=$obCon->normalizar($_REQUEST['cod_pagador_min']);
            $sql="SELECT cod_pagador_min FROM salud_eps WHERE cod_pagador_min = '$key'";
            $consulta=$obCon->Query($sql);
            $Datos=$obCon->FetchArray($consulta);
            if($Datos["cod_pagador_min"]==''){
                print("OK");
            }else{
                print("Error");
            }

            break;

        
    }
    
    
}else{
    print("No se recibieron datos");
}
