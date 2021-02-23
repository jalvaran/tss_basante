/**
 * Controlador para administrar los pagos que ingresan al modulo de tesoreria
 * JULIAN ANDRES ALVARAN
 * 2020-02-16
 */
var idListado=1;
document.getElementById("BtnMuestraMenuLateral").click(); //da click sobre el boton que esconde el menu izquierdo de la pagina principal

function mostrar_spinner(mensaje){
    var cadena = '';            

        cadena += '<div id="spinner1" class="m-2 d-inline-block" style="position:fixed;top: 50%;left: 50%;z-index:1;text-align:center;color:red"> ';
            cadena += '<strong>'+mensaje+'</strong><br> ';
             
        cadena += '</div>'; 
        var spinner = $(cadena);
        $("#div_spinner").prepend(spinner);
        document.getElementById('spinner1').innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
}

function ocultar_spinner(){
    $("#spinner1").remove();    
}

function CopiarAlPortapapelesID(idElemento){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(idElemento).text()).select();
    document.execCommand("copy");
    alertify.success("Texto Copiado: "+$(idElemento).text());
    $temp.remove();
}

function openModal(idModal){
    var id="#"+idModal;
    $(id).modal();
}

function AbreModal(idModal){
    $("#"+idModal).modal();
}

function MostrarListadoSegunID(){
    OcultaXID('div_filtro_mipres');
    if(idListado==1){
        ListarPacientes();
    }
    if(idListado==2){
        ListarReservas();
    }
    if(idListado==3){
        ListarCitas();
    }
    if(idListado==4){
        ListarPendientesPorFacturar();
    }
    if(idListado==5){
        ListarFacturas();
    }
    if(idListado==6){
        ListarRIPS();
    }
    if(idListado==7){
        ListarLiquidacionColaboradores();
    }    
    if(idListado==8){
        ListarDocumentosElectronicosPendientes();
    }
    
    if(idListado==9){
        ListarDocumentosElectronicosEnviados();
    }
    
    if(idListado==10){
        ListarDocumentosElectronicosError();
    }
    
    if(idListado==11){
        
        ListarDireccionamientoMipres();
    }
    if(idListado==12){
        
        ListarProgramacionMipres();
    }
    if(idListado==13){
        
        ListarEntregaMipres();
    }
    if(idListado==14){
        
        ListarReporteEntregaMipres();
    }
    
}

function CambiePagina(Funcion,Page=""){
    
    if(Page==""){
        if(document.getElementById('CmbPage')){
            Page = document.getElementById('CmbPage').value;
        }else{
            Page=1;
        }
    }
    if(Funcion==1){
        ListarPacientes(Page);
    }
    if(Funcion==2){
        ListarReservas(Page);
    }
    if(Funcion==3){
        ListarCitas(Page);
    }
    if(Funcion==4){
        ListarPendientesPorFacturar(Page);
    }
    if(Funcion==5){
        ListarFacturas(Page);
    }
    
    if(Funcion==6){
        ListarRIPS(Page);
    }
    
    if(Funcion==7){
        ListarLiquidacionColaboradores(Page);
    }
    
    if(Funcion==8){
        ListarDocumentosElectronicosPendientes(Page);
    }
    
    if(Funcion==9){
        ListarDocumentosElectronicosEnviados(Page);
    }
    
    if(Funcion==10){
        ListarDocumentosElectronicosError(Page);
    }
    if(Funcion==11){
        ListarDireccionamientoMipres(Page);
    }
    if(Funcion==12){
        ListarProgramacionMipres(Page);
    }
    if(Funcion==13){
        ListarEntregaMipres(Page);
    }
    if(Funcion==14){
        ListarReporteEntregaMipres(Page);
    }
    
}

