class MineInConstEntity{
    
    constructor(ownerName='', relation='neutral', start=0, time=0, ) {
        this.type = 'baseInConst';
        this.class = 'building';
        this.ownerName = ownerName;
        this.relation = relation;
        this.start = start;
        this.time = time;
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
    
};