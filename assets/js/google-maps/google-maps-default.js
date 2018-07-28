var map;

function initMap() {
    var myLatlng = new google.maps.LatLng(-34.623511, -58.436260);
    var mapOptions = {
        zoom: 15,
        scrollwheel: false,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    }
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        animation: google.maps.Animation.DROP,
        title: 'ArmaTuPedal'
    });

    var contentString = '<div class="info-window-content"><h2>ArmaTuPedal</h2>' +
        '<h3>Dejá de buscar el pedal perfecto. Diseñalo.</h3>';
        /*+'<p>Some more details for directions or company informations...</p></div>';*/

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
}

google.maps.event.addDomListener(window, 'load', initMap);

$('a[data-type="gmap"]').on('shown.bs.tab', function(e) {
    initialize();
})
