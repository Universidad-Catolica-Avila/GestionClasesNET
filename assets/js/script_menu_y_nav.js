window.addEventListener('load', function() {

    const header = document.getElementById('header');
    const body = document.body;
    const navBar = document.getElementById('navbar');

    document.getElementById("menu-hamburguesa").addEventListener("click", function() { 
        if (navBar.style.opacity === "0") {
            navBar.style.opacity = "100";
            navBar.style.pointerEvents = "auto";
            navBar.style.width = "20%";
            header.style.width = "80%";
        } else {
            navBar.style.opacity = "0";
            navBar.style.pointerEvents = "none";
            navBar.style.width = "0";
            header.style.width = "100%";
        }
    });
    
    document.getElementById("boton-pantalla-completa").addEventListener("click", function() {
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (body.requestFullscreen) {
                body.requestFullscreen();
            } if (body.mozRequestFullScreen) { 
                body.mozRequestFullScreen();
            } else if (body.webkitRequestFullscreen) { 
                body.webkitRequestFullscreen();
            } else if (body.msRequestFullscreen) {
                body.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) { 
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });
});
