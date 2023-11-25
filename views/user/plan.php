<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content" >
   
    <h2>
        <?php echo $plan['plan_name'] . " (#" . $plan['plan_id'] . ")"; ?>
    </h2>

    <?php 
        if ($plan['initial_weight'] != 0 || $plan['calories_goal'] != 0 || $plan['proteins_goal'] != 0 || $plan['carbs_goal'] != 0 || $plan['fats_goal'] != 0) {
            echo "<div class=\"planDetails\">";
                if ($plan['initial_weight'] != 0) {
                    echo "<p>";
                    echo "<span class=\"bold\">Initial weight: </span>";
                    echo $plan['initial_weight']; 
                    echo " kg";
                    echo "</p>";
                }

                if ($plan['calories_goal'] != 0) {
                    echo "<p>";
                    echo "<span class=\"bold\">Calories goal: </span>";
                    echo $plan['calories_goal']; 
                    echo " kcal";
                    echo "</p>";
                }

                if ($plan['proteins_goal'] != 0) {
                    echo "<p>";
                    echo "<span class=\"bold\">Proteins goal: </span>";
                    echo $plan['proteins_goal']; 
                    echo " g";
                    echo "</p>";
                }

                if ($plan['carbs_goal'] != 0) {
                    echo "<p>";
                    echo "<span class=\"bold\">Carbohydrates goal: </span>";
                    echo $plan['carbs_goal']; 
                    echo " g";
                    echo "</p>";
                }

                if ($plan['fats_goal'] != 0) {
                    echo "<p>";
                    echo "<span class=\"bold\">Fats goal: </span>";
                    echo $plan['fats_goal']; 
                    echo " g";
                    echo "</p>";
                }
            echo "</div>";
        }
    ?>
    

    <div id="trainingDays" class="viewplan">
        <?php 
        //make a loop to create tables for each day based on $days array
        foreach ($days as $day) {
            echo "<div class=\"trainingDay\">";
            if ($day["day_of_the_week"] != NULL) {
                echo "<h3>Day " . $day['day_name'] . " <span class=\"small\">(" . $day['day_of_the_week'] . ")</span></h3>";
            } else {
                echo "<h3>Day " . $day['day_name'] . "</h3>";
            }
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