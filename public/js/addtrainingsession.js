document.addEventListener("DOMContentLoaded", function () {
   console.log("addtrainingsession.js loaded");


    function hideSubRows (exerciseHeader) {

        const exerciseName = exerciseHeader.firstChild.textContent;
        
        //find all rows with the class exerciseName
        var exerciseRows = document.querySelectorAll('.e' + exerciseName);
        
        //remove hidden class from all rows with the class exerciseName
        exerciseRows.forEach(function (exerciseRow) {
            exerciseRow.classList.toggle('hidden');
        });
    }

    
    function redirectToPlan() {
        // Get the previous value from the data attribute
        const previousValue = this.getAttribute('data-previous');

        // Get the current selected option
        const currentOption = this.options[this.selectedIndex];

        // Check if the previous value was the placeholder and if the current option is not disabled
        if (previousValue !== '' && !currentOption.disabled) {
            // If it's a valid change, show the alert
            if (!confirm('Are you sure you want to change the plan? All unsaved data will be lost!')) {
                // If they do not confirm, reset to previous value and return without redirecting
                this.value = previousValue;
                return;
            }
        }

        // Continue with the plan change
        const planId = this.value;
        console.log(planId);

        // Update the data-previous attribute to the new value
        this.setAttribute('data-previous', planId);


        var newPath = 'addtrainingsession/' + planId;
        window.location.pathname = newPath;
    }

    // Initialize the select element with a data attribute for the previous value
    var plansSelectElement = document.getElementById('choosePlans');
    plansSelectElement.setAttribute('data-previous', plansSelectElement.value);

    // Add event listener to the select element
    plansSelectElement.addEventListener('change', redirectToPlan);


    var daysSelectElement = document.getElementById('chooseDays');

    //check how many options are in the select element
    var optionsLength = daysSelectElement.options.length;

    if (optionsLength == 1) {
        var trainingDayDiv = document.querySelector('.ATStrainingDay');
        //remove hidden class
        trainingDayDiv.classList.remove('hidden');
    } else {
        daysSelectElement.addEventListener('change', function () {
            //get current name of the option
            var selectedDay = this.options[this.selectedIndex].text;
            
            var otherDays = document.querySelectorAll('.ATStrainingDay');

            //loop through all divs with class ATStrainingDay
            for (var i = 0; i < otherDays.length; i++) {
                //get the id of the div
                var dayId = otherDays[i].id;
                //if the id of the div is the same as the selected option, remove hidden class
                if (dayId == selectedDay) {
                    otherDays[i].classList.remove('hidden');
                } else {
                    //if the id of the div is not the same as the selected option, add hidden class
                    otherDays[i].classList.add('hidden');
                }
            }
        });
    }



    var exerciseHeaders = document.querySelectorAll('.ATSexerciseHeader');
    console.log(exerciseHeaders);
    exerciseHeaders.forEach(function(exerciseHeader) {
        console.log(exerciseHeader);
        exerciseHeader.addEventListener('click', function() {
            
            hideSubRows(exerciseHeader);
        });
    });
  
});