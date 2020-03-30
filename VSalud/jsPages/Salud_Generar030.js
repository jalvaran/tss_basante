/*
 * Controlador para generar la circular 030
 */

function ConstruyaVista030(){
    
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
  
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular2';
    
    var form_data = new FormData();
        form_data.append('idAccion', 5)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        
                
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            
                       
            if(data=='OK'){
                
                document.getElementById(DivDestino).innerHTML="Vistas necesarias de la circular 030 construidas";
                document.getElementById("DivProcess").innerHTML='';
                CalculeRegistros();                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

function CalculeRegistros(){
    var DivDestino =  'DivMensajesCircular2';
    
    document.getElementById(DivDestino).innerHTML=document.getElementById(DivDestino).innerHTML+"<br>Calculando el total de registros";
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
  
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    
    
    var form_data = new FormData();
        form_data.append('idAccion', 6)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        
                
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            
            var respuestas = data.split(';');           
            if(respuestas[0]=='OK'){
                var GranTotalRegistros=respuestas[1]; //Se obtiene el total de los registros a insertar en la 030
                document.getElementById(DivDestino).innerHTML=document.getElementById(DivDestino).innerHTML+"<br>Total de Registros Encontrados: "+GranTotalRegistros;
                document.getElementById("DivProcess").innerHTML='';
                ConstruirRegistroDeControl030(GranTotalRegistros);                  
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

function ConstruirRegistroDeControl030(GranTotalRegistros){
    var DivDestino =  'DivMensajesCircular2';
    
    document.getElementById(DivDestino).innerHTML=document.getElementById(DivDestino).innerHTML+"<br>Construyendo el registro de control";
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
  
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    
    
    var form_data = new FormData();
        form_data.append('idAccion', 4)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('GranTotalRegistros', GranTotalRegistros)
        
                
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            
            var respuestas = data.split(';');           
            if(respuestas[0]=='OK'){
                var NombreArchivo=respuestas[1]; //Se obtiene el total de los registros a insertar en la 030
                var Link="<a href='"+NombreArchivo.substr(3)+"' download=''>Descargar</a>";
                document.getElementById(DivDestino).innerHTML=document.getElementById(DivDestino).innerHTML+"<br>Registro de control de la circular 030: "+Link;
                
                GenereRadicadosEnPeriodo("","",GranTotalRegistros,NombreArchivo);                  
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}


function GenereRadicadosEnPeriodo(Contador="",TotalRegistros='',GranTotalRegistros,NombreArchivo){
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 1)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('NombreArchivo', NombreArchivo)
                
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var Termina=respuestas[3];
                var porcentaje = Math.round((100/GranTotalRegistros)*RegistrosRealizados);
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", del AF En estado Radicado en el periodo seleccionado.<br>Total de Registros hasta el momento: "+RegistrosRealizados;
                if(Termina==''){
                    GenereRadicadosEnPeriodo(RegistrosRealizados,TotalRegistros,GranTotalRegistros,NombreArchivo);
                    
                }
                
                if(Termina=='Fin'){
                   // document.getElementById("DivProcess").innerHTML='';
                    GenereJuridicosEnPeriodo("","",RegistrosRealizados,GranTotalRegistros,NombreArchivo);
                    //Cierre030(RegistrosRealizados);
                }
                                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}
/**
 * Busque los registros en estado de cobro juridico durante el periodo seleccionado
 * @param {type} Contador
 * @param {type} TotalRegistros
 * @returns {undefined}
 */
function GenereJuridicosEnPeriodo(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 2)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", del AF En estado De Cobro Juridico en el periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenereJuridicosEnPeriodo(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                    
                    GenerePagadasEnPeriodo("","",ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}
/**
 * Busca las facturas pagadas en el periodo
 * @param {type} Contador
 * @param {type} TotalRegistros
 * @param {type} ContadorGeneral
 * @param {type} GranTotalRegistros
 * @param {type} NombreArchivo
 * @returns {undefined}
 */
function GenerePagadasEnPeriodo(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 7)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", que fueron pagados en el periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenerePagadasEnPeriodo(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                    
                    GenereDiferenciaEnPeriodo("","",ContadorGeneral,GranTotalRegistros,NombreArchivo);
                } 
                               
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}
/**
 * Genera las que fueron pagadas antes del periodo
 * @param {type} Contador
 * @param {type} TotalRegistros
 * @param {type} ContadorGeneral
 * @param {type} GranTotalRegistros
 * @param {type} NombreArchivo
 * @returns {undefined}
 */
function GenerePagadasAnteriores(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 8)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", que fueron pagados antes del periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenerePagadasAnteriores(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                    
                    GenereRadicadosAnteriores('','',ContadorGeneral,GranTotalRegistros,NombreArchivo);
                   
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

/**
 * Genera los radicados anteriores al periodo seleccionado
 * @param {type} Contador
 * @param {type} TotalRegistros
 * @param {type} ContadorGeneral
 * @param {type} GranTotalRegistros
 * @param {type} NombreArchivo
 * @returns {undefined}
 */
function GenereRadicadosAnteriores(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 9)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", que están en estado de radicado antes del periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenereRadicadosAnteriores(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                    
                    GenereDiferenciaAnteriores('','',ContadorGeneral,GranTotalRegistros,NombreArchivo);
                    
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}


function GenereDiferenciaEnPeriodo(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 10)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", en estado Diferencia en el periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenereDiferenciaEnPeriodo(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                    
                    GenerePagosEnRangoRadicadosFuera('','',ContadorGeneral,GranTotalRegistros,NombreArchivo);
                    
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

function GenereDiferenciaAnteriores(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 11)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", que en estado Diferencia Antes del periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenereDiferenciaAnteriores(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                   document.getElementById("DivProcess").innerHTML='';
                    //GenerePagosEnRangoRadicadosFuera("","",RegistrosRealizados,GranTotalRegistros,NombreArchivo);
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

function GenerePagosEnRangoRadicadosFuera(Contador="",TotalRegistros='',ContadorGeneral,GranTotalRegistros,NombreArchivo){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    var CmbAdicional = document.getElementById('CmbAdicional').value;
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 12)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
        form_data.append('CmbAdicional', CmbAdicional)
        form_data.append('Contador', Contador)
        form_data.append('TotalRegistros', TotalRegistros)
        form_data.append('ContadorGeneral', ContadorGeneral) 
        form_data.append('NombreArchivo', NombreArchivo) 
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var TotalRegistros=respuestas[1];
                var RegistrosRealizados=respuestas[2];
                var ContadorGeneral=respuestas[4];
                var Termina=respuestas[3];
                if(TotalRegistros==0){
                    var Divisor=1;
                }else{
                    var Divisor=TotalRegistros;
                }
                var porcentaje = Math.round((100/GranTotalRegistros)*ContadorGeneral);
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=RegistrosRealizados+' Registros guardados de '+TotalRegistros+", que en estado Recibieron pagos en el rango pero radicadas Antes del periodo seleccionado.<br>Total de Registros hasta el momento "+ContadorGeneral;
                if(Termina==''){
                    GenerePagosEnRangoRadicadosFuera(RegistrosRealizados,TotalRegistros,ContadorGeneral,GranTotalRegistros,NombreArchivo);
                }
                if(Termina=='Fin'){       
                   
                    document.getElementById("DivProcess").innerHTML='';
                      
                    //GenerePagadasEnPeriodo("","",RegistrosRealizados,GranTotalRegistros,NombreArchivo);
                }                    
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}

