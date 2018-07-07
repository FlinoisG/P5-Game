panelInterface = {
  
    select(toSelect, ownerName, relation) 
    {
        switch (toSelect.constructor.name){
            case "BaseEntity":
                this.selectBase(toSelect, ownerName, relation);
                break;
            case "PeonEntity":
                this.selectPeon(toSelect, ownerName, relation);
                break;
            default:
                console.log(':(');
        }
        
    },

    unSelect() 
    {
        document.getElementById('panelInterface').innerHTML = "";        
    },


    selectBase(toSelect, ownerName, relation) 
    {
        if (relation == "owned") {
            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " Base";

            var buyWorkers = document.createElement('a');
            buyWorkers.href = "?p=base.buyWorker&baseId=" + toSelect.id;
            buyWorkers.textContent = "Acheter péon";

            var slotTabs = document.createElement('div');
            slotTabs.className = "slotTabs"

            var workerTabButton = document.createElement('div');
            workerTabButton.className = "workerTab slotTab disabledLink";
            workerTabButton.textContent = "worker";
            
            var soldierTabButton = document.createElement('div');
            soldierTabButton.className = "soldierTab slotTab link";
            soldierTabButton.href = "?p=home";
            soldierTabButton.textContent = "soldiers";

            var panelSlots = document.createElement('div');
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
                        img.className = 'unitSlot unitSlotWorkerInConst';
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
            document.getElementById('panelInterface').innerHTML = "";
            
            document.getElementById('panelInterface').appendChild(title);
            document.getElementById('panelInterface').appendChild(slotTabs);
            document.getElementById('panelInterface').appendChild(panelSlots); 
            document.getElementById('panelInterface').appendChild(buyWorkers);
        } else {
            
            content = "<h4>#" + toSelect.id + " Base de " + ownerName + "</h4>"
            var title = document.createElement('h4');
            title.textContent = '#' + toSelect.id + " Base de " + ownerName;
            document.getElementById('panelInterface').innerHTML = "";
            document.getElementById('panelInterface').appendChild(title);
        }     
    },

    selectPeon(toSelect, relation) {
        content = "<h4>Péon " + toSelect.id + "</h4>"
        content += "<a href=\"#\">Construir Base</a>"
        
        document.getElementById('panelInterface').innerHTML = content;
    },

}