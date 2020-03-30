$('#BtnEnviar').click(submitInfo);

function submitInfo(event){
    
  event.preventDefault();
  var form_data = getInfoForm();
  $.ajax({
    url: './Consultas/SIHO.process.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
      if (data != "") { 
          console.log(data)
          document.getElementById('DivMensajesSIHO').innerHTML=""
          var divSIHO = document.getElementById('DivMensajesSIHO')
          var html='<table class="table table-bordered table table-hover" border="1" id="CarteraSIHO">' 
           html+='<tr><td style="text-align:center" ><strong>COD</strong></td>'
           html+='<td ><strong>ENTIDAD</strong></td>'
           html+='<td ><strong>SALDO ANTERIOR</strong></td>'
           html+='<td ><strong>PAGOS VIG ANTERIORES</strong></td>'
           html+='<td ><strong>CUENTAS PRESENTADAS EN EL PERIODO QUE SE INFORMA</strong></td>'
           html+='<td ><strong>PAGOS RECIBIDOS EN EL PERIODO QUE SE INFORMA</strong></td>'
           html+='<td ><strong>GLOSAS Y DESCUENTOS DESCARGADOS DE CARTERA</strong></td>'
           html+='<td ><strong>GLOSAS Y DESCUENTOS VIGENCIAS ANTERIORES</strong></td>'
           html+='<td ><strong>TOTAL CARTERA</strong></td>'
           html+='<td ><strong>60 dias</strong></td>'
           html+='<td ><strong>De 61 a 90 dias</strong></td>'
           html+='<td ><strong>De 91 a 180 dias</strong></td>'
           html+='<td ><strong>De 181 a 360 días</strong></td>'
           html+='<td ><strong>Mayor de 360</strong></td>'
           html+='<td ><strong>EQUIVALENTE PORCENTUAL DE LA CARTERA POR EPS</strong></td>'
           
        Object.keys(data).forEach(function(key){
            var SaldoAnterior = new Intl.NumberFormat().format(data[key].SaldoAnterior);
            html+='<tr><td>'+data[key].Codigo+'</td>'
            html+='<td>'+data[key].NombreEntidad+'</td>'
            html+='<td style="text-align:right"> $ '+SaldoAnterior+'</td></td>'
            html+='</tr>'
            //console.log(data[key]); 
        })
         html+='</tabla>'   
        $('#DivMensajesSIHO').append(html);
        
      }else {
        alert("No hay resultados para la consulta");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
}


function getInfoForm(){
  var form_data = new FormData();
  form_data.append('TxtFechaCorte', $("[id='TxtFechaCorte']").val());
  
  return form_data;
}