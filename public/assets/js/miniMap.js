var miniMap = L.map('minimapid').setView([49, 10.5], 3);

var minimapCursor = L.icon({
    iconUrl: '../public/assets/img/minimapCursor.png',
    iconSize:     [40, 20], // size of the icon
    iconAnchor:   [20, 10], // point of the icon which will correspond to marker's location
    popupAnchor:  [20, 0] // point from which the popup should open relative to the iconAnchor
});

var markerMiniMap = L.marker(map.getCenter(), {icon: minimapCursor}).addTo(miniMap);

if (typeof oreMapObj !== 'undefined') {
    oreMapObj.oreMap.forEach(ore => {
        //console.log(ore.x + ' ' + ore.y);
        //var marker = L.marker([ore.y, ore.x], {icon: minimapCursor}).addTo(map);
        var marker = L.circle([ore.y, ore.x], {
            color: 'blue',
            //fillColor: '#f03',
            fillOpacity: 1,
            radius: 1
        }).addTo(miniMap);
    });
}

miniMap.dragging.disable();
miniMap.touchZoom.disable();
miniMap.doubleClickZoom.disable();
miniMap.scrollWheelZoom.disable();
miniMap.boxZoom.disable();
miniMap.keyboard.disable();
//if (map.tap) map.tap.disable();

var lat, lng;
miniMap.addEventListener('mousemove', function(ev) {
   lat = ev.latlng.lat;
   lng = ev.latlng.lng;
});

miniMapClicked = false;
miniMap.addEventListener('mousemove', function(ev) {
    miniMap.addEventListener('mousedown', function(ev) {
        miniMapClicked = true;
    });
    miniMap.addEventListener('mouseup', function(ev) {
        miniMapClicked = false;
    });    
    if (miniMapClicked) {
        map.setView([lat, lng])
        markerMiniMap.setLatLng([lat, lng]); 
    }
});

miniMap.addEventListener('mousedown', function(ev) {
    map.setView([lat, lng])
    markerMiniMap.setLatLng([lat, lng]); 
});

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
}).addTo(miniMap);

