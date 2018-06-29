var map = L.map('mapid', {
    minZoom: 0,
    maxZoom: 15
}).setView([48.488, 16.994], 8);

var marker = L.marker([0, 0]).addTo(map);

var marker = L.marker([33, -12]).addTo(map);


var pos = {};
pos.lat = 0;
pos.lng = 0;
map.addEventListener('mousemove', function(ev) {
    pos = map.getCenter();
    console.log(pos);
    markerMiniMap.setLatLng(pos); 
});
//var marker = L.marker([pos.lat, pos.lng]).addTo(miniMap);

//map.dragging.disable();
map.touchZoom.disable();
map.doubleClickZoom.disable();
//map.scrollWheelZoom.disable();
map.boxZoom.disable();
//map.keyboard.disable();
//if (map.tap) map.tap.disable();


L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox.streets'
}).addTo(map);
