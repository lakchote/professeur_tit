/**
 * Created by Benoit on 09/04/2017.
 */

let isLocalStorage = false;
let fileReader = new FileReader();
let imageObs = undefined;

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
    var latlng = {lat: parseFloat($('#obs_form_latitude').val()), lng: parseFloat($('#obs_form_longitude').val())};
    geocoder.geocode({'location': latlng}, function (results, status) {
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

function dataURLtoFile(url, name, type) {
    return fetch(url)
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => new File([arrayBuffer], name, {type: type}));
}

(function checkLocalStorageData() {
    let imageStored = localStorage.getItem('obs_form[image]');
    if (!imageStored) return false;
    dataURLtoFile(imageStored, 'file.jpeg', 'image/jpeg').then(function (file) {
        imageObs = file;
    });
    let ville = localStorage.getItem('localisation');
    localStorage.setItem('obs_form[ville]', ville);
    let recherche = localStorage.getItem('recherche');
    let description = localStorage.getItem('obs_form[description]');
    if (ville && !(localStorage.getItem('obs_form[longitude]')) && navigator.onLine) {
        fetch('https://maps.googleapis.com/maps/api/geocode/json?address=' + ville + '&key=AIzaSyACO55kGEkd8YeNffAaErhE02wa_UigduQ')
            .then(response => response.json())
            .then(data => {
                localStorage.setItem('obs_form[longitude]', data.results[0].geometry.location.lng.toFixed(10));
                localStorage.setItem('obs_form[latitude]', data.results[0].geometry.location.lat.toFixed(11));
            });
    }
    $('#pac-input').val(ville);
    $('#recherche').val(recherche);
    $('#obs_form_description').val(description);
    isLocalStorage = true;
    fetch(imageStored)
        .then(response => response.blob())
        .then(blob => {
            $('#preview').removeClass('hidden');
            $('#theFile').attr('src', URL.createObjectURL(blob));
        });
})();

function checkOnlineStatus(data) {
    if (!navigator.onLine) {
        for (let pair of data.entries()) {
            if (pair[1] instanceof File) {
                let key = pair[0];
                fileReader.readAsDataURL(pair[1]);
                fileReader.onload = function () {
                    localStorage.setItem(key, fileReader.result);
                }
            }
            localStorage.setItem(pair[0], pair[1]);
        }
        alert('Vous ne disposez d\'aucune connexion Internet. Votre observation a été enregistrée');
        return false;
    }
    return true;
}

function getOfflineFormData() {
    let formData = new FormData();
    for (let i = 0; i < localStorage.length; ++i) {
        (localStorage.key([i]) === 'obs_form[image]') ? formData.append(localStorage.key([i]), imageObs) : formData.append(localStorage.key([i]), localStorage.getItem(localStorage.key([i])));
    }
    localStorage.clear();
    isLocalStorage = false;
    return formData;
}

$('#recherche').autocomplete({
    source: pathListing,
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


$('#location').click(function () {
    getLocation();
});

$('#obs_form_image').on('change', function (e) {
    var files = $(this)[0].files;
    if (files.length > 0) {
        var file = files[0];
        $('#preview').removeClass('hidden');
        $('#theFile').attr('src', window.URL.createObjectURL(file));
    }
});

$('#removeImage').on('click', function (e) {
    e.preventDefault();
    $('#obs_form_image').val('');
    $('#preview').addClass('hidden');
});


$('#btn-publish').click(function (e) {
    e.preventDefault();
    var $form = $('form[name="obs_form"]');
    var $recup = $('form').serializeArray();
    var $formdata = new FormData($form[0]);
    if (!checkOnlineStatus($formdata)) return false;
    var $data = (isLocalStorage === false) ? $formdata : getOfflineFormData();
    $.ajax
    ({
        url: $form.attr('action'),
        method: $form.attr('method'),
        contentType: false, // obligatoire pour de l'upload
        processData: false, // obligatoire pour de l'upload
        data: $data,
        statusCode: {
            201: function (msg) {
                $('#modal-load-desktop').html(msg);
                (isLocalStorage === false) ? $('#recherche').val($recup[0].value) : $('#recherche').val(localStorage.getItem('recherche'));
                $('#pac-input').val($('#obs_form_ville').val());
            },
            200: function () {
                window.location.replace(pathRedirect);
            }
        }
    });
});
