class MoveEntity {

    constructor() {
        this.type = 'move';
        this.displayText = 'Déplacer';
        this.class = 'order';
        this.imgName = 'unit_slot_green_arrow';
        this.textContent = 'numberSelector';
    };

    subPanelAction(origin=null, toSelect=null, params=null){
        moveOrder.move(origin, toSelect, params);
    };
    
}