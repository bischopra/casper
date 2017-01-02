var marker = {
    markers: [],
    addMarker: function(latLng, map) {
        var m = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: "Place event"
        });
        marker.markers.push(m);
        $('input[name="appbundle_event[latitude]"]').val(latLng.lat);
        $('input[name="appbundle_event[longitude]"]').val(latLng.lng);
        m.addListener('dragend', function() {
            var pos = m.getPosition();
            $('input[name="appbundle_event[latitude]"]').val(pos.lat);
            $('input[name="appbundle_event[longitude]"]').val(pos.lng);
       });
    },
    setMapOnAll: function(map) {
        for (var i = 0; i < marker.markers.length; i++) {
            marker.markers[i].setMap(map);
        }
    },
    clearMarkers: function() {
        marker.setMapOnAll(null);
    },
    showMarkers: function() {
        marker.setMapOnAll(map);
    },
    deleteMarkers: function() {
        marker.clearMarkers();
        marker.markers = [];
    }
};

var map = {
    gmap: '',
    event: function(){
        var mapCanvas = document.getElementById("map");
        var mapOptions = {
            center: new google.maps.LatLng($(mapCanvas).attr('data-lat'),$(mapCanvas).attr('data-lng')),
            zoom: 14,
            disableDefaultUI: true,
            zoomControl: true
        };        
        map.gmap = new google.maps.Map(mapCanvas, mapOptions);
        var pos = {
          lat: $(mapCanvas).attr('data-lat')*1,
          lng: $(mapCanvas).attr('data-lng')*1
        };
        
        var markerTitle = $('.details .name').text().trim();
        var m = new google.maps.Marker({
            position: pos,
            map: map.gmap,
            title: markerTitle
        });
        m.setMap(map.gmap);
    },
    init: function(){
        var mapCanvas = document.getElementById("map");
        var mapOptions = {
            center: new google.maps.LatLng(51.8986985,19.092234),
            zoom: 6,
            disableDefaultUI: true,
            zoomControl: true
        };
        map.gmap = new google.maps.Map(mapCanvas, mapOptions);
        
        var geocoder = new google.maps.Geocoder;
        geocoder.geocode({'address': 'Olsztyn'}, function (result, status) {
            if (status === 'OK') {
                console.log(result);
            } else {
                console.log('Error: ' + status);
            }
        });

        map.gmap.addListener('click', function(e) {
            marker.deleteMarkers();
            marker.addMarker(e.latLng, map.gmap);
        });

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            map.gmap.setCenter(pos);
            map.gmap.setZoom(12);
          });
        }
    },
    geoLocation: function(address){
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            map.gmap.setCenter(results[0].geometry.location);
            map.gmap.fitBounds(results[0].geometry.viewport);
            marker.deleteMarkers();
            marker.addMarker(results[0].geometry.location, map.gmap);
          } else {
            console.log('Geocode was not successful for the following reason: ' + status);
          }
        });
    }
};

$(function(){
    $('input[name="appbundle_event[city]"],input[name="appbundle_event[address]"]').change(function(){
        var address = $('input[name="appbundle_event[city]"]').val() + ', "' + $('input[name="appbundle_event[address]"]').val() + '"';
        map.geoLocation(address);
    });
});