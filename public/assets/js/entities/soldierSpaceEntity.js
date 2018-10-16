class SoldierSpaceEntity extends DefaultEntity {
    
    constructor() {
        super();
        this.type = 'soldierSpace';
        this.displayText = 'Stockage pour soldats';
        this.class = 'upgrade';
        this.cost = 100;
        this.buildTime = 0;
        this.imgName = 'unit_slot_base';
        this.textContent = 'regular';
    };
    
    subPanelAction(origin = null){
        window.location.replace("?p=task.buy&type=soldierSpace&origin=" + origin);
    };}