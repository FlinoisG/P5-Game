build = {

    build(type, baseId) 
    {

        document.removeEventListener('mouseup', unSelect);

        this.baseId = baseId
        if (type == "mine"){
            this.validImg = "../public/assets/img/mine_valid.png";
            this.invalidImg = "../public/assets/img/mine_invalid.png";
            this.imgOffsetX = 10;
            this.imgOffsetY = 10;
        } else {
            this.validImg = "../public/assets/img/base_valid.png";
            this.invalidImg = "../public/assets/img/base_invalid.png";
            this.imgOffsetX = -15;
            this.imgOffsetY = -17;
        }

        var buildingImg = document.createElement('img');
        buildingImg.id = 'buildingImg';
        buildingImg.className = 'buildingImg';
        buildingImg.src = '../public/assets/img/base_neutral.png';

        document.body.appendChild(buildingImg);
        var validated = true;

        document.addEventListener("mousemove", this.checkMousePos);
        Map.mainMap.map.addEventListener("mousemove", this.checkPosValidity);
        Map.mainMap.map.addEventListener("click", this.eventOnClick);

        if (type == "base"){
            var panelSubIcon = document.createElement('img');
            panelSubIcon.className = 'panelSubIcon';
            panelSubIcon.src = "../public/assets/img/unit_slot_red_cross.png";

            panelSubIcon.addEventListener('click', this.cancelBuild);

            var panelSubText = document.createElement('span');
            panelSubText.className = 'panelSubText';
            panelSubText.innerHTML = "Annuler";

            document.getElementById('optionInnerbase').innerHTML = "";
            document.getElementById('optionInnerbase').appendChild(panelSubIcon);
            document.getElementById('optionInnerbase').appendChild(panelSubText);
        }

    },

    checkMousePos(e){
        if (e.target.id == "mapid"){
            buildingImg.style.left=e.pageX+build.imgOffsetX+"px";
            buildingImg.style.top=e.pageY+build.imgOffsetY+"px";
            buildingImg.style.opacity = 0.5;
        } else {
            buildingImg.style.opacity = 0;
        }
    },

    checkPosValidity(e){
            
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
                validated = true;
            }
        }
        objectMapObj.forEach(object => {
            var dist = gridDistance([object.y, object.x], [y, x]);
            if (dist < 4) {
                validated = "distance";
            }
        });
        if (validated == true){
            buildingImg.src = build.validImg; 
        } else {
            buildingImg.src = build.invalidImg;
        }

    },

    eventOnClick(e){
        if (validated == true){
            pos = coordinatesToGrid(e.latlng.lng, e.latlng.lat);
            window.location.replace("?p=entity.buy&type="+$type+"&baseId=" + build.baseId + "&pos=[" + pos.x + "," + pos.y + "]");
        } else {
        }
    },

    cancelBuild(){
        panelInterface.selectWorker(build.baseId);
        document.removeEventListener("mousemove", build.checkMousePos);
        Map.mainMap.map.removeEventListener("mousemove", build.checkPosValidity);
        Map.mainMap.map.removeEventListener("click", build.eventOnClick);
        buildingImg.parentNode.removeChild(buildingImg);
        document.addEventListener('mouseup', unSelect);
    }

}
