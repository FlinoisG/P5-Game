moveOrder = {

    move(origin, toSelect, params)
    {
        this.type = params[0];
        this.amount = params[1]; 
        this.toSelect = toSelect;
        this.orderMode = true;
        this.moveMode = true;
        this.origin = origin;
        this.validImg = "../public/assets/img/green_arrow.png";
        this.invalidImg = "../public/assets/img/red_cross.png";
        this.imgOffsetX = -53;
        this.imgOffsetY = -19;

        document.removeEventListener('mouseup', unSelect);

        var buildingImg = document.createElement('img');
        buildingImg.id = 'buildingImg';
        buildingImg.className = 'buildingImg';
        buildingImg.src = '../public/assets/img/base_neutral.png';
        buildingImg.style.opacity = 0.7;

        //if (type == "mine"){
        //    this.marker = '';
        //    Map.mainMap.map.addEventListener("mousemove", this.mine);
        //}
        
        document.body.appendChild(buildingImg);

        document.addEventListener("mousemove", this.checkMousePos);
        //Map.mainMap.map.addEventListener("mousemove", this.checkPosValidity);
        //Map.mainMap.map.addEventListener("click", this.eventOnClick);
        
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

    checkMousePos(e){
        buildingImg.style.left=e.pageX+moveOrder.imgOffsetX+"px";
        buildingImg.style.top=e.pageY+moveOrder.imgOffsetY+"px";
        if (typeof(e.target.className) == "string"){
            if (e.target.className.includes("leaflet-marker-icon")){
                buildingImg.src = moveOrder.validImg
                moveOrder.validated = true;
            } else {
                buildingImg.src = moveOrder.invalidImg;
                moveOrder.validated = false;
            }
        }
    },/*

    checkPosValidity(e){
        var y = Math.round(coordinatesToGrid(0, e.latlng.lat, "y"));
        if (y % 2 == 1){
            y++;  
        }
        var x = Math.round(coordinatesToGrid(e.latlng.lng, 0, "x"));
        if (x % 2 == 1){
            x++;  
        }
    },

    eventOnClick(e){
        console.log(moveOrder.validated);
        if (moveOrder.validated == true){
            pos = coordinatesToGrid(e.latlng.lng, e.latlng.lat);
            window.location.replace("?p=task.moveUnit&type="+moveOrder.type+"&startOrigin=" + moveOrder.origin + "&$target="+"&$amount="+moveOrder.amount+"&$isBuilding=");
            //moveUnit($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
        } else {
        }
    },*/

    cancel(){
        moveOrder.orderMode = false;
        moveOrder.moveMode = false;
        if (moveOrder.type == "mine"){
            Map.mainMap.map.removeEventListener("mousemove", moveOrder.mineField);
            if(typeof(Map.mainMap.map._layers[moveOrder.marker]) != 'undefined'){   
                Map.mainMap.map.removeLayer(Map.mainMap.map._layers[moveOrder.marker]);
            }
        }
        document.removeEventListener("mousemove", moveOrder.checkMousePos);
        //Map.mainMap.map.removeEventListener("mousemove", moveOrder.checkPosValidity);
        //Map.mainMap.map.removeEventListener("click", moveOrder.eventOnClick);
        buildingImg.parentNode.removeChild(buildingImg);
        document.addEventListener('mouseup', unSelect);
        panelInterface.select(moveOrder.toSelect);
        panelInterface.selectWorkerSlot(moveOrder.toSelect);
    },

}
