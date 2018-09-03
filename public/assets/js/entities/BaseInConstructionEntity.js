class BaseInConstEntity extends DefaultEntity {
    
    constructor(ownerName='', relation='neutral', start=0, time=0, ) {
        super();
        this.type = 'baseInConst';
        this.class = 'building';
        this.ownerName = ownerName;
        this.relation = relation;
        this.start = start;
        this.time = time;
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
    
};