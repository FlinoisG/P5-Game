class BaseEntity extends DefaultEntity {
    
    constructor(
        id, 
        main,
        ownerName, 
        relation, 
        HP,
        content, 
        workerSpace, 
        soldierSpace, 
        marker
    ) {
        super();
        this.id = id;
        this.type = 'base';
        this.displayText = 'Base';
        this.main = main;
        this.class = 'building';
        this.ownerName = ownerName;
        this.relation = relation;
        this.HP = HP;
        this.maxHP = 0;
        this.content = content;
        this.workerSpace = workerSpace;
        this.soldierSpace = soldierSpace;
        this.cost = 200;
        this.buildTime = 0;
        this.imgName = 'unit_slot_base';
        this.textContent = 'regular';
        this.marker = marker;
    }

    subPanelAction(origin=null, toSelect=null, params=null){
        buildOrder.build('base', origin, toSelect);
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