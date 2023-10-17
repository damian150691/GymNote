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
        const dayDivs = document.querySelectorAll(".trainingDay");
        for (let i = 0; i < tables.length; i++) {
            tables[i].id = `MNP${i + 1}`;
            dayDivs[i].id = `day${i + 1}`;
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
                    const trainingDayDiv = document.getElementById(`day${number}`); //div that is being deleted
                    const table = document.getElementById(`MNP${number}`); //table that is being deleted
                    dayHeader.remove();
                    table.remove();
                    deleteButton.remove();
                    trainingDayDiv.remove();

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

    function addEventListenerToDeleteButtonMNP (deleteButton, newRow) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();

            const tableRows = newRow.closest("table");
            //get the id of the table
            const tableId = tableRows.id;
            
            newRow.remove();
            
            const tableRowsCount = tableRows.querySelectorAll(".tableRow").length;
            const setRowsCount = tableRows.querySelectorAll(".setRow").length;

            for (let i = 0; i < tableRowsCount; i++) {
                const tableRow = tableRows.querySelectorAll(".tableRow")[i];
                const letter = String.fromCharCode(97 + i);
                tableRow.querySelector("td:nth-child(1)").textContent = setRowsCount + letter.toUpperCase();
                
            }
        });

        
        

    }

    function addEventListenerToConfirmButtonMNP (confirmButton, newRow, addExerciseButton) {
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
            
            let exerciseName = newRow.querySelector("input[name='exerciseName[]']").value;
            if (exerciseName == "") {
                exerciseName = "-";
            }
            let exerciseSets = newRow.querySelector("input[name='exerciseSets[]']").value;
            if (exerciseSets == "") {
                exerciseSets = "-";
            }
            let exerciseRepeats = newRow.querySelector("input[name='exerciseRepeats[]']").value;
            if (exerciseRepeats == "") {
                exerciseRepeats = "-";
            }
            let exerciseWeight = newRow.querySelector("input[name='exerciseWeight[]']").value;
            if (exerciseWeight == "") {
                exerciseWeight = "-";
            }
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
            
            //check how many rows are there in nearest table with class setRow
            const setRows = newRow.closest("table").querySelectorAll(".setRow");
                
            // tableRowCount = setRows.length + letter (for example 1a, 1b, 1c)
            const letter = String.fromCharCode(97 + tableRowIds.length);
            tableRowCount = setRows.length + letter.toUpperCase();
                
            

            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.classList.add("edit-button");
            // Add event listener to the edit button
            addEventListenerToEditButtonMNP(editButton, newRow, addExerciseButton);

        
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-button");
            // Add event listener to the delete button
            addEventListenerToDeleteButtonMNP (deleteButton, newRow);

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
                <td><input type="text" name="exerciseName[]" /></td>
                <td><input type="text" name="exerciseSets[]" /></td>
                <td><input type="text" name="exerciseRepeats[]" /></td>
                <td><input type="text" name="exerciseWeight[]" /></td>
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

    function addSet (tableBody) {
        const setRow = document.createElement("tr");
        setRow.classList.add("setRow");
        setRow.innerHTML = `
            <td colspan="5"><input type"text" name="setName[]" value="Set"></td>
            <td><input type"text" name="setInterval[]" value="60"></td>
            <td><input type"text" name="setInfo[]" value=""></td>
            <td>
                <button class="confirm-button">Confirm</button>
            </td>
        `;
        tableBody.appendChild(setRow);
        return tableBody;
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
                <th>Exercise</th>
                <th>Sets</th>
                <th>Repetitions</th>
                <th>Weight[kg]</th>
                <th>Rest[s]</th>
                <th>Comments</th>
                <th></th>
            </tr>
        `;
        const tableBody = document.createElement("tbody");

        addSet (tableBody);

        const addExerciseButton = document.createElement("button");
        addExerciseButton.textContent = "Add Exercise";
        addExerciseButton.classList.add("addExercise");

        // wrap button into tfoot element
        const tfoot = document.createElement("tfoot");
        const tfootRow = document.createElement("tr");
        const tfootCell = document.createElement("td");
        
        tfootCell.classList.add("tfootAddExercise");
        tfootCell.colSpan = 8;
        tfootCell.appendChild(addExerciseButton);
        tfootRow.appendChild(tfootCell);
        tfoot.appendChild(tfootRow);

        
        addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody);
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
            trainingDayDiv.id = `day${dayCount}`;
            trainingDayDiv.classList.add("trainingDay");

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


    addDayButton ();
    savePlanButton ();

  
});






  
