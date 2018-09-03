class AttackEntity {
    
    constructor() {
        this.type = 'attack';
        this.class = 'order';
        this.imgName = 'unit_slot_attack';
        this.textContent = 'numberSelector';
    };
    
    subPanelAction(origin=null, toSelect=null, params=null){
        attackOrder.attack(origin, toSelect, params);
    };

}