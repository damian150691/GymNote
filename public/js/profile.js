document.addEventListener("DOMContentLoaded", function () {
   console.log("profile.js loaded");

   function editBio () {
        
        editBioButton = document.querySelector('.editBioButton');
        if (editBioButton) {
           
            editBioButton.addEventListener('click', function() {
                //hide the bio
                document.querySelector('.bio').classList.add('hidden');
                //show the edit bio form
                document.getElementById('edit_bio').classList.remove('hidden');

            });
        }

        cancelEditBioButton = document.getElementById('cancelEditBioButton');
        if (cancelEditBioButton) {
            cancelEditBioButton.addEventListener('click', function(event) {
                event.preventDefault();
                //hide the edit bio form
                document.getElementById('edit_bio').classList.add('hidden');
                //show the bio
                document.querySelector('.bio').classList.remove('hidden');

            });
        }

   }
  

    //check if there is fileToUpload input
    if (document.getElementById('fileToUpload')) {
        document.getElementById('fileToUpload').addEventListener('change', function() {
            // Get the selected file name
            var fileName = this.value.split('\\').pop(); 
            // Display the file name in the span
            document.querySelector('.file-name').textContent = fileName || 'No file chosen';
        });
    }

    //add alert to delete button
    deleteBtn = document.querySelector('input[name="delete"]');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to remove your profile picture?')) {
                event.preventDefault();
            }
        });
    }


editBio();
    
  
});