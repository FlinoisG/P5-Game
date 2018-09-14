buildOrder = {

    build(type, origin, toSelect)
    {
        this.toSelect = toSelect;
        this.orderMode = true;
        document.removeEventListener('mouseup', unSelect);

        this.origin = origin
        this.type = type
        if (type == "mine"){
            this.validImg = "../public/assets/img/mine_valid.png";
            this.invalidImg = "../public/assets/img/mine_invalid.png";
            this.imgOffsetX = -19;
            this.imgOffsetY = -22;
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

        if (type == "mine"){
            this.marker = '';
            Map.mainMap.map.addEventListener("mousemove", this.mineField);
        }
        
        document.body.appendChild(buildingImg);

        document.addEventListener("mousemove", this.checkMousePos);
        Map.mainMap.map.addEventListener("mousemove", this.checkPosValidity);
        Map.mainMap.map.addEventListener("click", this.eventOnClick);
        
        var panelSub = document.createElement('div');
        panelSub.id = 'panelSub';
        panelSub.className = 'panelSubOption panelSubbase';

        var optionInner = document.createElement('div');
        optionInner.id = 'optionInner';
        optionInner.className = 'optionInner';

        var panelSubIcon = document.createElement('img');
        panelSubIcon.className = 'panelSubIcon';
        panelSubIcon.src = "../public/assets/img/unit_slot_red_cross.png";

        panelSubIcon.addEventListener('click', this.cancel);

        var panelSubText = document.createElement('span');
        panelSubText.className = 'panelSubText';
        panelSubText.innerHTML = "Annuler";

        optionInner.appendChild(panelSubIcon);
        optionInner.appendChild(panelSubText);
        panelSub.appendChild(optionInner);

        document.getElementById('subPanelMain').innerHTML = "";
        document.getElementById('subPanelMain').appendChild(panelSub);

    },
    
    mineField(e){
        if (buildOrder.marker != ''){
            Map.mainMap.map.removeLayer(Map.mainMap.map._layers[buildOrder.marker]);
        }
        buildOrder.mineRangeMarker = L.circle([e.latlng.lat, e.latlng.lng], {
            color: 'green',
            radius: 50000,
            clickable: false,
            interactive: false,
        }).addTo(Map.mainMap.map);
        buildOrder.marker = buildOrder.mineRangeMarker._leaflet_id;
    },

    checkMousePos(e){
        if (e.target.id == "mapid"){
            buildingImg.style.left=e.pageX+buildOrder.imgOffsetX+"px";
            buildingImg.style.top=e.pageY+buildOrder.imgOffsetY+"px";
            buildingImg.style.opacity = 0.5;
            //if (buildOrder.type == "mine"){
                //buildOrder.setMineRadius();
            //}
        } else {
            buildingImg.style.opacity = 0;
            //if (buildOrder.type == "mine"){
                //buildOrder.mineRangeMarker.style.opacity = 0;
            //}
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
            if (dist < 3) {
                validated = "distance";
            }
        });
        if (validated == true){
            buildingImg.src = buildOrder.validImg; 
        } else {
            buildingImg.src = buildOrder.invalidImg;
        }

    },

    eventOnClick(e){
        if (validated == true){
            pos = coordinatesToGrid(e.latlng.lng, e.latlng.lat);
            if (buildOrder.origin == 'none,none'){
                window.location.replace("?p=task.newUserBase&type="+buildOrder.type+"&pos=[" + pos.x + "," + pos.y + "]");
            } else {
                window.location.replace("?p=task.buy&type="+buildOrder.type+"&origin=" + buildOrder.origin + "&pos=[" + pos.x + "," + pos.y + "]");
            }
        } else {
        }
    },

    cancel(){
        buildOrder.orderMode = false;
        if (buildOrder.type == "mine"){
            Map.mainMap.map.removeEventListener("mousemove", buildOrder.mineField);
            if(typeof(Map.mainMap.map._layers[buildOrder.marker]) != 'undefined'){   
                Map.mainMap.map.removeLayer(Map.mainMap.map._layers[buildOrder.marker]);
            }
        }
        document.removeEventListener("mousemove", buildOrder.checkMousePos);
        Map.mainMap.map.removeEventListener("mousemove", buildOrder.checkPosValidity);
        Map.mainMap.map.removeEventListener("click", buildOrder.eventOnClick);
        buildingImg.parentNode.removeChild(buildingImg);
        document.addEventListener('mouseup', unSelect);
        panelInterface.select(buildOrder.toSelect);
        panelInterface.selectWorkerSlot(buildOrder.toSelect);
    },

}
