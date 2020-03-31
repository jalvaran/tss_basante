/**
 * Controlador para administrar los pagos que ingresan al modulo de tesoreria
 * JULIAN ANDRES ALVARAN
 * 2020-02-16
 */

document.getElementById("BtnMuestraMenuLateral").click(); //da click sobre el boton que esconde el menu izquierdo de la pagina principal

/**
 * Funcion que lista las tablas de una base de datos
 * @returns {undefined}
 */
 

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
    
}

ListarPacientes();

