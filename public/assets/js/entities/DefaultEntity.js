class DefaultEntity {
    
    constructor() {
        
        var self = this;
        var req = new XMLHttpRequest();
        req.open("GET", "/public/index.php?p=data.getUnitSettings");
        req.addEventListener("load", function () {

            var unitSettings = JSON.parse(req.responseText);
            self.unitSettings = unitSettings;

            if (typeof(self.cost) !== 'undefined'){
                self.cost = unitSettings.cost[self.type + "Cost"];
                var panelText = document.getElementById("panelText" + self.type + "Cost");
                if (panelText !== null){
                    panelText.innerHTML = self.cost;
                }
            }

            if (typeof(self.buildTime) !== 'undefined'){
                self.buildTime = unitSettings.buildTime[self.type + "BuildTime"];
                var panelText = document.getElementById("panelText" + self.type + "BuildTime");
                if (panelText !== null){
                    panelText.innerHTML = self.buildTime;
                }
            }

            if (typeof(self.maxHP) !== 'undefined'){
                if (typeof(self.main) !== 'undefined' && self.main === 1){
                    self.maxHP = unitSettings.maxHP["mainMaxHP"];
                } else {
                    self.maxHP = unitSettings.maxHP[self.type + "MaxHP"];
                }  
                var panelText = document.getElementById("maxHP");
                if (panelText !== null){
                    panelText.innerHTML = self.maxHP;
                }              
            }

        });
        req.send(null);
    }    

}