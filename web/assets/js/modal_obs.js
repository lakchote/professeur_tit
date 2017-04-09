/**
 * Created by Benoit on 09/04/2017.
 */

function initMap() {
    var input = (document.getElementById('pac-input'));
    var options = {
        componentRestrictions: {country: 'fr'}
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert(alert1);
            return;
        }

        var nbAdressComp = place.address_components.length;
        for ($i = 0; $i < nbAdressComp; $i++) {
            if (place.address_components[$i].types[0] == 'locality') {
                var laVille = place.address_components[$i].short_name;
            }
        }
        if (!laVille) {
            window.alert(alert2);
            return;
        }

        $('#obs_form_ville').val(laVille);
        $('#obs_form_longitude').val(place.geometry.location.lng().toFixed(10));
        $('#obs_form_latitude').val(place.geometry.location.lat().toFixed(11));
    });

}
function geocodeLatLng(geocoder) {
    var latlng = {lat: parseFloat($('#obs_form_latitude').val()), lng:  parseFloat($('#obs_form_longitude').val())};
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results) {
                var laVille = results[0].address_components[2].short_name;
                $('#obs_form_ville').val(laVille);
            } else {
                window.alert('No results found');
            }
        } else {
            window.alert('Geocoder failed due to: ' + status);
        }
    });
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
}

function showPosition(position) {
    $('#obs_form_longitude').val(position.coords.longitude);
    $('#obs_form_latitude').val(position.coords.latitude);
    $('#pac-input').val(position.coords.latitude + " " + position.coords.longitude);
    var geocoder = new google.maps.Geocoder;
    geocodeLatLng(geocoder);
}

$('#recherche').autocomplete({
    source : pathListing,
    minLength: 3,
    create: function () {
        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
            return $('<li><strong>' + item.value + '</strong> / <i>' + item.label + '</i></li>').appendTo(ul);
        };
    },
    select: function (event, ui) {
        $("#recherche").val(ui.item.value + ' / ' + ui.item.label);
        $("#obs_form_taxon").val(ui.item.label);
        return false;
    },
});



$('#location').click(function() {
    getLocation();
});

$('#btn-publish').click(function(e) {
    e.preventDefault();
    var $formData = $('form').serializeArray();
    $.ajax({
        url: pathValidate,
        method: "POST",
        data: $formData,
        statusCode: {
            201: function (msg) {
                $('#modal-load-desktop').html(msg);
                $('#recherche').val($formData[3].value);
                $('#pac-input').val($('#obs_form_ville').val());
            },
            200: function () {
                $('form').submit();
            }
        }
    });
});
