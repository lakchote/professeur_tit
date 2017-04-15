/**
 * Created by Benoit on 15/04/2017.
 */

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
        return false;
    },
});

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
    });
    var input = (document.getElementById('lieux'));
    var options = {
        componentRestrictions: {country: 'fr'}
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            //window.alert(alert1);
            return;
        }
        map.setCenter(place.geometry.location);
        map.setZoom(17);

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

}

console.log(lang);

    $("#date").datepicker({
        language: lang,
        format: "dd/mm/yyyy",
        startDate: '01/01/1900',
        endDate: 'today',
        //startView: 3,
      //  defaultViewDate: { year: 1977, month: 04, day: 25 },
        autoclose: true,
    });

