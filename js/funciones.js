

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
