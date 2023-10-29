document.addEventListener("DOMContentLoaded", function () {
   console.log("profile.js loaded");
  

    //check if there is fileToUpload input
    if (document.getElementById('fileToUpload')) {
        document.getElementById('fileToUpload').addEventListener('change', function() {
            // Get the selected file name
            var fileName = this.value.split('\\').pop(); 
            // Display the file name in the span
            document.querySelector('.file-name').textContent = fileName || 'No file chosen';
        });
    }



    
  
});