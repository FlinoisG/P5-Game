class BaseEntity extends DefaultEntity {
    
  
    onClick() {
        panelInterface.select(this, this.ownerName, this.relation);
    }
}