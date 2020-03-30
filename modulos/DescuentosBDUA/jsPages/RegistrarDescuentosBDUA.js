/**
 * Controlador para registrar los descuentos BDUA de las capitass
 * JULIAN ALVARAN 2019-07-30
 * TECHNO SOLUCIONES SAS 
 * 
 */

/**
 * Cierra una ventana modal
 * @param {type} idModal
 * @returns {undefined}
 */
function CierraModal(idModal) {
    $("#"+idModal).modal('hide');//ocultamos el modal
    $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
    $('.modal-backdrop').remove();//eliminamos el backdrop del modal
}

function AbreModal(idModal){
    $("#"+idModal).modal();
}


function MuestraXID(id){
    
    
    document.getElementById(id).style.display="block";
    
    
}


function OcultaXID(id){
    
    
    document.getElementById(id).style.display="none";
    
    
}

/**
 * Muestra u oculta un elemento por su id
 * @param {type} id
 * @returns {undefined}
 */

function MuestraOcultaXID(id){
    
    var estado=document.getElementById(id).style.display;
    if(estado=="none" | estado==""){
        document.getElementById(id).style.display="block";
    }
    if(estado=="block"){
        document.getElementById(id).style.display="none";
    }
    
}

function MarqueErrorElemento(idElemento){
    console.log(idElemento);
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="pink";
    document.getElementById(idElemento).focus();
}

function ConfirmarEnvio(){
    var NumeroFactura=document.getElementById('NumeroFactura').value;
    if(NumeroFactura==""){
        alertify.alert("No se ha seleccionado una factura");
        return;
    }
    alertify.confirm('Está seguro que desea Registrar este descuento a la Factura '+NumeroFactura+'?',
        function (e) {
            if (e) {

                alertify.success("Iniciando proceso");                    
                RegistrarDescuento();
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function RegistrarDescuento(){
    document.getElementById('BtnEjecutar').disabled=true;
    document.getElementById('BtnEjecutar').value="Procesando...";
    var FechaDescuento=document.getElementById('FechaDescuento').value;
    var NumeroFactura=document.getElementById('NumeroFactura').value;
    var Radicado=document.getElementById('Radicado').value;
    var AfiliadosIMA=document.getElementById('AfiliadosIMA').value;
    var ValorDescuento=document.getElementById('ValorDescuento').value;
        
    var form_data = new FormData();
        form_data.append('Accion', 1);
        form_data.append('FechaDescuento', FechaDescuento);
        form_data.append('NumeroFactura', NumeroFactura);
        form_data.append('Radicado', Radicado);
        form_data.append('AfiliadosIMA', AfiliadosIMA);
        form_data.append('ValorDescuento', ValorDescuento);
        
    $.ajax({
        //async:false,
        url: './procesadores/RegistrarDescuentosBDUA.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
                
                alertify.success(respuestas[1]);
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Ejecutar";
                document.getElementById('NumeroFactura').value='';
                document.getElementById('Radicado').value='';
                document.getElementById('AfiliadosIMA').value='';
                document.getElementById('ValorDescuento').value='';
                
                document.getElementById('NumeroFactura').style.backgroundColor="white";
                document.getElementById('Radicado').style.backgroundColor="white";
                document.getElementById('AfiliadosIMA').style.backgroundColor="white";
                document.getElementById('ValorDescuento').style.backgroundColor="white";
                document.getElementById('FechaDescuento').style.backgroundColor="white";
                
                
            }else if(respuestas[0]==="E1"){
                
                alertify.alert(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Ejecutar";
                return;                
            }else{
                
                alertify.alert(data);
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Ejecutar";
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            document.getElementById('BtnEjecutar').disabled=false;
            document.getElementById('BtnEjecutar').value="Ejecutar";
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

document.getElementById('BtnMuestraMenuLateral').click();
