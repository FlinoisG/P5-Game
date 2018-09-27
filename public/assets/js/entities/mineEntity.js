class MineEntity extends DefaultEntity {

    constructor(
        id = 0, 
        ownerName = '', 
        relation = 'neutral', 
        HP = 100, 
        content = '', 
        workerSpace='', 
        soldierSpace='', 
        marker=null, 
    ) {
        super();
        this.id = id;
        this.type = 'mine';
        this.class = 'building';
        this.ownerName = ownerName;
        this.relation = relation;
        this.HP = HP;
        this.maxHP = 100;
        this.content = content;
        this.workerSpace = workerSpace;
        this.soldierSpace = soldierSpace;
        this.cost = 150;
        this.buildTime = 0;
        this.imgName = 'unit_slot_mine';
        this.textContent = 'regular',
        this.marker = marker;
    };

    subPanelAction(origin=null, toSelect=null, params=null){    
        buildOrder.build('mine', origin, toSelect);
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
            var url = "?p=task.attack&type=" + attackOrder.type
            + "&startOrigin=" + attackOrder.origin
            + "&target=" + targetOrigin
            + "&amount=" + attackOrder.amount;
            window.location.replace(url);
        } else {
            panelInterface.select(this);
        }
    }

}