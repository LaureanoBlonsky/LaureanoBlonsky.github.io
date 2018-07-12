

function sumarDiasHabiles(fromDate, days) {
    var count = 0;
    while (count < days) {
        fromDate.setDate(fromDate.getDate() + 1);
        if (fromDate.getDay() != 0 && fromDate.getDay() != 6) // Skip weekends
            count++;
    }
    return fromDate;
}

function formatDateDiaMes(fecha){
    //var options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
    var options = { weekday: 'long', month: 'short', day: 'numeric' };
    return fecha.toLocaleDateString("es-ES", options);
}

var EstadosPagos = {
  checkoutMp : 1,
  checkoutEfectivo : 2,

  mpApproved : 3,
  efectivoPagado : 4,

  mpPending : 5,
  mpIn_process : 6,
  mpIn_mediation : 7,

  mpRejected : 8,
  mpCancelled : 9,

  mpRefunded : 10,
  mpCharged_back : 11,

  desconocido : 999

};

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};
