function countDown(target, time) {

    var x = setInterval(function() {

        var now = Math.floor(Date.now() / 1000);
        var distance = time - now;

        var minutes = Math.floor((distance % (60 * 60)) / 60);
        var seconds = Math.floor((distance % 60));

        target.innerHTML = minutes + ":" + seconds;

        if (distance < 0) {
            clearInterval(x);
            target.innerHTML = "Finition..";
        }
    }, 1000);

}
