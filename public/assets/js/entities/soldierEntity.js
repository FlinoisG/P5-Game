class SoldierEntity extends DefaultEntity {
    
    constructor(
        ownerName='', 
        relation='neutral', 
    ) {
        super();
        this.type = 'soldier';
        this.displayText = 'Soldat';
        this.class = 'unit';
        this.cost = 100;
        this.buildTime = 3;
        this.imgName = 'unit_slot_soldier_finished';
        this.textContent = 'regular';
        this.ownerName = ownerName;
        this.relation = relation;
    };
    
    subPanelAction(origin=null){
        window.location.replace("?p=task.buy&type=soldier&origin=" + origin);
    };

    onClick(e) {
        if (moveOrder.moveMode == true){
            var targetOrigin = this.type+","+this.id;
            var url = "?p=task.moveUnit&type=" + moveOrder.type
            + "&startOrigin=" + moveOrder.origin
            + "&target=" + targetOrigin
            + "&amount=" + moveOrder.amount;
            window.location.replace(url);
        } else if (attackOrder.attackMode == true){
            var targetOrigin = this.type+","+this.id;
            var url = "?p=task.attack"
            + "&startOrigin=" + attackOrder.origin
            + "&target=" + targetOrigin
            + "&amount=" + attackOrder.amount;
            window.location.replace(url);
        } else {
            panelInterface.select(this);
        }
    }

}