function build() {

    var buildingImg = document.createElement('img');
    buildingImg.id = 'buildingImg';
    buildingImg.className = 'buildingImg';
    buildingImg.src = '../public/assets/img/base_neutral.png';

    document.body.appendChild(buildingImg);

    document.addEventListener("mousemove", function(e){
        buildingImg.style.left=e.pageX+10+"px";
        buildingImg.style.top=e.pageY+10+"px";

    });
    Map.mainMap.map.addEventListener("mousemove", function(e){
        var validated = true;
        var y = Math.round(coordinatesToGrid(0, e.latlng.lat, "y"));
        if (y % 2 == 1){
             y++;  
        }
        var x = Math.round(coordinatesToGrid(e.latlng.lng, 0, "x"));
        if (x % 2 == 1){
            x++;  
       }
        if (typeof waterMapObj[y] !== 'undefined') {
            if (typeof waterMapObj[y][x] !== 'undefined'){
                validated = "eau";
            } else {

            }
        }
        objectMapObj.forEach(object => {
            var dist = gridDistance([object.y, object.x], [y, x]);
            if (dist < 4) {
                validated = "distance";
            }
        });
        if (validated == true){
            buildingImg.src = '../public/assets/img/base_valid.png'; 
        } else {
            buildingImg.src = '../public/assets/img/base_invalid.png';
        }
    });

}
