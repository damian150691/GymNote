document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed!");

    function createHeaderMNP (dayCount, trainingDaysDiv) {
        const dayHeader = document.createElement("h3");
        dayHeader.textContent = `Day ${dayCount}`;
        trainingDaysDiv.appendChild(dayHeader);
    }

    function addEventListenerToEditButtonMNP (editButton, tableRow, addExerciseButton) {
        editButton.addEventListener("click", function (event) {
            event.preventDefault();
            //getting values from the table row using nth-child
            const tableRowCount = tableRow.querySelector("td:nth-child(1)").textContent;
            const exerciseName = tableRow.querySelector("td:nth-child(2)").textContent;
            const exerciseSets = tableRow.querySelector("td:nth-child(3)").textContent;
            const exerciseRepeats = tableRow.querySelector("td:nth-child(4)").textContent;
            const exerciseWeight = tableRow.querySelector("td:nth-child(5)").textContent;
            let exerciseInterval = tableRow.querySelector("td:nth-child(6)").textContent;
            if (exerciseInterval == "-") {
                exerciseInterval = "";
            }
            let exerciseInfo = tableRow.querySelector("td:nth-child(7)").textContent;
            if (exerciseInfo == "-") {
                exerciseInfo = "";
            }
            //change the text content into input fields with values
            tableRow.innerHTML = `
            <td class="table-row-id">${tableRowCount}</td>
            <td><input type="text" name="exerciseName[]" value="${exerciseName}" class="required" /></td>
            <td><input type="number" name="exerciseSets[]" value="${exerciseSets}" class="required" /></td>
            <td><input type="number" name="exerciseRepeats[]" value="${exerciseRepeats}" class="required" /></td>
            <td><input type="number" name="exerciseWeight[]" value="${exerciseWeight}" class="required" /></td>
            <td><input type="text" name="exerciseInterval[]" value="${exerciseInterval}" /></td>
            <td><input type="text" name="exerciseInfo[]" value="${exerciseInfo}" /></td>
            <td><button class="confirm-button">Confirm</button></td>
            `;
            //find the confirm button
            const confirmButton = tableRow.querySelector(".confirm-button");
            // Add event listener to the confirm button
            addEventListenerToConfirmButtonMNP (confirmButton, tableRow, addExerciseButton);


        });
    }

    function addEventListenerToConfirmButtonMNP (confirmButton, newRow, addExerciseButton) {
        confirmButton.addEventListener("click", function (event) {
            event.preventDefault();
            console.log("confirmButton clicked");
            //validate all of the inputs in the row that has "required" class
            const inputFields = newRow.querySelectorAll(".required");
            for (let i = 0; i < inputFields.length; i++) {
                if (inputFields[i].value == "") {
                    alert("Please fill out all required fields (marked with *)");
                    return;
                }
            }
            
            const exerciseName = newRow.querySelector("input[name='exerciseName[]']").value;
            const exerciseSets = newRow.querySelector("input[name='exerciseSets[]']").value;
            const exerciseRepeats = newRow.querySelector("input[name='exerciseRepeats[]']").value;
            const exerciseWeight = newRow.querySelector("input[name='exerciseWeight[]']").value;
            let exerciseInterval = newRow.querySelector("input[name='exerciseInterval[]']").value;
            if (exerciseInterval == "") {
                exerciseInterval = "-";
            }
            let exerciseInfo = newRow.querySelector("input[name='exerciseInfo[]']").value;
            if (exerciseInfo == "") {
                exerciseInfo = "-";
            }
            let tableRowCount = 1;
            //check if there is a row with class "table=row-id"
            const tableRowIds = newRow.closest("table").querySelectorAll(".table-row-id");
            if (tableRowIds.length > 0) {
            //get the first column value and add 1 to it
            tableRowCount = parseInt(tableRowIds[tableRowIds.length - 1].textContent) + 1;
            } 

            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.classList.add("edit-button");
            // Add event listener to the edit button
            addEventListenerToEditButtonMNP(editButton, newRow, addExerciseButton);

            // Create a table cell
            const tdEditButton = document.createElement("td");
            tdEditButton.appendChild(editButton);

            // Insert the table cell into the table row
            newRow.innerHTML = `
                <td class="table-row-id">${tableRowCount}</td>
                <td>${exerciseName}</td>
                <td>${exerciseSets}</td>
                <td>${exerciseRepeats}</td>
                <td>${exerciseWeight}</td>
                <td>${exerciseInterval}</td>
                <td>${exerciseInfo}</td>
            `;
            newRow.appendChild(tdEditButton);
            newRow.classList.remove("inputRow");
            newRow.classList.add("tableRow");
            addExerciseButton.classList.remove("hidden");
            confirmButton.remove();
            
            
            
        });
    }

    function addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody) {
        addExerciseButton.addEventListener("click", function (event) {
            event.preventDefault();

            //check if there are any rows in the nearest table that contains the class "inputRow"
            const inputRows = tableBody.querySelectorAll(".inputRow");
            


            if (inputRows.length == 0) {
                const newRow = document.createElement("tr");
                newRow.classList.add("inputRow");
                newRow.innerHTML = `
                <td></td>
                <td><input type="text" name="exerciseName[]" class="required" /></td>
                <td><input type="number" name="exerciseSets[]" class="required" /></td>
                <td><input type="number" name="exerciseRepeats[]" class="required" /></td>
                <td><input type="number" name="exerciseWeight[]" class="required" /></td>
                <td><input type="text" name="exerciseInterval[]" /></td>
                <td><input type="text" name="exerciseInfo[]" /></td>
                <td><button class="confirm-button">Confirm</button></td>
                `;
                //append the new row to the table
                tableBody.appendChild(newRow);
                confirmButton = newRow.querySelector(".confirm-button");
                addEventListenerToConfirmButtonMNP (confirmButton, newRow, addExerciseButton);
                addExerciseButton.classList.add("hidden");

            }
            

        });
    }

    function createTableMNP (trainingDaysDiv) {
        const newTable = document.createElement("table");
        newTable.classList.add("trainingTable");
        const tableHeader = document.createElement("thead");
        tableHeader.innerHTML = `
            <tr>
                <th></th>
                <th>Exercise*</th>
                <th>Sets*</th>
                <th>Repetitions*</th>
                <th>Weight[kg]*</th>
                <th>Interval[s]</th>
                <th>Comments</th>
                <th></th>
            </tr>
        `;
        const tableBody = document.createElement("tbody");
        const addExerciseButton = document.createElement("button");
        addExerciseButton.textContent = "Add Exercise";
        addExerciseButton.classList.add("addExercise");
        addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody);
        newTable.appendChild(tableHeader);
        newTable.appendChild(tableBody);
        newTable.appendChild(addExerciseButton);
        trainingDaysDiv.appendChild(newTable);
            
    }

    

    function addDayButton () {
        console.log("addDayButton function called");
        const addDayButton = document.getElementById("addDay"); //button for adding days
        const trainingDaysDiv = document.getElementById("trainingDays"); //div for adding days and exercises
        let dayCount = 0; //necessary for adding days


        addDayButton.addEventListener("click", function (event) {
            console.log("addDayButton clicked");
            dayCount++;
            if (dayCount >= 7) {
                addDayButton.classList.add("hidden");
            }

            

            createHeaderMNP (dayCount, trainingDaysDiv);

            createTableMNP (trainingDaysDiv); 
        });

        
        
        
    }



    addDayButton ();
  
});






  
