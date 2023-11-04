<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Exercises</h2>
    
    <?php 
    require_once "../views/shared/errors.php";
    ?>

    <div id="categories">
        <ul>
            <?php
            foreach ($categories as $category) {
                echo "<li class=\"category\">" . $category['category'];
                
                //display exercises from the category
                echo "<ul class=\"exercises\">";
                
                foreach ($exercises[$category['id']] as $exercise) {
                    echo "<li><a href=\"/exercise/" . $exercise['id'] . "\">" . $exercise['name'] . "</a></li>";
                }
                
                echo "</ul>";

                echo "</li>";
            }
            ?>
        </ul>
    </div>
    
    
</div>