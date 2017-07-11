var marker = {
    markers: [],
    addMarker: function(latLng, map, options, callbacks) {
        if (typeof options === 'undefined') options = {};

        var m = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: options.isDraggable ? true : false,
            title: options.name ? options.name : "Place event"
        });

        marker.markers.push(m);
        for(k in callbacks) {
            callbacks[k](m);
        }
    },
    fillFormFields: function(m){
        var pos = m.getPosition();
        $('input[name="appbundle_event[latitude]"]').val(pos.lat);
        $('input[name="appbundle_event[longitude]"]').val(pos.lng);
    },
    addOnDrag: function(m, callbacks){
        m.addListener('dragend', function() {
            for(k in callbacks) {
                callbacks[k](m);
            }
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
    initLat: 51.8986985,
    initLng: 19.092234,
    initZoom: 6,
    mapId: "map",
    showEvents: function(){
        var mapCanvas = document.getElementById(map.mapId);
        var mapOptions = {
            center: new google.maps.LatLng(map.initLat, map.initLng),
            zoom: map.initZoom,
            disableDefaultUI: true,
            zoomControl: true
        };
        map.gmap = new google.maps.Map(mapCanvas, mapOptions);

        map.gmap.addListener('click', function(e) {
            marker.deleteMarkers();
            marker.addMarker(e.latLng, map.gmap, {isDraggable: 1}, [
                function(m){
                    map.placeEvents(m.getPosition(), 5);
                },
                function(m){
                    marker.addOnDrag(m, [
                        function(m){
                            map.placeEvents(m.getPosition(), 5);
                        }
                    ]);
                }
            ]);
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
    event: function(){
        var mapCanvas = document.getElementById(map.mapId);
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
        var mapCanvas = document.getElementById(map.mapId);
        var mapOptions = {
            center: new google.maps.LatLng(map.initLat, map.initLng),
            zoom: map.initZoom,
            disableDefaultUI: true,
            zoomControl: true
        };
        map.gmap = new google.maps.Map(mapCanvas, mapOptions);

        map.gmap.addListener('click', function(e) {
            marker.deleteMarkers();
            marker.addMarker(e.latLng, map.gmap, {isDraggable: 1}, [
                function(m){
                    marker.fillFormFields(m);
                },
                function(m){
                    marker.addOnDrag(m, [
                        function(m){
                            marker.fillFormFields(m);
                        }
                    ]);
                }
            ]);
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
    geoLocation: function(address, withEvents){
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            map.gmap.setCenter(results[0].geometry.location);
            map.gmap.fitBounds(results[0].geometry.viewport);
            marker.deleteMarkers();
            marker.addMarker(results[0].geometry.location, map.gmap, {isDraggable: 1}, [
                function(m){
                    marker.fillFormFields(m);
                },
                function(m){
                    marker.addOnDrag(m, [
                        function(m){
                            marker.fillFormFields(m);
                        }
                    ]);
                }
            ]);
            if (withEvents) {
                map.placeEvents(results[0].geometry.location, 5);
            }
          } else {
            console.log('Geocode was not successful for the following reason: ' + status);
          }
        });
    },
    placeEvents: function(latLng, radius){
        $.ajax({
            url: 'nearbyevents',
            method: "POST",
            data: 'lat=' + latLng.lat() + '&lng=' + latLng.lng() + '&r=' + radius,
            success: function(data){
                map.handleEvents(data.events);
            }
        });
    },
    handleEvents: function(events){
        for(ev in events){
            marker.addMarker({lat: events[ev].lat*1, lng: events[ev].lng*1}, map.gmap, {
                name: events[ev].name + ', ' + events[ev].address + ', ' + events[ev].city
            });
        }
    }
};