var Tilelayers = {
    "SpinalMap": "https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png?apikey=213fa43d7d40475091d54ee21fa593ae",
    "Stamen": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",
    "mapBox": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",

};

const Map = {"tilemap": Tilelayers.Stamen};

Map.mainMap = {

    mapInit: function(){
        this.map = L.map('mapid', {
            //minZoom: 7,
            maxZoom: 18,
            //maxBounds: [
                //south west
            //    [32.7, -11.3269],
                //north east
            //    [61.37567, 32.39868]
            //    ], 
            maxBoundsViscosity: 1.0
        }).setView([gridToCoordinates(0, 131.48, "y"), gridToCoordinates(224.83, 0, "x")], 2);
        this.map.addEventListener('click', function(ev) {
            x = coordinatesToGrid(ev.latlng.lng, 0, "x");
            y = coordinatesToGrid(0, ev.latlng.lat, "y");
            //console.log('grid x: ' + (x) + ', y: ' + (y));
            //console.log(Map.mainMap.map.getBounds());
        });     
        
        //this.map.dragging.disable();
        //this.map.touchZoom.disable();
        this.map.doubleClickZoom.disable();
        //this.map.scrollWheelZoom.disable();
        this.map.boxZoom.disable();
        //this.map.keyboard.disable();
        //if (this.map.tap) this.map.tap.disable();
        this.setMarkers();
        this.setOreMap();
        this.setObjectMap();
        this.setTileLayer();
        Map.miniMap.mapInit();
    },

    setMarkers: function(){
        this.baseOwnerIcon = L.icon({
            iconUrl: 'assets/img/base_owner.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        this.baseEnemyIcon = L.icon({
            iconUrl: 'assets/img/base_enemy.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        this.baseNeutralIcon = L.icon({
            iconUrl: 'assets/img/base_neutral.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        this.mineOwnerIcon = L.icon({
            iconUrl: 'assets/img/mine_owner.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        this.mineEnemyIcon = L.icon({
            iconUrl: 'assets/img/mine_enemy.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        this.mineNeutralIcon = L.icon({
            iconUrl: 'assets/img/mine_neutral.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        this.baseInConstIcon = L.icon({
            iconUrl: 'assets/img/base_construction.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        this.mineInConstIcon = L.icon({
            iconUrl: 'assets/img/mine_construction.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        this.workerNeutralIcon = L.icon({
            iconUrl: 'assets/img/worker_neutral.png',
            iconSize:     [32, 33],
            iconAnchor:   [16, 17],
            popupAnchor:  [0, -20]
        });
        this.workerOwnerIcon = L.icon({
            iconUrl: 'assets/img/worker_owner.png',
            iconSize:     [32, 33],
            iconAnchor:   [16, 17],
            popupAnchor:  [0, -20]
        });
        this.workerEnemyIcon = L.icon({
            iconUrl: 'assets/img/worker_enemy.png',
            iconSize:     [32, 33],
            iconAnchor:   [16, 17],
            popupAnchor:  [0, -20]
        });
    },
   
    setOreMap: function(){
        if (typeof oreMapObj !== 'undefined') {
            oreMapObj.oreMap.forEach(ore => {
                var R = Math.round(ore.value * 51)+51;
                var G = Math.round(ore.value * 41)+61;
                var B = Math.round(ore.value * 197)+58;
                var oreMarker = L.circle([ore.y, ore.x], {
                    //color: '#55719e',
                    color: 'rgb('+R+', '+G+', '+B+')',
                    radius: 2000
                }).addTo(this.map);
                oreMarker.bindPopup("bonjour " + ore.value);
            });
        }
    },

    setObjectMap: function(){
        
        if (typeof objectMapObj !== 'undefined') {
            objectMapObj.forEach(object => {
                x = gridToCoordinates(object.x, 0, 'x');
                y = gridToCoordinates(0, object.y, 'y');
                if (object.type == "base"){
                    if (object.owner == "player"){
                        if (object.main == 1){
                            this.map.setView([y, x], 9);
                        }
                        icon = this.baseOwnerIcon;
                        relation = "owned";
                    } else if (object.owner == "enemy"){
                        icon = this.baseEnemyIcon;
                        relation = "enemy";
                    } else {
                        icon = this.baseNeutralIcon;
                        relation = "neutral";
                    }
                    baseMarker = L.marker([y, x], {
                        icon: icon,
                        riseOnHover: true,
                    }).addTo(this.map);
                    const baseEntity = new BaseEntity(object.id, object.ownerName, relation, object.content, object.workerSpace, object.soldierSpace, baseMarker);
                    baseMarker.bindPopup("#"+object.id+" base de "+object.ownerName);
                    baseMarker.addEventListener('click', function(e) {
                        baseEntity.onClick(e);
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            var target = (window.location.search.substr(14));
                            if (target != ''){
                                var origin = target.split(",");
                                var originType = origin[0];
                                var str = origin[1].split("&");
                                var originId = str[0];
                                if (originType == "base" && originId == object.id){
                                    console.log(baseEntity.marker._latlng);
                                    baseEntity.onClick();
                                    Map.mainMap.map.setView(baseEntity.marker._latlng)
                                    if (window.location.search.includes('soldierTab')){
                                        panelInterface.soldierTab(baseEntity);
                                    }
                                }
                            }
                        }                    
                    }
                } else if (object.type == "mine") {
                    if (object.owner == "player"){
                        icon = this.mineOwnerIcon;
                        relation = "owned";
                    } else if (object.owner == "enemy"){
                        icon = this.mineEnemyIcon;
                        relation = "enemy";
                    } else {
                        icon = this.mineNeutralIcon;
                        relation = "neutral";
                    }
                    mineMarker = L.marker([y, x], {
                        icon: icon,
                        riseOnHover: true,
                    }).addTo(this.map);
                    const mineEntity = new MineEntity(object.id, object.ownerName, relation, object.content, object.workerSpace, object.soldierSpace);
                    mineMarker.addEventListener('click', function(e) {
                        mineEntity.onClick(e);
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            if (typeof(target) != 'undefined'){
                                var target = (window.location.search.substr(14));
                                var origin = target.split(",");
                                var originType = origin[0];
                                var str = origin[1].split("&");
                                var originId = str[0];
                                if (originType == 'mine' && originId == object.id){
                                    Map.mainMap.map.setView(mineEntity.marker._latlng)
                                    mineEntity.onClick();
                                }
                            }
                        }                    
                    }
                } else if (object.type == "baseInConst") {
                    console.log(object);
                    var now = Math.floor(Date.now() / 1000);
                    if (object.start > now) {
                        opacity = 0.3;
                    } else {
                        opacity = 1;
                    }
                    if (object.owner == "player"){
                    } else if (object.owner == "enemy"){
                        relation = "enemy";
                    } else {
                        relation = "neutral";
                    }
                    baseInConstMarker = L.marker([y, x], {
                        icon: this.baseInConstIcon,
                        opacity: opacity
                    }).addTo(this.map);
                    const baseInConstEntity = new BaseInConstEntity(object.ownerName, relation, object.start, object.time);
                    baseInConstMarker.addEventListener('click', function(e) {
                        baseInConstEntity.onClick(e);
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            if (typeof(target) != 'undefined'){
                                var target = (window.location.search.substr(14));
                                var origin = target.split(",");
                                var originType = origin[0];
                                var str = origin[1].split("&");
                                var originId = str[0];
                                if (originType == 'mine' && originId == object.id){
                                    Map.mainMap.map.setView(baseEntity.marker._latlng)
                                    baseEntity.onClick();
                                }
                            }
                        }                    
                    }
                } else if (object.type == "mineInConst") {
                    var now = Math.floor(Date.now() / 1000);
                    if (object.start > now) {
                        opacity = 0.3;
                    } else {
                        opacity = 1;
                    }
                    if (object.owner == "player"){
                    } else if (object.owner == "enemy"){
                        relation = "enemy";
                    } else {
                        relation = "neutral";
                    }
                    mineInConstMarker = L.marker([y, x], {
                        icon: this.mineInConstIcon,
                        opacity: opacity
                    }).addTo(this.map);
                    const mineInConstEntity = new MineInConstEntity(object.ownerName, relation, object.start, object.time);
                    mineInConstMarker.addEventListener('click', function(e) {
                        mineInConstEntity.onClick(e);
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            if (typeof(target) != 'undefined'){
                                var target = (window.location.search.substr(14));
                                var origin = target.split(",");
                                var originType = origin[0];
                                var str = origin[1].split("&");
                                var originId = str[0];
                                if (originType == 'mine' && originId == object.id){
                                    Map.mainMap.map.setView(mineEntity.marker._latlng)
                                    mineEntity.onClick();
                                }
                            }
                        }                    
                    }
                } else if (object.type == "worker") {
                    console.log(object);
                    if (object.owner == "player"){
                        relation = "owner";
                        icon = this.workerOwnerIcon;
                        var color = "#0000FF";
                    } else if (object.owner == "enemy"){
                        relation = "enemy";
                        icon = this.workerEnemyIcon;
                        var color = "#FF0000";
                    } else {
                        relation = "neutral";
                        icon = this.workerNeutralIcon;
                        var color = "#FFFFFF";
                    }
                    var posStart = [gridToCoordinates(0, object.posStart[1], "y"), gridToCoordinates(object.posStart[0], 0, "x")];
                    var posEnd = [gridToCoordinates(0, object.posEnd[1], "y"), gridToCoordinates(object.posEnd[0], 0, "x")];
                    
                    var moveDuration = object.time - object.start;
                    var moveNow = (Math.floor(Date.now() / 1000)) - object.start;
                    var percent = 100*moveNow/moveDuration;
                    var workerPos = getPosFromDist(posStart, posEnd, percent);
                    if (moveNow < moveDuration) {
                        var workerPath = L.polygon([
                            posStart,
                            posEnd
                        ], {
                            dashArray: "5, 5",
                            color: color,
                        }).addTo(this.map);
                        workerMarker = L.marker(workerPos, {
                            icon: icon,
                        }).addTo(this.map);
                        const workerEntity = new WorkerEntity(object.ownerName, relation, object.start, object.time);
                        workerMarker.addEventListener('click', function(e) {
                            workerEntity.onClick(e);
                        });
                        unitMovementUpdator(workerMarker, posStart, posEnd, object.start, object.time);
                    }                    
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            if (typeof(target) != 'undefined'){
                                var target = (window.location.search.substr(14));
                                var origin = target.split(",");
                                var originType = origin[0];
                                var str = origin[1].split("&");
                                var originId = str[0];
                                if (originType == 'mine' && originId == object.id){
                                    Map.mainMap.map.setView(mineEntity.marker._latlng)
                                    mineEntity.onClick();
                                }
                            }
                        }                    
                    }
                }
            });
        }
    },

    setTileLayer: function(){
        L.tileLayer(Map.tilemap, {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(this.map);
    }

}

Map.miniMap = {

    mapInit: function(){
        this.miniMap = L.map('minimapid').setView([49, 10.5], 3);  
        this.setOreMap();
        this.setMinimapCursor();
        this.setMinimapControls();
        this.setTileLayer();
        this.setBaseMap();
        
    },

    setMinimapCursor: function(){   
        if (typeof(this.polygon) != "undefined"){
            this.miniMap.removeLayer(this.polygon);  
        }
        var bounds = Map.mainMap.map.getBounds();
        var northWest = bounds.getNorthWest();
        var northEast = bounds.getNorthEast();
        var southWest = bounds.getSouthWest();
        var southEast = bounds.getSouthEast();
        this.polygon = L.polygon([
            [northWest.lat, northWest.lng],
            [northEast.lat, northEast.lng],
            [southEast.lat, southEast.lng],
            [southWest.lat, southWest.lng]
        ], {
            stroke: true,
            weight: 1.5,
            lineJoin: 'miter-clip',
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.05,
            clickable: false
        }).addTo(this.miniMap);
        
    },
        
    setOreMap: function(){
        
        if (typeof oreMapObj !== 'undefined') {
            oreMapObj.oreMap.forEach(ore => {
                var R = Math.round(ore.value * 51)+51;
                var G = Math.round(ore.value * 41)+61;
                var B = Math.round(ore.value * 197)+58;
                var oreMarker = L.circle([ore.y, ore.x], {
                    color: 'rgb('+R+', '+G+', '+B+')',
                    fillOpacity: 1,
                    radius: 1,
                    stroke: false,
                    clickable: false
                }).addTo(this.miniMap);
                
            });
        }
    },

    setBaseMap: function(){
        if (typeof objectMapObj !== 'undefined') {
            objectMapObj.forEach(base => {
                x = gridToCoordinates(base.x, 0, 'x');
                y = gridToCoordinates(0, base.y, 'y');
                if (base.owner == "player"){
                    color = "blue";
                } else if (base.owner == "enemy"){
                    color = "red";
                } else {
                    color = "grey";
                }
                baseMarker = L.circle([y, x], {
                    color: color,
                    radius: 20000
                    
                }).addTo(this.miniMap);
            });
        }
    },

    setMinimapControls: function(){

        this.miniMap.dragging.disable();
        this.miniMap.touchZoom.disable();
        this.miniMap.doubleClickZoom.disable();
        this.miniMap.scrollWheelZoom.disable();
        this.miniMap.boxZoom.disable();
        this.miniMap.keyboard.disable();

        var lat, lng;
        this.miniMap.addEventListener('mousemove', function(ev) {
           lat = ev.latlng.lat;
           lng = ev.latlng.lng;
        });        
        
        Map.miniMap.miniMap.addEventListener('mousedown', function() {
            Map.miniMap.miniMapClicked = true;
        });
        
        Map.miniMap.miniMap.addEventListener('mouseup', function() {
            Map.miniMap.miniMapClicked = false;
        });

        Map.miniMap.miniMap.addEventListener('mousemove', function() {
            if (Map.miniMap.miniMapClicked) {
                Map.mainMap.map.setView([lat, lng])
            }
        });
        
        Map.miniMap.miniMap.addEventListener('mousedown', function() {
            Map.mainMap.map.setView([lat, lng])
        });

        Map.mainMap.map.on('move', function() {
            Map.miniMap.setMinimapCursor();
        });

        Map.mainMap.map.on('zoomend', function() {
            Map.miniMap.setMinimapCursor();
        });
    },

    setTileLayer: function(){
        L.tileLayer(Map.tilemap, {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(this.miniMap);
    }

}

Map.mainMap.mapInit();

