<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class ProcesoGerencial extends conexion{
    public function CrearProcesoGerencial($Fecha,$idIps,$idEps,$Nombre,$idConcepto,$Observaciones,$idUser,$Vector) {
        
        $DatosProcesosGerenciales= $this->DevuelveValores("salud_procesos_gerenciales_conceptos", "ID", $idConcepto);
        $Concepto=$DatosProcesosGerenciales["Concepto"];
                
        //////Creo el proceso           
        $tab="salud_procesos_gerenciales";
        $NumRegistros=6;

        $Columnas[0]="Fecha";		$Valores[0]=$Fecha;
        $Columnas[1]="NombreProceso";   $Valores[1]=$Nombre;
        $Columnas[2]="Concepto";        $Valores[2]=$Concepto;
        $Columnas[3]="EPS";             $Valores[3]=$idEps;
        $Columnas[4]="idUser";         $Valores[4]=$idUser;
        $Columnas[5]="IPS";             $Valores[5]=$idIps;
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        $id=$this->ObtenerMAX($tab, "ID", 1, "");
        $this->AgregarSoporteProcesoGerencial($Fecha, $id, $Observaciones, $Vector);
              
    }
    
    public function AgregarSoporteProcesoGerencial($Fecha,$idProceso,$Observaciones,$Vector) {
        $id=$this->ObtenerMAX("salud_procesos_gerenciales_archivos", "ID", 1, "");
        $id=$id+1;  //para evitar sobreescribir archivos
        if(!empty($_FILES['Soporte']['name'])){
            //echo "<script>alert ('entra foto')</script>";
            $Atras="../";
            $carpeta="SoportesSalud/ProcesosGerenciales/";
            opendir($Atras.$carpeta);
            $Name=$id."_".str_replace(' ','_',$_FILES['Soporte']['name']);
            $destino=$carpeta.$Name;
            move_uploaded_file($_FILES['Soporte']['tmp_name'],$Atras.$destino);
	}
        $tab="salud_procesos_gerenciales_archivos";
        $NumRegistros=4;

        $Columnas[0]="Fecha";		$Valores[0]=$Fecha;
        $Columnas[1]="idProceso";       $Valores[1]=$idProceso;
        $Columnas[2]="Observaciones";   $Valores[2]=$Observaciones;
        $Columnas[3]="Soporte";         $Valores[3]=$destino;
                
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
    }
    
    //Fin Clases
}