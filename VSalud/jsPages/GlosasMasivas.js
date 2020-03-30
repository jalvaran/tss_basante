/**
 * Controlador para la gestion de cargas masivas de glosas
 * JULIAN ALVARAN 2018-07-29
 * TECHNO SOLUCIONES SAS EN ASOCIACION CON SITIS SA
 * 317 774 0609
 */
/**
 * Envia el archivo de carga de glosas masivas al servidor
 * @returns {undefined}
 */
function CargarArchivoGlosasMasivas(){
    document.getElementById('BtnEnviarCargaMasiva').disabled=true;
    var form_data = new FormData();
    form_data.append('UpCargaMasivaGlosas', $('#UpCargaMasivaGlosas').prop('files')[0]);
    form_data.append('idAccion',1);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);            
            
            if(data==='OK'){
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML="Archivo Cargado";
                $('.progress-bar').css('width','20%').attr('aria-valuenow', 20);  
                document.getElementById('LyProgresoCMG').innerHTML="20%";
                LeerArchivo();
            }else{
                BorrarCarga();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de subir el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function BorrarCarga(){
    var form_data = new FormData();
    form_data.append('idAccion',2);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            alertify.error(data); 
            document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=document.getElementById('EstadoProgresoGlosasMasivas').innerHTML+"<br>"+data;
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Envia la peticion al servidor para leer el archivo e ingresarlo a la tabla temporal
 * @returns {undefined}
 */
function LeerArchivo(){
    var form_data = new FormData();
    form_data.append('idAccion',3);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if(data==='OK'){
                var msg="Archivo leido y copiado para empezar validaciones";
                alertify.success(msg); 
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=msg;
                $('.progress-bar').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoCMG').innerHTML="40%";
                AnalizarRegistros();
            }else{
                BorrarCarga();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de leer el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Realiza las  validaciones en cada registro enviado
 * @returns {undefined}
 */
function AnalizarRegistros(){
    var form_data = new FormData();
    form_data.append('idAccion',4);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if(data==='OK'){
                var msg="Archivo validado Correctamente";
                alertify.success(msg); 
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=msg;
                $('.progress-bar').css('width','60%').attr('aria-valuenow', 60);  
                document.getElementById('LyProgresoCMG').innerHTML="60%";
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                RegistraGlosas();
            }else{
                BorrarCarga();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                alertify.alert("Error");
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de leer el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function RegistraGlosas(){
    var form_data = new FormData();
    form_data.append('idAccion',5);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]==='OK'){
                var Porcentaje = respuestas[3]; 
                var msg="Registradas "+respuestas[2]+" Glosas de un total de "+respuestas[1]+" para un progreso del "+Porcentaje;
                Porcentaje=parseInt(Porcentaje)+60;
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=msg;
                $('.progress-bar').css('width',Porcentaje+'%').attr('aria-valuenow', Porcentaje);  
                document.getElementById('LyProgresoCMG').innerHTML=Porcentaje+"%";
                //document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                RegistraGlosas();
            }else if(respuestas[0]==='FIN'){
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML="Las Glosas fueron creadas exitósamente";
                $('.progress-bar').css('width','100%').attr('aria-valuenow', 100);  
                document.getElementById('LyProgresoCMG').innerHTML="100%";
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                BorrarCarga();
                
            }else{
                BorrarCarga();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                alertify.alert("Error");
                document.getElementById('EstadoProgresoGlosasMasivas').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de leer el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}