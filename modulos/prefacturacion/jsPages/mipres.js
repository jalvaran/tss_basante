
function ListarProgramacionMipres(Page=1,datos_mipres=''){
    
    var idDiv="DivGeneralDraw";
    
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 1);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
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
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
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
                ListarProgramacionMipres(1);
                              
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
    document.getElementById(idDivMensajes).innerHTML='<div id="GifProcess">Consultando...<img   src="../../images/loader.gif" alt="Cargando" height="50" width="50"></div>';
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
                ListarProgramacionMipres(1,datos_mipres);    
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