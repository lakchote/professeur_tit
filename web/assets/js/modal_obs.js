let isLocalStorage = false;
let urlCSV = '/assets/csv/taxrefv2.csv';
let csvOfflineData = [];
let fileReader = new FileReader();
let imageObs = undefined;

function initMap() {
    let input = (document.getElementById('pac-input'));
    let options = {
        componentRestrictions: {country: 'fr'}
    };
    let autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function () {
        let place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert(alert1);
            return;
        }

        let nbAdressComp = place.address_components.length;
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
    let latlng = {lat: parseFloat($('#obs_form_latitude').val()), lng: parseFloat($('#obs_form_longitude').val())};
    geocoder.geocode({'location': latlng}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results) {
                let laVille = results[0].address_components[2].short_name;
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
    let geocoder = new google.maps.Geocoder;
    geocodeLatLng(geocoder);
}

function dataURLtoFile(url, name, type) {
    return fetch(url)
        .then(response => response.arrayBuffer())
        .then(arrayBuffer => new File([arrayBuffer], name, {type: type}));
}

(function getCSVDataForOffLine()
{
    if(localStorage.getItem('taxref'))
    {
        csvOfflineData = JSON.parse(localStorage.getItem('taxref'));
        return false;
    }
    fetch(urlCSV)
        .then(response => response.text())
        .then(csvData =>
        {
            let arrayCsv = csvData.split(/\n/);
            for(let i = 1; i < arrayCsv.length; ++i)
            {
                let filterArrayEntries = arrayCsv[i].split(',');
                if(filterArrayEntries[10][0] === '"') filterArrayEntries[10] = filterArrayEntries[10].slice(1);
                csvOfflineData.push({label: filterArrayEntries[10], value: filterArrayEntries[10], nomLatin: filterArrayEntries[7]});
            }
            localStorage.setItem('taxref', JSON.stringify(csvOfflineData));
            csvOfflineData = JSON.parse(localStorage.getItem('taxref'));
        });
})();

(function checkLocalStorageData() {
    let imageStored = localStorage.getItem('obs_form[image]');
    if (!imageStored) return false;
    dataURLtoFile(imageStored, 'file.jpeg', 'image/jpeg').then(function (file) {
        imageObs = file;
    });
    let ville = localStorage.getItem('localisation');
    if (ville && !(localStorage.getItem('obs_form[longitude]')) && (getOnlineStatus())) {
        fetch('https://maps.googleapis.com/maps/api/geocode/json?address=' + ville + '&key=AIzaSyACO55kGEkd8YeNffAaErhE02wa_UigduQ')
            .then(response => response.json())
            .then(data =>
            {
                if((Math.sign(data.results[0].geometry.location.lng.toFixed(10)) === -1) || (Math.sign(data.results[0].geometry.location.lat.toFixed(11)) === -1))
                {
                    alert('Ville incorrect.');
                    return false;
                }
                localStorage.setItem('obs_form[longitude]', data.results[0].geometry.location.lng.toFixed(10));
                localStorage.setItem('obs_form[latitude]', data.results[0].geometry.location.lat.toFixed(11));
            });
    }
    localStorage.setItem('obs_form[ville]', ville);
    $('#pac-input').val(ville);
    $('#recherche').val(localStorage.getItem('recherche'));
    $('#obs_form_description').val(localStorage.getItem('obs_form[description]'));
    fetch(imageStored)
        .then(response => response.blob())
        .then(blob => {
            $('#preview').removeClass('hidden');
            $('#theFile').attr('src', URL.createObjectURL(blob));
        });
    isLocalStorage = true;
})();

function getOnlineStatus()
{
    return navigator.onLine;
}

function checkOnlineStatus(data) {
    if (!getOnlineStatus()) {
        for (let pair of data.entries()) {
            if (pair[1] instanceof File && pair[1].size > 0) {
                let key = pair[0];
                fileReader.readAsDataURL(pair[1]);
                fileReader.onload = function () {
                    localStorage.setItem(key, fileReader.result);
                }
            }
            if(!(pair[0] === 'obs_form[image]')) localStorage.setItem(pair[0], pair[1]);
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
    return formData;
}

$('#recherche').autocomplete({
    source: getOnlineStatus() ? pathListing : csvOfflineData,
    minLength: 3,
    create: function () {
        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
            return (getOnlineStatus()) ? $('<li><strong>' + item.value + '</strong> / <i>' + item.label + '</i></li>').appendTo(ul) : $('<li><strong>' + item.value + '</strong> / <i>' + item.nomLatin + '</i></li>').appendTo(ul);
        };
    },
    select: function (event, ui) {
        if((getOnlineStatus()))
        {
            $("#recherche").val(ui.item.value + ' / ' + ui.item.label);
            $("#obs_form_taxon").val(ui.item.label);
            return false;
        }
        else
        {
            $("#recherche").val(ui.item.value + ' / ' + ui.item.nomLatin);
            $("#obs_form_taxon").val(ui.item.nomLatin);
            return false;
        }
    },
});


$('#location').click(function () {
    getLocation();
});

$('#obs_form_image').on('change', function (e) {
    let files = $(this)[0].files;
    if (files.length > 0) {
        let file = files[0];
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
    let $form = $('form[name="obs_form"]');
    let recupRecherche = $('#recherche').val();
    let $formdata = new FormData($form[0]);
    if (!checkOnlineStatus($formdata)) return false;
    let $data = (isLocalStorage === false) ? $formdata : getOfflineFormData();
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
                $('#recherche').val(recupRecherche);
                $('#pac-input').val($('#obs_form_ville').val());
            },
            200: function () {
                window.location.replace(pathRedirect);
            }
        }
    });
});
