var huboError;
var idEnvio=0;
var precioEnvio=0;

$(document).ajaxStart(function(){
  huboError = false;
  $("#envios").css("display","none");
  $("#loader-envios").css("display","block");
  $("#radio-retira").prop('checked', true);
    $("#error-calc-envio").css("display","none");

});
$(document).ajaxComplete(function(){
  $("#loader-envios").css("display","none");
  if(!huboError){
    $("#envios").css("display","flex");
  }
});

$(document).ajaxError(function(event, request, settings) {
  huboError=true;
  $("#loader-envios").css("display","none");
  $("#envios").css("display","none");
  $("#error-calc-envio").css("display","block");
});

function calcularEnvio(producto){
  $("#ops-envio").load("../php/obtener-envios.php", {codProducto: producto, codPostal: document.getElementById("input-cp").value});
}

function cambiarEnvio(valorProducto){
  valorProducto = parseFloat(valorProducto);
  var valueSeleccionado = $("input[name=shippingOption]:checked").val();
  var labelSeleccionado="Retiro por Caballito";
  var costoSeleccionado=0.0;
  if(valueSeleccionado!=0){
    labelSeleccionado = $("label[for='"+valueSeleccionado+"']").text();

    costoSeleccionado = $("td[for='"+valueSeleccionado+"']").attr("value");
    costoSeleccionado = costoSeleccionado.replace(",",".")
    costoSeleccionado = parseFloat(costoSeleccionado);

  }

  idEnvio=valueSeleccionado;
  precioEnvio = costoSeleccionado;
  var total = valorProducto + costoSeleccionado;
  total = Number(total).toFixed(2);
  costoSeleccionado = Number(costoSeleccionado).toFixed(2);


  $("#metodo-envio").text(labelSeleccionado);
  $("#costo-envio").text(("$"+costoSeleccionado).replace(".",","));
  $("#total").text(("$"+total).replace(".",","));

  var efectivo = $("#checkboxEfectivo").is(':checked');

  if(efectivo && idEnvio!=0){
    $("#checkboxEfectivo").prop("checked", false);
    informarReglasEfectivoYEnvio();
  }

}

function validarEnvioYEfectivo(valorProducto){
  var efectivo = $("#checkboxEfectivo").is(':checked');

  if(efectivo && idEnvio!=0){
    $("#0").prop("checked", true);
    informarReglasEfectivoYEnvio();
  }
  cambiarEnvio(valorProducto);
}

function informarReglasEfectivoYEnvio(){
  $('#informarReglasEfectivoYEnvio').modal('show');
}

function checkValidation(producto){

  var frmvalid = $("#frmDatos").valid({errorClass: "authError"});
  if (frmvalid) {
    var nombre = $("#nombre").val();
    var mail = $("#mail").val();
    var suscribirse = $("#checkboxSuscribirse").is(':checked');
    //idEnvio
    //precioEnvio
    //valorProducto
    var efectivo = $("#checkboxEfectivo").is(':checked');

    guardarInicioDeCompra(nombre, mail, suscribirse, idEnvio, precioEnvio, producto, efectivo);

    $MPC.openCheckout ({
        url: "https://www.mercadopago.com/mla/checkout/pay?pref_id=185944080-50a46900-6616-42ba-b6cb-c9a19c60d93e",
        mode: "modal",
        onreturn: function(json) {
          if (json.collection_status=='approved'){
              alert ('Pago acreditado');
          } else if(json.collection_status=='pending'){
              alert ('El usuario no completó el pago');
          } else if(json.collection_status=='in_process'){
              alert ('El pago está siendo revisado');
          } else if(json.collection_status=='rejected'){
              alert ('El pago fué rechazado, el usuario puede intentar nuevamente el pago');
          } else if(json.collection_status==null){
              alert ('El usuario no completó el proceso de pago, no se ha generado ningún pago');
          }
        }
    });
    //alert(" nombre:"+nombre+" \n mail:"+mail+" \n suscribirse:"+suscribirse+" \n idEnvio:"+idEnvio+" \n precioEnvio:"+precioEnvio+" \n valorProducto:"+valorProducto+" \n efectivo:"+efectivo)
  }
}
function guardarInicioDeCompra(nombre, mail, suscribirse, idEnvio, precioEnvio, producto, efectivo){
  var botonPagar = $("#botonPagar");
  $.ajax({
     type: "POST", // Method type GET/POST
     url: "../php/guardar-inicio-compra.php", //Ajax Action url
     data: {pnombre: nombre, pmail:mail, psuscribirse:suscribirse, pidEnvio:idEnvio, pprecioEnvio:precioEnvio, pproducto:producto, pefectivo:efectivo},

     // Before call ajax you can do activity like please wait message
     beforeSend: function(xhr){
         //botonPagar.text("antes");
     },

     //Will call if method not exists or any error inside php file
     error: function(obj,text,error) {
       //alert(obj.responseText);
     },

     success: function(data, textStatus, jqXHR){
         //botonPagar.text("ok");
     }
  });

}
