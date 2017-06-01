/**
 * Created by Benoit on 15/04/2017.
 */

/* AUTOCOMPLETE DE L'ESPECE */
$('#espece').autocomplete({
    source : pathListing,
    minLength: 3,
    create: function () {
        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
            return $('<li><strong>' + item.value + '</strong> / <i>' + item.label + '</i></li>').appendTo(ul);
        };
    },
    select: function (event, ui) {
        $("#espece").val(ui.item.value + ' / ' + ui.item.label);
        $("#map_form_taxon").val(ui.item.label);
        $(this).blur();
        return false;
    },
});

/* DATE PARAMETERS*/
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
if(dd<10){
    dd='0'+dd
}
if(mm<10){
    mm='0'+mm
}

today = yyyy+'-'+mm+'-'+dd;
$('#map_form_date').attr("max", today);


$('#map_form_date').on('change', function() {
    $('#map_form_date').blur();
})


/* MAP MANAGEMENT */
var map, infowindow;
var markers = [];

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.93926, lng: 1.9552},
        zoom: 5,
        mapTypeId: 'terrain'
    });
    var input = (document.getElementById('map_form_ville'));
    var options = {
        componentRestrictions: {country: 'fr'}
    };
    infowindow = new google.maps.InfoWindow();
    var autocomplete = new google.maps.places.Autocomplete(input, options);


    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            //window.alert(alert1);
            return;
        }
        map.setCenter(place.geometry.location);
        map.setZoom(14);

        var nbAdressComp = place.address_components.length;
        for ($i = 0; $i < nbAdressComp; $i++) {
            if (place.address_components[$i].types[0] == 'locality') {
                var laVille = place.address_components[$i].short_name;
            }
        }
        if (!laVille) {
           // window.alert(alert2);
            return;
        }
        $('#lieux').val(laVille);
  });

    getMarkers();
}




// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}


// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}


// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

function makeInfoWindowEvent(map, infowindow, contentString, marker) {
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });
}


$('#deleteEspece, #deleteDate , #deleteVille').on('click', function() {
    switch (this.id) {
        case 'deleteEspece':
            $('#map_form_taxon').val("");
            $('#espece').val("").blur();
            break;
        case 'deleteDate':
            $('#map_form_date').val("").blur();
            break;
        case 'deleteVille':
            $('#map_form_ville').val("");
            map.setCenter({lat: 46.93926, lng: 1.9552});
            map.setZoom(5);
            break;
        default:
            break;

    }
})


function getMarkers() {
    var formData = $('form').serializeArray();
    $.ajax({
        url: pathMap,
        method: "POST",
        data: formData,
        statusCode: {
            201: function () {
                console.log('pb');
            },
            200: function (data) {
                deleteMarkers();
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
                    image = "../uploads/observations/" + image;

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
                    '<li><b>Auteur : </b><a href="'+ pathUser.replace('user_slug', data[i]['userSlug']) +'">' + data[i]['auteur'] +'</a></li>'+
                    '<li><b>Date : </b>' + date +'</li>'+
                    '</ul>'+
                    '</div>'+
                    '</div>';

                makeInfoWindowEvent(map, infowindow, windowContent, marker);
                markers.push(marker);
            }

            }
        }
    });
}

$('#espece, #map_form_date').on('blur', function() {
    getMarkers();
});

