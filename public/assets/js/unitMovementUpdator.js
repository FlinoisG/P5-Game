function unitMovementUpdator(target, posStart, posEnd, timeStart, timeEnd) {

    var x = setInterval(function() {
        var moveDuration = timeEnd - timeStart;
        var moveNow = (Math.floor(Date.now() / 1000)) - timeStart;
        var percent = 100*moveNow/moveDuration;
        var newPos = getPosFromDist(posStart, posEnd, percent);
        target.setLatLng(newPos); 
        console.log(percent);
        if (percent > 100) {
            clearInterval(x);
        }
    }, 1000);

}
