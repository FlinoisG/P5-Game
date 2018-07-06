panelInterface = {
  
    select(toSelect, ownerName, relation) {
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

    selectBase(toSelect, ownerName, relation) {
        content = "<h4>#" + toSelect.id + " Base de " + ownerName + "</h4>"
        if (relation == "owned") {
            content = "<h4>#" + toSelect.id + " Base</h4>";
            content += "<a href=\"?p=base.buyWorker&baseId=" + toSelect.id + "\">Acheter péon</a><br>";
            content += "<div class=\"panelSlots\">";
            var freeSlots = 10;
            if (toSelect.content.length != 0) {                
                if (toSelect.content.workers !== undefined && toSelect.content.workers != 0){
                    for (i = 0; i < toSelect.content.workers; i++) {
                        
                        content += `
                            <div class="unitSlotContainer">
                                <img class="unitSlot unitSlotWorker" src="../public/assets/img/unit_slot_worker_finished.png" alt="Worker unit">
                            </div>`;
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
                        var secs = time - mins * 60;
                        content += `
                            <div class="unitSlotContainer">
                                <img class=\"unitSlot unitSlotWorkerInConst\" src=\"../public/assets/img/unit_slot_worker_ip.png\" alt=\"Worker unit in construction\">
                                <div class="panelUnitTimer">`+mins+`:`+secs+`</div>
                            </div>`;
                        
                        freeSlots--;
                    });
                }
            }
            for (i = 0; i < freeSlots; i++) {
                content += `
                    <div class="unitSlotContainer">
                        <img class="unitSlot emptySlot" src="../public/assets/img/unit_slot_empty.png" alt="Empty unit slot">
                    </div>`;              
            }  
            content += "</div>";          
        }        
        document.getElementById('panelInterface').innerHTML = content;
    },

    selectPeon(toSelect, relation) {
        content = "<h4>Péon " + toSelect.id + "</h4>"
        content += "<a href=\"#\">Construir Base</a>"
        document.getElementById('panelInterface').innerHTML = content;
    },

}