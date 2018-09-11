attackOrder = {

    attack(origin, toSelect, params)
    {
        console.log(params);
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
        
        document.body.appendChild(buildingImg);

        document.addEventListener("mousemove", this.checkMousePos);
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
    },

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
        buildingImg.parentNode.removeChild(buildingImg);
        document.addEventListener('mouseup', unSelect);
        panelInterface.select(attackOrder.toSelect);
        panelInterface.selectWorkerSlot(attackOrder.toSelect);
    },

}
