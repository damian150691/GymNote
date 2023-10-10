<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
            // Display the success message
            echo '<p class="success">' . $_SESSION['success'] . '</p>';
            
            // Clear the session variable
            unset($_SESSION['success']);
        }
?>


<?php if (isset($errors) && count($errors) > 0) : ?>
    <div class="error">
        <?php foreach ($errors as $error) : ?>
         
 
 
<p class="error"><?php echo $error ?></p>
 
 
 
        <?php endforeach ?>
    </div>
<?php  endif ?>