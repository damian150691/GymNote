document.addEventListener("DOMContentLoaded", function () {
   console.log("exercises.js loaded");

   
    // Wait for the DOM to fully load

    // Add click event to all LI elements that have a submenu
    var hasSubmenu = document.querySelectorAll('.category');
    hasSubmenu.forEach(function(li) {
        li.addEventListener('click', function() {
            // Get the next sibling UL and toggle the 'display' property
            var submenu = this.querySelector('.exercises');
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                // If you want a sliding effect, you can use slideDown helper function (see below)
                slideDown(submenu);
            } else {
                submenu.style.display = 'none';
                // If you want a sliding effect, you can use slideUp helper function (see below)
                slideUp(submenu);
            }
            document.querySelectorAll('.category').forEach(function(submenu) {
                submenu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
        });
    });

    


    // Slide down effect
    function slideUp(el) {
        var height = el.offsetHeight; // Get current height
        el.style.transition = 'height 0.4s'; // Set transition effect
        el.style.height = height + 'px'; // Set initial height
    
        // Begin transition to height 0
        setTimeout(function() {
            el.style.height = '0px';
        }, 10); // Start the animation shortly after
    
        // After the transition is complete, hide the element
        setTimeout(function() {
            el.style.display = 'none';
            el.style.removeProperty('height'); // Remove the height property to clean up inline styles
            el.style.removeProperty('transition'); // Remove the transition property to clean up inline styles
        }, 410); // 400ms for the transition, plus a little buffer
    }
    
    // You also need to ensure the initial style is set for the slide down to work consistently
    function slideDown(el) {
        var height = el.scrollHeight + 'px'; // Get the full height
        el.style.removeProperty('display'); // Remove the display property so the element is visible
        var display = window.getComputedStyle(el).display;
        if(display === 'none') display = 'block';
    
        el.style.display = display;
        el.style.height = '0px'; // Set initial height to 0
    
        // Begin the transition to the full height
        setTimeout(function() {
            el.style.transition = 'height 0.4s'; // Set transition effect
            el.style.height = height;
        }, 10);
    
        // After the transition is complete, remove the inline height so the content can resize naturally
        setTimeout(function() {
            el.style.removeProperty('height'); // Clean up inline style
            el.style.removeProperty('transition'); // Clean up inline style
        }, 410);
    }
  
});