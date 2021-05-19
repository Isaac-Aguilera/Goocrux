/*
    Muestra la preview de los videos.
*/
function bigImg(x) {
    x.autoplay = true;
    x.preload = "auto";
    if (x.readyState == 4) {
        x.play();
    }
}

function normalImg(x) {
    x.autoplay = false;
    if (x.readyState == 4) {
        x.pause();
        var v = x.src
        x.src = "";
        x.src = v;
    }
}