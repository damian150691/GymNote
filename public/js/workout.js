document.addEventListener("DOMContentLoaded", function () {
   console.log("trainingsession.js loaded");

   const exerciseTableBody = document.getElementById("exercisesTable").querySelector("tbody");
   const exerciseTableRows = exerciseTableBody.querySelectorAll("tr");
   
   let lastInsertedSetName = null; // Track the last inserted set name
   
   exerciseTableRows.forEach((row) => {
       const setName = row.getAttribute("set_name");
   
       // Check if a new set has started
       if (lastInsertedSetName !== setName) {
           // Insert new row with one column that has textContent = "Set " + setName before the current row
           const newRow = exerciseTableBody.insertRow(row.rowIndex-1);
           const newCell = newRow.insertCell(0);
           newCell.textContent = "Set " + setName;
           newCell.setAttribute("colspan", "5");
           newCell.setAttribute("class", "setRow");

   
           lastInsertedSetName = setName; // Update the last inserted set name
       }
   });
});
