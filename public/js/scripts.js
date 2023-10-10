document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed!");

  // Define the confirmDelete function
  function confirmDelete(userId) {
    console.log("confirmDelete called");
    if (confirm("Are you sure you want to delete this user?")) {
        deleteUser(userId);
    }
  }

  function deleteUser(userId) {
    console.log("deleteUser called");
    fetch("delete_user.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "user_id=" + userId
    })
    .then(response => {
        if (response.status === 200) {
            return response.text();
        } else {
            throw new Error("Error: " + response.status);
        }
    })
    .then(data => {
        // Handle success, e.g., display a success message
        alert(data);
        // Reload the page
        window.location.reload();
    })
    .catch(error => {
        // Handle errors
        alert(error.message);
    });
}

  // Get the "Delete" link and attach the click event handler

  var deleteUserLinks = document.querySelectorAll(".deleteUserLink");
  for (var i = 0; i < deleteUserLinks.length; i++) {
    var userId = deleteUserLinks[i].getAttribute("data-userid");
    deleteUserLinks[i].onclick = function () {
        console.log("deleteUserLink clicked");
        confirmDelete(userId);
    };
  }


  
});






  
