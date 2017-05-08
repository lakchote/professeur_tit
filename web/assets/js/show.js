/**
 * Created by Benoit on 07/05/2017.
 */
var map, infowindow;
var markers = [];
function initMap() {
    var myLatLng = {lat: -25.363, lng: 131.044};

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.93926, lng: 1.9552},
        zoom: 5,
        mapTypeId: 'terrain',
        scrollwheel: false,
        scaleControl: true,
        mapTypeControl: false,
    });
    infowindow = new google.maps.InfoWindow();
    for (var i = 0; i < data.length; i++) {
        var long = data[i]['longitude'];
        var lat = data[i]['latitude'];
        var titre = data[i]['taxon'];
        var latLng = new google.maps.LatLng(lat,long);
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            title: titre,
        });
        var image = data[i]['image'];
        if (image != "") {
            image = "/professeur_tit/web/uploads/observations/" + image;
        }
        else {
            image = "/professeur_tit/web/assets/img/tit_logo.png";
        }

        var date = new Date(data[i]['date']['date']);
        var options = {weekday: "long", year: "numeric", month: "long", day: "numeric"};
        var date = date.toLocaleDateString($lang, options);

        var windowContent = '<div class="carte__popUp">' +
            '<div id="siteNotice">' +
            '<p class="carte__popUp--title">'+titre +'</p>' +
            '</div>'+
            '<div id="bodyContent">' +
            '<img src="'+ image +'" class="carte__popUp--photo img-rounded img-responsive">'+
            '<ul>'+
            '<li><b>Auteur : </b><a href="'+ pathUser.replace('user_id', data[i]['userId']) +'">' + data[i]['auteur'] +'</a></li>'+
            '<li><b>Date : </b>' + date +'</li>'+
            '</ul>'+
            '</div>'+
            '</div>';

        makeInfoWindowEvent(map, infowindow, windowContent, marker);
        markers.push(marker);
}
}


function makeInfoWindowEvent(map, infowindow, contentString, marker) {
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });
}