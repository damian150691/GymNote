document.addEventListener("DOMContentLoaded", function () {
  console.log("admin.js loaded");
  
    // setting active class for nav links
    // Get the current URL pathname
    var currentPath = window.location.pathname;

    // Get all the links within the admin-nav-list
    var navLinks = document.querySelectorAll('.admin-nav-list a');

    // Loop through the links to find the matching href
    navLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentPath) {
            // Add the "active" class to the parent <li> of the matching link
            link.parentElement.classList.add('active');
        }
    });

    if (currentPath === '/admin') {
        // Add the "active" class to the first <li> of the admin-nav-list
        navLinks[0].parentElement.classList.add('active');
    }
  
});