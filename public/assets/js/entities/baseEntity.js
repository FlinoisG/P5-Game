class BaseEntity {
    
    constructor(
        id, 
        ownerName, 
        relation, 
        HP,
        content, 
        workerSpace, 
        soldierSpace, 
        marker
    ) {
        this.id = id;
        this.type = 'base';
        this.class = 'building';
        this.ownerName = ownerName;
        this.relation = relation;
        this.HP = HP;
        this.maxHP = 100;
        this.content = content;
        this.workerSpace = workerSpace;
        this.soldierSpace = soldierSpace;
        this.cost = 50;
        this.buildTime = 3;
        this.imgName = 'unit_slot_base';
        this.textContent = 'regular';
        this.marker = marker;
    }

    subPanelAction(origin=null, toSelect=null, params=null){
        buildOrder.build('base', origin, toSelect);
    };

    onClick(e) {
        if (moveOrder.moveMode == true){
            var targetOigin = this.type+","+this.id;
            var url = "?p=task.moveUnit&type=" + moveOrder.type
            + "&startOrigin=" + moveOrder.origin
            + "&target=" + targetOigin
            + "&amount=" + moveOrder.amount;
            window.location.replace(url);
        } else {
            panelInterface.select(this);
        }
    }

}