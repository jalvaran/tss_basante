
function ListarDireccionamientoMipres(Page=1,datos_mipres=''){
    MuestraOcultaXID('div_filtro_mipres');
    var idDiv="DivGeneralDraw";
    
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value; 
    var cmb_estado_mipres =document.getElementById("cmb_estado_mipres").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 1);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
        form_data.append('cmb_estado_mipres', cmb_estado_mipres);
        if(datos_mipres["fecha_inicial"]){
            
            form_data.append('fecha_inicial_mipres', datos_mipres["fecha_inicial"]);
            form_data.append('fecha_final_mipres', datos_mipres["fecha_final"]); 
            form_data.append('fecha_consulta_mipres', datos_mipres["fecha_consulta"]); 
            form_data.append('porcentaje_barra_mipres', 100); 
        }        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/mipres_programacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            dibuja_filtros_mipres(1,cmb_estado_mipres);
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


 
function confirma_programar_mipres(mipres_id){
    alertify.confirm('Seguro que desea programar este mipres?',
        function (e) {
            if (e) {
                
                iniciar_programacion_mipres_x_id(mipres_id);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
     
} 
 
function iniciar_programacion_mipres_x_id(mipres_id){
    delete datos_mipres;
    var datos_mipres=[];
    datos_mipres["mipres_id"]=mipres_id;
    obtenga_token_consulta_mipres(datos_mipres,2);
}    
    
function programar_mi_pres_x_id(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Programando...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '5'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('mipres_id', datos_mipres["mipres_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                iniciar_consulta_mipres_x_prescripcion(respuestas[3]);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function frm_entregar_mipres(mipres_id){
    AbreModal('ModalAcciones');
    OcultaXID('BntModalAcciones');
    OcultaXID('btnCerrarModal');
    var idDiv="DivFrmModalAcciones";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">cargando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('mipres_id', mipres_id);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/mipres_programacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){   
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos 
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function confirma_entregar_mipres(mipres_id){
    alertify.confirm('Seguro que desea entregar este mipres?',
        function (e) {
            if (e) {
                
                iniciar_entrega_mipres_x_id(mipres_id);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
     
} 
 
function iniciar_entrega_mipres_x_id(mipres_id){
    delete datos_mipres;
    var datos_mipres=[];
    datos_mipres["mipres_id"]=mipres_id;
    obtenga_token_consulta_mipres(datos_mipres,3);
}    
    
function entregar_mi_pres_x_id(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Entregando...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    var mipres_cantidad_entregada=document.getElementById('mipres_cantidad_entregada').value;
    var mipres_fecha_real_entrega=document.getElementById('mipres_fecha_real_entrega').value;
    var mipres_tipo_documento_recibe=document.getElementById('mipres_tipo_documento_recibe').value;
    var mipres_identificacion_recibe=document.getElementById('mipres_identificacion_recibe').value;
    var mipres_parentesco=document.getElementById('mipres_parentesco').value;
    var mipres_nombre_recibe=document.getElementById('mipres_nombre_recibe').value;
    var mipres_causas_no_entrega=document.getElementById('mipres_causas_no_entrega').value;
    
    var form_data = new FormData();
        form_data.append('Accion', '6'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('mipres_id', datos_mipres["mipres_id"]);
        form_data.append('mipres_cantidad_entregada', mipres_cantidad_entregada);
        form_data.append('mipres_fecha_real_entrega', mipres_fecha_real_entrega);
        form_data.append('mipres_tipo_documento_recibe', mipres_tipo_documento_recibe);
        form_data.append('mipres_identificacion_recibe', mipres_identificacion_recibe);
        form_data.append('mipres_parentesco', mipres_parentesco);
        form_data.append('mipres_nombre_recibe', mipres_nombre_recibe);
        form_data.append('mipres_causas_no_entrega', mipres_causas_no_entrega);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                iniciar_consulta_mipres_x_prescripcion(respuestas[3]);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}



function confirma_anular_programacion_mipres(programacion_id){
    alertify.confirm('Seguro que desea anular esta programación?',
        function (e) {
            if (e) {
                
                iniciar_anular_programacion_mipres_x_id(programacion_id);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
     
} 
 
function iniciar_anular_programacion_mipres_x_id(programacion_id){
    delete datos_mipres;
    var datos_mipres=[];
    datos_mipres["programacion_id"]=programacion_id;
    obtenga_token_consulta_mipres(datos_mipres,4);
}    
    
function anular_progracion_mipres(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Anulando...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '7'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('programacion_id', datos_mipres["programacion_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                iniciar_consulta_mipres_x_prescripcion(respuestas[2]);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}



function confirma_anular_entrega_mipres(entrega_id){
    alertify.confirm('Seguro que desea anular esta entrega?',
        function (e) {
            if (e) {
                
                iniciar_anular_entrega_mipres_x_id(entrega_id);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
     
} 
 
function iniciar_anular_entrega_mipres_x_id(entrega_id){
    delete datos_mipres;
    var datos_mipres=[];
    datos_mipres["entrega_id"]=entrega_id;
    obtenga_token_consulta_mipres(datos_mipres,5);
}    
    
function anular_entrega_mipres(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Anulando...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '8'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('entrega_id', datos_mipres["entrega_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                iniciar_consulta_mipres_x_prescripcion(respuestas[2]);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function obtenga_token_consulta_mipres(datos_mipres,funcion){
    var idDivMensajes='sp_msg_mipres';
    
    var form_data = new FormData();
        form_data.append('Accion', '1'); 
                        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                datos_mipres["token_consultas"]=respuestas[2];
                if(funcion==1){
                    consulte_direccionamiento_mipres_x_rango(datos_mipres);
                }
                if(funcion==2){
                    programar_mi_pres_x_id(datos_mipres);
                }
                if(funcion==3){
                    entregar_mi_pres_x_id(datos_mipres);
                } 
                if(funcion==4){
                    anular_progracion_mipres(datos_mipres);
                } 
                if(funcion==5){
                    anular_entrega_mipres(datos_mipres);
                } 
                if(funcion==6){
                    consulte_entrega_mipres_x_rango(datos_mipres);
                } 
                if(funcion==7){
                    consulte_programacion_mipres_x_rango(datos_mipres);
                } 
                if(funcion==8){
                    consulta_direccionamiento_mipres_x_prescripcion(datos_mipres);
                } 
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function iniciar_consulta_mipres(){
    delete datos_mipres;
    var idDivMensajes='sp_msg_mipres';
    
    var FechaInicialMiPres=document.getElementById("FechaInicialMiPres").value;    
    var FechaFinalMiPres=document.getElementById("FechaFinalMiPres").value;    
    
    var form_data = new FormData();
        form_data.append('Accion', '3'); 
        form_data.append('FechaInicialMiPres', FechaInicialMiPres);
        form_data.append('FechaFinalMiPres', FechaFinalMiPres);
                
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                var datos_mipres=[];
                datos_mipres["fecha_inicial"]=respuestas[2];
                datos_mipres["fecha_final"]=respuestas[3];
                datos_mipres["fecha_consulta"]=respuestas[4];
                datos_mipres["total_dias"]=respuestas[5];
                obtenga_token_consulta_mipres(datos_mipres,1);
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}



function consulte_direccionamiento_mipres_x_rango(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando direccionamiento...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["fecha_consulta"];
    
    var form_data = new FormData();
        form_data.append('Accion', '4'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('fecha_inicial', datos_mipres["fecha_inicial"]);
        form_data.append('fecha_final', datos_mipres["fecha_final"]); 
        form_data.append('fecha_consulta', datos_mipres["fecha_consulta"]); 
        form_data.append('total_dias', datos_mipres["total_dias"]); 
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                datos_mipres["fecha_consulta"]=respuestas[2];
                var porcentaje=respuestas[3];
                
                var sp_msg=respuestas[4];
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";                
                consulte_direccionamiento_mipres_x_rango(datos_mipres);
            }else if(respuestas[0]=="FIN"){  
                alertify.success(respuestas[1]);
                
                iniciar_consulta_entrega_mipres();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function iniciar_consulta_entrega_mipres(){
    delete datos_mipres;
    var idDivMensajes='sp_msg_mipres';
    
    var FechaInicialMiPres=document.getElementById("FechaInicialMiPres").value;    
    var FechaFinalMiPres=document.getElementById("FechaFinalMiPres").value;    
    
    var form_data = new FormData();
        form_data.append('Accion', '9'); 
        form_data.append('FechaInicialMiPres', FechaInicialMiPres);
        form_data.append('FechaFinalMiPres', FechaFinalMiPres);
                
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                var datos_mipres=[];
                datos_mipres["fecha_inicial"]=respuestas[2];
                datos_mipres["fecha_final"]=respuestas[3];
                datos_mipres["fecha_consulta"]=respuestas[4];
                datos_mipres["total_dias"]=respuestas[5];
                obtenga_token_consulta_mipres(datos_mipres,6);
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function consulte_entrega_mipres_x_rango(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando Entregas...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["fecha_consulta"];
    
    var form_data = new FormData();
        form_data.append('Accion', '10'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('fecha_inicial', datos_mipres["fecha_inicial"]);
        form_data.append('fecha_final', datos_mipres["fecha_final"]); 
        form_data.append('fecha_consulta', datos_mipres["fecha_consulta"]); 
        form_data.append('total_dias', datos_mipres["total_dias"]); 
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                datos_mipres["fecha_consulta"]=respuestas[2];
                var porcentaje=respuestas[3];                
                var sp_msg=respuestas[4];
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";                
                consulte_entrega_mipres_x_rango(datos_mipres);
            }else if(respuestas[0]=="FIN"){  
                alertify.success(respuestas[1]);
                
                iniciar_consulta_programacion_mipres();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}



function iniciar_consulta_programacion_mipres(){
    delete datos_mipres;
    var idDivMensajes='sp_msg_mipres';
    
    var FechaInicialMiPres=document.getElementById("FechaInicialMiPres").value;    
    var FechaFinalMiPres=document.getElementById("FechaFinalMiPres").value;    
    
    var form_data = new FormData();
        form_data.append('Accion', '11'); 
        form_data.append('FechaInicialMiPres', FechaInicialMiPres);
        form_data.append('FechaFinalMiPres', FechaFinalMiPres);
                
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                var datos_mipres=[];
                datos_mipres["fecha_inicial"]=respuestas[2];
                datos_mipres["fecha_final"]=respuestas[3];
                datos_mipres["fecha_consulta"]=respuestas[4];
                datos_mipres["total_dias"]=respuestas[5];
                obtenga_token_consulta_mipres(datos_mipres,7);
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function consulte_programacion_mipres_x_rango(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando programación...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["fecha_consulta"];
    
    var form_data = new FormData();
        form_data.append('Accion', '12'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('fecha_inicial', datos_mipres["fecha_inicial"]);
        form_data.append('fecha_final', datos_mipres["fecha_final"]); 
        form_data.append('fecha_consulta', datos_mipres["fecha_consulta"]); 
        form_data.append('total_dias', datos_mipres["total_dias"]); 
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                datos_mipres["fecha_consulta"]=respuestas[2];
                var porcentaje=respuestas[3];                
                var sp_msg=respuestas[4];
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";                
                consulte_programacion_mipres_x_rango(datos_mipres);
            }else if(respuestas[0]=="FIN"){  
                alertify.success(respuestas[1]);
                MostrarListadoSegunID();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}



function ListarProgramacionMipres(Page=1,datos_mipres=''){
    MuestraOcultaXID('div_filtro_mipres');
    var idDiv="DivGeneralDraw";
    
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value; 
    var cmb_estado_mipres =document.getElementById("cmb_estado_mipres").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 3);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
        form_data.append('cmb_estado_mipres', cmb_estado_mipres);
        if(datos_mipres["fecha_inicial"]){
            
            form_data.append('fecha_inicial_mipres', datos_mipres["fecha_inicial"]);
            form_data.append('fecha_final_mipres', datos_mipres["fecha_final"]); 
            form_data.append('fecha_consulta_mipres', datos_mipres["fecha_consulta"]); 
            form_data.append('porcentaje_barra_mipres', 100); 
        }        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/mipres_programacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            dibuja_filtros_mipres(2,cmb_estado_mipres);
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function ListarEntregaMipres(Page=1,datos_mipres=''){
    MuestraOcultaXID('div_filtro_mipres');
    var idDiv="DivGeneralDraw";
    
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value; 
    var cmb_estado_mipres =document.getElementById("cmb_estado_mipres").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 4);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
        form_data.append('cmb_estado_mipres', cmb_estado_mipres);
        if(datos_mipres["fecha_inicial"]){
            
            form_data.append('fecha_inicial_mipres', datos_mipres["fecha_inicial"]);
            form_data.append('fecha_final_mipres', datos_mipres["fecha_final"]); 
            form_data.append('fecha_consulta_mipres', datos_mipres["fecha_consulta"]); 
            form_data.append('porcentaje_barra_mipres', 100); 
        }        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/mipres_programacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            dibuja_filtros_mipres(3,cmb_estado_mipres);
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function iniciar_consulta_mipres_x_prescripcion(prescripcion_id){
    delete datos_mipres;
    var datos_mipres=[];
    datos_mipres["prescripcion_id"]=prescripcion_id;
    obtenga_token_consulta_mipres(datos_mipres,8);
}    
    
function consulta_direccionamiento_mipres_x_prescripcion(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando Direccionamiento X Prescripcion...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '13'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('NoPrescripcion', datos_mipres["prescripcion_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                consulta_programacion_mipres_x_prescripcion(datos_mipres);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}

function consulta_programacion_mipres_x_prescripcion(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando Programacion X Prescripcion...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '14'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('NoPrescripcion', datos_mipres["prescripcion_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                consulta_entrega_mipres_x_prescripcion(datos_mipres);
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}

function consulta_entrega_mipres_x_prescripcion(datos_mipres){
    
    var idDivMensajes='sp_msg_mipres';
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando Entrega X Prescripcion...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
    document.getElementById(idDivMensajes).innerHTML=document.getElementById(idDivMensajes).innerHTML+" "+datos_mipres["ID"];
    
    var form_data = new FormData();
        form_data.append('Accion', '15'); 
        form_data.append('token_consultas', datos_mipres["token_consultas"]);
        form_data.append('NoPrescripcion', datos_mipres["prescripcion_id"]);
        
        $.ajax({
        url: './procesadores/mipres.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1]);
                MostrarListadoSegunID();
                              
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1],0);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}

function dibuja_filtros_mipres(tabla_id,defecto){
    
    var idDiv="div_filtro_mipres";
    
    var form_data = new FormData();
        form_data.append('Accion', 5);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('tabla_id', tabla_id);
        form_data.append('defecto', defecto);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/mipres_programacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}