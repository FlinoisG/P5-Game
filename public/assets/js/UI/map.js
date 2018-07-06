var Tilelayers = {
    "SpinalMap": "https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png?apikey=213fa43d7d40475091d54ee21fa593ae",
    "Stamen": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",
    "mapBox": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",

};

const Map = {"tilemap": Tilelayers.Stamen};

Map.mainMap = {

    mapInit: function(){
        this.map = L.map('mapid', {
            minZoom: 7,
            maxZoom: 18,
            maxBounds: [
                //south west
                [32.7, -11.7],
                //north east
                [61.7, 32.1]
                ], 
            maxBoundsViscosity: 1.0
        //}).setView([49, 10.5], 10);
        }).setView([gridToCoordinates(0, 131.48, "y"), gridToCoordinates(224.83, 0, "x")], 2);
        this.map.addEventListener('click', function(ev) {
            x = coordinatesToGrid(ev.latlng.lng, 0, "x");
            y = coordinatesToGrid(0, ev.latlng.lat, "y");
            //console.log('grid x: ' + Math.round(ev.latlng.lng) + ', y: ' + Math.round(ev.latlng.lat));
            console.log('grid x: ' + (x) + ', y: ' + (y));
        });

        this.map.addEventListener('mousemove', function(ev) {
            this.pos = Map.mainMap.map.getCenter();
            Map.miniMap.markerMiniMap.setLatLng(this.pos); 
        });

        
        //this.map.dragging.disable();
        this.map.touchZoom.disable();
        this.map.doubleClickZoom.disable();
        //this.map.scrollWheelZoom.disable();
        this.map.boxZoom.disable();
        //this.map.keyboard.disable();
        //if (this.map.tap) this.map.tap.disable();
        this.setOreMap();
        this.setBaseMap();
        this.setTileLayer();
        Map.miniMap.mapInit();

        console.log(gridDistance([0,0],[3,3]));

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

    setBaseMap: function(){
        if (typeof baseMapObj !== 'undefined') {
            baseMapObj.forEach(base => {
                x = gridToCoordinates(base.x, 0, 'x');
                y = gridToCoordinates(0, base.y, 'y');
                if (base.owner == "player"){
                    if (base.main == 1){
                        this.map.setView([y, x], 10);
                    }
                    color = "blue";
                    relation = "owned";
                } else if (base.owner == "enemy"){
                    color = "red";
                    relation = "enemy";
                } else {
                    color = "grey";
                    relation = "neutral";
                }
                baseMarker = L.circle([y, x], {
                    color: color,
                    radius: 5000
                }).addTo(this.map);
                if (relation != "neutral"){
                    const baseEntity = new BaseEntity(base.id, base.ownerName, relation, base.content);
                    baseMarker.addEventListener('click', function(ev) {
                        baseEntity.onClick();
                    });
                    if (window.location.search.includes('focus')){
                        var target = (window.location.search.substr(14));
                        if (target.startsWith('base') && target[5] == base.id){
                            baseEntity.onClick();
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
        this.setCursorIcon();
        this.miniMap = L.map('minimapid').setView([49, 10.5], 3);  
        this.markerMiniMap = L.marker(Map.mainMap.map.getCenter(), {icon: this.minimapCursor}).addTo(this.miniMap);
        this.setOreMap();
        this.setMinimapControls();
        this.setTileLayer();
        this.setBaseMap();
    },      

    setCursorIcon: function(){
        this.minimapCursor = L.icon({
            iconUrl: '../public/assets/img/minimapCursor.png',
            iconSize:     [40, 20], // size of the icon
            iconAnchor:   [20, 10], // point of the icon which will correspond to marker's location
            popupAnchor:  [20, 0] // point from which the popup should open relative to the iconAnchor
        });
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
                    stroke: false
                }).addTo(this.miniMap);
            });
        }
    },

    setBaseMap: function(){
        if (typeof baseMapObj !== 'undefined') {
            baseMapObj.forEach(base => {
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
        //if (map.tap) map.tap.disable();

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
                Map.miniMap.markerMiniMap.setLatLng([lat, lng]); 
            }
        });
        
        Map.miniMap.miniMap.addEventListener('mousedown', function() {
            Map.mainMap.map.setView([lat, lng])
            Map.miniMap.markerMiniMap.setLatLng([lat, lng]); 
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

