panelInterface = {
  
    select(toSelect) 
    {
        switch (toSelect.constructor.name){
            case "BaseEntity":
                this.selectBase(toSelect);
                break;
            case "PeonEntity":
                this.selectPeon(toSelect);
                break;
            default:
                console.log(':(');
        }
        
    },

    unSelect() 
    {
        document.getElementById('panelInterface').innerHTML = "";        
    },


    selectBase(toSelect) 
    {
        if (toSelect.relation == "owned") {
            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " Base";

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
            panelSlots.id = "panelSlots"
            panelSlots.className = "panelSlots"

            slotTabs.appendChild(workerTabButton);
            slotTabs.appendChild(soldierTabButton);

            var freeSlots = 10;

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
                            var slots = document.getElementsByClassName('unitSlot')
                            for (i = 0; i < slots.length; i++) {
                                slots[i].style.border = "1px solid #292929";
                                slots[i].className = slots[i].className.replace(' slotSelected','');
                            }
                            ev.target.className += " slotSelected";
                            this.selectWorker(toSelect.id);
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
                        var mins = Math.floor(time / 60);
                        if (mins == 0) mins = "00";
                        var secs = time - mins * 60;
                        if (secs == 0) secs = "00";
                        var displayTime = mins + ":" + secs;
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
            document.getElementById('panelInterface').appendChild(slotTabs);
            document.getElementById('panelInterface').appendChild(panelSlots); 
            document.getElementById('panelInterface').appendChild(panelSub);

            var workerEntity = new WorkerEntity; 
            var workerSpaceEntity = new WorkerSpaceEntity; 

            SoldierEntity.link = toSelect.id;
            workerSpaceEntity.link = toSelect.id;

            baseOptions = [
                workerEntity,
                workerSpaceEntity
            ]

            document.getElementById('panelSub').appendChild(this.buildSubPanel(baseOptions, toSelect.id));

            soldierTabButton.addEventListener("click", (ev) => {
                this.soldierTab(toSelect);
            });
        } else {
            content = "<h4>#" + toSelect.id + " Base de " + toSelect.ownerName + "</h4>"
            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " Base de " + toSelect.ownerName;
            document.getElementById('panelInterface').innerHTML = "";
            document.getElementById('panelInterface').appendChild(title);
        }     
    },

    soldierTab(toSelect) 
    {
        document.getElementById('slotTabs').innerHTML = "";
        document.getElementById('panelSlots').innerHTML = "";

        var workerTabButton = document.createElement('div');
        workerTabButton.className = "soldierTab slotTab link";
        workerTabButton.textContent = "workers";
        
        var soldierTabButton = document.createElement('div');
        soldierTabButton.className = "soldierTab slotTab disabledLink";
        soldierTabButton.textContent = "soldiers";        

        var freeSlots = 10;
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
                        this.selectSoldier(toSelect.id);
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
                    var mins = Math.floor(time / 60);
                    if (mins == 0) mins = "00";
                    var secs = time - mins * 60;
                    if (secs == 0) secs = "00";
                    var displayTime = mins + ":" + secs;
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

        soldierEntity = new SoldierEntity; 
        soldierSpaceEntity = new SoldierSpaceEntity; 

        soldierEntity.link = toSelect.id;
        soldierSpaceEntity.link = toSelect.id;

        soldierOptions = [
            soldierEntity,
            soldierSpaceEntity
        ]

        document.getElementById('panelSub').innerHTML = "";
        document.getElementById('panelSub').appendChild(this.buildSubPanel(soldierOptions, toSelect.id));

        workerTabButton.addEventListener("click", () => {
            this.selectBase(toSelect);
        });
    },

    selectWorker(baseId) {
        document.getElementById('panelSub').innerHTML = "";

        baseEntity = new BaseEntity; 
        mineEntity = new MineEntity; 

        baseEntity.link = baseId;
        mineEntity.link = baseId;

        workerOptions = [
            baseEntity,
            mineEntity
        ]

        document.getElementById('panelSub').appendChild(this.buildSubPanel(workerOptions, baseId));
    },

    selectSoldier(baseId) {
        document.getElementById('panelSub').innerHTML = "";

        baseEntity = new BaseEntity; 
        mineEntity = new MineEntity; 

        baseEntity.link = baseId;
        mineEntity.link = baseId;

        workerOptions = [
            baseEntity,
            mineEntity
        ]

        document.getElementById('panelSub').appendChild(this.buildSubPanel(workerOptions, baseId));
    },

    buildSubPanel(options, selectedId=null)
    {
        var subPanelMain = document.createElement('div');
        subPanelMain.id = "subPanelMain";

        options.forEach(option => {
            var panelSubOption = document.createElement('div');
            panelSubOption.id = "panelSub"+option.class;
            panelSubOption.className = "panelSubOption " + "panelSub" + option.class;

            var SubOptionIcon = document.createElement('img');
            SubOptionIcon.className = 'panelSubIcon';
            SubOptionIcon.src = '../public/assets/img/' + option.imgName + '.png';

            SubOptionIcon.addEventListener("click", function () {
                option.subPanelAction(selectedId);
            });

            var SubOptionText = document.createElement('span');
            SubOptionText.className = 'panelSubText';
            SubOptionText.innerHTML = "Acheter "+option.class+"<br>Cout: "+option.cost+"metal, "+option.buildTime+"mn";

            panelSubOption.appendChild(SubOptionIcon);
            panelSubOption.appendChild(SubOptionText);

            subPanelMain.appendChild(panelSubOption);
        });
        return subPanelMain; 
    },

}