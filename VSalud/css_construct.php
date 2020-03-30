<?php
	

//////////////////////////////////////////////////////////////////////////
////////////Clase para iniciar css ///////////////////////////////////
////////////////////////////////////////////////////////////////////////

class CssIni{
	private $Titulo;
		
	function __construct($Titulo,$Headers=1){
            if($Headers==1){
                ?> <style> #ventana-flotante {
                        width: 360px;  /* Ancho de la ventana */
                        height: 90px;  /* Alto de la ventana */
                        background:#58B0E7;
                        background:-moz-linear-gradient(top,#B4F6FF 1px,#63D0FE 1px,#58B0E7);
                        background:-webkit-gradient(linear,0 0,0 100%,color-stop(0.02,#B4F6FF),color-stop(0.02,#63D0FE),color-stop(1,#58B0E7));  /* Color de fondo */
                        position: fixed;
                        top: 200px;
                        left: 50%;
                        margin-left: -180px;
                        color:#FFFFFF;
                        box-shadow:0px 1px #EDEDED;
                        -moz-box-shadow:0px 1px #EDEDED;
                        -webkit-box-shadow:0px 1px #EDEDED;
                        text-shadow:0px 1px #388DBE;
                        border-color:#3390CA;
                        border-radius: 4px;
                        }
                        #ventana-flotante #contenedor {
                        padding: 25px 10px 10px 10px;
                        }
                        #ventana-flotante .cerrar {
                        float: right;
                        border-bottom: 1px solid #bbb;
                        border-left: 1px solid #bbb;
                        color: white;
                        background: red;
                        line-height: 17px;
                        text-decoration: none;
                        padding: 0px 14px;
                        font-family: Arial;
                        border-radius: 0 0 0 5px;
                        box-shadow: -1px 1px white;
                        font-size: 18px;
                        -webkit-transition: .3s;
                        -moz-transition: .3s;
                        -o-transition: .3s;
                        -ms-transition: .3s;
                        }
                        #ventana-flotante .cerrar:hover {
                        background: #ff6868;
                        color: white;
                        text-decoration: none;
                        text-shadow: -1px -1px red;
                        border-bottom: 1px solid red;
                        border-left: 1px solid red;
                        }
                        #ventana-flotante #contenedor .contenido {
                        padding: 15px;
                        color:#FFFFFF;
                                box-shadow:0px 1px #EDEDED;
                                -moz-box-shadow:0px 1px #EDEDED;
                                -webkit-box-shadow:0px 1px #EDEDED;
                                text-shadow:0px 1px #3C3C3C;
                                border-color:#202020;
                                background:#525252;
                                background:-moz-linear-gradient(top,#9F9F9F 1px,#6C6C6C 1px,#525252);
                                background:-webkit-gradient(linear,0 0,0 100%,color-stop(0.02,#9F9F9F),color-stop(0.02,#6C6C6C),color-stop(1,#525252));        
                        margin: 0 auto;
                        border-radius: 4px;
                        }
                        .oculto {-webkit-transition:1s;-moz-transition:1s;-o-transition:1s;-ms-transition:1s;opacity:0;-ms-opacity:0;-moz-opacity:0;visibility:hidden;}

                .notification-container {
                    display:scroll; position:fixed; top:70px; right:10px;
                    width: 50px;
                    height: 50px;    
                    cursor:pointer;
                }

                .notification-counter {   
                    position: absolute;
                    top: -2px;
                    left: 25px;

                    background-color: rgba(212, 19, 13, 1);
                    color: #fff;
                    border-radius: 18px;
                    padding: 1px 3px;
                    font: 14px Verdana;
                }
                </style> 
                <?php
		print("
		<meta http-equiv=Content-Type content=text/html charset='UTF-8' />
		<title>$Titulo</title>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<meta name='description' content='Software de Techno Soluciones'>
		<meta name='author' content='Techno Soluciones SAS'>

		<!-- Le styles -->
		<link href='css/bootstrap.css' rel='stylesheet'>
		<link href='css/pagination.css' rel='stylesheet' type='text/css' />
		<link href='css/B_blue.css' rel='stylesheet' type='text/css' />
		<style type='text/css'>
		  body {
			padding-top: 60px;
			padding-bottom: 40px;
		  }
		</style>
		<link href='css/bootstrap-responsive.css' rel='stylesheet'>
                
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src='../assets/js/html5shiv.js'></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		
		<link rel='apple-touch-icon-precomposed' sizes='144x144' href='ico/apple-touch-icon-144-precomposed.png'>
		<link rel='apple-touch-icon-precomposed' sizes='114x114' href='ico/apple-touch-icon-114-precomposed.png'>
		<link rel='apple-touch-icon-precomposed' sizes='72x72' href='ico/apple-touch-icon-72-precomposed.png'>
                <link rel='apple-touch-icon-precomposed' href='ico/apple-touch-icon-57-precomposed.png'>
                                           <link rel='shortcut icon' href='../images/technoIco.ico'>
		
                <link rel='stylesheet' href='chousen/docsupport/prism.css'>
                <link rel='stylesheet' href='chousen/source/chosen.css'>
                <link rel='stylesheet' href='css/calendar.css'>   
                
                
                <link rel='stylesheet' href='alertify/themes/alertify.core.css' />
                <link rel='stylesheet' href='alertify/themes/alertify.default.css' id='toggleCSS' />
		");
		print('<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
                
		<script src="alertify/lib/alertify.min.js"></script>
                
                ');
            }   
            
	}
	
	/////////////////////Inicio una cabecera
	
	function CabeceraIni($Title){
		
		print('
			 <div class="navbar navbar-inverse navbar-fixed-top" >
			  <div class="navbar-inner">
				<div class="container">
				  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="brand" href="../VMenu/Menu.php">'.$Title.'</a>
				  <div class="nav-collapse collapse">
					<ul class="nav">
					<li>
					
		');
	}
	
	/////////////////////Cierro la Cabecera de la pagina
	
	function CabeceraFin(){
		
		print('
				</li>
				</ul>
				  </div><!--/.nav-collapse -->
				</div>
			  </div>
			</div>
		
		');
	}
	
	
	/////Crea botones con despliegue
		
	function CreaBotonDesplegable($NombreBoton,$TituloBoton,$idBoton="")
  {
	
		
	print('<li><a href="#'.$NombreBoton.'" id="'.$idBoton.'"  role="button" class="btn" data-toggle="modal" title="'.$TituloBoton.'">
			<span class="badge badge-success">'.$TituloBoton.'</span></a></li>');

	}	
	
	function CreaBotonAgregaPreventa($Page,$idUser)
  {
		
	print('	<a class="brand" href="'.$Page.'?BtnAgregarPreventa='.$idUser.'">+ Preventa</a>');

	}	
	
	
	/////////////////////Crea un Formulario
	
	function CrearForm($nombre,$action,$method,$target){
		print('<li>'
                        . '<form name= "'.$nombre.'" action="'.$action.'" id="'.$nombre.'" method="'.$method.'" target="'.$target.'"></li>');
		
	}
        /////////////////////Crea un Formulario
	
	function CrearForm2($nombre,$action,$method,$target){
            print('<form name= "'.$nombre.'" action="'.$action.'" id="'.$nombre.'" method="'.$method.'" target="'.$target.'" enctype="multipart/form-data">');
		
	}
	
	/////////////////////Crea un Formulario
	
	function CrearFormularioEvento($nombre,$action,$method,$target,$evento){
		print('<form name= "'.$nombre.'" action="'.$action.'" id="'.$nombre.'" method="'.$method.'" target="'.$target.'" '.$evento.'" enctype="multipart/form-data">');
		
	}
	
	
	/////////////////////Cierra un Formulario
	
	function CerrarForm(){
		print('</li></form>');
		
	}
	
	
	/////////////////////Crea un Select
	
	function CrearSelect($nombre,$evento,$ancho=200){
		print('<select id="'.$nombre.'" required name="'.$nombre.'" style="width:'.$ancho.'px" onchange="'.$evento.'" >');
		
	}
        
        /////////////////////Crea un Select con requerimiento
	
	function CrearSelect2($Vector){
            $nombre=$Vector["Nombre"];
            $evento=$Vector["Evento"];
            $funcion=$Vector["Funcion"];
            if($Vector["Required"]==1){
                $R="required";
            }else{
                $R="";
            }
            print("<select id='$nombre' $R name='$nombre' $evento='$funcion'>");
		
	}
        
        /////////////////////Crea un Select personalizado
	
	function CrearSelectPers($Vector){
            $nombre=$Vector["Nombre"];
            $Evento=$Vector["Evento"];
            $Ancho=$Vector["Ancho"];
            $Alto=$Vector["Alto"];
            print('<select name="'.$nombre.'" '.$Evento.' style="width:'.$Ancho.'px; height:'.$Alto.'px;" >');
		
	}
	
	/////////////////////Cierra un Select
	
	function CerrarSelect(){
		print('</select>');
		
	}
	
	
	/////////////////////Crea un Option Select
	
	function CrearOptionSelect($value,$label,$selected){
		
		if($selected==1)
			print('<option value="'.$value.'" selected>'.$label.'</option>');
		else
			print('<option value="'.$value.'">'.$label.'</option>');
		
	}
        
        /////////////////////Crea un Option Select
	
	function CrearOptionSelect2($value,$label,$javascript,$selected){
		
		if($selected==1)
			print('<option value="'.$value.'"  selected '.$javascript.'>'.$label.'</option>');
		else
			print('<option value="'.$value.'" '.$javascript.'>'.$label.' </option>');
		
	}
	
	
	/////////////////////Crea un Cuadro de texto input
	
	function CrearInputText($nombre,$type,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$ToolTip='Rellena este Campo',$Max="",$Min=""){
		
            if($nombre=="TxtDevuelta"){
                    $TFont="2em";
                }else {
                    $TFont="1em";
                }
                
		if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		
		if($Required==1)
			$Required="required";
		else
			$Required="";
                
                if($type=="date"){
                    //$ReadOnly="readonly";
                    
                    //$TxtFuncion="DeshabilitarTeclado(event,'$nombre');";
                }
                 
		$JavaScript=$TxtEvento.' = '.$TxtFuncion;
                $OtrasOpciones="";
                if($Max<>''){
                    $OtrasOpciones="max=$Max min=$Min";
                }
                
                print('<strong style="color:'.$color.'">'.$label.'<input name="'.$nombre.'" value="'.$value.'" type="'.$type.'" id="'.$nombre.'" '.$OtrasOpciones.' placeholder="'.$placeh.'" '.$JavaScript.' 
                '.$ReadOnly.' '.$Required.' autocomplete="off" style="width: '.$Ancho.'px;height: '.$Alto.'px; font-size: '.$TFont.' ;data-toggle="tooltip" title="'.$ToolTip.'" "></strong>');
                
	}
	
	/////////////////////Crea un text area
	
	function CrearTextArea($nombre,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required=1,$BorderWidth=1){
		
		if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		$RequiredTipo="required";
		if($Required==1)
			$RequiredTipo="required";
                
                print("<strong style= 'color:$color'>$label<textarea name='$nombre' id='$nombre' placeholder='$placeh' $TxtEvento = '$TxtFuncion'" 
                ." $ReadOnly  autocomplete='off' $RequiredTipo style='width: ".$Ancho."px; height: ".$Alto."px;border-top-width:".$BorderWidth."px;border-left-width:".$BorderWidth."px;border-right-width:".$BorderWidth."px;border-bottom-width:".$BorderWidth."px;' >".$value."</textarea></strong>");

			
		
	}
	
	/////////////////////Crea un Cuadro de texto input
	
	function CrearInputNumber($nombre,$type,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$Min,$Max,$Step,$css=""){
		
		if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		
		if($Required==1)
			$Required="required";
		else
			$Required="";
		
			print('<strong style="color:'.$color.'">'.$label.'<input name="'.$nombre.'" value="'.$value.'" type="'.$type.'" id="'.$nombre.'" placeholder="'.$placeh.'" '.$TxtEvento.' = "'.$TxtFuncion.'" 
			'.$ReadOnly.' '.$Required.' min="'.$Min.'"   max="'.$Max.'" step="'.$Step.'" autocomplete="off" style="width: '.$Ancho.'px;height: '.$Alto.'px;'.$css.'"></strong>');
		
	}
	
	/////////////////////Crea un Boton Submit
	
	function CrearBoton($nombre,$value){
		print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" class="btn btn-info"></input>');
		
	}
        
       
        
        /////////////////////Crea un Boton Submit
	
	function CrearBotonReset($nombre,$value){
		print('<input type="reset" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" class="btn btn-warning">');
		
	}
	
	/////////////////////Crea un Cuadro de Dialogo
	
	function CrearCuadroDeDialogo($id,$title){
		
		print(' <div id="'.$id.'" class="modal hide fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true" >
       
          	
            <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
    	        <h3 id="myModalLabel">'.$title.'</h3>
            </div>
            <div class="modal-body">
           	    <div class="row-fluid">
	               
    	            <div class="span6">
                    	
						
                   
            
        ');
		
	}
        
        /////////////////////Crea un Cuadro de Dialogo
	
	function CrearCuadroDeDialogo2($id,$title,$visible,$VectorDialogo){
            if($visible==1){
                $visible=true;
            }else{
                $visible=false;
            }
		print('<div id="'.$id.'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
       
          	
            <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="'.$visible.'">×</button>
    	        <h3 id="myModalLabel">'.$title.'</h3>
            </div>
            <div class="modal-body">
           	    <div class="row-fluid">
	               
    	            <div class="span6">
                    	
						
                   
            
        ');
		
	}
	
        /////////////////////Crea un Cuadro de Dialogo
	
	function CrearCuadroDeDialogoAmplio($id,$title){
		
		print(' <div id="'.$id.'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style=" width: 95%;left:23%;">
       
          	
            <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
    	        <h3 id="myModalLabel">'.$title.'</h3>
            </div>
            <div class="modal-body">
           	                       	
		
        ');
		
	}
        
        /////////////////////Crea un Cuadro de Dialogo
	
	function CrearModalAmplio($id,$title,$Vector){
		
            print('<div id="'.$id.'" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" style="width:90%;left:5%;margin:0">');
		
            print('<div class="modal-dialog">');
            
            print('<div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">'.$title.'</h4>
                    </div>
                    <div class="modal-body">');
		
	}
        
        function CrearModal($id,$title,$Vector){
            
            print('<div id="'.$id.'" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">');
		
            print('<div class="modal-dialog">');
            
            print('<div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">'.$title.'</h4>
                    </div>
                    <div class="modal-body">');
		
	}
        
        function CerrarModal($Nombre="BtnCierreModal") {
            print('</div>
                    <div class="modal-footer">
                      <button type="button" id='.$Nombre.' class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>

                </div>
               
               
              </div>');
            
        }
        
        /////////////////////Cierra un Cuadro de Dialogo
	
	function CerrarCuadroDeDialogoAmplio(){
		print(' </div></div>');
        }
	/////////////////////Cierra un Cuadro de Dialogo
	
	function CerrarCuadroDeDialogo(){
		print(' </div>
                </div>
            </div>
            
            <div class="modal-footer">
        	    <button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i> <strong>Cerrar</strong></button>
            	
            </div></div>');
		
	}
	
	
	/////////////////////Crear una Tabla
	
	function CrearTabla(){
		print('<div class="table-responsive"><table class="table table-bordered table table-hover" >');
		
	}
	
	/////////////////////Crear una fila para una tabla
	
	function FilaTabla($FontSize){
		print('<tr style="font-size:'.$FontSize.'px">');
		
	}
	
	function CierraFilaTabla(){
		print('</tr>');
		
	}
	
	/////////////////////Crear una columna para una tabla
	
	function ColTabla($Contenido,$ColSpan,$align="L"){
            if($align=="L"){
              $align="left";  
            }
            if($align=="R"){
              $align="right";  
            }
            if($align=="C"){
              $align="center";  
            }
            print('<td colspan="'.$ColSpan.' " style="text-align:'.$align.'"   >'.$Contenido.'</td>');
		
	}
	
	function CierraColTabla(){
		print('</td>');
		
	}
	/////////////////////Cierra una tabla
	
	function CerrarTabla(){
		print('</table></div>');
		
	}
	
	/////////////////////Crear una columna para una tabla
	
	function ColTablaDel($Page,$tabla,$IdTabla,$ValueDel,$idPre){
           
		print('<td align="center">
                  	<a href="'.$Page.'?del='.$ValueDel.'&TxtTabla='.$tabla.'&TxtIdTabla='.$IdTabla.'&TxtIdPre='.$idPre.'" title="Eliminar de la Lista" >
               		<i class="icon-remove">X</i>
                                    </a>
                                </td>');
		
	}
	
	/////////////////////Crear una columna para enviar una variable por URL
	
	function ColTablaVar($Page,$Variable,$Value,$idPre,$Title){
		print('<td><a href="'.$Page.'?'.$Variable.'='.$Value.'&TxtIdPre='.$idPre.'" title="'.$Title.'">'.$Title.'</a></td>');
                               
		
	}
	
	/////////////////////Crear una columna con un formulario
	
	function ColTablaFormInputText($FormName,$Action,$Method,$Target,$TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required,$TxtHide,$ValueHide,$idPreventa){
			
		print('<td>');
		$this->CrearForm2($FormName,$Action,$Method,$Target);
		$this->CrearInputText($TxtHide,"hidden","",$ValueHide,"","","","","","","","");
		$this->CrearInputText("CmbPreVentaAct","hidden","",$idPreventa,"","","","",0,0,0,0);
                $this->CrearInputNumber($TxtName, $TxtType, $TxtLabel, $TxtValue, $TxtPlaceh, $TxtColor, "", "", $TxtAncho, $TxtAlto, $ReadOnly, $Required, "", "", "any");
		//$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,"","",$TxtAncho,$TxtAlto,$ReadOnly,$Required);
		
                print("<input type='submit' id='BtnCantidad$TxtName' name='BtnEditarCantidad' value='E' style='width: 30px;height: 30px;' $TxtEvento='$TxtFuncion' >");
		$this->CerrarForm();
		print('</td>');
                               
		
	}
        
        /////////////////////Crear una columna con un formulario
	
	function DivColTablaFormInputText($FormName,$Action,$Method,$Target,$TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required,$TxtHide,$ValueHide,$idPreventa){
			
		$this->DivColTable("left", 0, 1, "black", "100%", "");
		$this->CrearForm2($FormName,$Action,$Method,$Target);
		$this->CrearInputText($TxtHide,"hidden","",$ValueHide,"","","","","","","","");
		$this->CrearInputText("CmbPreVentaAct","hidden","",$idPreventa,"","","","",0,0,0,0);
                $this->CrearInputNumber($TxtName, $TxtType, $TxtLabel, $TxtValue, $TxtPlaceh, $TxtColor, "", "", $TxtAncho, $TxtAlto, $ReadOnly, $Required, "", "", "any");
		//$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,"","",$TxtAncho,$TxtAlto,$ReadOnly,$Required);
		
                print("<input type='submit' id='BtnCantidad$TxtName' class='btn btn-warning' name='BtnEditarCantidad' value='E' style='width: 30px;height: 30px;' $TxtEvento='$TxtFuncion' >");
		$this->CerrarForm();
		print('</div>');
                               
		
	}
        
        /////////////////////Crear una columna con un formulario
	
	function ColTablaFormEditarPrecio($FormName,$Action,$Method,$Target,$TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtFuncion2,$TxtAncho,$TxtAlto,$ReadOnly,$Required,$TxtHide,$ValueHide,$idPreventa,$TxtPrecioMayor,$ValueMayor){
				
		print('<td>');
                 //print("<script>alert('Vector $ReadOnly')</script>");
		$this->CrearForm2($FormName,$Action,$Method,$Target);
		$this->CrearInputText($TxtHide,"hidden","",$ValueHide,"","","","","","","","");
                $this->CrearInputText($TxtPrecioMayor,"hidden","",$ValueMayor,"","","","","","","","");
		$this->CrearInputText("CmbPreVentaAct","hidden","",$idPreventa,"","","","",0,0,0,0);
                //$this->CrearInputNumber($TxtName, $TxtType, $TxtLabel, $TxtValue, $TxtPlaceh, $TxtColor, "", "", $TxtAncho, $TxtAlto, $ReadOnly, $Required, "", "", "any")
		$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,"","",$TxtAncho,$TxtAlto,$ReadOnly,$Required);
                
		//print("<input type='submit' id='BtnEditar$TxtName' name='BtnEditar' value='E' style='width: 30px;height: 30px;' onClick='$TxtFuncion'>");
		$vector["Enable"]=$ReadOnly;
                $vector["Color"]="Azul";
                $this->CrearBotonPersonalizado("BtnEditar", "E",$vector);
                $vector["Color"]="Verde";
                $this->CrearBotonPersonalizado("BtnMayorista", "M",$vector);
                $this->CerrarForm();
		print('</td>');
                               
		
	}
        
        /////////////////////Crear una columna con un formulario
	
	function DivColTablaFormEditarPrecio($FormName,$Action,$Method,$Target,$TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtFuncion2,$TxtAncho,$TxtAlto,$ReadOnly,$Required,$TxtHide,$ValueHide,$idPreventa,$TxtPrecioMayor,$ValueMayor){
				
		$this->DivColTable("left", 0, 1, "black", "100%", "");
                 //print("<script>alert('Vector $ReadOnly')</script>");
		$this->CrearForm2($FormName,$Action,$Method,$Target);
		$this->CrearInputText($TxtHide,"hidden","",$ValueHide,"","","","","","","","");
                $this->CrearInputText($TxtPrecioMayor,"hidden","",$ValueMayor,"","","","","","","","");
		$this->CrearInputText("CmbPreVentaAct","hidden","",$idPreventa,"","","","",0,0,0,0);
                //$this->CrearInputNumber($TxtName, $TxtType, $TxtLabel, $TxtValue, $TxtPlaceh, $TxtColor, "", "", $TxtAncho, $TxtAlto, $ReadOnly, $Required, "", "", "any")
		$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,"","",$TxtAncho,$TxtAlto,$ReadOnly,$Required);
                
		//print("<input type='submit' id='BtnEditar$TxtName' name='BtnEditar' value='E' style='width: 30px;height: 30px;' onClick='$TxtFuncion'>");
		$vector["Enable"]=$ReadOnly;
                $vector["Color"]="Azul";
                $this->CrearBotonPersonalizado("BtnEditar", "E",$vector);
                $vector["Color"]="Verde";
                $this->CrearBotonPersonalizado("BtnMayorista", "M",$vector);
                $this->CerrarForm();
		print('</div>');
                               
		
	}
	
	
	/////////////////////Crear una columna con un formulario
	
	function ColTablaInputText($TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required){
		print('<td>');
		
		$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required);
		
		print('</td>');
                               
		
	}
        
        /////////////////////Crear una columna con un formulario
	
	function DivColTablaInputText($TxtName,$TxtType,$TxtValue,$TxtLabel,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required){
		
		
		$this->CrearInputText($TxtName,$TxtType,$TxtLabel,$TxtValue,$TxtPlaceh,$TxtColor,$TxtEvento,$TxtFuncion,$TxtAncho,$TxtAlto,$ReadOnly,$Required);
		
		
                               
		
	}
	
	/////////////////////Crear una columna con un formulario
	
	function ColTablaBoton($nombre,$value){
		print('<td>');
		
		$this->CrearBoton($nombre,$value);
		
		print('</td>');
                               
		
	}
	
	
	function CreaMenuBasico($Title){
		print('<div id="MenuBasico">
			<ul class="nav">
				
				<li><a href="">'.$Title.'</a>
					<ul>
						
						
						
					
				
	');
		
		                              
		
	}
	
	function CreaSubMenuBasico($Title,$Link){
		print('<li><a href="'.$Link.'" target="_blank">'.$Title.'</a></li>');
	}
	
	function CierraMenuBasico(){
		print('</ul></li></ul></div>');
	}
	
	function CrearImageLink($page,$imagerute,$target,$Alto=200,$Ancho=200){
		print('<a href="'.$page.'" target="'.$target.'"><img src="'.$imagerute.'" style="cursor:pointer;height:'.$Alto.'px; width:'.$Ancho.'px"></a>');
	}
        function CrearImage($Nombre,$imagerute,$Alterno,$Alto,$Ancho,$Javascript=""){
            
            print('<img id="'.$Nombre.'"  nombre="'.$Nombre.'" '.$Javascript.'  src="'.$imagerute.'" onerror="this.src=`'.$Alterno.'`;" style="cursor:pointer;height:'.$Alto.'px; width:'.$Ancho.'px; " data-toggle="tooltip" title="'.$Alterno.'">');
	}
	function CrearLink($link,$target,$Titulo){
		print('<a href="'.$link.'" target="'.$target.'" >'.$Titulo.'</a>');
	}
        function CrearLinkID($link,$target,$Titulo,$VectorDatosExtra){
            $ID=$VectorDatosExtra["ID"];
            if(isset($VectorDatosExtra["JS"])){
                $JS=$VectorDatosExtra["JS"];
            }else{
                $JS="";
            }
            $ColorLink="blue";
            if(isset($VectorDatosExtra["Color"])){
                $ColorLink=$VectorDatosExtra["Color"];
            }
            print('<a id="'.$ID.'" href="'.$link.'" target="'.$target.'" '.$JS.' style="color:'.$ColorLink.'">'.$Titulo.'</a>');
	}
	
	/////////////////////Crear una fila para una tabla
	function CrearFilaNotificacion($Mensaje,$FontSize){
		print('<tr><div class="alert alert-success" align="center" style="font-size:'.$FontSize.'px"><strong>'.$Mensaje.'</strong></div></tr>');
		
	}
        
        /////////////////////Crear una fila para una tabla
	function CrearNotificacionVerde($Mensaje,$FontSize){
		print('<div class="alert alert-success fade in" align="center" style="font-size:'.$FontSize.'px">'
                        . ''
                        . '<strong>'.$Mensaje.'</strong></div>');
		
	}
        
        /////////////////////Crear una fila para una tabla
	function CrearNotificacionAzul($Mensaje,$FontSize){
		print('<div class="alert alert-info fade in" align="center" style="font-size:'.$FontSize.'px">'
                        . ''
                        . '<strong>'.$Mensaje.'</strong></div>');
		
	}
        
        /////////////////////Crear una fila para una tabla
	function CrearNotificacionNaranja($Mensaje,$FontSize){
		print('<div class="alert alert-warning fade in" align="center" style="font-size:'.$FontSize.'px">'
                        . ''
                        . '<strong>'.$Mensaje.'</strong></div>');
		
	}
        
        /////////////////////Crear una fila para una tabla
	function CrearNotificacionRoja($Mensaje,$FontSize){
		print('<div class="alert alert-danger fade in" align="center" style="font-size:'.$FontSize.'px">'
                        . ''
                        . '<strong>'.$Mensaje.'</strong></div>');
		
	}
	
	/////////////////////Crea un Boton Submit con evento
	
	function CrearBotonConfirmado($nombre,$value){
		print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" onclick="Confirmar(); return false" class="btn btn-danger">');
		
	}
        
        /////////////////////Crea un Boton Submit con evento
	
	function CrearBotonEvento($nombre,$value,$enabled,$evento,$funcion,$Color,$VectorBoton){
            
            switch ($Color){
                case "verde":
                    $Clase="btn btn-success";
                    break;
                case "naranja":
                    $Clase="btn btn-warning";
                    break;
                case "rojo":
                    $Clase="btn btn-danger";
                    break;
                case "blanco":
                    $Clase="btn";
                    break;
                case "azulclaro":
                    $Clase="btn btn-info";
                    break;
                case "azul":
                    $Clase="btn btn-info";
                    break;
            }
            if($enabled==1){
                print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" '.$evento.'="'.$funcion.' ; return false" class="'.$Clase.'">');
            }else{
                print('<input type="submit" id="'.$nombre.'" disabled="true" name="'.$nombre.'" value="'.$value.'" '.$evento.'="'.$funcion.' ; return false" class="'.$Clase.'">');  
            }
		
		
	}
        
        /////////////////////Crea un Boton Submit con evento
	
	function CrearBotonConfirmado2($nombre,$value,$enabled,$VectorBoton){
            if($enabled==1){
                print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" onclick="Confirmar(); return false" class="btn btn-danger">');
            }else{
                print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" disabled="true" onclick="Confirmar(); return false" class="btn btn-danger">');
            }
		
		
	}
        
        /////////////////////Crea un Boton Editar Green
	
	function CrearBotonVerde($nombre,$value){
		print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" class="btn btn-success">');
		
	}
        
        /////////////////////Crea un Boton Editar Green
	
	function CrearBotonPersonalizado($nombre,$value,$vector){
           $Color=$vector["Color"];
           if($Color=="Azul"){
               $Class='class="btn btn-primary"';
               
           }
           if($Color=="Verde"){
               $Class='class="btn btn-success"';
           }
           if($Color=="Rojo"){
               $Class='class=" btn btn-danger" ';
           }
            if($vector["Enable"]==1){
                
                $enable='disabled="false"';
            }else if($vector["Enable"]==0){
                $enable='';
                
            }
            
            print('<input type="submit" id="'.$nombre.'" '.$enable.' name="'.$nombre.'" value="'.$value.'" '.$Class.'>');
		
	}
        
        /////////////////////Crea un Boton Naranja
	
	function CrearBotonNaranja($nombre,$value,$js=""){
		print('<input type="submit" id="'.$nombre.'"  name="'.$nombre.'" value="'.$value.'" '.$js.' class="btn btn-warning">');
		
	}
        
        /////////////////////Agrega los javascripts
	
	function AgregaJS(){
            
            print('<script src="js/jquery.js"></script>
            <script src="js/bootstrap-transition.js"></script>
            
            <script src="js/bootstrap-alert.js"></script>
            <script src="js/bootstrap-modal.js"></script>
            <script src="js/bootstrap-dropdown.js"></script>
            <script src="js/bootstrap-scrollspy.js"></script>
            <script src="js/bootstrap-tab.js"></script>
            <script src="js/bootstrap-tooltip.js"></script>
            <script src="js/bootstrap-popover.js"></script>
            <script src="js/bootstrap-button.js"></script>
            <script src="js/bootstrap-collapse.js"></script>
            <script src="js/bootstrap-carousel.js"></script>
            <script src="js/bootstrap-typeahead.js"></script>
            <script src="js/funciones.js"></script>
            <script src="js/shortcuts.js"></script>
            <script src="chousen/source/jquery.min.js" type="text/javascript"></script>
            <script src="chousen/source/chosen.jquery.js" type="text/javascript"></script>
            <script src="chousen/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
            <script src="js/calendar.js"></script>
            <script src="js/cronometro.js"></script>
            
             ');
            
            ?>

            <script type="text/javascript">
            var config = {
              '.chosen-select'           : {},
              '.chosen-select-deselect'  : {allow_single_deselect:true},
              '.chosen-select-no-single' : {disable_search_threshold:10},
              '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
              '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
              $(selector).chosen(config[selector]);
            }
          </script>
          
		<?php
	}
        
        /////////////////////Agrega el boton para subir
	
	function AgregaSubir(){
            print('<a style="display:scroll; position:fixed; bottom:10px; right:10px;" href="#" title="Volver arriba"><img src="../images/up1_amarillo.png" /></a>');
		
	}
        
        /////////////////////Crear un DIV
	
	function CrearDiv($ID, $Class, $Alineacion,$Visible, $Habilitado,$Styles=""){
            if($Visible==1)
                $V="block";
            else
                $V="none";
            
            if($Habilitado==1) ///pensado a futuro, aun no esta en uso
                $H="true";
            else
                $H="false";
            print("<div id='$ID' class='$Class' align='$Alineacion' style='display:$V;$Styles' >");
		
	}
        
        
        
        /////////////////////Crear un DIV
	
	function CrearDiv2($ID, $Class, $Alineacion,$Visible, $Habilitado, $Ancho, $Alto,$top,$left,$posicion,$Vector){
            if($Visible==1)
                $V="block";
            else if($Visible==2)
                $V="scroll";
            else{
                $V="none";
            }
            if($Habilitado==1) ///pensado a futuro, aun no esta en uso
                $H="true";
            else
                $H="false";
                        
            print(' <div id="'.$ID.'" class="'.$Class.'" align="'.$Alineacion.'" style="display:'.$V.';width: '.$Ancho.';height:'.$Alto.';top:'.$top.';margin-left:'.$left.';position:'.$posicion.'" > ');
		
	}
        
        /////////////////////Crear un DIV
	
	function CerrarDiv(){
            print("</div>");
		
	}
        
        /////////////////////Crear una Alerta
	
	function AlertaJS($Mensaje,$Tipo,$Formatos,$Iconos){
            if($Tipo==1){
                $Alerta="alert";
            }
            if($Tipo==2){
                $Alerta="confirm";
            }
            if($Tipo==3){
                $Alerta="prompt";
            }
            print("<script>$Alerta('$Mensaje');</script>");
		
	}
        
        

////////////////////////////Crear Footer	
		
function Footer(){
		$Year=date("Y");
		print('<footer>    
  <div style="text-align: center">
    <div>
    <div>
       <a href="../VMenu/Menu.php" class="f_logo"><img src="../images/header-logo1.png" alt=""></a><a href="../VMenu/Menu.php" class="f_logo"><img src="../images/header-logo.png" alt=""></a>
    </div>  
    
      <div class="copy">
      &copy; '.$Year.' | <a href="#">Privacy Policy</a> <br> Software  designed by <a href="http://technosoluciones.com.co/" rel="nofollow" target="_blank">Techno Soluciones SAS</a>
      </div>
    </div>
  </div>
</footer>
		');
	}
        
     
/////////////////////Crear una Chosen
	
	function CrearSelectChosen($Nombre, $VarSelect){
          $Ancho='200px'; 
          $PlaceHolder="Seleccione una opcion"; 
          if(isset($VarSelect["Ancho"])){
             $Ancho=$VarSelect["Ancho"].'px';
          }
              
          
          if(isset($VarSelect["PlaceHolder"])){
             $PlaceHolder=$VarSelect["PlaceHolder"];
          }           
           
           
           if(isset($VarSelect["Required"])){
               $Required="required=1";
           }else{
               $Required="";
           }
           if(isset($VarSelect["Title"]) and !empty($VarSelect["Title"])){
                print("<strong>$VarSelect[Title]</strong><br>");
           }
           echo '<select id="'.$Nombre.'" data-placeholder="'.$PlaceHolder.'" class="chosen-select"  tabindex="1" name="'.$Nombre.'" '.$Required.' style="width:'.$Ancho.';">';
           
       	
	}   
        
        /////////////////////Crear una Chosen
	
	function CrearTableChosen($Nombre,$Tabla,$Condicion,$Display1,$Display2,$Display3,$idItem, $Ancho,$Requerido,$PlaceHolder,$Titulo,$idSeleccionado=""){
            $obCon=new conexion(1);
            $consulta=$obCon->ConsultarTabla($Tabla, $Condicion);
            $VarSelect["Ancho"]=$Ancho;
            $VarSelect["Required"]=$Requerido;
            $VarSelect["PlaceHolder"]=$PlaceHolder;
            $VarSelect["Title"]=$Titulo;
            $this->CrearSelectChosen($Nombre, $VarSelect);
           
            $this->CrearOptionSelect("", $PlaceHolder, 0);
            while($DatosTabla=$obCon->FetchArray($consulta)){
               $Dato1= utf8_encode($DatosTabla[$Display1]);
               $Dato2= utf8_encode($DatosTabla[$Display2]);
               $Dato3= utf8_encode($DatosTabla[$Display3]);
               $sel=0;
               if($idSeleccionado==$DatosTabla[$idItem]){
                   $sel=1;
               }
               $this->CrearOptionSelect($DatosTabla[$idItem], "$Dato1 $Dato2 $Dato3", $sel);
               
            }
            
            $this->CerrarSelect();
	}   
        
        /////////////////////Asignar ancho a un elemento por id
	
	function AnchoElemento($id, $Ancho){
             
          echo'<script>document.getElementById("'.$id.'").style.width = "'.$Ancho.'px";</script>';
    
	} 
        
        
        /////////////////////Crear un upload
	
	function CrearUpload($Nombre){
             
          print('<input type="file" name="'.$Nombre.'" id="'.$Nombre.'"></input>');
    
	} 
        
        /////////////////////Crear un frame
	
	function frame($name,$page,$border,$alto,$ancho,$VectorFrame){
             
          print("<iframe name='$name' id='$name' src='$page' frameborder=$border style='height: ".$alto."%; width:".$ancho."%'></iframe>"); 
    
	} 
        
        /////////////////////Agrega JavaScrips exclusivos de venta Rapida
	
	function AgregaJSVentaRapida(){
             
          print("<script>atajos();posiciona('TxtCodigoBarras');</script> "); 
    
	} 
        
        /////////////////////Crear una imagen con una funcion javascrip
	
	function CrearBotonImagen($Titulo,$Nombre,$target,$RutaImage,$javascript,$Alto,$Ancho,$posicion,$margenes,$VectorBim){
             
          //print("<a href='$target' title='$Titulo'><image name='$Nombre' id='$Nombre' src='$RutaImage' $javascript style='display:scroll; position:".$posicion."; right:10px; height:".$Alto."px; width: ".$Ancho."px;'></a>");
          
          print('<a href="'.$target.'" role="button"  data-toggle="modal" title="'.$Titulo.'" style="display:scroll; position:'.$posicion.'; '.$margenes.'; height:'.$Alto.'px; width: '.$Ancho.'px;">
			<image src='.$RutaImage.' name='.$Nombre.' id='.$Nombre.' src='.$RutaImage.' '.$javascript.' ></a>');
	} 
        
        /////////////////////Crear una imagen con una funcion javascrip
	
	function CrearLinkImagen($Titulo,$Nombre,$target,$RutaImage,$javascript,$Alto,$Ancho,$MasStilos,$margenes,$VectorBim){
             
          //print("<a href='$target' title='$Titulo'><image name='$Nombre' id='$Nombre' src='$RutaImage' $javascript style='display:scroll; position:".$posicion."; right:10px; height:".$Alto."px; width: ".$Ancho."px;'></a>");
          
          print('<a href="'.$target.'" role="button"  data-toggle="modal" title="'.$Titulo.'" style="display:scroll; height:'.$Alto.'px; width: '.$Ancho.'px;'.$MasStilos.'">
			<image src='.$RutaImage.' name='.$Nombre.' id='.$Nombre.' src='.$RutaImage.' '.$javascript.' ></a>');
	} 
        
        /////////////////////Crear una imagen con una funcion javascrip
	
	function CrearInputFecha($Titulo,$Nombre,$Value,$Ancho,$Alto,$VectorFe){
            //include_once '../modelo/php_conexion.php';
            $obVenta=new conexion(1);
            $DatosFechaCierre=$obVenta->DevuelveValores("cierres_contables", "ID", 1);
            $FechaCierre=$DatosFechaCierre["Fecha"];
          print("<div onmouseout=ValidarFecha('$FechaCierre','$Nombre');>");
          print("<strong>$Titulo </strong> <input type='text' size='12' name='$Nombre' id='$Nombre' value='$Value' autocomplete='off' style='width: ".$Ancho."px;height: ".$Alto."px; font-size: 1em' onchange=ValidarFecha('$FechaCierre','$Nombre');>");
          print("</div>");
          print('<script type="text/javascript">
                
                        new JsDatePick({
                                useMode:2,
                                target:"'.$Nombre.'",
                                dateFormat:"%Y-%m-%d"

                        });
                
        </script>');
	} 
        
        /////////////////////Crear una imagen con una funcion javascrip
	
	function DibujeCronometro($Ancho){
                    
          print('<div id="crono" style="width: '.$Ancho.'%;">
		<div class="reloj" id="Horas" style="display:none;">00</div>
		<div class="reloj" id="Minutos">00</div>
		<div class="reloj" id="Segundos">:00</div>
		<div class="reloj" id="Centesimas" style="display:none;">:00</div>
		
	</div>');
	} 
        
        /////////////////////Crear una imagen con una funcion javascrip
	
	function DibujeControlCronometro($Ancho){
                    
          print('<div id="crono" style="width: '.$Ancho.'%;">
		
		<input type="button" class="boton" id="inicio" value="Start &#9658;" onclick="inicio();">
		<input type="button" class="boton" id="parar" value="Stop &#8718;" onclick="parar();" disabled>
		<input type="button" class="boton" id="continuar" value="Resume &#8634;" onclick="inicio();" disabled>
		<input type="button" class="boton" id="reinicio" value="Reset &#8635;" onclick="reinicio();" disabled>
	</div>');
	} 
        
        /////////////////////Dibujar un boton de busqueda
	
	function BotonBuscar($Alto,$Ancho,$Vector){
                    
          print('<button type="submit" class="btn btn-info" > <img src="../images/busqueda.png" class="img-rounded" alt="Cinque Terre" width="'.$Ancho.'" height="'.$Alto.'"></button>');
	} 
        
        /////////////////////Dibujar un cuadro de busqueda
	
	function DibujeCuadroBusqueda($Nombre,$pageConsulta,$OtrasVariables,$DivTarget,$Evento,$Alto,$Ancho,$Vector){
            
                            
            ?>
            <script>
            function Busqueda<?php echo"$Nombre"?>() {
                
                str=document.getElementById("<?php echo"$Nombre"?>").value;
                //document.getElementById("<?php echo"$Nombre"?>").value="";
                <?php
                if(isset($Vector["Variable"][0])){
                    $idObjeto=$Vector["Variable"][0];
                    print("$idObjeto=document.getElementById('$idObjeto').value;");
                    //print("$idObjeto=document.getElementById('$idObjeto').value;");
                    $OtrasVariables.='"+'.$idObjeto.'+"';
                    //$VariableJS='"+document.getElementById(`'.$idObjeto.'`).value';
                }
                ?>
                if (str == "") {
                    document.getElementById("<?php echo"$DivTarget"?>").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("<?php echo"$DivTarget"?>").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","<?php echo"$pageConsulta"?>="+str+"&<?php print($OtrasVariables);?>",true);
                    xmlhttp.send();
                }
            }
            </script>
            <?php 
            $TipoText="text";
            if($Nombre=="TxtAutorizacion"){
                $TipoText="password";
            }
            $this->CrearInputText($Nombre, $TipoText, "", "", "Buscar", "Black", $Evento, "Busqueda".$Nombre."()", $Ancho, $Alto, 0, 0);
            
            $this->BotonBuscar(20, 20, "");
            
            
	} 
        
/////////////////////Dibujar un icono que muestra y oculta un div
	
	function CrearBotonOcultaDiv($Titulo,$Div,$Ancho,$Alto,$Enable,$Vector){
          if($Enable==0){
                $e='';
                
            }else{
                $e='disabled="false"';
                
            }          
          print("<button type='submit' $e class='btn btn-default' onclick=MuestraOculta('$Div');>$Titulo <image width='$Ancho' height='$Alto' name='imgHidde' id='imgHidde' src='../images/hidde.png' ></button>");
	}    
        
        /////////////////////Dibujar un cuadro de busqueda
	
	function DivPage($Nombre,$pageConsulta,$OtrasVariables,$DivTarget,$Evento,$Alto,$Ancho,$Vector){
            
                            
            ?>
            <script>
            function Busqueda<?php echo"$Nombre"?>() {
                
                str=document.getElementById("<?php echo"$Nombre"?>").value;
                <?php
                if(isset($Vector["Variable"][0])){
                    $idObjeto=$Vector["Variable"][0];
                    print("$idObjeto=document.getElementById('$idObjeto').value;");
                    //print("$idObjeto=document.getElementById('$idObjeto').value;");
                    $OtrasVariables.='"+'.$idObjeto.'+"';
                    //$VariableJS='"+document.getElementById(`'.$idObjeto.'`).value';
                }
                ?>
                if (str == "") {
                    document.getElementById("<?php echo"$DivTarget"?>").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("<?php echo"$DivTarget"?>").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","<?php echo"$pageConsulta"?>",true);                    
                    xmlhttp.send();
                    
                }
                
            }
            
            </script>
            <?php 
            $this->CrearBotonEvento($Nombre, "Cargar", 1, $Evento, "Busqueda".$Nombre."()", "verde", "");
            $this->CrearInputText($Nombre, "hidden", "", "", "", "Black", "", "", "", "", 0, 0);
            
            //$this->BotonBuscar(20, 20, "");
                 
	} 
        public function CrearTextAreaEnriquecida($nombre,$label,$value,$placeh,$color,$TxtEvento,$TxtFuncion,$Ancho,$Alto,$ReadOnly,$Required,$Vector) {
            if($ReadOnly==1)
			$ReadOnly="readonly";
		else
			$ReadOnly="";
		
		if($Required==1){
			print("<strong style= 'color:$color'>$label<textarea name='$nombre' id='editor' placeholder='$placeh' $TxtEvento = '$TxtFuncion'" 
			." $ReadOnly autocomplete='off' style='width: ".$Ancho."px; height: ".$Alto."px;' required>".$value."</textarea></strong>");
                }else{
			print("<strong style= 'color:$color'>$label<textarea name='$nombre' id='editor' placeholder='$placeh' $TxtEvento = '$TxtFuncion'" 
			." $ReadOnly autocomplete='off' style='width: ".$Ancho."px; height: ".$Alto."px;' >".$value."</textarea></strong>");
                }
        }
        //Iniciamos el texto enriquecido
        public function IniTextoEnriquecido() {
            print("<script>
                initSample();
                </script>");
        }
        
        
        //Select de envio a div
         /////////////////////Dibujar un cuadro de busqueda
	
	function DibujeSelectBuscador($Nombre,$pageConsulta,$OtrasVariables,$DivTarget,$Evento,$Alto,$Ancho,$tabla,$Condicion,$idItemValue,$OptionDisplay1,$OptionDisplay2,$Vector){
            $obVenta=new conexion(1);
                            
            ?>
            <script>
            function Busqueda<?php echo"$Nombre"?>() {
                
                str=document.getElementById("<?php echo"$Nombre"?>").value;
                <?php
                if(isset($Vector["Variable"][0])){
                    $idObjeto=$Vector["Variable"][0];
                    print("$idObjeto=document.getElementById('$idObjeto').value;");
                    //print("$idObjeto=document.getElementById('$idObjeto').value;");
                    $OtrasVariables.='"+'.$idObjeto.'+"';
                    //$VariableJS='"+document.getElementById(`'.$idObjeto.'`).value';
                }
                ?>
                if (str == "") {
                    document.getElementById("<?php echo"$DivTarget"?>").innerHTML = "";
                    return;
                } else {
                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("<?php echo"$DivTarget"?>").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","<?php echo"$pageConsulta"?>&idSel="+str+"&<?php print($OtrasVariables);?>",true);
                    xmlhttp.send();
                }
            }
            </script>
            <?php 
            $this->CrearSelect($Nombre, "Busqueda".$Nombre."()");
            $this->CrearOptionSelect("", "Seleccione un Item", 0);
            $consulta=$obVenta->ConsultarTabla($tabla, $Condicion);
            while ($DatosConsulta=$obVenta->FetchAssoc($consulta)){
                $this->CrearOptionSelect($DatosConsulta[$idItemValue], "$DatosConsulta[$OptionDisplay1] $DatosConsulta[$OptionDisplay2]", 0);
            }
            $this->CerrarSelect();
            //$this->CrearInputText($Nombre, "text", "", "", "Buscar", "Black", $Evento, "Busqueda".$Nombre."()", $Ancho, $Alto, 0, 0);
            
            //$this->BotonBuscar(20, 20, "");
            
            
	} 
        
        public function CrearSelectTable($Nombre,$tabla,$Condicion,$idItemValue,$OptionDisplay1,$OptionDisplay2,$Evento,$FuncionJS,$idSel,$Requerido,$LeyendaInicial="Seleccione un Item") {
            $obVenta=new conexion(1);
            $Vector["Nombre"]=$Nombre;
            $Vector["Evento"]=$Evento;
            $Vector["Funcion"]=$FuncionJS;
            $Vector["Required"]=$Requerido;
            $this->CrearSelect2($Vector);
            $this->CrearOptionSelect("", $LeyendaInicial, 0);
            $consulta=$obVenta->ConsultarTabla($tabla, $Condicion);
            while ($DatosConsulta=$obVenta->FetchAssoc($consulta)){
                $Sel=0;
                if($DatosConsulta[$idItemValue]==$idSel){
                  $Sel=1;  
                }
                $this->CrearOptionSelect($DatosConsulta[$idItemValue], "$DatosConsulta[$OptionDisplay1] $DatosConsulta[$OptionDisplay2]", $Sel);
            }
            $this->CerrarSelect();
        }
        
        //Crear una tabla con un div
        public function DivTable() {
            print("<div style='display:table;'>");
        }
        //Crear una tabla con un div
        public function DivRowTable() {
            print("<div style='display:table-row; '>");
        }
        //Crear una tabla con un div
        public function DivColTable($Align,$Border,$BorderWith,$ColorFont,$FontSize,$Vector) {
            if($Border==1){
                $Border="border-style: solid;border-width:$BorderWith";
            }else{
                $Border="";
            }
            print("<div style='display:table-cell; text-align:$Align;$Border;font-size:$FontSize;color:$ColorFont'>");
        }
        
        
        public function VentanaFlotante($Mensaje) {
            print("<div id='ventana-flotante'>
                    <a class='cerrar' href='javascript:void(0);' onclick='document.getElementById(&apos;ventana-flotante&apos;).className = &apos;oculto&apos;'>x</a>

                        <div id='contenedor'>

                            <div class='contenido'>
                            
                            $Mensaje

                            </div>

                        </div>

                    </div>");
        }
        
        public function DivNotificacionesJS() {
            print("<div id='DivRespuestasJS' style='position: scroll;bottom:10px; right:10px;width: 300px;'></div>");
        }
        //Imagen que oculta o muestra un div o un objeto
        public function ImageOcultarMostrar($Nombre,$Leyenda,$idObjeto,$Ancho,$Alto,$Vector,$RutaImage='../images/circle.png') {
            print("<strong>$Leyenda</strong><image name='$Nombre' id='$Nombre' src='$RutaImage' style='cursor: pointer;height:$Ancho"."px".";width:$Alto"."px"."' onclick=MuestraOculta('$idObjeto');>");
        }
        //Div para maquetear
        public function DivGrid($ID, $Class, $Alineacion,$Visible, $Habilitado,$Ubicacion,$Altura,$Ancho,$Border,$BorderColor){
            if($Visible==1)
                $V="block";
            else
                $V="none";
            if($Ubicacion==1)
                $Ubicacion="left";
            if($Ubicacion==2)
               $Ubicacion="center";
            if($Ubicacion==3)
               $Ubicacion="rigth";
            if($Habilitado==1) ///pensado a futuro, aun no esta en uso
                $H="true";
            else
                $H="false";
            print("<div id='$ID' class='$Class' align='$Alineacion' style='display:$V;float: left;$Ubicacion;height:".$Altura."%;width:".$Ancho."%;overflow: auto;border: ".$Border."px solid;border-color: $BorderColor;' >");
        }
        
        //DivBusquedas
        
        function CrearDivBusquedas($ID, $Class, $Alineacion,$Visible, $Habilitado){
            if($Visible==1)
                $V="block";
            else
                $V="none";
            
            if($Habilitado==1) ///pensado a futuro, aun no esta en uso
                $H="true";
            else
                $H="false";
            print("<div id='$ID' class='fade in' align='$Alineacion' style='display:$V;' >");
            print('<a href="#" class="close" data-dismiss="alert" aria-label="close">X</a>');
		
	}
        
        //Multi Select
        
        public function CrearMultiSelectTable($Nombre,$tabla,$Condicion,$idItemValue,$OptionDisplay1,$OptionDisplay2,$Evento,$FuncionJS,$idSel,$Requerido,$Ancho="200") {
            $obVenta=new conexion(1);
            
            print('<select multiple class="form-control" id="'.$Nombre.'" name="'.$Nombre.'[]" style=" width:'.$Ancho.'px">');
                        
            
            $consulta=$obVenta->ConsultarTabla($tabla, $Condicion);
            while ($DatosConsulta=$obVenta->FetchAssoc($consulta)){
                $Sel=0;
                if($DatosConsulta[$idItemValue]==$idSel){
                  $Sel=1;  
                }
                //print("<option>$DatosConsulta[ID]</option>");
                $this->CrearOptionSelect($DatosConsulta[$idItemValue], "$DatosConsulta[$OptionDisplay1] $DatosConsulta[$OptionDisplay2]", $Sel);
            }
            $this->CerrarSelect();
        }
        //Agrega graficos
        public function AgregaJSGraficos() {
            print('<script src="js/highcharts.js"></script>
            <script src="js/modules/exporting.js"></script>');
        }
        
        //Agrega un Grafico
        public function CreeGraficoBarrasSimple($Titulo,$Subtitulo,$EjeX,$EjeY,$VectorColumnas,$VectorValores,$idDiv,$Vector) {
             ?>               
        <script>
        $(function () {
            $('#<?php echo "$idDiv"?>').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo "$Titulo"?>'
                },
                subtitle: {
                    text: '<?php echo "$Subtitulo"?>'
                },
                xAxis: {
                    categories: [

                        <?php foreach ($VectorColumnas as $NombreColumnas){
                            echo "'$NombreColumnas',";
                        }
                        ?> 
                    ],



                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '<?php echo "$EjeY"?>'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>$ {point.y:.1f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '<?php echo "$EjeX"?>',
                    data: [<?php foreach ($VectorValores as $Valores){
                        print($Valores.",");    
                        
                        }
                        ?> 
        ]

                },  ]
            });
        });
        </script>
	 <?php   
        }
        
        //Checkbox animado
        public function CheckOnOff($Nombre,$JavaScript,$Activo,$Vector) {
            
            if($Activo==1){
                $imagerute="../images/on.png";
                $Alterno="On";
                
            }
            if($Activo==0){
                $imagerute="../images/off.png";
                $Alterno="Off";
                
            }
            if($Activo==2){
                $imagerute="../images/unable.png";
                $Alterno="Off";
                $JavaScript="";
            }
                
            $this->CrearImage($Nombre, $imagerute, $Alterno, 60, 100, $JavaScript);
        }
        
        //Cuadro de dialogo para crear tercero
        
        public function DialTercero($id,$titulo,$myPage,$Vector) {
            $obCon=new conexion(1);
            $this->CrearCuadroDeDialogo($id,$titulo); 
	 
                $this->CrearForm2("FrmCrearCliente",$myPage,"post","_self");
                $this->CrearSelect("CmbTipoDocumento","Oculta()");
                $this->CrearOptionSelect('13','Cedula',1);
                $this->CrearOptionSelect('31','NIT',0);
                $this->CerrarSelect();
                //$css->CrearInputText("CmbPreVentaAct","hidden","",$idPreventa,"","","","",0,0,0,0);
                $this->CrearInputText("TxtNIT","number","","","Identificacion","black","","",200,30,0,1);
                $this->CrearInputText("TxtPA","text","","","Primer Apellido","black","onkeyup","CreaRazonSocial()",200,30,0,0);
                $this->CrearInputText("TxtSA","text","","","Segundo Apellido","black","onkeyup","CreaRazonSocial()",200,30,0,0);
                $this->CrearInputText("TxtPN","text","","","Primer Nombre","black","onkeyup","CreaRazonSocial()",200,30,0,0);
                $this->CrearInputText("TxtON","text","","","Otros Nombres","black","onkeyup","CreaRazonSocial()",200,30,0,0);
                $this->CrearInputText("TxtRazonSocial","text","","","Razon Social","black","","",200,30,0,1);
                $this->CrearInputText("TxtDireccion","text","","","Direccion","black","","",200,30,0,1);
                $this->CrearInputText("TxtTelefono","text","","","Telefono","black","","",200,30,0,1);
                $this->CrearInputText("TxtEmail","text","","","Email","black","","",200,30,0,1);
                $VarSelect["Ancho"]="200";
                $VarSelect["PlaceHolder"]="Seleccione el municipio";
                $this->CrearSelectChosen("CmbCodMunicipio", $VarSelect);
                $sql="SELECT * FROM cod_municipios_dptos";
                $Consulta=$obCon->Query($sql);
                   while($DatosMunicipios=$obCon->FetchArray($Consulta)){
                       $Sel=0;
                       if($DatosMunicipios["ID"]==1011){
                           $Sel=1;
                       }
                       $this->CrearOptionSelect($DatosMunicipios["ID"], $DatosMunicipios["Ciudad"], $Sel);
                   }
                $this->CerrarSelect();
                echo '<br><br>';
                $this->CrearBoton("BtnCrearCliente", "Crear Cliente");
                $this->CerrarForm();
            $this->CerrarCuadroDeDialogo(); 
        }
        public function BotonNotificaciones($Vector) {
            print('<div class="notification-container">        
                <img src="../images/notificaciones.png" style="height:50px;width:50px" href="#ModalAlertasTS5" data-toggle="modal"></img><span id="TS5_Alertas" class="notification-counter">NC</span></input>
            </div>');
            $this->CrearModal("ModalAlertasTS5", "Notificaciones", "");
                $this->CrearDiv("DivNotificacionesTS5", "", "center", 1, 1);
                $this->CerrarDiv();
            $this->CerrarModal();
        }
        public function ProgressBar($NombreBarra,$NombreLeyenda,$Tipo,$Valor,$Min,$Max,$Ancho,$Leyenda,$Color,$Vector) {
            print('<div class="progress">
                    <div id="'.$NombreBarra.'" name="'.$NombreBarra.'" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="'.$Valor.'" aria-valuemin="'.$Min.'" aria-valuemax="'.$Max.'" style="width:'.$Ancho.'%">
                      <div id="'.$NombreLeyenda.'" name="'.$NombreLeyenda.'"">'.$Leyenda.'</div>
                    </div>
                  </div>');
        }
        
        public function AgregaCssJSSelect2() {
            print("<link rel='stylesheet' type='text/css' href='select2\dist\css/select2.min.css' />");
            print('<script src="select2\vendor\jquery-2.1.0.js"></script>');
            print('<script src="select2\dist\js/select2.min.js"></script>');     
    
        }
        //////////////////////////////////FIN
}
	
	

?>