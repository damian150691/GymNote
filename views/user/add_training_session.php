<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php";?>
    
    <h2>
        Add Training Session
    </h2>
    
    <form id="addTrainingSession">

        <label for="dateInput">Date: </label>
        <input type="date" name="dateInput" id="dateInput" class="MNPheaderInputs"
            <?php
                // Set the default value to today's date
                echo 'value="' . date("Y-m-d") . '"';

                // Limit the maximum date to today to prevent future dates
                echo ' max="' . date("Y-m-d") . '"';
            ?>
        >

        <label for="choosePlans">Add training session to:</label>
        <select id="choosePlans" name="choosePlans" class="form-control" required>
            <?php
                // Check if $defaultPlan is not set or doesn't have a valid plan_id
                if (!isset($defaultPlan) || !isset($defaultPlan['plan_id'])) {
                    echo "<option value='' disabled selected>Please select a plan</option>";
                }

                foreach ($plans as $plan) {
                    $selected = isset($defaultPlan['plan_id']) && $plan['plan_id'] == $defaultPlan['plan_id'] ? 'selected' : '';
                    echo "<option value='" . $plan['plan_id'] . "' $selected>#". $plan['plan_id'] . " " . $plan['plan_name'] . "</option>";
                }
            ?>
        </select>
        <div class="spcr"></div>
        <?php

        if (isset($chosenPlan)) {
            $daysCount = count($days);
            echo "<select id=\"chooseDays\" name=\"chooseDays\" class=\"form-control\" required>";

            // Map day names to numeric values, using lowercase for consistency
            $dayMap = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7];

            // Getting the current day of the week as a number (1 for Monday, 7 for Sunday)
            $currentDayOfWeek = date('N');

            // Default selected day
            $selectedDayId = null;
            $closestDifference = PHP_INT_MAX;

            foreach ($days as $day) {
                // Convert the day name to a number, making sure to use lowercase for comparison
                $dayOfWeek = isset($dayMap[strtolower($day['day_of_the_week'])]) ? $dayMap[strtolower($day['day_of_the_week'])] : null;

                if ($dayOfWeek) {
                    // Calculate the difference in days
                    $difference = abs($dayOfWeek - $currentDayOfWeek);

                    // Check if this day is closer to the current day
                    if ($difference < $closestDifference) {
                        $closestDifference = $difference;
                        $selectedDayId = $day['day_id'];
                    }
                }
            }

            if ($daysCount > 1) {
                echo "<option value='' disabled" . (!$selectedDayId ? " selected" : "") . ">Please select a day</option>";
                foreach ($days as $day) {
                    $selected = ($day['day_id'] == $selectedDayId) ? " selected" : "";
            
                    // Prepare the day of the week display
                    $dayOfWeekDisplay = "";
                    if (!empty($day['day_of_the_week']) && $day['day_of_the_week'] != '0') {
                        $dayOfWeekDisplay = " (" . $day['day_of_the_week'] . ")";
                    }
            
                    echo "<option value='" . $day['day_id'] . "'" . $selected . ">Day " . $day['day_name'] . $dayOfWeekDisplay . "</option>";
                }
            } else {
                echo "<option value='" . $days[0]['day_id'] . "' selected>Day " . $days[0]['day_name'] . "</option>";
            }

            echo "</select>";
            echo "<div class=\"spcr\"></div>";
        }





        echo "<a href=\"editplan/". $chosenPlan['plan_id'] ."\"><button>Edit original Plan</button></a>";
        
        echo "<div id=\"days\">";

        ?>
            <div class="flex-wrapper">
                <div class ="sessionOptions">

                    <label for="bodyWeight">Body weight <span class="small">(optional):</span></label>
                    <input type="number" name="bodyWeight" id="bodyWeight" class="MNPheaderInputs" placeholder="kg" min="0" step="0.1"
                    <?php
                        if ($chosenPlan['initial_weight'] != NULL) {
                            echo 'value="' . $chosenPlan['initial_weight'] . '"';
                        }
                    ?>
                    >
                
                
                    <label for="caloriesGoal">Calories goal <span class="small">(optional):</span></label>
                    <input type="number" name="caloriesGoal" id="caloriesGoal" class="MNPheaderInputs" placeholder="kcal"
                    <?php
                        if ($chosenPlan['calories_goal'] != NULL) {
                            echo 'value="' . $chosenPlan['calories_goal'] . '"';
                        }
                    ?>
                    >

                    

                    <div class="spcr"></div>   

                    <div class="flex-wrapper">
                        <div class="one-third">
                            <label for="proteinsGoal">Proteins goal <span class="small">(optional):</span></label>
                            <input type="number" name="proteinsGoal" id="proteinsGoal" class="MNPheaderNutrientsInputs" placeholder="g"
                            <?php
                                if ($chosenPlan['proteins_goal'] != NULL) {
                                    echo 'value="' . $chosenPlan['proteins_goal'] . '"';
                                }
                            ?>
                            >
                        </div>

                        <div class="one-third">
                            <label for="carbsGoal">Carbohydrates goal <span class="small">(optional):</span></label>
                            <input type="number" name="carbsGoal" id="carbsGoal" class="MNPheaderNutrientsInputs" placeholder="g"
                            <?php
                                if ($chosenPlan['carbs_goal'] != NULL) {
                                    echo 'value="' . $chosenPlan['carbs_goal'] . '"';
                                }
                            ?>
                            >
                        </div>

                        <div class="one-third">
                            <label for="fatsGoal">Fats goal <span class="small">(optional):</span></label>
                            <input type="number" name="fatsGoal" id="fatsGoal" class="MNPheaderNutrientsInputs" placeholder="g"
                            <?php
                                if ($chosenPlan['fats_goal'] != NULL) {
                                    echo 'value="' . $chosenPlan['fats_goal'] . '"';
                                }
                            ?>
                            >
                        </div>
                    </div>

                    
                </div>
                
            </div>
        <?php
            foreach ($days as $day) {


                
                


                echo "<div id=\"Day " . $day['day_name'] . "\" class=\"ATStrainingDay hidden\">";
                echo "<h3>Day " . $day['day_name'] . "</h3>";
                echo "<table id=\"ATS" . $day['day_name'] . "\" class=\"ATStrainingTable\">";
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
                echo "<th>Record?</th>";
                echo "<th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                //list all sets for the day
                foreach ($sets[$day['day_id']] as $set) {
                    echo "<tr class=\"ATSsetRow\">";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td colspan=\"3\">" . $set['set_name'] . "</td>";
                    echo "<td>" . $set['rest'] . "</td>";
                    echo "<td>" . $set['comments'] . "</td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "</tr>";
                    foreach ($exercises[$set['set_id']] as $exercise) {
                        echo "<tr class=\"ATSexerciseHeader \">";
                        echo "<td reference_id=\"" . $exercise['reference_id'] . "\" day_id=\"" . $day['day_id'] . "\" set_id=\"" . $set['set_id'] . "\">" . $exercise['lp'] . "</td>";
                        echo "<td>" . $exercise['exercise_name'] . "</td>";
                        echo "<td>" . $exercise['sets'] . "</td>";
                        echo "<td>" . $exercise['repetitions'] . "</td>";
                        echo "<td>" . $exercise['weight'] . "</td>";
                        echo "<td>" . $exercise['rest'] . "</td>";
                        echo "<td>" . $exercise['comments'] . "</td>";
                        echo "<td><input type=\"checkbox\" name=\"record_trainng\" class=\"recordTrainingCheckbox\"></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        if ($exercise['sets'] >= 1) {
                            for ($i = 1; $i <= $exercise['sets']; $i++) {
                                echo "<tr reference_id=\"" . $exercise['reference_id'] . "\" day_id=\"" . $day['day_id'] . "\" set_id=\"" . $set['set_id'] . "\" sub_id=\"" . $i . "\" class=\"ATSinputRow e" . $exercise['lp'] . " hidden\">";
                                echo "<td reference_id=\"" . $exercise['reference_id'] . "\">(" . $exercise['lp'] . ")</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td><input type=\"number\" name=\"reps_input\"></td>";
                                echo "<td><input type=\"number\" step=\"0.1\" name=\"weight_input\"></td>";
                                echo "<td></td>";
                                echo "<td><input type=\"text\" name=\"comments_input\"></td>";
                                echo "<td></td>";
                                echo "<td><button class=\"smallbtn deleteButton\">Delete</button></td>";
                                echo "</tr>";
                            }
                        } 
                    }
                }
                
                echo "</tbody>";
                echo "</table>";
                echo "<button class=\"saveTrainingSession\">Save Training Session</button>";
                echo "</div>";
            }
            echo "</div>";

        ?>
    </form>
</div>