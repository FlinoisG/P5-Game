var Tilelayers = {
    "SpinalMap": "https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png?apikey=213fa43d7d40475091d54ee21fa593ae",
    "Stamen": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",
    "mapBox": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",

};

const Map = {"tilemap": Tilelayers.Stamen};

Map.mainMap = {

    mapInit: function(){
        this.map = L.map('mapid', {
            minZoom: 8,
            maxZoom: 18,
            maxBounds: [
                //south west
                [32.7, -11.3269],
                //north east
                [61.37567, 32.39868]
                ], 
            maxBoundsViscosity: 1.0
        }).setView([gridToCoordinates(0, 131.48, "y"), gridToCoordinates(224.83, 0, "x")], 2);
        this.map.addEventListener('click', function(ev) {
            x = coordinatesToGrid(ev.latlng.lng, 0, "x");
            y = coordinatesToGrid(0, ev.latlng.lat, "y");
            //console.log('grid x: ' + (x) + ', y: ' + (y));
            //console.log(Map.mainMap.map.getBounds());
        });     
        
        //this.map.dragging.disable();
        this.map.touchZoom.disable();
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
        
    },
   
    setOreMap: function(){
        if (typeof oreMapObj !== 'undefined') {
            oreMapObj.oreMap.forEach(ore => {
                var oreMarker = L.circle([ore.y, ore.x], {
                    color: '#55719e',
                    radius: 500
                }).addTo(this.map);
            });
        }
    },

    setObjectMap: function(){
        var baseOwnerIcon = L.icon({
            iconUrl: 'assets/img/base_owner.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        var baseEnemyIcon = L.icon({
            iconUrl: 'assets/img/base_enemy.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        var baseNeutralIcon = L.icon({
            iconUrl: 'assets/img/base_neutral.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        var mineOwnerIcon = L.icon({
            iconUrl: 'assets/img/mine_owner.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        var mineEnemyIcon = L.icon({
            iconUrl: 'assets/img/mine_enemy.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        var mineNeutralIcon = L.icon({
            iconUrl: 'assets/img/mine_neutral.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        var baseInConstIcon = L.icon({
            iconUrl: 'assets/img/base_construction.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
            popupAnchor:  [0, -20]
        });
        var mineInConstIcon = L.icon({
            iconUrl: 'assets/img/mine_construction.png',
            iconSize:     [37, 43],
            iconAnchor:   [19, 22],
            popupAnchor:  [0, -20]
        });
        if (typeof objectMapObj !== 'undefined') {
            objectMapObj.forEach(object => {
                x = gridToCoordinates(object.x, 0, 'x');
                y = gridToCoordinates(0, object.y, 'y');
                if (object.type == "base"){
                    if (object.owner == "player"){
                        if (object.main == 1){
                            this.map.setView([y, x], 10);
                        }
                        icon = baseOwnerIcon;
                        relation = "owned";
                    } else if (object.owner == "enemy"){
                        icon = baseEnemyIcon;
                        relation = "enemy";
                    } else {
                        icon = baseNeutralIcon;
                        relation = "neutral";
                    }
                    baseMarker = L.marker([y, x], {
                        icon: icon,
                        riseOnHover: true,
                    }).addTo(this.map);
                    console.log(object.id);
                    const baseEntity = new BaseEntity(object.id, object.ownerName, relation, object.content, object.workerSpace, object.soldierSpace, baseMarker);
                    $target = 'cul';
                    baseMarker.bindPopup("#"+object.id+" base de "+object.ownerName);
                    baseMarker.addEventListener('click', function(e) {
                        baseEntity.onClick();
                        //e.target.setIcon(baseNeutralIcon);
                        e.target._icon.className = e.target._icon.className + " selectedMarker";   
                        baseMarker.addEventListener('click', function(e) {
                        });
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            var target = (window.location.search.substr(14));
                            if (target.startsWith('base') && target[5] == object.id){
                                baseEntity.onClick();
                                if (window.location.search.includes('soldierTab')){
                                    panelInterface.soldierTab(baseEntity);
                                }
                            }
                        }                    
                    }
                } else if (object.type == "mine") {
                    if (object.owner == "player"){
                        icon = mineOwnerIcon;
                        relation = "owned";
                    } else if (object.owner == "enemy"){
                        icon = mineEnemyIcon;
                        relation = "enemy";
                    } else {
                        icon = mineNeutralIcon;
                        relation = "neutral";
                    }
                    mineMarker = L.marker([y, x], {
                        icon: icon,
                        riseOnHover: true,
                    }).addTo(this.map);
                    const mineEntity = new MineEntity(object.id, object.ownerName, relation, object.content, object.workerSpace, object.soldierSpace);
                    mineMarker.addEventListener('click', function(ev) {
                        mineEntity.onClick();
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            var target = (window.location.search.substr(14));
                            if (target.startsWith('mine') && target[5] == object.id){
                                baseEntity.onClick();
                            }
                        }                    
                    }
                } else if (object.type == "baseInConst") {
                    if (object.owner == "player"){
                    } else if (object.owner == "enemy"){
                        relation = "enemy";
                    } else {
                        relation = "neutral";
                    }
                    baseInConstMarker = L.marker([y, x], {
                        icon: baseInConstIcon,
                    }).addTo(this.map);
                    const baseInConstEntity = new BaseInConstEntity(object.ownerName, relation);
                    baseInConstMarker.addEventListener('click', function(ev) {
                        baseInConstEntity.onClick();
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            var target = (window.location.search.substr(14));
                            if (target.startsWith('mine') && target[5] == object.id){
                                baseEntity.onClick();
                            }
                        }                    
                    }
                } else if (object.type == "mineInConst") {
                    if (object.owner == "player"){
                    } else if (object.owner == "enemy"){
                        relation = "enemy";
                    } else {
                        relation = "neutral";
                    }
                    mineInConstMarker = L.marker([y, x], {
                        icon: mineInConstIcon,
                    }).addTo(this.map);
                    const mineInConstEntity = new MineInConstEntity(object.ownerName, relation);
                    mineInConstMarker.addEventListener('click', function(ev) {
                        mineInConstEntity.onClick();
                    });
                    if (relation == "owned"){
                        if (window.location.search.includes('focus')){
                            var target = (window.location.search.substr(14));
                            if (target.startsWith('mine') && target[5] == object.id){
                                mineEntity.onClick();
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
                //console.log(ore.x + ' ' + ore.y);
                //var marker = L.marker([ore.y, ore.x], {icon: minimapCursor}).addTo(map);
                var oreMarker = L.circle([ore.y, ore.x], {
                    color: '#55719e',
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

