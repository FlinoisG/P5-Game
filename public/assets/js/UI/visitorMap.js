var Tilelayers = {
    "SpinalMap": "https://{s}.tile.thunderforest.com/spinal-map/{z}/{x}/{y}.png?apikey=213fa43d7d40475091d54ee21fa593ae",
    "Stamen": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",
    "mapBox": "https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_nolabels/{z}/{x}/{y}{r}.png",

};

const Map = {"tilemap": Tilelayers.Stamen};

Map.mainMap = {

    mapInit: function(){
        if (objectMapObj.length > 0){
            var rdm = Math.floor(Math.random() * Math.floor(objectMapObj.length));
            var rdm2 = Math.floor(Math.random() * Math.floor(objectMapObj.length));

            this.map = L.map('mapid', {
                minZoom: 1,
                maxZoom: 18,
                maxBounds: [
                    //south west
                    [32.7, -11.3269],
                    //north east
                    [61.37567, 32.39868]
                ], 
                maxBoundsViscosity: 1.0
            }).setView([gridToCoordinates(0, objectMapObj[rdm].y, "y"), gridToCoordinates(objectMapObj[rdm].x, 0, "x")], 6);   
        } else {
            this.map = L.map('mapid', {
                minZoom: 1,
                maxZoom: 18,
                maxBounds: [
                    //south west
                    [32.7, -11.3269],
                    //north east
                    [61.37567, 32.39868]
                ], 
                maxBoundsViscosity: 1.0
            }).setView([gridToCoordinates(0, 131.48, "y"), gridToCoordinates(224.83, 0, "x")], 2);  
        }
        
        this.map.dragging.disable();
        this.map.touchZoom.disable();
        this.map.doubleClickZoom.disable();
        this.map.scrollWheelZoom.disable();
        this.map.boxZoom.disable();
        this.map.keyboard.disable();
        if (this.map.tap) this.map.tap.disable();
        this.setMarkers();
        this.setOreMap();
        this.setObjectMap();
        this.setTileLayer();
        if (objectMapObj.length > 0){
            this.map.setView([gridToCoordinates(0, objectMapObj[rdm2].y, "y"), gridToCoordinates(objectMapObj[rdm2].x, 0, "x")], 6, {
                "animate": true,
                "pan": {
                "duration": 400
                }
            });
        } else {
            this.map.setView([gridToCoordinates(0, 131.48, "y"), gridToCoordinates(224.83, 0, "x")], 6, {
                "animate": true,
                "pan": {
                "duration": 400
                }
            });
        }
    },

    setMarkers: function(){
        this.baseNeutralIcon = L.icon({
            iconUrl: 'assets/img/base_neutral.png',
            iconSize:     [30, 33],
            iconAnchor:   [15, 17],
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
    },
   
    setOreMap: function(){
        if (typeof oreMapObj !== 'undefined') {
            oreMapObj.oreMap.forEach(ore => {
                var R = Math.round(ore.value * 51)+51;
                var G = Math.round(ore.value * 41)+61;
                var B = Math.round(ore.value * 197)+58;
                var oreMarker = L.circle([ore.y, ore.x], {
                    color: 'rgb('+R+', '+G+', '+B+')',
                    radius: 1500,
                    interactive: false,
                }).addTo(this.map);
                oreMarker.bindPopup("bonjour " + ore.value);
            });
        }
    },

    setObjectMap: function(){
        objectMapObj.forEach(object => {
            x = gridToCoordinates(object.x, 0, 'x');
            y = gridToCoordinates(0, object.y, 'y');
            if (object.type == "base"){
                icon = this.baseNeutralIcon;
                relation = "neutral";
                baseMarker = L.marker([y, x], {
                    icon: icon,
                    interactive: false,
                }).addTo(this.map);
            } else if (object.type == "mine") {
                icon = this.mineNeutralIcon;
                relation = "neutral";
                mineMarker = L.marker([y, x], {
                    icon: icon,
                    interactive: false,
                }).addTo(this.map);
            } else if (object.type == "baseInConst") {
                relation = "neutral";
                baseInConstMarker = L.marker([y, x], {
                    icon: this.baseInConstIcon,
                    interactive: false,
                }).addTo(this.map);
            } else if (object.type == "mineInConst") {
                relation = "neutral";
                mineInConstMarker = L.marker([y, x], {
                    icon: this.mineInConstIcon,
                    interactive: false,
                }).addTo(this.map);
            }
        });
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


Map.mainMap.mapInit();

