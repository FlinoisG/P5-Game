class PeonEntity extends DefaultEntity {
    constructor(id) {
        super();
        this.id = id;
    }
  
    onClick() {
        panelInterface.select(this, relation);
    }
}