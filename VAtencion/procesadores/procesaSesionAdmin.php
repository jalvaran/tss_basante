<?php 


	//////Creo una Sesion
	if(isset($_REQUEST['BtnCrearSesion'])){
		
            $obVenta=new conexion($idUser);
            $FechaSesion=$obVenta->normalizar($_REQUEST["TxtFechaSesion"]);
            $TipoSesion=$obVenta->normalizar($_REQUEST["CmbTipoSesion"]);
            $NombreSesion=$obVenta->normalizar($_REQUEST["TxtSesion"]);
            $obVenta->CrearSesionConsejo($FechaSesion,$TipoSesion,$NombreSesion,"");// Crea otra preventa
            
            header("location:$myPage");
	}
        
        //////Creo una Sesion
	if(isset($_REQUEST['BtnControlCrono'])){
		
            $obVenta=new conexion($idUser);
            $Orden=$obVenta->normalizar($_REQUEST["BtnControlCrono"]);
            if($Orden=="INICIAR"){
                $obVenta->ActualizaRegistro("crono_controles", "Estado", "PLAY", "ID", 1);
            }
            if($Orden=="PAUSAR"){
                $obVenta->ActualizaRegistro("crono_controles", "Estado", "STOP", "ID", 1);
            }
            if($Orden=="REINICIAR"){
                $obVenta->ActualizaRegistro("crono_controles", "Estado", "REINICIO", "ID", 1);
                $obVenta->ActualizaRegistro("crono_controles", "idConcejal", "0", "ID", 1);
                $obVenta->ActualizaRegistro("crono_controles", "Fin", "0", "ID", 1);
            }
                
            header("location:$myPage");
	}
	
       //////Otorga la palabra
	if(isset($_REQUEST['BtnOtorgarTiempo'])){
		
            $obVenta=new conexion($idUser);
            $idSesionConcejo=$obVenta->normalizar($_REQUEST["idSesionConcejo"]);
            $idConcejal=$obVenta->normalizar($_REQUEST["idConcejal"]);
            $idTiempo=$obVenta->normalizar($_REQUEST["idTiempo"]);
            $obVenta->OtorgarPalabraSesion($idSesionConcejo,$idConcejal,$idTiempo,"");
            
            header("location:$myPage");
	}
        ///////////////Fin
        
	?>