function ListarPacientes(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 1);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function FormularioCrearEditarPaciente(TipoFormulario=1,idEditar=0){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('TipoFormulario', TipoFormulario);
        form_data.append('idEditar', idEditar);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; 
            
            $('#CodEPS').select2({
		  
                placeholder: 'Selecciona una EPS',
                ajax: {
                  url: 'buscadores/salud_eps.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
              
              $('#CodigoDANE').select2({
		  
                placeholder: 'Selecciona un Municipio',
                ajax: {
                  url: 'buscadores/catalogo_municipios.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function ValidaDocumentoPaciente(){
    var idDivMensajes='DivMensajes';
    
    var TipoDocumento=document.getElementById("TipoDocumento").value;    
    var NumeroDocumento=document.getElementById("NumeroDocumento").value;    
    if(TipoDocumento=='' || NumeroDocumento==''){
        return;
    }   
    var form_data = new FormData();
        form_data.append('Accion', '1'); 
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('NumeroDocumento', NumeroDocumento);
                
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
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
                DesMarqueErrorElemento(respuestas[2]);
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
    
function CalculeEdad(){
    var idDivMensajes='DivMensajes';
    
    var FechaNacimiento=document.getElementById("FechaNacimiento").value;    
     
    var form_data = new FormData();
        form_data.append('Accion', '2'); 
        form_data.append('FechaNacimiento', FechaNacimiento);
               
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            
            if(respuestas[0]=="OK"){                
                //alertify.success(respuestas[1]);
                var Edad=respuestas[3];
                var Unidad=respuestas[4];
                document.getElementById("Edad").value=Edad;
                $("#UnidadMedidaEdad option:selected").attr("selected",false);
                $("#UnidadMedidaEdad option[value="+ Unidad +"]").attr("selected",true);
                
                DesMarqueErrorElemento(respuestas[2]);
                
            }else if(respuestas[0]=="E1"){  
                //alertify.error(respuestas[1],0);
                document.getElementById("Edad").value='';
                var Unidad='';
                $("#UnidadMedidaEdad option:selected").attr("selected",false);
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
  

function CrearEditarPaciente(TipoFormulario=1,idEditar=0){
    
    var idBoton='btnGuardarPaciente';
    document.getElementById(idBoton).disabled=true;
    var TipoDocumento=document.getElementById("TipoDocumento").value;    
    var NumeroDocumento=document.getElementById("NumeroDocumento").value;    
    var CodEPS=document.getElementById("CodEPS").value;    
    var idRegimenPaciente=document.getElementById("idRegimenPaciente").value;  
    var PrimerNombre=document.getElementById("PrimerNombre").value;
    var SegundoNombre=document.getElementById("SegundoNombre").value;
    var PrimerApellido=document.getElementById("PrimerApellido").value;    
    var SegundoApellido=document.getElementById("SegundoApellido").value;  
    var FechaNacimiento=document.getElementById("FechaNacimiento").value;
    var Edad=document.getElementById("Edad").value;
    var UnidadMedidaEdad=document.getElementById("UnidadMedidaEdad").value;    
    var Sexo=document.getElementById("Sexo").value;  
    var CodigoDANE=document.getElementById("CodigoDANE").value;
    var Direccion=document.getElementById("Direccion").value;
    var ZonaResidencial=document.getElementById("ZonaResidencial").value;
    var Telefono=document.getElementById("Telefono").value;
    var Correo=document.getElementById("Correo").value;
    
    
    var form_data = new FormData();
        form_data.append('Accion', '3'); 
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('NumeroDocumento', NumeroDocumento);
        form_data.append('CodEPS', CodEPS);
        form_data.append('idRegimenPaciente', idRegimenPaciente);
        form_data.append('PrimerNombre', PrimerNombre);
        form_data.append('SegundoNombre', SegundoNombre);
        form_data.append('PrimerApellido', PrimerApellido); 
        form_data.append('SegundoApellido', SegundoApellido);
        form_data.append('FechaNacimiento', FechaNacimiento);
        form_data.append('Edad', Edad);
        form_data.append('UnidadMedidaEdad', UnidadMedidaEdad);
        form_data.append('Sexo', Sexo);
        form_data.append('CodigoDANE', CodigoDANE);
        form_data.append('Direccion', Direccion);
        form_data.append('ZonaResidencial', ZonaResidencial); 
        form_data.append('Telefono', Telefono);
        form_data.append('Correo', Correo);
        form_data.append('TipoFormulario', TipoFormulario);
        form_data.append('idEditar', idEditar);
        
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
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
                document.getElementById(idBoton).disabled=false;
                ListarPacientes();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);
                
            }
            document.getElementById(idBoton).disabled=false;         
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
    }
    
 function ConfirmaGuardarEditarPaciente(TipoFormulario=1,idEditar=0){
    alertify.confirm('Está seguro que desea Guardar?',
        function (e) {
            if (e) {
                
                CrearEditarPaciente(TipoFormulario,idEditar);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function OcultaXID(id){
    
    
    document.getElementById(id).style.display="none";
    
    
}
    
function MarqueErrorElemento(idElemento){
    
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="pink";
    document.getElementById(idElemento).focus();
}

function DesMarqueErrorElemento(idElemento){
    
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="white";
    
}



function ListarReservas(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;
    var Estado =document.getElementById("cmbFiltrosReservas").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 5);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
        form_data.append('Estado', Estado);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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


function FormularioCrearEditarReserva(TipoFormulario=1,idEditar=0){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 6);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('TipoFormulario', TipoFormulario);
        form_data.append('idEditar', idEditar);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; 
            
            $('#idPaciente').select2({
		  
                placeholder: 'Selecciona un paciente',
                ajax: {
                  url: 'buscadores/prefactura_paciente.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
              
              $('#Cie10').select2({
		  
                placeholder: 'Selecciona un Diagnostico',
                ajax: {
                  url: 'buscadores/salud_cie10.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function ConfirmaGuardarEditarReserva(TipoFormulario=1,idEditar=0){
    alertify.confirm('Está seguro que desea Guardar?',
        function (e) {
            if (e) {
                
                CrearEditarReserva(TipoFormulario,idEditar);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function CrearEditarReserva(TipoFormulario=1,idEditar=0){
    
    var idBoton='btnGuardarReserva';
    document.getElementById(idBoton).disabled=true;
    var idPaciente=document.getElementById("idPaciente").value;    
    var NumeroAutorizacion=document.getElementById("NumeroAutorizacion").value;    
    var CantidadServicios=document.getElementById("CantidadServicios").value;    
    var Cie10=document.getElementById("Cie10").value;    
    var Observaciones=document.getElementById("Observaciones").value;  
      
    var form_data = new FormData();
        form_data.append('Accion', '4'); 
        form_data.append('idPaciente', idPaciente);
        form_data.append('NumeroAutorizacion', NumeroAutorizacion);
        form_data.append('CantidadServicios', CantidadServicios);
        form_data.append('Cie10', Cie10);
        form_data.append('Observaciones', Observaciones);        
        form_data.append('TipoFormulario', TipoFormulario);
        form_data.append('idEditar', idEditar);
        
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
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
                document.getElementById(idBoton).disabled=false;
                ListarReservas();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);
                
            }
            document.getElementById(idBoton).disabled=false;         
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
    }
    
    

function VerReserva(idReserva){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 7);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
               
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; 
            $('#Hora').timepicker({
                showInputs: false
              })
              
              
            $('#idHospital').select2({
		  
                placeholder: 'Selecciona una ips',
                ajax: {
                  url: 'buscadores/ips.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
              ListarCitasReserva(idReserva);
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function AgregarCitaAReserva(idReserva){
    var idBoton='btnCrearCita';
    document.getElementById(idBoton).disabled=true;
    var idHospital=document.getElementById("idHospital").value;    
    var Fecha=document.getElementById("FechaCitaReserva").value;    
    var Hora=document.getElementById("Hora").value; 
    var Observaciones=document.getElementById("Observaciones").value;  
      
    var form_data = new FormData();
        form_data.append('Accion', '5'); 
        form_data.append('idHospital', idHospital);
        form_data.append('Fecha', Fecha);
        form_data.append('Hora', Hora);
        form_data.append('Observaciones', Observaciones); 
        form_data.append('idReserva', idReserva);
        
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
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
                var ServiciosDisponibles=respuestas[2];
                document.getElementById(idBoton).disabled=false;
                document.getElementById('FechaCitaReserva').value='';
                ListarCitasReserva(idReserva);
                ActualizarServiciosDisponibles(ServiciosDisponibles);
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);
                
            }
            document.getElementById(idBoton).disabled=false;         
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function ListarCitasReserva(idReserva){
    var idDiv="DivCitasReserva";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 8);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function ActualizarServiciosDisponibles(ServiciosDisponibles){
    document.getElementById('spServiciosDisponibles').innerHTML=ServiciosDisponibles;
}

function EliminarCita(idReserva,Tabla,idItem){
        
    var form_data = new FormData();
        form_data.append('Accion', 6);
        form_data.append('Tabla', Tabla);
        form_data.append('idItem', idItem);
        form_data.append('idReserva', idReserva);
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                
                alertify.error(respuestas[1]);
                var ServiciosDisponibles=respuestas[2];  
                ListarCitasReserva(idReserva);
                ActualizarServiciosDisponibles(ServiciosDisponibles);
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                                
            }else{
                alertify.alert(data);
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function ConfirmarCita(idReserva,idItem){
        
    var form_data = new FormData();
        form_data.append('Accion', 7);        
        form_data.append('idItem', idItem);
        form_data.append('idReserva', idReserva);
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                
                alertify.success(respuestas[1]);
                 
                ListarCitasReserva(idReserva);
                
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                                
            }else{
                alertify.alert(data);
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function FormularioValidarReserva(idReserva){
    AbreModal('ModalAcciones');
    OcultaXID('BntModalAcciones');
    OcultaXID('btnCerrarModal');
    var idDiv="DivFrmModalAcciones";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 9);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){   
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos 
            ListarValidacionesReservas(idReserva);
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function ValidarReserva(idReserva){
    var idBoton='btnValidarReserva';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Guardando...";
    
    var Fecha=document.getElementById('Fecha').value;
    var Observaciones=document.getElementById('Observaciones').value;
        
    var form_data = new FormData();
        form_data.append('Accion', 8);
        form_data.append('Fecha', Fecha);
        form_data.append('Observaciones', Observaciones);
        form_data.append('idReserva', idReserva);        
        form_data.append('SoporteValidacionReserva', $('#SoporteValidacionReserva').prop('files')[0]);
        
                
    $.ajax({
        //async:false,
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
                
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Validar";
                alertify.success(respuestas[1]);
                ListarValidacionesReservas(idReserva);
                ListarReservas();
            }else if(respuestas[0]==="E1"){
                
                alertify.alert(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Validar";
                return;                
            }else{
                
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Validar";
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Validar";
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function ListarValidacionesReservas(idReserva){
    var idDiv="DivValidacionesReservas";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 10);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function EliminarValidacion(idReserva,Tabla,idItem){
        
    var form_data = new FormData();
        form_data.append('Accion', 9);
        form_data.append('Tabla', Tabla);
        form_data.append('idItem', idItem);
        form_data.append('idReserva', idReserva);
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                
                alertify.error(respuestas[1]);
                
                ListarValidacionesReservas(idReserva);
                ListarReservas();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                                
            }else{
                alertify.alert(data);
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function FormularioAdjuntarDocumentosCita(idCita,ListaAActualizar=1){
    AbreModal('ModalAcciones');
    OcultaXID('BntModalAcciones');
    OcultaXID('btnCerrarModal');
    var idDiv="DivFrmModalAcciones";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 11);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idCita', idCita);
        form_data.append('ListaAActualizar', ListaAActualizar);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){   
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos 
            ListarAdjuntosCita(idCita,ListaAActualizar);
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function AdjuntarDocumentoCita(idCita,idReserva,ListaAActualizar=1){
    var idBoton='btnAdjuntar';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Guardando...";
    
    var form_data = new FormData();
        form_data.append('Accion', 10);
        form_data.append('idCita', idCita); 
        
        form_data.append('upSoporte', $('#upSoporte').prop('files')[0]);
        
                
    $.ajax({
        //async:false,
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
                
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Adjuntar";
                alertify.success(respuestas[1]);
                ListarAdjuntosCita(idCita,ListaAActualizar);
                if(ListaAActualizar==1){
                    ListarCitasReserva(idReserva);
                }else{
                    ListarCitas();
                }
                document.getElementById("upSoporte").value="";
            }else if(respuestas[0]==="E1"){
                
                alertify.alert(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Adjuntar";
                return;                
            }else{
                
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Adjuntar";
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Adjuntar";
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function ListarAdjuntosCita(idCita,ListadoAActualizar=1){
    var idDiv="DivAdjuntosCita";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 12);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idCita', idCita);
        form_data.append('ListadoAActualizar', ListadoAActualizar);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function EliminarAdjuntoCita(idReserva,idCita,Tabla,idItem,ListadoAActualizar=1){
        
    var form_data = new FormData();
        form_data.append('Accion', 11);
        form_data.append('Tabla', Tabla);
        form_data.append('idItem', idItem);
        form_data.append('idReserva', idReserva);
        form_data.append('idCita', idCita);
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                
                alertify.error(respuestas[1]);
                
                ListarAdjuntosCita(idCita,ListadoAActualizar);
                if(ListadoAActualizar==1){
                    ListarCitasReserva(idReserva);
                }else{
                    ListarCitas();
                }
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                                
            }else{
                alertify.alert(data);
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function ListarCitas(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;
    var Estado =document.getElementById("cmbFiltrosCitas").value;
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 13);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
        form_data.append('Estado', Estado);
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function ListarPendientesPorFacturar(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 14);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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


function FormularioCrearFactura(idReserva){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Fecha =document.getElementById("FechaFinalRangos").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 15);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
        form_data.append('Fecha', Fecha);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data;
            
            $('.SelectServicio').select2({
		
                placeholder: 'Servicio',
                ajax: {
                  url: 'buscadores/catalogo_servicios.search.php',
                  dataType: 'json',
                  delay: 250,
                                    
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              }); 
              
              $('.SelectColaborador').select2({
		
                placeholder: 'Colaborador',
                ajax: {
                  url: 'buscadores/colaboradores.search.php',
                  dataType: 'json',
                  delay: 250,
                                    
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              }); 
              
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function CalculeTotalItem(idItem){
    var idServicio="cmbServicio_"+idItem;
    var idRecorrido="cmbRecorrido_"+idItem;
    var idCajaTotalItem="TxtTarifa_"+idItem;
    var Servicio=document.getElementById(idServicio).value;    
    var Recorrido=document.getElementById(idRecorrido).value;
    
    var form_data = new FormData();
        form_data.append('Accion', '12'); 
        form_data.append('Servicio', Servicio);
        form_data.append('Recorrido', Recorrido);
               
        $.ajax({
        url: './procesadores/salud_prefacturacion.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            
            if(respuestas[0]=="OK"){                
                
                var TotalItem=respuestas[1];                
                document.getElementById(idCajaTotalItem).value=TotalItem;
                calcularTotal();                
            }else if(respuestas[0]=="E1"){  
                
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);
                
            }
                   
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
    }
    
function calcularTotal(){
    
    var Total=0;
    $(".texttarifa").each(function(){
        if($(this).val()!=''){
            Total=Total+parseFloat($(this).val());
        }
        
    });
    console.log(Total);
    document.getElementById("spTotalFactura").innerHTML=number_format(Total,2);
    
}    

function confirmaGuardarFactura(){
    var idBoton='btnGuardar';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Guardando...";
    
    alertify.confirm('Está seguro que desea guardar esta factura?',
        function (e) {
            if (e) {             
                GuardarFactura();
            }else{
                alertify.error("Se canceló el proceso");                
                document.getElementById(idBoton).disabled=false;
                document.getElementById(idBoton).value="Guardar";
                return;
            }
        });
}
    
    
function GuardarFactura(){
        var Fecha=document.getElementById('FechaFinalRangos').value;
        
        var btnEnviar = $("#btnGuardar");
        var jsonFormularioFactura=$('#frmFactura').serialize();
        
        //console.log(jsonFormularioFactura);
        var form_data = new FormData();
        
            form_data.append('Accion', 13);
            form_data.append('Fecha', Fecha);            
            form_data.append('jsonFormularioFactura',jsonFormularioFactura);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Enviando"); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Guardar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                    //alertify.success(respuestas[1]);
                    alertify.alert(respuestas[1]);
                    MostrarListadoSegunID();
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}


function ListarFacturas(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    var idTipoFactura =document.getElementById("idTipoFactura").value;
    var idRegimenFactura =document.getElementById("idRegimenFactura").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 16);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
        form_data.append('idTipoFactura', idTipoFactura);
        form_data.append('idRegimenFactura', idRegimenFactura);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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



function ConfirmeGenerarRIPS(FechaInicial,FechaFinal){
    
    alertify.confirm('Está seguro que desea Generar estos RIPS, El proceso es Irreversible?',
        function (e) {
            if (e) {
                
                GenerarRIPSAF(FechaInicial,FechaFinal);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}


function GenerarRIPSAF(FechaInicial,FechaFinal){
        
        var Condicion = $("#btnGenerarRIPS").data("condicion");
        var btnEnviar = $("#btnGenerarRIPS");
        var idTipoFactura = $("#idTipoFactura").val();
        var idRegimenFactura =document.getElementById("idRegimenFactura").value;
        var form_data = new FormData();
        
            form_data.append('Accion', 14);
            form_data.append('q', Condicion);            
            form_data.append('FechaInicial',FechaInicial);
            form_data.append('FechaFinal',FechaFinal);
            form_data.append('idTipoFactura',idTipoFactura);
            form_data.append('idRegimenFactura',idRegimenFactura);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Generando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                    //alertify.success(respuestas[1]);
                    alertify.success(respuestas[1]);
                    GenerarRIPSAD(FechaInicial,FechaFinal,respuestas[2]);
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}


function GenerarRIPSAD(FechaInicial,FechaFinal,CuentaRIPS){
        
        var Condicion = $("#btnGenerarRIPS").data("condicion");
        var btnEnviar = $("#btnGenerarRIPS");
        
        var form_data = new FormData();
        
            form_data.append('Accion', 15);
            form_data.append('q', Condicion);            
            form_data.append('FechaInicial',FechaInicial);
            form_data.append('FechaFinal',FechaFinal);
            form_data.append('CuentaRIPS',CuentaRIPS);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Generando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                    //alertify.success(respuestas[1]);
                    alertify.success(respuestas[1]);
                    GenerarRIPSAT(FechaInicial,FechaFinal,respuestas[2]);
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}


function GenerarRIPSAT(FechaInicial,FechaFinal,CuentaRIPS){
        
        var Condicion = $("#btnGenerarRIPS").data("condicion");
        var btnEnviar = $("#btnGenerarRIPS");
        
        var form_data = new FormData();
        
            form_data.append('Accion', 16);
            form_data.append('q', Condicion);            
            form_data.append('FechaInicial',FechaInicial);
            form_data.append('FechaFinal',FechaFinal);
            form_data.append('CuentaRIPS',CuentaRIPS);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Generando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                    //alertify.success(respuestas[1]);
                    alertify.success(respuestas[1]);
                    GenerarRIPSUS(FechaInicial,FechaFinal,respuestas[2]);
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}

function GenerarRIPSUS(FechaInicial,FechaFinal,CuentaRIPS){
        
        var Condicion = $("#btnGenerarRIPS").data("condicion");
        var btnEnviar = $("#btnGenerarRIPS");
        
        var form_data = new FormData();
        
            form_data.append('Accion', 17);
            form_data.append('q', Condicion);            
            form_data.append('FechaInicial',FechaInicial);
            form_data.append('FechaFinal',FechaFinal);
            form_data.append('CuentaRIPS',CuentaRIPS);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Generando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                    //alertify.success(respuestas[1]);
                    alertify.success(respuestas[1]);
                    GenerarRIPSCT(FechaInicial,FechaFinal,respuestas[2]);
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}

function GenerarRIPSCT(FechaInicial,FechaFinal,CuentaRIPS){
        
        var Condicion = $("#btnGenerarRIPS").data("condicion");
        var btnEnviar = $("#btnGenerarRIPS");
        var idTipoFactura =document.getElementById("idTipoFactura").value;
        var idRegimenFactura =document.getElementById("idRegimenFactura").value;
        var form_data = new FormData();
        
            form_data.append('Accion', 18);
            form_data.append('q', Condicion);            
            form_data.append('FechaInicial',FechaInicial);
            form_data.append('FechaFinal',FechaFinal);
            form_data.append('CuentaRIPS',CuentaRIPS);
            form_data.append('idTipoFactura',idTipoFactura);
            form_data.append('idRegimenFactura',idRegimenFactura);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Generando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                   
                    alertify.alert(respuestas[1]);
                    
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}


function ListarRIPS(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 17);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function FormularioAnularFactura(idFactura){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    
    var form_data = new FormData();
        form_data.append('Accion', 18);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idFactura', idFactura);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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


function ConfimaAnularFactura(idFactura){
    alertify.confirm('Está seguro que desea Anular esta Factura?',
        function (e) {
            if (e) {
                
                AnularFactura(idFactura);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function AnularFactura(idFactura){
        
       
        var btnEnviar = $("#btnAnularFactura");
        var Observaciones =document.getElementById("Observaciones").value;
        var TipoAnulacion =document.getElementById("TipoAnulacion").value;
        var form_data = new FormData();
        
            form_data.append('Accion', 19);
            
            form_data.append('idFactura',idFactura);
            form_data.append('Observaciones',Observaciones);
            form_data.append('TipoAnulacion',TipoAnulacion);
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Anulando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                   
                    alertify.alert(respuestas[1]);
                    MostrarListadoSegunID();
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}



function FormularioAnularReserva(idReserva){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    
    var form_data = new FormData();
        form_data.append('Accion', 19);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idReserva', idReserva);
        
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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


function ConfimaAnularReserva(idReserva){
    alertify.confirm('Está seguro que desea Anular esta Factura?',
        function (e) {
            if (e) {
                
                AnularReserva(idReserva);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function AnularReserva(idReserva){
        
       
        var btnEnviar = $("#btnAnularReserva");
        var Observaciones =document.getElementById("Observaciones").value;
        
        var form_data = new FormData();
        
            form_data.append('Accion', 20);
            
            form_data.append('idReserva',idReserva);
            form_data.append('Observaciones',Observaciones);
            
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            beforeSend: function(){
                /*
                * Esta función se ejecuta durante el envió de la petición al
                * servidor.
                * */
                // btnEnviar.text("Enviando"); Para button 
                btnEnviar.val("Anulando..."); // Para input de tipo button
                btnEnviar.attr("disabled","disabled");
            },
            complete:function(data){
                /*
                * Se ejecuta al termino de la petición
                * */
                btnEnviar.val("Generar");
                btnEnviar.removeAttr("disabled");
            },
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                   
                    alertify.alert(respuestas[1]);
                    MostrarListadoSegunID();
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}

function ListarLiquidacionColaboradores(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    var Busquedas =document.getElementById("TxtBusquedas").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 20);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function editar_registro(tabla,valor_id,campo_edit,id_edit){
        
       
       
        var valor =document.getElementById(valor_id).value;
        
        var form_data = new FormData();
        
            form_data.append('Accion', 21);
            
            form_data.append('tabla',tabla);
            form_data.append('campo_edit',campo_edit);
            form_data.append('id_edit',id_edit);
            form_data.append('valor',valor);
            
        
        $.ajax({
            url: './procesadores/salud_prefacturacion.process.php',
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            //data: form_data,
            type: 'post',
            data: form_data,
            
            success: function(data){
               var respuestas = data.split(';'); 
                if (respuestas[0] == "OK"){                 
                   
                    alertify.success(respuestas[1]);
                    
                }else if(respuestas[0] == "E1"){
                    alertify.error(respuestas[1]);
                    MarqueErrorElemento(respuestas[2]);
                }else{
                    alertify.alert(data);

              }

            },
            error: function(data){
                alert(xhr.status);
                alert(thrownError);
            }
        });
        // Nos permite cancelar el envio del formulario
        return false;
    

}

function ListarDocumentosElectronicosPendientes(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">Cargando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 21);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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


function ListarDocumentosElectronicosEnviados(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">Cargando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 22);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function ListarDocumentosElectronicosError(Page=1){
    var idDiv="DivGeneralDraw";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">Cargando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;    
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 23);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);        
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
                
                
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/salud_prefacturacion.draw.php',// se indica donde llegara la informacion del objecto
        
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

function ver_json_documento(empresa_id,documento_electronico_id){
    
    if(empresa_id==''){
        var empresa_id=document.getElementById("empresa_id").value;
    }
    
    openModal('ModalAcciones');
    var idDiv="DivFrmModalAcciones";
    urlQuery='procesadores/facturador.process.php';    
    var form_data = new FormData();
        form_data.append('Accion', 10);  
        form_data.append('empresa_id', empresa_id);  
        form_data.append('documento_electronico_id', documento_electronico_id); 
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
            //document.getElementById(idDiv).innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        },
        complete: function(){
           
        },
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });

}

function reportar_documento_electronico_api(documento_electronico_id){
    
    var boton_id="btn_reporte_documento_"+documento_electronico_id;
    document.getElementById(boton_id).disabled=true;
    var empresa_id = document.getElementById('empresa_id').value;    
        
    var form_data = new FormData();
        form_data.append('Accion', 7);        
        form_data.append('empresa_id', empresa_id);
        form_data.append('documento_electronico_id', documento_electronico_id);        
         
        $.ajax({
        url: './procesadores/facturador.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function() { //lo que hará la pagina antes de ejecutar el proceso
           mostrar_spinner('Procesando');
        },
        
        success: function(data){
            ocultar_spinner();
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                
                alertify.success(respuestas[1]);
                idListado=9;
                MostrarListadoSegunID();
                
            }else if(respuestas[0]=="E1"){                
                alert(respuestas[1]);
                idListado=10;
                MostrarListadoSegunID();
                
            }else{
                alert(data);
                
            }
                       
        },
        error: function (xhr, ajaxOptions, thrownError) {
            ocultar_spinner();
            document.getElementById(boton_id).disabled=false;
            alert(xhr.status);
            alert(thrownError);
          }
      });
}
    
MostrarListadoSegunID();

