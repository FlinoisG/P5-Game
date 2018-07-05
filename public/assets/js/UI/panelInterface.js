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
            content += "<a href=\"#\">Acheter péon</a><br>";
            for (i = 0; i < 12; i++) {
                if (i < 2) {
                    content += "<img class=\"unitSlot\" src=\"../public/assets/img/unit_slot_worker_finished.png\" alt=\"Empty unit slot\">"
                }
                if (i > 2 && i < 5) {
                    content += "<img class=\"unitSlot\" src=\"../public/assets/img/unit_slot_worker_ip.png\" alt=\"Empty unit slot\">"
                } 
                if (i > 5) {
                    content += "<img class=\"unitSlot\" src=\"../public/assets/img/unit_slot_empty.png\" alt=\"Empty unit slot\">"
                    if (i == 6){
                        content += "<br>";
                    }
                }
                
                ;
            }
            
        }        
        document.getElementById('panelInterface').innerHTML = content;
    },

    selectPeon(toSelect, relation) {
        content = "<h4>Péon " + toSelect.id + "</h4>"
        content += "<a href=\"#\">Construir Base</a>"
        document.getElementById('panelInterface').innerHTML = content;
    },

}