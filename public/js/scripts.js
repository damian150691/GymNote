document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed!");
  var dayCount = 0;

    function updateDayHeadersMNP () {
        //update all of the day headers so that they are in order
        const dayHeaders = document.querySelectorAll("#trainingDays h3");
        for (let i = 0; i < dayHeaders.length; i++) {
            dayHeaders[i].textContent = `Day ${i + 1}`;
        }
        //check if there are less than 7 days, if so, show the add day button
        dayCount = dayHeaders.length;
        if (dayCount < 7) {
            const addDayButton = document.getElementById("addDay");
            addDayButton.classList.remove("hidden");
        }
        //update all of the tables id's
        const tables = document.querySelectorAll(".trainingTable");
        for (let i = 0; i < tables.length; i++) {
            tables[i].id = `MNP${i + 1}`;
        }
    }

    function addEventListenerToDeleteDayButtonMNP (deleteButton) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();
            //add alert with the option to cancel the deletion
            const confirmDelete = confirm("Are you sure you want to delete this day? All of the exercises will be deleted as well.");
            if (!confirmDelete) {
                return;
            }

            const dayHeader = deleteButton.previousElementSibling;
            const dayHeaderH3 = deleteButton.previousElementSibling.textContent;
            const id = dayHeaderH3.match(/\d+/g);
            if (id) {
                id.forEach(number => {
                    const table = document.getElementById(`MNP${number}`); //table that is being deleted
                    dayHeader.remove();
                    table.remove();
                    deleteButton.remove();

                    //call function to update all of the <h3> elements so that they are in order
                    updateDayHeadersMNP ();
                });
            }

            
            //get the add day button
        });
    }

    function createHeaderMNP (dayCount, trainingDaysDiv) {
        const dayHeader = document.createElement("h3");
        dayHeader.textContent = `Day ${dayCount}`;
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Delete Day";
        deleteButton.classList.add("deleteDayBtn");
        addEventListenerToDeleteDayButtonMNP (deleteButton);
        trainingDaysDiv.appendChild(dayHeader);
        trainingDaysDiv.appendChild(deleteButton);
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

    function updateFirstColumnMNP (dayCount) {

        const table = document.getElementById(`MNP${dayCount}`); //table that is being updated
        //check if table is not empty
        if (table !== null) {
            const tableRows = table.querySelectorAll("tbody tr"); //all of the table rows in the table
            for (let i = 0; i < tableRows.length; i++) {
                tableRows[i].querySelector("td:nth-child(1)").textContent = i + 1;
            }
        }
    }

    function addEventListenerToDeleteButtonMNP (deleteButton, tableRow, addExerciseButton, dayCount) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();
            tableRow.remove();
            //call to function that updates the first column in a table, so that the numbers are in order
            updateFirstColumnMNP (dayCount);
        });

        
        

    }

    function addEventListenerToConfirmButtonMNP (confirmButton, newRow, addExerciseButton, dayCount) {
        confirmButton.addEventListener("click", function (event) {
            event.preventDefault();
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

        
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-button");
            // Add event listener to the delete button
            addEventListenerToDeleteButtonMNP (deleteButton, newRow, addExerciseButton, dayCount);

            // Create a table cell
            const tdButtons = document.createElement("td");
            tdButtons.appendChild(editButton);
            tdButtons.appendChild(deleteButton);


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
            newRow.appendChild(tdButtons);
            newRow.classList.remove("inputRow");
            newRow.classList.add("tableRow");
            addExerciseButton.classList.remove("hidden");
            confirmButton.remove();
            
            
            
        });
    }

    function addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody, dayCount) {
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
                addEventListenerToConfirmButtonMNP (confirmButton, newRow, addExerciseButton, dayCount);
                addExerciseButton.classList.add("hidden");

            }
            

        });
    }

    function createTableMNP (trainingDayDiv, dayCount) {
        const newTable = document.createElement("table");
        //add id (MPNdayCount) to the table so that it can be found later
        newTable.id = `MNP${dayCount}`;
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

        // wrap button into tfoot element
        const tfoot = document.createElement("tfoot");
        const tfootRow = document.createElement("tr");
        const tfootCell = document.createElement("td");
        //add style to the tfoot cell - text align center
        tfootCell.classList.add("tfootAddExercise");
        tfootCell.colSpan = 8;
        tfootCell.appendChild(addExerciseButton);
        tfootRow.appendChild(tfootCell);
        tfoot.appendChild(tfootRow);

        
        addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody, dayCount);
        newTable.appendChild(tableHeader);
        newTable.appendChild(tableBody);
        newTable.appendChild(tfoot);
        trainingDayDiv.appendChild(newTable);
            
    }

    

    function addDayButton () {
        const addDayButton = document.getElementById("addDay"); //button for adding days
        const trainingDaysDiv = document.getElementById("trainingDays"); //div for adding days and exercises
        
        

        if (!addDayButton) {
            return;
        }

        addDayButton.addEventListener("click", function (event) {
            dayCount++;
            if (dayCount >= 7) {
                addDayButton.classList.add("hidden");
                
            }
            const trainingDayDiv = document.createElement("div"); //div for adding days and exercises
            
            //add class to the div with daycount
            trainingDayDiv.classList.add(`day${dayCount}`, "trainingDay");

            trainingDaysDiv.appendChild(trainingDayDiv);

            createHeaderMNP (dayCount, trainingDayDiv);

            createTableMNP (trainingDayDiv, dayCount);
        });

        
        
        
    }

    function savePlanButton() {
        const savePlanBtn = document.getElementById("savePlan");

        if (!savePlanBtn) {
            return;
        }

        savePlanBtn.addEventListener("click", function (event) {
            event.preventDefault();
            var tableData = {};
            var allTables = document.querySelectorAll(".trainingTable");
            allTables.forEach((table, index) => {
                var tableRows = table.querySelectorAll("tbody .tableRow");
                var tableRowsData = [];
                tableRows.forEach(function (row) {
                    var rowData = {
                        No: row.querySelector("td:nth-child(1)").textContent,
                        Exercise: row.querySelector("td:nth-child(2)").textContent,
                        Sets: row.querySelector("td:nth-child(3)").textContent,
                        Repetitions: row.querySelector("td:nth-child(4)").textContent,
                        Weight: row.querySelector("td:nth-child(5)").textContent,
                        Interval: row.querySelector("td:nth-child(6)").textContent,
                        Comments: row.querySelector("td:nth-child(7)").textContent
                    };
                    tableRowsData.push(rowData);
                });
                tableData["Day" + (index + 1)] = tableRowsData;
            });
    
            // Convert the tableData object to a JSON string
            var jsonData = JSON.stringify(tableData);

            console.log(jsonData);

            // Send the JSON string to the PHP script
            fetch('/saveplan', {
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

                // If you want to display the response data on the page, you can do it here.
            })
            .catch(error => {
                console.error(error);
            });

            // redirecting user to /myplans 
            window.location.replace("/myplans");
            window.location.href = "/myplans";

        });
    }


    function getPlans () {  
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/getplans', true);
    }


    addDayButton ();
    savePlanButton ();

  
});






  