/**
 * Cierra la circular 
 * @param {type} ContadorGeneral
 * @returns {undefined}
 */
function Cierre030(ContadorGeneral){    
    
    var TxtFechaInicial = document.getElementById('TxtFechaInicial').value;
    var TxtFechaFinal = document.getElementById('TxtFechaFinal').value;
    
    var DivDestino =  'DivMensajesCircular';
    
    var form_data = new FormData();
        form_data.append('idAccion', 4)
        form_data.append('TxtFechaInicial', TxtFechaInicial)
        form_data.append('TxtFechaFinal', TxtFechaFinal)
               
        form_data.append('ContadorGeneral', ContadorGeneral)        
        $.ajax({
        //async:false,
        url: 'procesadores/Salud_Genere030.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{   
            console.log(data);
            var respuestas = data.split(';');            
            if(respuestas[0]=='OK'){
                var NombreArchivo=respuestas[1];
                
                var porcentaje =100;
                
                $('.progress-bar').css('width',porcentaje+'%').attr('aria-valuenow', porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=porcentaje+"%";
                document.getElementById(DivDestino).innerHTML=NombreArchivo;
                document.getElementById("DivProcess").innerHTML='';
                                
            }else{
                document.getElementById(DivDestino).innerHTML=data;
                document.getElementById("DivProcess").innerHTML='';
            }
            
                        
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}