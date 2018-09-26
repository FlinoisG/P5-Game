function miningAnimation(minePos, nodePos, map) {
    console.log(minePos);
    console.log(nodePos);
    L.polyline([minePos, nodePos], {color: 'red'}).addTo(map);
    var x = setInterval(function() {
        var marker2 = L.Marker.movingMarker([nodePos, minePos, nodePos],
        [1000, 1000], {autostart: true}).addTo(map);

        marker2.on('end', function() {
            map.removeLayer(marker2);
        });
    }, 3500);

}
