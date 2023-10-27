document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed!");
  
    /*function addUser () {
        const addUserButton = document.getElementById("addUserButton");
        if (!addUserButton) {
            return;
        }
        
        addUserButton.addEventListener("click", function (event) {
            event.preventDefault();
            const userListTable = document.getElementById("adminUserList");
            const userInputRow = document.createElement("tr");
            userInputRow.classList.add("userInputRow");
            if (userListTable.lastElementChild.classList.contains("userInputRow")) {
                alert("Please confirm the previous user before adding a new one.");
                return;
            }
            userInputRow.innerHTML = `
                <td></td>
                <td><input type="text" name="username" placeholder="Username"></td>
                <td><input type="text" name="email" placeholder="Email"></td>
                <td><input type="text" name="firs_name" placeholder="First Name"></td>
                <td></td>
                <td></td>
                <td><input type="text" name="confirmed" placeholder="Yes/No"></td>
                <td><input type="text" name="role" placeholder="user/admin"></td>
                <td><a href="#">Confirm</a></td>
            `;
            userListTable.appendChild(userInputRow);

            let confirmButton = userInputRow.lastElementChild.firstElementChild;
            confirmButton.addEventListener("click", function (event) {
                event.preventDefault();
                //validate inputs
                let username = userInputRow.children[1].firstElementChild.value;
                let email = userInputRow.children[2].firstElementChild.value;
                let firstName = userInputRow.children[3].firstElementChild.value;
                let confirmed = userInputRow.children[6].firstElementChild.value;
                let role = userInputRow.children[7].firstElementChild.value;
                if (!username || !email || !confirmed || !role) {
                    alert("Username, email, confirmed and role are required fields.");
                    return;
                }
                //validate username (at least 3 characters, only letters and numbers)
                let usernameRegex = /^[a-zA-Z0-9]{3,}$/;
                if (!usernameRegex.test(username)) {
                    alert("Username must be at least 3 characters long and contain only letters and numbers.");
                    return;
                }
                //validate email
                let emailRegex = /\S+@\S+\.\S+/;
                if (!emailRegex.test(email)) {
                    alert("Please enter a valid email address.");
                    return;
                }
                //validate confirmed (must be yes or no - regardles if uppercase or lowercase)
                confirmed = confirmed.toLowerCase();
                if (confirmed !== "yes" && confirmed !== "no") {
                    alert("Confirmed must be either yes or no.");
                    return;
                }
                if (role !== "user" && role !== "admin") {
                    alert("Role must be either user or admin.");
                    return;
                }
                //gather data into array
                var userData = {
                    username: username,
                    email: email,
                    first_name: firstName,
                    confirmed: confirmed,
                    role: role
                }
                
                var jsonData = JSON.stringify(userData);
                console.log(jsonData);

                // Send the JSON string to the PHP script
                fetch('/adduser', {
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

            });

        });
    }

    addUser();*/
  
});