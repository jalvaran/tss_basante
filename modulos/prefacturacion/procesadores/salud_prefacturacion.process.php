<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

include_once("../clases/salud_prefacturacion.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new Prefacturacion($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Validar si existe un paciente
            
            $TipoDocumento=$obCon->normalizar($_REQUEST["TipoDocumento"]);
            $NumeroDocumento=$obCon->normalizar($_REQUEST["NumeroDocumento"]);
            if($TipoDocumento==''){
                exit("E1;Debe seleccionar un Tipo de Documento;TipoDocumento");
            }
            if(!is_numeric($NumeroDocumento) or $NumeroDocumento<0){
                exit("E1;El campo debe contener un Valor Numerico mayor a cero;NumeroDocumento");
            }
            
            $sql="SELECT ID FROM prefactura_paciente WHERE TipoDocumento='$TipoDocumento' AND NumeroDocumento='$NumeroDocumento' ";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            if($Validacion["ID"]==''){
                print("OK;El Paciente no existe;NumeroDocumento");
            }else{
                print("E1;El Paciente ya Existe en el ID = ".$Validacion["ID"].";NumeroDocumento");
            }
            
        break;//Fin caso 1
        
        case 2://Calcula la edad de acuerdo a la fecha de nacimiento
            
            $FechaNacimiento=$obCon->normalizar($_REQUEST["FechaNacimiento"]);
            
            if(!(validateDate($FechaNacimiento, 'Y-m-d'))){
                exit("E1;Debe seleccionar una Fecha de Nacimiento válida;FechaNacimiento");
            }
            
            $DatosEdad=$obCon->CalcularEdad($FechaNacimiento);
            $Edad=$DatosEdad["Edad"];
            $Unidad=$DatosEdad["Unidad"];
            print("OK;Edad Calculada;FechaNacimiento;$Edad;$Unidad");
            
        break;//Fin caso 2 
        
        case 3://Guarda o edita un paciente
            
            $Datos["TipoDocumento"]=$obCon->normalizar($_REQUEST["TipoDocumento"]);
            $Datos["NumeroDocumento"]=$obCon->normalizar($_REQUEST["NumeroDocumento"]);
            $Datos["CodEPS"]=$obCon->normalizar($_REQUEST["CodEPS"]);
            $Datos["idRegimenPaciente"]=$obCon->normalizar($_REQUEST["idRegimenPaciente"]);
            $Datos["PrimerNombre"]=$obCon->normalizar($_REQUEST["PrimerNombre"]);
            $Datos["SegundoNombre"]=$obCon->normalizar($_REQUEST["SegundoNombre"]);
            $Datos["PrimerApellido"]=$obCon->normalizar($_REQUEST["PrimerApellido"]);
            $Datos["SegundoApellido"]=$obCon->normalizar($_REQUEST["SegundoApellido"]);
            $Datos["FechaNacimiento"]=$obCon->normalizar($_REQUEST["FechaNacimiento"]);
            //$Datos["Edad"]=$obCon->normalizar($_REQUEST["Edad"]);
            //$Datos["UnidadMedidaEdad"]=$obCon->normalizar($_REQUEST["UnidadMedidaEdad"]);
            $Datos["Sexo"]=$obCon->normalizar($_REQUEST["Sexo"]);
            $Datos["CodigoDANE"]=$obCon->normalizar($_REQUEST["CodigoDANE"]);
            $Datos["Direccion"]=$obCon->normalizar($_REQUEST["Direccion"]);
            $Datos["ZonaResidencial"]=$obCon->normalizar($_REQUEST["ZonaResidencial"]);
            $Datos["Telefono"]=$obCon->normalizar($_REQUEST["Telefono"]);
            $Datos["Correo"]=$obCon->normalizar($_REQUEST["Correo"]);
            $TipoFormulario=$obCon->normalizar($_REQUEST["TipoFormulario"]);
            $idEditar=$obCon->normalizar($_REQUEST["idEditar"]);
            
            if(!(validateDate($Datos["FechaNacimiento"], 'Y-m-d'))){
                exit("E1;Debe seleccionar una Fecha de Nacimiento válida;FechaNacimiento");
            }
            
            foreach ($Datos as $key => $value) {
                if($value=='' and $key<>'SegundoNombre' and $key<>'Correo'){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            if(!is_numeric($Datos["NumeroDocumento"]) or $Datos["NumeroDocumento"]<=0){
                exit("E1;El campo NumeroDocumento Debe ser un valor Numerico mayor a Cero;NumeroDocumento");
            }
            $TipoDocumento=$Datos["TipoDocumento"];
            $NumeroDocumento=$Datos["NumeroDocumento"];
            $sql="SELECT ID FROM prefactura_paciente WHERE TipoDocumento='$TipoDocumento' AND NumeroDocumento='$NumeroDocumento' ";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            if($Validacion["ID"]<>''){
                print("OK;El Paciente ya Existe;NumeroDocumento");
            }
            
            if($TipoFormulario==1){
                $Datos["Created"]=date("Y-m-d H:i:s");
                $sql=$obCon->getSQLInsert("prefactura_paciente", $Datos);
            }
            if($TipoFormulario==2){
                $sql=$obCon->getSQLUpdate("prefactura_paciente", $Datos);
                $sql.=" WHERE ID='$idEditar'";
            }
            $obCon->Query($sql);
            print("OK;Registro Guardado Correctamente");
            
        break;//Fin caso 3
           
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>