document.addEventListener("DOMContentLoaded", function () {
    console.log("scripts.js loaded!");

    function setActive() {
        var aObj = document.getElementById('nav-bar').getElementsByTagName('a');
        var currentPage = document.location.pathname;
    
        // Define your custom page mappings here
        var customMappings = [
            { from: '/plansforothers', to: '/myplans' },
            // Add more mappings as needed
            // { from: '/anotherPage', to: '/linkedPage' },
        ];
    
        for (var i = 0; i < aObj.length; i++) {
            if (currentPage.indexOf(aObj[i].pathname) >= 0) {
                aObj[i].className = 'activeMainNav';
            }
    
            // Iterate over custom mappings
            customMappings.forEach(function(mapping) {
                if (currentPage === mapping.from) {
                    // Find and activate the link for the mapped 'to' page
                    for (var j = 0; j < aObj.length; j++) {
                        if (aObj[j].pathname === mapping.to) {
                            aObj[j].className = 'activeMainNav';
                        }
                    }
                }
            });
        }
    }
    
    window.onload = setActive;

});