class SoldierEntity extends DefaultEntity {
    
    constructor(
        ownerName='', 
        relation='neutral', 
    ) {
        super();
        this.type = 'soldier';
        this.class = 'unit';
        this.cost = 50;
        this.buildTime = 3;
        this.imgName = 'unit_slot_soldier_finished';
        this.textContent = 'regular';
        this.ownerName = ownerName;
        this.relation = relation;
    };
    
    subPanelAction(origin=null){
        window.location.replace("?p=task.buy&type=soldier&origin=" + origin);
    };

}