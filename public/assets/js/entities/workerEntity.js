class WorkerEntity extends DefaultEntity {
    
    constructor(
        ownerName='', 
        relation='neutral', 
    ) {
        super();
        this.type = 'worker';
        this.class = 'unit';
        this.cost = 100;
        this.buildTime = 0;
        this.imgName = 'unit_slot_worker_finished';
        this.textContent = 'regular';
        this.ownerName = ownerName;
        this.relation = relation;
    };
    
    subPanelAction(origin=null){
        window.location.replace("?p=task.buy&type=worker&origin=" + origin);
    };

}