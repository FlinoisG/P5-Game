class WorkerSpaceEntity extends DefaultEntity {
    
    constructor() {
        super();
        this.type = 'workerSpace';
        this.class = 'upgrade';
        this.cost = 0;
        this.buildTime = 0;
        this.imgName = 'unit_slot_base';
        this.textContent = 'regular';
    };
    
    subPanelAction(origin = null){
        window.location.replace("?p=task.buy&type=workerSpace&origin=" + origin);
    };

}