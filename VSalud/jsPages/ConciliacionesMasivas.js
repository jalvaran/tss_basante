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
function CargarArchivoConciliaciones(){
    document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=true;
    var form_data = new FormData();
    form_data.append('UpCargaMasivaConciliaciones', $('#UpCargaMasivaConciliaciones').prop('files')[0]);
    form_data.append('idAccion',1);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivasConciliaciones.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);            
            
            if(data==='OK'){
                document.getElementById('EstadoProgresoConciliaciones').innerHTML="Archivo Cargado";
                $('#PgProgresoConciliaciones').css('width','20%').attr('aria-valuenow', 20);  
                document.getElementById('LyProgresoConciliaciones').innerHTML="20%";
                LeerArchivoConciliaciones();
            }else{
                BorrarCargaConciliaciones();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de subir el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Borra La carga
 * @returns {undefined}
 */
function BorrarCargaConciliaciones(){
    var form_data = new FormData();
    form_data.append('idAccion',2);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivasConciliaciones.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){

            alertify.error(data); 
            document.getElementById('EstadoProgresoConciliaciones').innerHTML=data;

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Se lee el archivo en excel y se escribe en la tabla temporal
 * @returns {undefined}
 */
function LeerArchivoConciliaciones(){
    var form_data = new FormData();
    form_data.append('idAccion',3);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivasConciliaciones.process.php',
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
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=msg;
                $('#PgProgresoConciliaciones').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoConciliaciones').innerHTML="40%";
                AnalizarRegistrosConciliaciones();
            }else{
                BorrarCargaConciliaciones();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=data;
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
 * Analiza los registros de las conciliaciones realizando las diferentes validaciones
 * @returns {undefined}
 */
function AnalizarRegistrosConciliaciones(){
    var form_data = new FormData();
    form_data.append('idAccion',4);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivasConciliaciones.process.php',
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
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=msg;
                $('#PgProgresoConciliaciones').css('width','60%').attr('aria-valuenow', 60);  
                document.getElementById('LyProgresoConciliaciones').innerHTML="60%";
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
                RegistraConciliaciones();
            }else{
                BorrarCargaConciliaciones();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
                alertify.alert("Error");
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=data;
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
 * Registra las conciliaciones
 * @returns {undefined}
 */
function RegistraConciliaciones(){
    var form_data = new FormData();
    form_data.append('idAccion',5);
    $.ajax({
        async:false,
        url: './Consultas/AccionesCargasMasivasConciliaciones.process.php',
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
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=msg;
                $('#PgProgresoConciliaciones').css('width',Porcentaje+'%').attr('aria-valuenow', Porcentaje);  
                document.getElementById('LyProgresoConciliaciones').innerHTML=Porcentaje+"%";
                //document.getElementById('BtnEnviarCargaMasiva').disabled=false;
                RegistraConciliaciones();
            }else if(respuestas[0]==='FIN'){
                document.getElementById('EstadoProgresoConciliaciones').innerHTML="";
                $('#PgProgresoConciliaciones').css('width','100%').attr('aria-valuenow', 100);  
                document.getElementById('LyProgresoConciliaciones').innerHTML="100%";
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
               
                
            }else{
                BorrarCargaConciliaciones();//Elimina los registros de la tabla Control de cargas
                document.getElementById('BtnEnviarCargaMasivaConciliaciones').disabled=false;
                alertify.alert("Error");
                document.getElementById('EstadoProgresoConciliaciones').innerHTML=data;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de leer el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}