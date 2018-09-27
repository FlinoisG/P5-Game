class DefaultEntity {
    
    constructor() {

        var self = this;
        var req = new XMLHttpRequest();
        req.open("GET", "/public/index.php?p=data.getUnitSettings"); // local
        //req.open("GET", "/P5-Game/public/index.php?p=data.getUnitSettings"); //server
        req.addEventListener("load", function () {
            var unitSettings = JSON.parse(req.responseText);
            self.unitSettings = unitSettings;
            self.updateCost();
            self.updateBuildTime();
            self.updateMaxHP();
            self.checkFocus();
        });
        req.send(null);
    }  

    updateCost(){
        if (typeof(this.cost) !== 'undefined'){
            this.cost = this.unitSettings.cost[this.type + "Cost"];
            var panelText = document.getElementById("panelText" + this.type + "Cost");
            if (panelText !== null){
                panelText.innerHTML = this.cost;
            }
        }
    }

    updateBuildTime(){
        if (typeof(this.buildTime) !== 'undefined'){
            this.buildTime = this.unitSettings.buildTime[this.type + "BuildTime"];
            var panelText = document.getElementById("panelText" + this.type + "BuildTime");
            if (panelText !== null){
                panelText.innerHTML = this.buildTime;
            }
        }
    }

    updateMaxHP(){
        if (typeof(this.maxHP) !== 'undefined'){
            if (typeof(this.main) !== 'undefined' && this.main === 1){
                var index = "mainMaxHP";
            } else {
                var index = this.type + "MaxHP";
            }  
            this.maxHP = this.unitSettings.maxHP[index];
            if (panelInterface.selected === this){
                var panelText = document.getElementById("maxHP");
                if (panelText !== null){
                    panelText.innerHTML = this.maxHP;
                }   
            }           
        }
    }
    
    checkFocus(){
        if (window.location.search.includes('focus')){
            var target = (window.location.search.substr(14));
            if (target != ''){
                var origin = target.split(",");
                var originType = origin[0];
                var str = origin[1].split("&");
                var originId = str[0];
                if (originType == this.type && originId == this.id){
                    this.onClick();
                    if (window.location.search.includes('soldierTab')){
                        panelInterface.soldierTab(this);
                    }
                }
            }
        }
    }

}