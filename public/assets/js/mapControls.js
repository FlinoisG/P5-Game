var drag = false;
var cancel = false;

document.addEventListener("mousedown", function(){
    drag = false; 
    cancel = true;   
}, false);

document.addEventListener("mousemove", function(){
    if (cancel){
        cancel = false;
    } else {
        drag = true; 
    }    
}, false);

document.addEventListener('mouseup', function(ev) {
    if (ev.target.id == "mapid" && !drag){
        panelInterface.unSelect();
    }    
});

