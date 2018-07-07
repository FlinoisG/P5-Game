function countDown(target, time) {

    var x = setInterval(function() {

        var now = Math.floor(Date.now() / 1000);
        var distance = time - now;

        var minutes = Math.floor((distance % (60 * 60)) / 60);
        var seconds = Math.floor((distance % 60));

        if (minutes < 10)  minutes = "0" + minutes;
        if (seconds < 10)  seconds = "0" + seconds;

        target.innerHTML = minutes + ":" + seconds;

        if (distance < 0) {
            clearInterval(x);
            target.innerHTML = "00:00";
        }
    }, 1000);

}
