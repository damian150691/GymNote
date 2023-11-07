document.addEventListener("DOMContentLoaded", function () {
    console.log("scripts.js loaded!");

    function setActive() {
        aObj = document.getElementById('nav-bar').getElementsByTagName('a');
        for (i = 0; i < aObj.length; i++) {
            if (document.location.href.indexOf(aObj[i].href) >= 0) {
                aObj[i].className = 'activeMainNav';
            }
        }
    }

    window.onload = setActive;

});