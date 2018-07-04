var Board = {};

Board.map = {

    //map: "",
    //pos: {"lat": 0, "lng": 0},

    mapInit: function(){
        this.map = L.map('mapid', {
            minZoom: 8,
            maxZoom: 18,
            maxBounds: [
                //south west
                [32.7, -11.7],
                //north east
                [61.7, 32.1]
                ], 
            maxBoundsViscosity: 1.0
        }).setView([49, 10.5], 10);
        this.map.addEventListener('click', function(ev) {
            x = coordinatesToGrid(ev.latlng.lng, 0, "x");
            y = coordinatesToGrid(0, ev.latlng.lat, "y");
            //console.log('grid x: ' + Math.round(ev.latlng.lng) + ', y: ' + Math.round(ev.latlng.lat));
            console.log('grid x: ' + (x) + ', y: ' + (y));
        });
        
        this.map.addEventListener('mousemove', function(ev) {
            this.pos = Board.map.map.getCenter();
            Board.miniMap.markerMiniMap.setLatLng(this.pos); 
        });
        //this.map.dragging.disable();
        this.map.touchZoom.disable();
        this.map.doubleClickZoom.disable();
        //this.map.scrollWheelZoom.disable();
        this.map.boxZoom.disable();
        //this.map.keyboard.disable();
        //if (this.map.tap) this.map.tap.disable();
        this.setOreMap();
        this.setTileLayer();
        
    },
   
    setOreMap: function(){
        if (typeof oreMapObj !== 'undefined') {
            oreMapObj.oreMap.forEach(ore => {
                var oreMarker = L.circle([ore.y, ore.x], {
                    color: 'blue',
                    radius: 500
                }).addTo(this.map);
            });
        }
    },

    setBaseMap: function(){
        if (typeof baseMapObj !== 'undefined') {
            baseMapObj.forEach(base => {
                var color;
                console.log(base);
                if (base.owner == "player"){
                    color = "green";
                } else if (base.owner == "enemy"){
                    color = "red";
                } else {
                    color = "grey";
                }
                x = gridToCoordinates(base.x, 0, 'x')
                y = gridToCoordinates(0, base.y, 'y')
                console.log([x, y]);
                var baseMarker = L.circle([y, x], {
                    color: color,
                    radius: 5000
                }).addTo(this.map);
            });
        }
    },

    setTileLayer: function(){
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(this.map);
    }

}

Board.miniMap = {

    //miniMap: "",
    //markerMiniMap: "",
    //miniMapClicked: "",

    mapInit: function(){
        this.setCursorIcon();
        this.miniMap = L.map('minimapid').setView([49, 10.5], 3);  
        this.markerMiniMap = L.marker(Board.map.map.getCenter(), {icon: this.minimapCursor}).addTo(this.miniMap);
        this.setOreMap();
        this.setMinimapControls();
        this.setTileLayer();
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
                    color: 'blue',
                    fillOpacity: 1,
                    radius: 1
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
        
        Board.miniMap.miniMap.addEventListener('mousedown', function() {
            Board.miniMap.miniMapClicked = true;
        });
        Board.miniMap.miniMap.addEventListener('mouseup', function() {
            Board.miniMap.miniMapClicked = false;
        });  
        Board.miniMap.miniMap.addEventListener('mousemove', function() {
            if (Board.miniMap.miniMapClicked) {
                Board.map.map.setView([lat, lng])
                Board.miniMap.markerMiniMap.setLatLng([lat, lng]); 
            }
        });
        
        Board.miniMap.miniMap.addEventListener('mousedown', function() {
            Board.map.map.setView([lat, lng])
            Board.miniMap.markerMiniMap.setLatLng([lat, lng]); 
        });
    },

    setTileLayer: function(){
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(this.miniMap);
    }

}

Board.map.mapInit();
Board.miniMap.mapInit();
