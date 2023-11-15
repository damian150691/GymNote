document.addEventListener("DOMContentLoaded", function () {
   console.log("addtrainingsession.js loaded");

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

        // Update the data-previous attribute to the new value
        this.setAttribute('data-previous', planId);


        var newPath = 'addtrainingsession/' + planId;
        window.location.pathname = newPath;
    }

    function addEventListenertoDeleteButtons() {
        //add event listener to the delete button
        var deleteButtons = document.querySelectorAll('.deleteButton');
        deleteButtons.forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                var rowToDelete = event.target.parentNode.parentNode;
                rowToDelete.remove();
            });
        });
    }


    function addRow(rowWithButton) {
        let exerciseName = rowWithButton.previousSibling.firstChild.textContent.trim()
        let referenceId = rowWithButton.previousSibling.firstChild.getAttribute('reference_id');
        

        var newRow = document.createElement('tr');
        newRow.classList.add('ATSinputRow');
        newRow.classList.add('e' + exerciseName);

        // Helper function to create a td element with optional content and attribute
        function createCell(content = '', attribute = null) {
            var cell = document.createElement('td');
            if (content !== '') {
                if (content instanceof HTMLElement) {
                    cell.appendChild(content);
                } else {
                    cell.textContent = content;
                }
            }
            // Check if attribute is provided and set it
            if (attribute !== null && typeof attribute === 'object') {
                const attributeName = Object.keys(attribute)[0];
                const attributeValue = attribute[attributeName];
                cell.setAttribute(attributeName, attributeValue);
            }
            return cell;
        }

        // Add cells to the row with attribute for the first cell
        newRow.appendChild(createCell('(' + exerciseName + ')', { 'reference_id': referenceId }));
        newRow.appendChild(createCell()); // Empty cell
        newRow.appendChild(createCell()); // Empty cell


        // Input for reps
        var repsInput = document.createElement('input');
        repsInput.type = 'number';
        repsInput.name = 'reps_input';
        newRow.appendChild(createCell(repsInput));

        // Input for weight
        var weightInput = document.createElement('input');
        weightInput.type = 'text';
        weightInput.name = 'weight_input';
        newRow.appendChild(createCell(weightInput));

        // Leave the cell for 'rest' blank
        newRow.appendChild(createCell());

        // Input for comments
        var commentsInput = document.createElement('input');
        commentsInput.type = 'text';
        commentsInput.name = 'comments_input';
        newRow.appendChild(createCell(commentsInput));

        newRow.appendChild(createCell()); // Empty cell

        // Delete button
        var deleteButton = document.createElement('button');
        deleteButton.classList.add('smallbtn', 'deleteButton');
        deleteButton.textContent = 'Delete';
        newRow.appendChild(createCell(deleteButton));
        

        // find next row with class ATSexerciseHeader and insert new row before it. If there is none insert it at the end of the table
        let currentRow = rowWithButton;
        var nextRow = rowWithButton.nextSibling;

        while (nextRow != null && !nextRow.classList.contains('ATSexerciseHeader') && !nextRow.classList.contains('ATSsetRow')) {
            currentRow = nextRow;
            nextRow = nextRow.nextSibling;
        }

        
        currentRow.after(newRow);
        addEventListenertoDeleteButtons();
    }


    function toggleSubRows(exerciseHeader) {
        const exerciseName = exerciseHeader.firstChild.textContent.trim();
        var exerciseRows = document.querySelectorAll('.e' + exerciseName);
        let isHidden = false;
    
        exerciseRows.forEach(function(exerciseRow) {
            exerciseRow.classList.toggle('hidden');
            if (exerciseRow.classList.contains('hidden')) {
                isHidden = true;
            } else {
                isHidden = false;
            }
        });

        //check if the exerciseRow is not hidden
        if (exerciseRows.length == 0 || !isHidden) {
                
            rowWithButton = document.createElement('tr');
            rowWithButton.classList.add('rowWithButton');

            var td = document.createElement('td');
            td.setAttribute('colspan', '9');
            td.classList.add('text-center');
            var button = document.createElement('button');
            button.textContent = 'Add row';
            button.classList.add('addRowButton');
            button.classList.add('smallbtn');

            td.appendChild(button);
            rowWithButton.appendChild(td);

            button = rowWithButton.querySelector('.addRowButton');
            button.addEventListener('click', function(event) {
                event.preventDefault();

                addRow(rowWithButton);
            });

            //append new row after exerciseRow
            exerciseHeader.after(rowWithButton);
        } else { 
            var rowWithButton = exerciseHeader.nextSibling;
            rowWithButton.remove();   
        }
    
        var recordCheckbox = exerciseHeader.querySelector('.recordTrainingCheckbox');
        recordCheckbox.checked = !recordCheckbox.checked;
    }



    function saveTrainingSession() {
        var notHiddenInputRows = [];
        const inputRows = document.querySelectorAll('.ATSinputRow');
    
        inputRows.forEach(function(inputRow) {
            if (!inputRow.classList.contains('hidden')) {
                notHiddenInputRows.push(inputRow);
            }
        });
    
        // Keep only rows where at least one input has a value
        rowsToSave = notHiddenInputRows.filter(function(row) {
            const inputs = row.querySelectorAll('input');
            // Check if any input in this row has a value
            return Array.from(inputs).some(input => input.value);
        });


    
        console.log(rowsToSave);

        var plan_id = document.getElementById('choosePlans').value;
        var dataToSave = {};

        // Initializing the array for plan_id
        dataToSave["plan_id: " + plan_id] = [];

        // Assuming rowsToSave is a collection of <tr> elements
        rowsToSave.forEach(function(row) {
            var rowData = {};

            // Extracting the set_id, day_id, and reference_id from the <tr> element
            rowData.set_id = row.getAttribute('set_id');
            rowData.day_id = row.getAttribute('day_id');
            rowData.reference_id = row.getAttribute('reference_id');
            

            // Extracting the exercise name and removing brackets
            var exerciseNameCell = row.querySelector('td[reference_id="' + rowData.reference_id + '"]');
            if (exerciseNameCell) {
                var nameWithBrackets = exerciseNameCell.textContent.trim();
                rowData.lp = nameWithBrackets.replace(/[\(\)]/g, ''); // Removing brackets
            }

            rowData.sub_id = row.getAttribute('sub_id');

            // Extracting the values from input fields
            var repsInput = row.querySelector('input[name="reps_input"]');
            rowData.reps = repsInput ? repsInput.value : null;

            var weightInput = row.querySelector('input[name="weight_input"]');
            rowData.weight = weightInput ? weightInput.value : null;

            var commentsInput = row.querySelector('input[name="comments_input"]');
            rowData.comments = commentsInput ? commentsInput.value : null;

            // Adding the extracted data as a sub-element under plan_id
            dataToSave["plan_id: " + plan_id].push(rowData);
        });

        var jsonData = JSON.stringify(dataToSave);
                console.log(jsonData);


        // Send the JSON string to the PHP script
        fetch('/savetrainingsession', {
            method: 'POST',
            body: jsonData,
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            // Handle the response from the PHP script
            console.log(data);
            if (data.message) {
                alert('Session saved successfully!');
            } else {
                alert('Something went wrong!');
            }

        })
        .catch(error => {
            console.error(error);
        });


    }





    /*--------- Redirect to Plan ---------*/
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
    /*--------- End of Redirect to Plan ---------*/

    

    /*--------- Rows handling ---------*/
    var exerciseHeaders = document.querySelectorAll('.ATSexerciseHeader');
    exerciseHeaders.forEach(function(exerciseHeader) {
        exerciseHeader.addEventListener('click', function(event) {
            // If the clicked element is the checkbox, toggle its state
            if (event.target.classList.contains('recordTrainingCheckbox')) {
                event.target.checked = !event.target.checked;
            }
            toggleSubRows(exerciseHeader);
            const exerciseName = exerciseHeader.firstChild.textContent.trim();

            const parentTable = exerciseHeader.parentNode.parentNode;
            console.log(parentTable);

            var exerciseRows = parentTable.querySelectorAll('.e' + exerciseName);
            console.log(exerciseRows);
            if (exerciseRows.length == 0) {     
                addRow(exerciseHeader.nextElementSibling);
            }
            
        });
    });
    /*--------- End of Rows handling ---------*/



    /*--------- Save Training Session ---------*/

    var saveButton = document.getElementById('saveTrainingSession');
    saveButton.addEventListener('click', function(event) {
        event.preventDefault();
        saveTrainingSession();
    });

    /*--------- End of Save Training Session ---------*/


   

});
