var huboError;
var idEnvio=0;
var precioEnvio=0;

if(getUrlParameter("pago")=="incompleto"){
  procesoPagoInterrumpido();
  agregarEstadoDeVenta(EstadosPagos.mpPending,"checkout.js - redirigido MP");
} else if (getUrlParameter("pago")=="error"){
  procesoPagoInterrumpido();
  agregarEstadoDeVenta(EstadosPagos.mpRejected,"checkout.js - redirigido MP");
}


$(document).ajaxStart(function(){
  huboError = false;
  $("#envios").css("display","none");
  $("#loader-envios").css("display","block");
  $("#radio-retira").prop('checked', true);
  $("#error-calc-envio").css("display","none");
  console.log("ajaxStart");
});
$(document).ajaxComplete(function(){
  $("#loader-envios").css("display","none");
  if(!huboError){
    $("#envios").css("display","flex");
  }
  console.log("ajaxComplete");
});

$(document).ajaxError(function(event, request, settings) {
  huboError=true;
  $("#loader-envios").css("display","none");
  $("#envios").css("display","none");
  $("#error-calc-envio").css("display","block");
  console.log("ajaxError");
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
function procesoPagoInterrumpido(){
  $('#procesoPagoInterrumpido').modal('show');
}
function errorMP(){
  $('#errorMP').modal('show');
}
function loadingMP(bool){
  if(bool){
    $('#loadingMP').modal('show');
  } else {
    $('#loadingMP').modal('hide');
  }
}
function loadingEfectivo(bool){
  if(bool){
    $('#loadingEfectivo').modal('show');
  } else {
    $('#loadingEfectivo').modal('hide');
  }
}


// INICIAR COMPRA
function iniciarCompra(producto){
  var frmvalid = $("#frmDatos").valid({errorClass: "authError"});
  if (frmvalid) {
    var nombre = $("#nombre").val();
    var mail = $("#mail").val();
    var suscribirse = $("#checkboxSuscribirse").is(':checked');
    var efectivo = $("#checkboxEfectivo").is(':checked');

    guardarInicioDeCompra(nombre, mail, suscribirse, idEnvio, precioEnvio, producto, efectivo, "checkout.js");
  }
}

// GUARDAR INICIO DE COMPRA
function guardarInicioDeCompra(nombre, mail, suscribirse, idEnvio, precioEnvio, producto, efectivo, comentario){
  $.ajax({
     type: "POST", url: "../php/guardar-inicio-compra.php",
     data: {pnombre: nombre, pmail:mail, psuscribirse:suscribirse, pidEnvio:idEnvio, pprecioEnvio:precioEnvio, pproducto:producto, pefectivo:efectivo,pcomentario:comentario},
     beforeSend: function(xhr){
         console.log("guardando inicio de compra: "+nombre+" "+ mail+" "+ suscribirse+" "+ idEnvio+" "+ precioEnvio+" "+ producto+" "+ efectivo);
         if (efectivo){
           loadingEfectivo(true);
         } else {
           loadingMP(true);
         }
     },
     error: function(obj,text,error) {
         console.log("guardando inicio de compra: ERROR:"+obj.responseText);
         if (efectivo){
           loadingEfectivo(false);
         } else {
           loadingMP(false);
         }
         errorMP();
     },
     success: function(data, textStatus, jqXHR){
       console.log("guardando inicio de compra: Ok");
         if (efectivo){
           window.location.href="checkout-listo.html";
         } else {
           crearPreferenciaPagoMP(nombre, mail, producto);
         }
     }
  });

}

// CREAR PREFERENCIA MP
function crearPreferenciaPagoMP(nombre, mail, producto){
  $.ajax({
     type: "POST", url: "../php/generar-preferencia-mp.php",
     data: {pnombre: nombre, pmail:mail, pproducto:producto},
     beforeSend: function(xhr){
         console.log("generando preferencia MP");
     },
     error: function(obj,text,error) {
       console.log("generando preferencia MP: ERROR:"+obj.responseText);
       loadingMP(false);
       errorMP();

     },
     success: function(data, textStatus, jqXHR){
       console.log("generando preferencia MP Ok"+ data);
       loadingMP(false);
       ejecutarPagoMP(data);
     }
  });
}

// EJECUTAR PREFERENCIA DE PAGO MP
function ejecutarPagoMP(url){
    $MPC.openCheckout ({
        url: url,
        mode: "modal",
        onreturn: function(json) {
          if (json.collection_status=='approved'){
              agregarEstadoDeVenta(EstadosPagos.mpApproved,"checkout.js");
              window.location.href="checkout-listo.html";
          } else if(json.collection_status=='pending'){
              agregarEstadoDeVenta(EstadosPagos.mpPending,"checkout.js");
              procesoPagoInterrumpido();
          } else if(json.collection_status=='in_process'){
              agregarEstadoDeVenta(EstadosPagos.mpInProcess,"checkout.js");
              window.location.href="checkout-listo.html";
          } else if(json.collection_status=='rejected'){
              agregarEstadoDeVenta(EstadosPagos.mpRejected,"checkout.js");
              procesoPagoInterrumpido();
          } else {
              agregarEstadoDeVenta(EstadosPagos.desconocido,"checkout.js - "+json.collection_status);
              procesoPagoInterrumpido();
          }
        }
    });
}

function agregarEstadoDeVenta(idEstado, comentario){
  console.log("agregando estado a venta: "+idEstado);
  $.ajax({
     type: "POST", // Method type GET/POST
     url: "../php/venta-agregar-estado.php", //Ajax Action url
     data: {pidEstado: idEstado, pcomentario:comentario},

     // Before call ajax you can do activity like please wait message
     beforeSend: function(xhr){
         //alert("ok");
     },

     //Will call if method not exists or any error inside php file
     error: function(obj,text,error) {
       console.log("agregando estado a venta: "+idEstado+" ERROR:"+obj.responseText);
       //alert("error");
     },

     success: function(data, textStatus, jqXHR){
       console.log("agregando estado a venta: "+idEstado+" Ok");
         //alert("ok");
     }
  });
}
