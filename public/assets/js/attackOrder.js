attackOrder = {

    attack(origin, toSelect, params)
    {
        //this.type = params[0];
        this.amount = params; 
        this.toSelect = toSelect;
        this.orderMode = true;
        this.attackMode = true;
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

    checkMousePos(e){
        buildingImg.style.left=e.pageX+attackOrder.imgOffsetX+"px";
        buildingImg.style.top=e.pageY+attackOrder.imgOffsetY+"px";
        if (typeof(e.target.className) == "string"){
            if (e.target.className.includes("leaflet-marker-icon")){
                buildingImg.src = attackOrder.validImg
                attackOrder.validated = true;
            } else {
                buildingImg.src = attackOrder.invalidImg;
                attackOrder.validated = false;
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
*/
    //eventOnClick(e){
        //console.log(attackOrder.validated);
        //if (attackOrder.validated == true){
        //    pos = coordinatesToGrid(e.latlng.lng, e.latlng.lat);
        //    window.location.replace("?p=task.attackUnit&type="+attackOrder.type+"&startOrigin=" + attackOrder.origin + "&$target="+"&$amount="+attackOrder.amount+"&$isBuilding=");
        //    //attackUnit($type=null, $startOrigin=null, $target=null, $amount=1, $isBuilding=false)
        //} else {
        //}
    //},

    cancel(){
        attackOrder.orderMode = false;
        attackOrder.attackMode = false;
        if (attackOrder.type == "mine"){
            Map.mainMap.map.removeEventListener("mousemove", attackOrder.mineField);
            if(typeof(Map.mainMap.map._layers[attackOrder.marker]) != 'undefined'){   
                Map.mainMap.map.removeLayer(Map.mainMap.map._layers[attackOrder.marker]);
            }
        }
        document.removeEventListener("mousemove", attackOrder.checkMousePos);
        //Map.mainMap.map.removeEventListener("mousemove", attackOrder.checkPosValidity);
        //Map.mainMap.map.removeEventListener("click", attackOrder.eventOnClick);
        buildingImg.parentNode.removeChild(buildingImg);
        document.addEventListener('mouseup', unSelect);
        panelInterface.select(attackOrder.toSelect);
        panelInterface.selectWorkerSlot(attackOrder.toSelect);
    },

}
