document.addEventListener("DOMContentLoaded", function () {
    console.log("myplans.js loaded!");

    //gather all of the links that have "delete" in them
    var deleteLinks = document.querySelectorAll("a[href*='/deleteplan/']");
    //add event listener to each link
    for (var i = 0; i < deleteLinks.length; i++) {
        deleteLinks[i].addEventListener("click", function (event) {
            //add alert to confirm delete
            var confirmation = confirm("Are you sure you want to delete this plan? This would also delete all of the workouts in this plan. This action cannot be undone.");
            //if user clicks cancel, stop the link from firing
            if (!confirmation) {
                event.preventDefault();
            }
        });
    }
});