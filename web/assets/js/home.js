/*function initMap()
{
    var map = document.querySelectorAll('.map');
    for (var i = 0; i < map.length; i++) {
        var lng = parseFloat(map[i].getAttribute('data-lng'));
        var lat = parseFloat(map[i].getAttribute('data-lat'));
        var latLng = {lat: lat, lng: lng};
        var loadMap = new google.maps.Map(map[i],
            {
                center: latLng,
                zoom: 15
            });
        var setMarker = new google.maps.Marker(
            {
                position: latLng,
                map: loadMap
            });
    }
}*/
$('#obs-load-desktop').click(function (e)
{
    if ($(window).width() <= 768) {
        $('#modal-load').removeClass('modal-lg');
    }
    e.preventDefault();
    var url = this.getAttribute('data-url');
    $.ajax({
        url: url,
        method: 'GET'
    }).done(function (msg) {
        $('#modal-load-desktop').html(msg);
    });
});
