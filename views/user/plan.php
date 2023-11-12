<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>
        <?php echo $plan['plan_name'] . " (id=" . $plan['plan_id'] . ")"; ?>
    </h2>
    <div id="trainingDays" class="viewplan">
        <?php 
        //make a loop to create tables for each day based on $days array
        foreach ($days as $day) {
            echo "<div class=\"trainingDay\">";
            echo "<h3>Day " . $day['day_name'] . "</h3>";
            echo "<table id=\"MNP" . $day['day_name'] . "\" class=\"trainingTable stripped\">";
            echo "<tbody>";
            //create thead with exercises names
            echo "<thead>";
            echo "<tr>";
            echo "<th>No</th>";
            echo "<th>Exercise</th>";
            echo "<th>Set-reps</th>";
            echo "<th>Reps</th>";
            echo "<th>Weight</th>";
            echo "<th>Rest</th>";
            echo "<th>Comments</th>";
            echo "</tr>";
            echo "</thead>";
            //list all sets for the day
            foreach ($sets[$day['day_id']] as $set) {
                echo "<tr class=\"setRow\">";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td colspan=\"3\">" . $set['set_name'] . "</td>";
                echo "<td>" . $set['rest'] . "</td>";
                echo "<td>" . $set['comments'] . "</td>";
                echo "</tr>";
                foreach ($exercises[$set['set_id']] as $exercise) {
                    echo "<tr class=\"tableRow\">";
                    echo "<td>" . $exercise['lp'] . "</td>";
                    echo "<td>" . $exercise['exercise_name'] . "</td>";
                    echo "<td>" . $exercise['sets'] . "</td>";
                    echo "<td>" . $exercise['repetitions'] . "</td>";
                    echo "<td>" . $exercise['weight'] . "</td>";
                    echo "<td>" . $exercise['rest'] . "</td>";
                    echo "<td>" . $exercise['comments'] . "</td>";
                    echo "</tr>";
                }
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }

        ?>
    </div>
    
    
    
</div>