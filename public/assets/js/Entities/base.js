class BaseEntity extends DefaultEntity {
    
    constructor(id, ownerName, relation, content) {
        super();
        this.id = id;
        this.ownerName = ownerName;
        this.relation = relation;
        this.content = content;
    }
    
    onClick() {
        panelInterface.select(this, this.ownerName, this.relation);
    }
    
}