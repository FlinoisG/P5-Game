class BaseEntity extends DefaultEntity {
    constructor(id) {
        super();
        this.id = id;
        console.log('pop ' + id);
    }
  
    onClick() {
        document.getElementById('panelSelect').innerHTML = "<h4>Base "+this.id+"</h4>";
        console.log(this.id);
    }
}