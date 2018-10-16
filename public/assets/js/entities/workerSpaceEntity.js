class WorkerSpaceEntity extends DefaultEntity {
    
    constructor() {
        super();
        this.type = 'workerSpace';
        this.displayText = 'Stockage pour ouvriers';
        this.class = 'upgrade';
        this.cost = 100;
        this.buildTime = 0;
        this.imgName = 'unit_slot_base';
        this.textContent = 'regular';
    };
    
    subPanelAction(origin = null){
        window.location.replace("?p=task.buy&type=workerSpace&origin=" + origin);
    };

}