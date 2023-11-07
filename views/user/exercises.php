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
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name of exercise</th>
                    <th>Category</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($categories as $category) {
                    
                    foreach ($exercises[$category['id']] as $exercise) {
                        echo "<tr class=\"exercise-row\">";
                        echo "<td>" . htmlspecialchars($exercise['id']) . "</td>";
                        echo "<td><a href=\"/exercise/" . htmlspecialchars($exercise['id']) . "\">" . htmlspecialchars($exercise['name']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($category['category']) . "</td>";
                        
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    
    
</div>