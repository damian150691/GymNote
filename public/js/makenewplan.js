document.addEventListener("DOMContentLoaded", function () {
  const addDayButton = document.getElementById("addDay"); //button for adding days
  const trainingDaysDiv = document.getElementById("trainingDays"); //div for adding days and exercises
  let dayCount = 0; //necessary for adding days
  let originalRow1 = null; //necessary for merging cells

  // Adding Days and Exercises
  addDayButton.addEventListener("click", function (event) {
    let tableRowCount = 1; // Initialize tableRowCount variable
    const dayHeader = document.createElement("h3");
    dayCount++;
    // Create a new H3 element for the day
    dayHeader.textContent = `Day ${dayCount}`;

    // Append the new H3 element to the trainingDays div
    trainingDaysDiv.appendChild(dayHeader);

    

    // Create a new table for the day
    const newTable = document.createElement("table");
    newTable.classList.add("trainingTable");

    // Add the table header
    const tableHeader = document.createElement("thead");
    tableHeader.innerHTML = `
      <tr>
        <th></th>
        <th>Exercise</th>
        <th>Sets</th>
        <th>Repetitions</th>
        <th>Weight[kg]</th>
        <th>Interval[s]</th>
        <th>Comments</th>
        <th></th>
      </tr>
    `;
  

    // Add the table body
    const tableBody = document.createElement("tbody");

    // Add Exercise button for this table
    const addExerciseButton = document.createElement("button");
    addExerciseButton.textContent = "Add Exercise";
    addExerciseButton.classList.add("addExercise");

    addExerciseButton.addEventListener("click", function (event) {
      // Prevent the default form submission behavior
      event.preventDefault();

      // Create a new table row for the exercise
      const newRow = document.createElement("tr");
      

      // Define the HTML content for the new row
      newRow.innerHTML = `
        <td></td>
        <td><input type="text" name="exerciseName[]" /></td>
        <td><input type="number" name="exerciseSets[]" /></td>
        <td><input type="number" name="exerciseRepeats[]" /></td>
        <td><input type="number" name="exerciseWeight[]" /></td>
        <td><input type="text" name="exerciseInterval[]" /></td>
        <td><input type="text" name="exerciseInfo[]" /></td>
        <td><button class="confirm-button">Confirm</button></td>
      `;

      // Append the new row to the table
      tableBody.appendChild(newRow);

      // checking how many rows are in the first column of the table
      let rowCountInFirstColumn = document.querySelectorAll(".table-row-id").length;
      tableRowCount = rowCountInFirstColumn + 1;


      // Add event listener to the confirm button
      const confirmButton = newRow.querySelector(".confirm-button");

      confirmButton.addEventListener("click", function () {
        // Replace input fields with plain texts
        const exerciseName = newRow.querySelector("input[name='exerciseName[]']").value;
        const exerciseSets = newRow.querySelector("input[name='exerciseSets[]']").value;
        const exerciseRepeats = newRow.querySelector("input[name='exerciseRepeats[]']").value;
        const exerciseWeight = newRow.querySelector("input[name='exerciseWeight[]']").value;
        const exerciseInterval = newRow.querySelector("input[name='exerciseInterval[]']").value;
        const exerciseInfo = newRow.querySelector("input[name='exerciseInfo[]']").value;

        
        const editButton = document.createElement("button");
        editButton.textContent = "Edit";
        editButton.classList.add("edit-button");

        // Add event listener to the edit button
        editButton.addEventListener("click", function (event) {
          event.preventDefault();
          let newConfirmButton = confirmButton;
          //find the closest row
          let closestRow = this.closest("tr"); 
          //map row values to variables
          let exerciseName = closestRow.querySelector("td:nth-child(2)").textContent;
          let exerciseSets = closestRow.querySelector("td:nth-child(3)").textContent;
          let exerciseRepeats = closestRow.querySelector("td:nth-child(4)").textContent;
          let exerciseWeight = closestRow.querySelector("td:nth-child(5)").textContent;
          let exerciseInterval = closestRow.querySelector("td:nth-child(6)").textContent;
          let exerciseInfo = closestRow.querySelector("td:nth-child(7)").textContent;


          //change the text content into input fields with values
          closestRow.innerHTML = `
          <td></td>
          <td><input type="text" name="exerciseName[]" value="${exerciseName}" /></td>
          <td><input type="number" name="exerciseSets[]" value="${exerciseSets}" /></td>
          <td><input type="number" name="exerciseRepeats[]" value="${exerciseRepeats}" /></td>
          <td><input type="number" name="exerciseWeight[]" value="${exerciseWeight}" /></td>
          <td><input type="text" name="exerciseInterval[]" value="${exerciseInterval}" /></td>
          <td><input type="text" name="exerciseInfo[]" value="${exerciseInfo}" /></td>
          `;
         
        });

        // Create a new <td> element to hold the button
        const buttonCell = document.createElement("td");
        buttonCell.appendChild(editButton);

    


        newRow.innerHTML = `
          <td class="table-row-id">${tableRowCount}</td>
          <td>${exerciseName}</td>
          <td>${exerciseSets}</td>
          <td>${exerciseRepeats}</td>
          <td>${exerciseWeight}</td>
          <td>${exerciseInterval}</td>
          <td>${exerciseInfo}</td>
        `;

        // Append the button cell to the newRow
        newRow.appendChild(buttonCell);
        
   
      // Inside the click event listener for the "Confirm" button
      if (originalRow1) {
        const originalCol1 = originalRow1.querySelector("td:nth-child(2)");
        const currentCol1 = newRow.querySelector("td:nth-child(2)");
        const originalCol2 = originalRow1.querySelector("td:nth-child(1)");
        const currentCol2 = newRow.querySelector("td:nth-child(1)");

        if (originalCol1.textContent === currentCol1.textContent) {
          // Merge the second column of the current row with the original row
          originalCol1.rowSpan = parseInt(originalCol1.rowSpan) + 1;
          newRow.deleteCell(1); // Delete the second column of the current row
          originalCol2.rowSpan = parseInt(originalCol2.rowSpan) + 1;
          newRow.deleteCell(0); 
        } else {
          // Update the original row to the current row
          originalRow1 = newRow;
        }
      } else {
        // Set the original row to the current row for the first occurrence
        originalRow1 = newRow;
      };
      
        
      });
    });

    // Append the table header, table body, and Add Exercise button to the new table
    newTable.appendChild(tableHeader);
    newTable.appendChild(tableBody);
    newTable.appendChild(addExerciseButton);

    // Append the new table to the trainingDays div
    trainingDaysDiv.appendChild(newTable);


  });

  
});






  
