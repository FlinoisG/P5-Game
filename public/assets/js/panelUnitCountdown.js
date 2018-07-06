//var el = document.getElementById("timer"),
  mins = 2,
  secs = 0;

function countDown() {
  if (secs || mins) {
    setTimeout(countDown, 100); // Should be 1000, but I'm impatient
  }
  el.innerHTML = mins + ":" + (secs.toString().length < 2 ? "0" + secs : secs); // Pad number
  secs -= 1;
  if (secs < 0) {
    mins -= 1;
    secs = 59;
  }
}

//countDown();

console.log(document.getElementsByClassName("panelUnitTimer"));