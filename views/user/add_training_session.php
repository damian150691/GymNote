<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php";?>
    
    <h2>
        Add Workout
    </h2>
    
    <form id="addTrainingSession">

        

        <label for="choosePlans">Add workout to:</label>
        <?php
            if (!isset($defaultPlan) && $activePlan != NULL) {
                //redirect to the addworkout page with the active plan
                echo "<script>window.location.href = '/addworkout/" . $activePlan['plan_id'] . "';</script>";
            }
        ?>
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

        

        <?php if (isset($defaultPlan)): ?>

            <label for="dateInput">Date: </label>
            <input type="date" name="dateInput" id="dateInput" class="MNPheaderInputs"
                <?php
                    // Set the default value to today's date
                    echo 'value="' . date("Y-m-d") . '"';

                    // Limit the maximum date to today to prevent future dates
                    echo ' max="' . date("Y-m-d") . '"';
                ?>
            >
            <div class="spcr"></div>
            <?php

            
           
            echo "<select id=\"chooseDays\" name=\"chooseDays\" class=\"form-control\" required>";

            

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
            





            
            
            echo "<div id=\"days\">";

            ?>
                <div class="flex-wrapper">
                    <div class ="sessionOptions">

                        <label for="bodyWeight">Body weight <span class="small">(optional):</span></label>
                        <input type="number" name="bodyWeight" id="bodyWeight" class="MNPheaderInputs" placeholder="kg" min="0" step="0.1"
                        <?php
                            if (isset($chosenPlan) && $chosenPlan['initial_weight'] != NULL) {
                                echo 'value="' . $chosenPlan['initial_weight'] . '"';
                            }
                        ?>
                        >
                    
                    
                        <label for="caloriesGoal">Calories goal <span class="small">(optional):</span></label>
                        <input type="number" name="caloriesGoal" id="caloriesGoal" class="MNPheaderInputs" placeholder="kcal"
                        <?php
                            if (isset($chosenPlan) && $chosenPlan['calories_goal'] != NULL) {
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
                                    if (isset($chosenPlan) && $chosenPlan['proteins_goal'] != NULL) {
                                        echo 'value="' . $chosenPlan['proteins_goal'] . '"';
                                    }
                                ?>
                                >
                            </div>

                            <div class="one-third">
                                <label for="carbsGoal">Carbohydrates goal <span class="small">(optional):</span></label>
                                <input type="number" name="carbsGoal" id="carbsGoal" class="MNPheaderNutrientsInputs" placeholder="g"
                                <?php
                                    if (isset($chosenPlan) && $chosenPlan['carbs_goal'] != NULL) {
                                        echo 'value="' . $chosenPlan['carbs_goal'] . '"';
                                    }
                                ?>
                                >
                            </div>

                            <div class="one-third">
                                <label for="fatsGoal">Fats goal <span class="small">(optional):</span></label>
                                <input type="number" name="fatsGoal" id="fatsGoal" class="MNPheaderNutrientsInputs" placeholder="g"
                                <?php
                                    if (isset($chosenPlan) && $chosenPlan['fats_goal'] != NULL) {
                                        echo 'value="' . $chosenPlan['fats_goal'] . '"';
                                    }
                                ?>
                                >
                            </div>
                        </div>

                        
                    </div>
                    
                </div>
            <?php
            if (isset($chosenPlan)) {
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
                    echo "<button class=\"saveTrainingSession\">Save Workout</button>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "</div>";
            }
            ?>
        <?php endif; ?>
    </form>
</div>