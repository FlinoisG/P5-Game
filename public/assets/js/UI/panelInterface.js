panelInterface = {
  
    select(toSelect) 
    {
        //console.log(toSelect);
        if (buildOrder.orderMode){
            buildOrder.cancel();
        }
        if (moveOrder.orderMode){
            moveOrder.cancel();
        }
        switch (toSelect.type){
            case "base":
                this.selectBuilding(toSelect);
                break;
            case "main":
                this.selectBuilding(toSelect);
                break;
            case "mine":
                this.selectBuilding(toSelect);
                break;
            case "baseInConst":
                this.selectConstruction(toSelect);
                break;
            case "mineInConst":
                this.selectConstruction(toSelect);
                break;
            default:
                this.unSelect();
                console.log('Selection inconnue');
                console.log(toSelect);
        }
        
    },

    unSelect() 
    {
        document.getElementById('panelInterface').innerHTML = "";        
    },


    selectBuilding(toSelect) 
    {
        //console.log(toSelect);        
        if (toSelect.relation == "owned") {
            this.tab = "workerTab";

            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " " + toSelect.type;

            var healthBarDiv = document.createElement('div');
            healthBarDiv.className = "progress healthBar";

            var health = (toSelect.HP / toSelect.maxHP);
            var healthBarPercent = health * 100;

            var healthGreen = health * 200;
            var healthRed = 200 - (health * 200);
            var healthBarColor = "rgb("+ healthRed +","+ healthGreen + ",0)";

            var healthText = document.createElement('p');
            healthText.innerHTML = "HP : " + toSelect.HP + "/<span id=\"maxHP\">" + toSelect.maxHP + "</span>";
            healthText.className = "healthText";

            var healthBar = document.createElement('div');
            healthBar.className = "healthBarProgress progress-bar progress-bar-striped progress-bar-animated";
            healthBar.setAttribute('role', "progressbar");
            healthBar.setAttribute('aria-valuenow', healthBarPercent);
            healthBar.setAttribute('aria-valuemin', 0);
            healthBar.setAttribute('aria-valuemax', toSelect.maxHP);
            healthBar.style = "width: " + healthBarPercent + "%; background-color: " + healthBarColor + " !important;";

            var slotTabs = document.createElement('div');
            slotTabs.id = "slotTabs"
            slotTabs.className = "slotTabs"

            var workerTabButton = document.createElement('div');
            workerTabButton.className = "workerTab slotTab disabledLink";
            workerTabButton.textContent = "workers";
            
            var soldierTabButton = document.createElement('div');
            soldierTabButton.className = "soldierTab slotTab link";
            soldierTabButton.textContent = "soldiers";

            var panelSlots = document.createElement('div');
            panelSlots.id = "panelSlots";
            panelSlots.className = "panelSlots";

            healthBarDiv.appendChild(healthBar);
            healthBarDiv.appendChild(healthText);

            slotTabs.appendChild(workerTabButton);
            slotTabs.appendChild(soldierTabButton);

            var workerSpace = toSelect.workerSpace +1;

            var freeSlots = workerSpace;

            if (toSelect.content.length != 0) {                
                if (toSelect.content.workers !== undefined && toSelect.content.workers != 0){
                    for (i = 0; i < toSelect.content.workers; i++) {
                        var container = document.createElement('div');
                        container.className = 'unitSlotContainer';
                        var img = document.createElement('img');                        
                        img.className = 'unitSlot unitSlotWorker';
                        img.src = '../public/assets/img/unit_slot_worker_finished.png';
                        container.appendChild(img);
                        panelSlots.appendChild(container);
                        container.addEventListener("click", (ev) => {
                            if (buildOrder.orderMode){
                                buildOrder.cancel();
                            }
                            if (moveOrder.orderMode){
                                moveOrder.cancel();
                            }
                            var slots = document.getElementsByClassName('unitSlot')
                            for (i = 0; i < slots.length; i++) {
                                slots[i].style.border = "1px solid #292929";
                                slots[i].className = slots[i].className.replace(' slotSelected','');
                            }
                            ev.target.className += " slotSelected";
                            this.selectWorkerSlot(toSelect);
                        });
                        freeSlots--;
                    }
                }
                if (toSelect.content.workersInConst !== undefined && toSelect.content.workersInConst != 0){
                    toSelect.content.workersInConst.forEach(worker => {
                        var timestamp = Math.floor(Date.now() / 1000);
                        var time = worker - timestamp;
                        if (time < 0){
                            time = 0;
                        }
                        var displayTime = timestampToTime(time);
                        var container = document.createElement('div');
                        container.className = 'unitSlotContainer';                        
                        var img = document.createElement('img');
                        img.className = 'unitSlot unitSlotworkerInConst';
                        img.src = '../public/assets/img/unit_slot_worker_ip.png';
                        var timer = document.createElement('div');
                        timer.className = 'panelUnitTimer';
                        timer.textContent = displayTime;
                        container.appendChild(img);
                        container.appendChild(timer);
                        panelSlots.appendChild(container);
                        countDown(timer, worker);
                        freeSlots--;
                    });
                }
            }
            for (i = 0; i < freeSlots; i++) {
                var container = document.createElement('div');
                container.className = 'unitSlotContainer';
                var img = document.createElement('img');             
                img.className = 'unitSlot emptySlot';
                img.src = '../public/assets/img/unit_slot_empty.png';
                container.appendChild(img);
                panelSlots.appendChild(container);        
            }  

            
            var panelSub = document.createElement('div');
            panelSub.id = "panelSub";
            panelSub.className = "panelSub";            

            document.getElementById('panelInterface').innerHTML = "";
            document.getElementById('panelInterface').appendChild(title);
            document.getElementById('panelInterface').appendChild(healthBarDiv);
            document.getElementById('panelInterface').appendChild(slotTabs);
            document.getElementById('panelInterface').appendChild(panelSlots); 
            document.getElementById('panelInterface').appendChild(panelSub);

            switch(toSelect.type){
                case 'base':
                    options = [
                        new WorkerEntity,
                        new WorkerSpaceEntity
                    ]
                    break;
                case 'mine':
                    options = [
                        new WorkerSpaceEntity
                    ]
                    break;
            }

            document.getElementById('panelSub').appendChild(this.buildSubPanel(options, toSelect));

            soldierTabButton.addEventListener("click", () => {
                if (buildOrder.orderMode){
                    buildOrder.cancel();
                }
                if (moveOrder.orderMode){
                    moveOrder.cancel();
                }
                this.soldierTab(toSelect);
            });

            if (toSelect.content.workerSpaceInConst !== undefined && toSelect.content.workersInConst != 0){
                var workerSpaceCooldown = document.createElement('div');
                workerSpaceCooldown.className = "workerSpaceCooldown subPanelCooldown";
                workerSpaceCooldown.textContent = "Upgrade en cours...";

                var timestamp = Math.floor(Date.now() / 1000);
                var time = toSelect.content.workerSpaceInConst - timestamp;
                if (time < 0){
                    time = 0;
                }
                var displayTime = timestampToTime(time);
                var timer = document.createElement('div');
                timer.className = 'panelSubTimer';
                timer.textContent = displayTime;

                workerSpaceCooldown.appendChild(timer);
                document.getElementById('optionInnerworkerSpace').appendChild(workerSpaceCooldown);
                countDown(timer, toSelect.content.workerSpaceInConst);
            }
        } else {
            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " " + toSelect.type + " de " + toSelect.ownerName;
            document.getElementById('panelInterface').innerHTML = "";
            document.getElementById('panelInterface').appendChild(title);
        }     
    },

    soldierTab(toSelect) 
    {
        this.tab = "soldierTab";
        document.getElementById('slotTabs').innerHTML = "";
        document.getElementById('panelSlots').innerHTML = "";

        var workerTabButton = document.createElement('div');
        workerTabButton.className = "soldierTab slotTab link";
        workerTabButton.textContent = "workers";
        
        var soldierTabButton = document.createElement('div');
        soldierTabButton.className = "soldierTab slotTab disabledLink";
        soldierTabButton.textContent = "soldiers";        

        var freeSlots = toSelect.soldierSpace +1;

        if (toSelect.content.length != 0) { 

            if (toSelect.content.soldiers !== undefined && toSelect.content.soldiers != 0){
                for (i = 0; i < toSelect.content.soldiers; i++) {
                    var container = document.createElement('div');
                    container.className = 'unitSlotContainer';
                    var img = document.createElement('img');                        
                    img.className = 'unitSlot unitSlotSoldier';
                    img.src = '../public/assets/img/unit_slot_soldier_finished.png';
                    container.appendChild(img);
                    document.getElementById('panelSlots').appendChild(container);

                    container.addEventListener("click", (ev) => {
                        var slots = document.getElementsByClassName('unitSlot')
                        for (i = 0; i < slots.length; i++) {
                            slots[i].style.border = "1px solid #292929";
                        }
                        ev.target.style.border = "1px solid #90b2d6";
                        this.selectSoldier(toSelect);
                    });

                    freeSlots--;
                }
            }

            if (toSelect.content.soldiersInConst !== undefined && toSelect.content.soldiersInConst != 0){
                toSelect.content.soldiersInConst.forEach(soldier => {
                    var timestamp = Math.floor(Date.now() / 1000);
                    var time = soldier - timestamp;
                    if (time < 0){
                        time = 0;
                    }
                    var displayTime = timestampToTime(time);
                    var container = document.createElement('div');
                    container.className = 'unitSlotContainer';                        
                    var img = document.createElement('img');
                    img.className = 'unitSlot unitSlotSoldierInConst';
                    img.src = '../public/assets/img/unit_slot_soldier_ip.png';
                    var timer = document.createElement('div');
                    timer.className = 'panelUnitTimer';
                    timer.textContent = displayTime;
                    container.appendChild(img);
                    container.appendChild(timer);
                    document.getElementById('panelSlots').appendChild(container);
                    countDown(timer, soldier);
                    freeSlots--;
                });
            }

        }

        for (i = 0; i < freeSlots; i++) {
            var container = document.createElement('div');
            container.className = 'unitSlotContainer';
            var img = document.createElement('img');                
            img.className = 'unitSlot emptySlot';
            img.src = '../public/assets/img/unit_slot_empty.png';
            container.appendChild(img);
            document.getElementById('panelSlots').appendChild(container);        
        }  
        
        document.getElementById('slotTabs').appendChild(workerTabButton);
        document.getElementById('slotTabs').appendChild(soldierTabButton);

        switch(toSelect.type){
            case 'base':
                soldierOptions = [
                    new SoldierEntity,
                    new SoldierSpaceEntity
                ]
                break;
            case 'mine':
                soldierOptions = [
                    new SoldierSpaceEntity
                ]
                break;
        }
        

        document.getElementById('panelSub').innerHTML = "";
        document.getElementById('panelSub').appendChild(this.buildSubPanel(soldierOptions, toSelect));

        workerTabButton.addEventListener("click", () => {
            if (buildOrder.orderMode){
                buildOrder.cancel();
            }
            if (moveOrder.orderMode){
                moveOrder.cancel();
            }
            this.selectBuilding(toSelect);
        });

        if (toSelect.content.soldierSpaceInConst !== undefined && toSelect.content.soldierInConst != 0){

            var soldierSpaceCooldown = document.createElement('div');
            soldierSpaceCooldown.id = "soldierSpaceCooldown";
            soldierSpaceCooldown.className = "soldierSpaceCooldown subPanelCooldown";
            soldierSpaceCooldown.textContent = "Upgrade en cours...";

            var timestamp = Math.floor(Date.now() / 1000);
            var time = toSelect.content.soldierSpaceInConst - timestamp;
            if (time < 0){
                time = 0;
            }
            var displayTime = timestampToTime(time);
            var timer = document.createElement('div');
            timer.className = 'panelSubTimer';
            timer.textContent = displayTime;

            soldierSpaceCooldown.appendChild(timer);
            document.getElementById('optionInnersoldierSpace').appendChild(soldierSpaceCooldown);
            countDown(timer, toSelect.content.soldierSpaceInConst);
        }
    },

    selectConstruction(toSelect)
    {
        var now = Math.floor(Date.now() / 1000);
        var buildTime = toSelect.time - toSelect.start;
        var timeLeft = toSelect.time - now;
        var displayTime = timestampToTime(timeLeft);

        var displayPercent = (Math.floor((timeLeft / buildTime) * 100));
        if (displayPercent < 0) displayPercent = 100;

        var title = document.createElement('h4');
        title.textContent = toSelect.type + " de " + toSelect.ownerName;
        
        var pTime = document.createElement('p');
        pTime.textContent = displayTime; 

        var pPercent = document.createElement('p');
        pPercent.textContent = (displayPercent * -1 + 100) + "%"; 

        document.getElementById('panelInterface').innerHTML = "";
        document.getElementById('panelInterface').appendChild(title);
        document.getElementById('panelInterface').appendChild(pTime);
        document.getElementById('panelInterface').appendChild(pPercent);

        countDown(pTime, toSelect.time);
    },

    selectWorkerSlot(toSelect) {
        document.getElementById('panelSub').innerHTML = "";
        
        workerOptions = [
            new MoveEntity, 
            new BaseEntity, 
            new MineEntity
        ]

        document.getElementById('panelSub').appendChild(this.buildSubPanel(workerOptions, toSelect));
    },

    selectSoldier(toSelect) {
        document.getElementById('panelSub').innerHTML = "";

        workerOptions = [
            new MoveEntity,
            new AttackEntity
        ]

        document.getElementById('panelSub').appendChild(this.buildSubPanel(workerOptions, toSelect));
    },

    buildSubPanel(options, toSelect)
    {
        var subPanelMain = document.createElement('div');
        subPanelMain.id = "subPanelMain";
        options.forEach(option => {

            var panelSubOption = document.createElement('div');
            panelSubOption.id = "panelSub"+option.type;
            panelSubOption.className = "panelSubOption " + "panelSub" + option.type;

            var SubOptionIcon = document.createElement('img');
            SubOptionIcon.className = 'panelSubIcon';
            SubOptionIcon.src = '../public/assets/img/' + option.imgName + '.png';

            SubOptionIcon.addEventListener("click", function () {
                if (option.type == "move"){
                    var type = panelInterface.tab.replace('Tab','');;
                    var amount = document.getElementById('numberSelectorNumber').textContent;
                    option.subPanelAction(toSelect.type+","+toSelect.id, toSelect, [type, amount]);
                } else if (option.type == "attack"){
                    var type = panelInterface.tab.replace('Tab','');;
                    var amount = document.getElementById('numberSelectorNumber').textContent;
                    option.subPanelAction(toSelect.type+","+toSelect.id, toSelect, amount);
                } else {
                    option.subPanelAction(toSelect.type+","+toSelect.id, toSelect);
                }
            });

            var SubOptionText = document.createElement('div');
            SubOptionText.className = 'panelSubText';
            if (option.textContent == "regular"){
                SubOptionText.innerHTML = "Acheter " + option.type + "<br>Cout: <span id=\"panelText" + option.type + "Cost\">" + option.cost + "</span>metal, <span id=\"panelText" + option.type + "BuildTime\">"+option.buildTime+"</span>mn";
            } else if (option.textContent == "numberSelector"){
                if (this.tab == "workerTab"){
                    numberSelector(SubOptionText, toSelect.content.workers, option.type);
                } else {
                    numberSelector(SubOptionText, toSelect.content.soldiers, option.type);
                }
            } else {
                SubOptionText.innerHTML = option.textContent;
            }

            if (typeof option.cost !== 'undefined'){
                if (userMetal < option.cost){

                    SubOptionText.innerHTML = "Acheter " + option.type + "<br>Cout: <span style=\"color:#FF0000;\"><span id=\"panelText" + option.type + "Cost\">"+option.cost+"</span>metal</span>, <span id=\"panelText" + option.type + "BuildTime\">"+option.buildTime+"</span>mn";
                    var optionDisabled = document.createElement('div');
                    optionDisabled.className = "workerSpaceCooldown subPanelDisabled";

                }
            }
            
            var optionInner = document.createElement('div');
            optionInner.id = "optionInner"+option.type;
            optionInner.className = "optionInner";

            optionInner.appendChild(SubOptionIcon);
            optionInner.appendChild(SubOptionText);

            panelSubOption.appendChild(optionInner);
            subPanelMain.appendChild(panelSubOption);

            if (userMetal < option.cost){
                optionInner.appendChild(optionDisabled);
            }

        });
        return subPanelMain; 
    },

}