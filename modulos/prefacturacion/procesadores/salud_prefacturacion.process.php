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
            
            
            if($TipoFormulario==1){//Si es creacion
                $sql="SELECT ID FROM prefactura_paciente WHERE TipoDocumento='$TipoDocumento' AND NumeroDocumento='$NumeroDocumento' ";
                $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
                if($Validacion["ID"]<>''){
                    exit("OK;El Paciente ya Existe;NumeroDocumento");
                }
                $Datos["Created"]=date("Y-m-d H:i:s");
                $Datos["idUser"]=$idUser;
                $sql=$obCon->getSQLInsert("prefactura_paciente", $Datos);
            }
            if($TipoFormulario==2){
                $sql=$obCon->getSQLUpdate("prefactura_paciente", $Datos);
                $sql.=" WHERE ID='$idEditar'";
            }
            $obCon->Query($sql);
            print("OK;Registro Guardado Correctamente");
            
        break;//Fin caso 3
        
        case 4://Guarda o edita una reserva
            
            $Datos["idPaciente"]=$obCon->normalizar($_REQUEST["idPaciente"]);
            $Datos["NumeroAutorizacion"]=$obCon->normalizar($_REQUEST["NumeroAutorizacion"]);
            $Datos["CantidadServicios"]=$obCon->normalizar($_REQUEST["CantidadServicios"]);
            $Datos["Cie10"]=$obCon->normalizar($_REQUEST["Cie10"]);
            $Datos["Observaciones"]=$obCon->normalizar($_REQUEST["Observaciones"]);
            
            $TipoFormulario=$obCon->normalizar($_REQUEST["TipoFormulario"]);
            $idEditar=$obCon->normalizar($_REQUEST["idEditar"]);
            
            foreach ($Datos as $key => $value) {
                if($value=='' ){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            if(!is_numeric($Datos["CantidadServicios"]) or $Datos["CantidadServicios"]<=0){
                exit("E1;El campo Cantidad de Servicios Debe ser un valor Numerico mayor a Cero;CantidadServicios");
            }
            $Tabla="prefactura_reservas";
            $idPaciente=$Datos["idPaciente"];
            $NumeroAutorizacion=$Datos["NumeroAutorizacion"];
            
            
            if($TipoFormulario==1){
                $sql="SELECT ID FROM $Tabla WHERE idPaciente='$idPaciente' AND NumeroAutorizacion='$NumeroAutorizacion' ";
                $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
                if($Validacion["ID"]<>''){
                    exit("E1;El Numero de autorizacion ya ha sido usado para este paciente;NumeroAutorizacion");
                }
                $Datos["Created"]=date("Y-m-d H:i:s");
                $Datos["idUser"]=$idUser;
                $Datos["Estado"]=1;
                $sql=$obCon->getSQLInsert($Tabla, $Datos);
                $obCon->Query($sql);
                $idReserva=$obCon->ObtenerMAX($Tabla, "ID", 1,"");
            }
            if($TipoFormulario==2){
                $sql=$obCon->getSQLUpdate($Tabla, $Datos);
                $sql.=" WHERE ID='$idEditar'";
                $obCon->Query($sql);
                $idReserva=$idEditar;
            }
            
            print("OK;Registro Guardado Correctamente en el ID: $idReserva;$idReserva");
            
        break;//Fin caso 4
        
        case 5://Agregar una cita a una reserva
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            $idHospital=$obCon->normalizar($_REQUEST["idHospital"]);
            $Fecha=$obCon->normalizar($_REQUEST["Fecha"]);
            $Hora=$obCon->normalizar($_REQUEST["Hora"]);
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            if($idHospital==''){
                exit("E1;Debe seleccionar un hospital;idHospital");
            }
            if($Fecha==''){
                exit("E1;Debe seleccionar una fecha;Fecha");
            }
            if($Hora==''){
                exit("E1;Debe Seleccionar una Hora;Hora");
            }
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $sql="SELECT COUNT(ID) as TotalCitas FROM prefactura_reservas_citas WHERE idReserva='$idReserva' AND Estado<10";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            if($Validacion["TotalCitas"]>=$DatosReserva["CantidadServicios"]){
                exit("E1;Ya se agregaron todas las citas autorizadas");
            }
            $obCon->AgregarCitaReserva($idReserva, $idHospital, $Fecha, $Hora,$Observaciones, $idUser);
            $CitasDisponibles=$DatosReserva["CantidadServicios"]-($Validacion["TotalCitas"]+1);
            if($DatosReserva["Estado"]==1){
                $obCon->ActualizaRegistro("prefactura_reservas", "Estado", 2, "ID", $idReserva);
            }            
            exit("OK;Cita Agregada;$CitasDisponibles");
            
            
        break;//Fin caso 5  
        
        case 6://Eliminar una cita a una reserva
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            if($idItem==''){
                exit("E1;No se recibió la cita a eliminar");
            }
            
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $sql="SELECT COUNT(ID) as TotalCitas FROM prefactura_reservas_citas WHERE idReserva='$idReserva' AND Estado<10";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            $obCon->ActualizaRegistro("prefactura_reservas_citas", "Estado", 11, "ID", $idItem, 0);            
            $CitasDisponibles=$DatosReserva["CantidadServicios"]-($Validacion["TotalCitas"]-1);
            
            if($CitasDisponibles==$DatosReserva["CantidadServicios"]){
                $obCon->ActualizaRegistro("prefactura_reservas", "Estado", 1, "ID", $idReserva);
            }
            exit("OK;Cita Borrada;$CitasDisponibles");
            
            
        break;//Fin caso 6
        
        case 7://Confirmar una cita de una reserva
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            if($idItem==''){
                exit("E1;No se recibió la cita a Confirmar");
            }
            
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            
            $obCon->ActualizaRegistro("prefactura_reservas_citas", "Estado", 2, "ID", $idItem, 0);            
            
            exit("OK;Cita Confirmada");
            
            
        break;//Fin caso 7
        
        case 8://valida una reserva
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            $Fecha=$obCon->normalizar($_REQUEST["Fecha"]);
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            
            if($idReserva==''){
                exit("E1;No se recibió el id de la Reserva");
            }
            if($Fecha==''){
                exit("E1;Debe seleccionar una fecha de Validacion;Fecha");
            }
            if($Observaciones==''){
                exit("E1;Debe escribir las observaciones de la validacion;Observaciones");
            }
            
            $Extension="";
            if(!empty($_FILES['SoporteValidacionReserva']['name'])){
                
                $info = new SplFileInfo($_FILES['SoporteValidacionReserva']['name']);
                $Extension=($info->getExtension());  
                $Tamano=filesize($_FILES['SoporteValidacionReserva']['tmp_name']);
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/Reservas/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/Reservas/$idReserva/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                opendir($carpeta);
                $idAdjunto=uniqid(true);
                $destino=$carpeta.$idReserva."_".$idAdjunto.".".$Extension;
                
                move_uploaded_file($_FILES['SoporteValidacionReserva']['tmp_name'],$destino);
                
            }else{
                exit("E1;Debe adjuntar un soporte de la validacion;SoporteValidacionReserva");
            }
            $DatosValidacio=$obCon->DevuelveValores("prefactura_reservas_validacion", "idReserva", $idReserva);
            if($DatosValidacio["ID"]<>''){
                exit("E1;Esta Reserva ya fué validada");
            }
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            if($DatosReserva["Estado"]<=2){
                $obCon->ActualizaRegistro("prefactura_reservas", "Estado", 3, "ID", $idReserva);
            }
            $obCon->ValidarReserva($idReserva, $Fecha, $Observaciones, $destino, $Tamano, $_FILES['SoporteValidacionReserva']['name'], $Extension, $idUser);
            
            exit("OK;Reserva $idReserva Validada");
        break;//Fin caso 8    
           
        case 9://Eliminar la validacion de una reserva
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            if($idItem==''){
                exit("E1;No se recibió la cita a eliminar");
            }
            
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $sql="SELECT COUNT(ID) as TotalCitas FROM prefactura_reservas_citas WHERE idReserva='$idReserva' AND Estado<10";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            $obCon->BorraReg("prefactura_reservas_validacion", "ID", $idItem);  
            $CitasDisponibles=$DatosReserva["CantidadServicios"]-($Validacion["TotalCitas"]);
            
            if($CitasDisponibles==$DatosReserva["CantidadServicios"]){
                $obCon->ActualizaRegistro("prefactura_reservas", "Estado", 1, "ID", $idReserva);
            }else{
                $obCon->ActualizaRegistro("prefactura_reservas", "Estado", 2, "ID", $idReserva);
            }
            exit("OK;Validacion Borrada");
            
            
        break;//Fin caso 9
        
        case 10://Adjuntar un documento a una cita
            $idCita=$obCon->normalizar($_REQUEST["idCita"]);
            
            if($idCita==''){
                exit("E1;No se recibió el id de la Reserva");
            }
            
            $Extension="";
            if(!empty($_FILES['upSoporte']['name'])){
                
                $info = new SplFileInfo($_FILES['upSoporte']['name']);
                $Extension=($info->getExtension());  
                $Tamano=filesize($_FILES['upSoporte']['tmp_name']);
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/Citas/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta="../../../SoportesSalud/SoportesPrefacturacion/Citas/$idCita/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                opendir($carpeta);
                $idAdjunto=uniqid(true);
                $destino=$carpeta.$idCita."_".$idAdjunto.".".$Extension;
                
                move_uploaded_file($_FILES['upSoporte']['tmp_name'],$destino);
                
            }else{
                exit("E1;Debe adjuntar un documento;upSoporte");
            }
            
            $DatosCita=$obCon->DevuelveValores("prefactura_reservas_citas", "ID", $idCita);
            if($DatosCita["Estado"]<=2){
                $obCon->ActualizaRegistro("prefactura_reservas_citas", "Estado", 3, "ID", $idCita);
            }
            $obCon->AdjuntarDocumentoCita($idCita, $destino, $Tamano, $_FILES['upSoporte']['name'], $Extension, $idUser);
            exit("OK;Documento Adjuntado a la cita $idCita");
        break;//Fin caso 10
        
        case 11://Eliminar un adjunto de una cita
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idCita=$obCon->normalizar($_REQUEST["idCita"]);
            
            if($idCita==''){
                exit("E1;No se recibió el id de la cita");
            }
            if($idItem==''){
                exit("E1;No se recibió el adjunto a eliminar");
            }
            
            
            $obCon->BorraReg("prefactura_reservas_citas_adjuntos", "ID", $idItem);  
            $sql="SELECT COUNT(ID) as TotalAdjuntos FROM prefactura_reservas_citas_adjuntos WHERE idCita='$idCita'";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            $DatosCita=$obCon->DevuelveValores("prefactura_reservas_citas", "ID", $idCita);
            
            if($DatosCita["Estado"]<=3 and $Validacion["TotalAdjuntos"]==0){
                $obCon->ActualizaRegistro("prefactura_reservas_citas", "Estado", 2, "ID", $idCita);
            }
            exit("OK;Adjunto Eliminado");
            
            
        break;//Fin caso 11
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>