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

            const dayHeader = deleteButton.previousElementSibling.previousElementSibling;
            const dayHeaderH3 = dayHeader.textContent;
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

    function addEventListenerToAddSetButtonMNP (addSetButton, trainingDayDiv) {
        addSetButton.addEventListener("click", function (event) {
            event.preventDefault();
            const table = trainingDayDiv.querySelector(".trainingTable");
            const tableBody = table.querySelector("tbody");
            addSet (tableBody);
        });
    }

    function createHeaderMNP (dayCount, trainingDaysDiv) {
        const trainingDayDiv = trainingDaysDiv.closest(".trainingDay");
        
        const dayHeader = document.createElement("h3");
        dayHeader.textContent = `Day ${dayCount}`;

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Delete Day";
        deleteButton.classList.add("deleteDayBtn");

        addEventListenerToDeleteDayButtonMNP (deleteButton);

        const addSetButton = document.createElement("button");
        addSetButton.textContent = "Add Set";
        addSetButton.classList.add("addSetBtn");

        addEventListenerToAddSetButtonMNP (addSetButton, trainingDayDiv);

        trainingDaysDiv.appendChild(dayHeader);
        trainingDaysDiv.appendChild(addSetButton);
        trainingDaysDiv.appendChild(deleteButton);
    }

    function addEventListenerToEditButtonMNP (editButton, tableRow, addExerciseButton) {
        editButton.addEventListener("click", function (event) {
            event.preventDefault();
            //getting values from the table row using nth-child
            const tableRowCount = tableRow.querySelector("td:nth-child(1)").textContent;
            let exerciseName = tableRow.querySelector("td:nth-child(2)").textContent;
            if (exerciseName == "-") {
                exerciseName = "";
            }
            let exerciseSets = tableRow.querySelector("td:nth-child(3)").textContent;
            if (exerciseSets == "-") { 
                exerciseSets = "";
            }
            let exerciseRepeats = tableRow.querySelector("td:nth-child(4)").textContent;
            if (exerciseRepeats == "-") {
                exerciseRepeats = "";
            }
            let exerciseWeight = tableRow.querySelector("td:nth-child(5)").textContent;
            if (exerciseWeight == "-") {
                exerciseWeight = "";
            }
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
            <td><input type="text" name="exerciseName[]" value="${exerciseName}"/></td>
            <td><input type="text" name="exerciseSets[]" value="${exerciseSets}"/></td>
            <td><input type="text" name="exerciseRepeats[]" value="${exerciseRepeats}"/></td>
            <td><input type="text" name="exerciseWeight[]" value="${exerciseWeight}"/></td>
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

    
    

    function updateFirstColumnMNP (addExerciseButton, newRow) {
        //counting rows in the table
        let tableRowCount = 1;
        const currentSetRowId = addExerciseButton.parentElement.nextElementSibling.textContent;
        const currentSetNumber = currentSetRowId.match(/\d+/g).toString();
        const currentSetRow = addExerciseButton.closest(".setRow");
        const nextSetRowNumber = parseInt(currentSetNumber) + 1; 
        const nextSetRowClass = "set" + nextSetRowNumber;
        const nextSetRow = addExerciseButton.closest(".setRow").parentElement.querySelector(`.${nextSetRowClass}`);
        
        
        if (nextSetRow) {
            // count how many rows are between currentSetRow and nextSetRow
            let rowsBetween = currentSetRow.nextElementSibling;
            let rowsBetweenCount = 0;
            while (rowsBetween != nextSetRow) {
                rowsBetweenCount++;
                let currentFirstColumn = rowsBetween.querySelector("td:nth-child(1)");
                rowsBetween = rowsBetween.nextElementSibling;
                let letter = String.fromCharCode(96 + rowsBetweenCount);
                tableRowCount = currentSetNumber + letter.toUpperCase();
                currentFirstColumn.textContent = tableRowCount;
            }
            
            
        } else {
            // count how many rows are after currentSetRow
            let rowsAfter = currentSetRow.nextElementSibling;
            let rowsAfterCount = 0;
            while (rowsAfter != null) {
                rowsAfterCount++;
                let currentFirstColumn = rowsAfter.querySelector("td:nth-child(1)");
                rowsAfter = rowsAfter.nextElementSibling;
                let letter = String.fromCharCode(96 + rowsAfterCount);
                tableRowCount = currentSetNumber + letter.toUpperCase();
                currentFirstColumn.textContent = tableRowCount;
            }
            
        }
        
    }

    function addEventListenerToDeleteButtonMNP (deleteButton, newRow, addExerciseButton) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();

            //add alert with the option to cancel the deletion
            const confirmDelete = confirm("Are you sure you want to delete this exercise?");
            if (!confirmDelete) {
                return;
            }
            
            let currentRow = newRow;
            let nextRow = currentRow.nextElementSibling;
            let previousRow = currentRow.previousElementSibling;

            if (nextRow != null && nextRow.classList.contains("setRow")) {
                nextRow = true;
            }
            if (previousRow != null && previousRow.classList.contains("setRow")) {
                previousRow = true;
            }
            
            currentRow = currentRow.parentElement;
            let updateRow = currentRow.querySelector(".setRow").nextElementSibling;
            
            newRow.remove();
            

            if ((nextRow==true || nextRow == null) && previousRow==true) {
            } else {
                updateFirstColumnMNP (addExerciseButton, updateRow);
                
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

            
            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.classList.add("edit-button");
            // Add event listener to the edit button
            addEventListenerToEditButtonMNP(editButton, newRow, addExerciseButton);

        
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-button");
            // Add event listener to the delete button
            addEventListenerToDeleteButtonMNP (deleteButton, newRow, addExerciseButton);

            // Create a table cell
            const tdButtons = document.createElement("td");
            tdButtons.appendChild(editButton);
            tdButtons.appendChild(deleteButton);


            // Insert the table cell into the table row
            newRow.innerHTML = `
                <td class="table-row-id"></td>
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
            updateFirstColumnMNP (addExerciseButton, newRow);
            
            
            
        });
    }

    function addEventListenerToAddExerciseButtonMNP(addExerciseButton, tableBody) {
        addExerciseButton.addEventListener("click", function (event) {
            event.preventDefault();
    
            const inputRows = tableBody.querySelectorAll(".inputRow");
            const parentRow = addExerciseButton.closest(".setRow");
    
            if (inputRows.length === 0) {
                const newRow = document.createElement("tr");
                newRow.classList.add("inputRow");
                newRow.innerHTML = `
                    <td></td>
                    <td><input type="text" name="exerciseName[]"/></td>
                    <td><input type="text" name="exerciseSets[]"/></td>
                    <td><input type="text" name="exerciseRepeats[]"/></td>
                    <td><input type="text" name="exerciseWeight[]"/></td>
                    <td><input type="text" name="exerciseInterval[]"/></td>
                    <td><input type="text" name="exerciseInfo[]"/></td>
                    <td><button class="confirm-button">Confirm</button></td>
                `;
    
                const setID = addExerciseButton.closest(".setRow").classList[2];
                const setNumber = setID.match(/\d+/g).toString();
                console.log(setNumber);
    
                const confirmButton = newRow.querySelector(".confirm-button");
                addEventListenerToConfirmButtonMNP(confirmButton, newRow, addExerciseButton);
    
                // Find the next setRow after the current parentRow
                let nextRowID = parseInt(setNumber) + 1;
                let nextSetRow = tableBody.querySelector(`.set${nextRowID}`);
                
                while (nextSetRow && !nextSetRow.classList.contains("setRow")) {
                    nextSetRow = nextSetRow.nextElementSibling;
                }
    
                if (nextSetRow) {
                    // Insert the new row before the next setRow
                    tableBody.insertBefore(newRow, nextSetRow);
                } else {
                    // If there's no next setRow, simply append it to the tableBody
                    tableBody.appendChild(newRow);
                }
            } else {
                alert("Please confirm the exercise you are currently editing before adding a new one.");
            }
        });
    }
    

    function addEventListenerToDeleteButtonInSetRow (deleteButton, setRow) {
        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();
            //add alert with the option to cancel the deletion
            const confirmDelete = confirm("Are you sure you want to delete this set and all of the exercises in it?");
            if (!confirmDelete) {
                return;
            }

            

            let rowstoDelete = [];

            let currentRow = setRow;
            let nextSetRow = setRow.nextElementSibling;
            
            while (nextSetRow != null && (nextSetRow.classList.contains("tableRow") || nextSetRow.classList.contains("inputRow")) ) {
                
                currentRow = nextSetRow;
                
                rowstoDelete.push(currentRow);
                nextSetRow = currentRow.nextElementSibling;

                
            }
            rowstoDelete.forEach(row => {
                row.remove();
            });
            setRow.remove();
        });
    }

    function addEventListenerToConfirmButtonInSetRow (confirmButton, setRow) {
        confirmButton.addEventListener("click", function (event) {
            event.preventDefault();

            let setName = setRow.querySelector("input[name='setName[]']").value;
            if (setName == "") {
                setName = "-";
            }
            let setInterval = setRow.querySelector("input[name='setInterval[]']").value;
            if (setInterval == "") {
                setInterval = "-";
            }
            let setInfo = setRow.querySelector("input[name='setInfo[]']").value;
            if (setInfo == "") {
                setInfo = "-";
            }


            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.classList.add("edit-button");
            // Add event listener to the edit button
            //addEventListenerToEditButtonInSetRow(editButton, setRow);

        
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-button");
            // Add event listener to the delete button
            addEventListenerToDeleteButtonInSetRow (deleteButton, setRow);

            // Create a table cell
            const tdButtons = document.createElement("td");
            tdButtons.appendChild(editButton);
            tdButtons.appendChild(deleteButton);


            // Insert the table cell into the table row
            setRow.innerHTML = `
                <td colspan="5">${setName}</td>
                <td>${setInterval}</td>
                <td>${setInfo}</td>
            `;
            setRow.appendChild(tdButtons);
            setRow.classList.remove("inputSetRow");
            setRow.classList.add("setRow");
            
            confirmButton.remove();

        });
    }

    function addSet (tableBody) {
        
        const setRow = document.createElement("tr");
        const setRowsCount = tableBody.querySelectorAll(".setRow").length + 1;
        setRow.classList.add("inputSetRow");
        setRow.classList.add("setRow");
        setRow.classList.add(`set${setRowsCount}`);        
        setRow.innerHTML = `
            <td></td>
            <td></td>
            <td colspan="3">Set ${setRowsCount}</td>
            <td>60</td>
            <td></td>
            <td>
                <button class="edit-button">Edit</button>
                <button class="delete-button">Delete</button>
            </td>
        `;

        //const confirmButton = setRow.querySelector(".confirm-button");
        //addEventListenerToConfirmButtonInSetRow (confirmButton, setRow);
        tableBody.appendChild(setRow);

        let button = addExerciseButton (tableBody);
        
        buttonPlace = setRow.querySelector("td:nth-child(2)");
        buttonPlace.appendChild(button);

        return tableBody;
    }

    function addExerciseButton (tableBody) {
        const addExerciseButton = document.createElement("button");
        addExerciseButton.textContent = "Add Exercise";
        addExerciseButton.classList.add("addExercise");

        addEventListenerToAddExerciseButtonMNP (addExerciseButton, tableBody);

        return addExerciseButton;
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

        addSet (tableBody, dayCount);

        newTable.appendChild(tableHeader);
        newTable.appendChild(tableBody);

        buttonRow = addExerciseButton (tableBody);
        
        
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






  
