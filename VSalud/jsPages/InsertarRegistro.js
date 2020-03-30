
if ($('#cod_pagador_min').length) {
    
    document.getElementById("cod_pagador_min").addEventListener("change", VerificaCodigoEPS);
    
}

function VerificaCodigoEPS(){
    var form_data = new FormData();
        form_data.append('idAccion', 1); //1 para validar si existe un codigo eps
        form_data.append('cod_pagador_min', $('#cod_pagador_min').val());
      
    $.ajax({
        
        url: 'buscadores/ValidacionesInsert.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data)
           if(data=="Error"){
                alertify.alert("La EPS ya Existe");
                document.getElementById('BtnGuardarRegistro').disabled=true;
            }else{
                alertify.success("Eps disponible");
                document.getElementById('BtnGuardarRegistro').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //alert("Error al tratar de borrar el archivo");
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
