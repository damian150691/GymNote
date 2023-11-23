<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php";?>
    
    <h2>
        <?php
        if ($previousSession != NULL) {
            $previousSessionDate = $previousSession['session_date'];
            $previousSessionDate = explode('-', $previousSessionDate);
            $previousSessionDate = $previousSessionDate[2] . "-" . $previousSessionDate[1] . "-" . $previousSessionDate[0];
            $previousSessionDayOfTheWeek = date('l', strtotime($previousSessionDate));
            echo "<a href=\"/trainingsession/" . $previousSession['session_id'] . "\"><<<</a>";
        }

        echo $whichNo;
        ?>

        training session of Day <?php echo $dayName; ?> from<a href="/plan/<?php echo $plan['plan_id']; ?>"><?php echo $plan['plan_name']; ?></a>
        <?php
        if ($nextSession != NULL) {
            $nextSessionDate = $nextSession['session_date'];
            $nextSessionDate = explode('-', $nextSessionDate);
            $nextSessionDate = $nextSessionDate[2] . "-" . $nextSessionDate[1] . "-" . $nextSessionDate[0];
            $nextSessionDayOfTheWeek = date('l', strtotime($nextSessionDate));
            echo "<a href=\"/trainingsession/" . $nextSession['session_id'] . "\">>>></a>";
        }
        ?>
    </h2>
    
    <div class="session-info ta-left mg40">
        <p><span class="bold">Plan name:</span> <?php echo $trainingSession['plan_name']; ?></p>

        <p><span class="bold">Day:</span> <?php echo $dayName; ?> (<?php echo $dayOfTheWeek; ?>)</p>

        <?php if ($trainingSession['current_body_weight'] != NULL && $trainingSession['current_body_weight'] != 0): ?>
            <p><span class="bold">Current body weight:</span> <?php echo $trainingSession['current_body_weight']; ?> kg
            <?php
                if ($previousSession != NULL) {
                    $previousBodyWeight = $previousSession['current_body_weight'];
                    $currentBodyWeight = $trainingSession['current_body_weight'];
                    $weightDifference = $currentBodyWeight - $previousBodyWeight;
                    if ($weightDifference > 0) {
                        echo "<span class=\"small green\"> (+" . $weightDifference . " kg)</span>";
                    } else if ($weightDifference < 0) {
                        echo "<span class=\"small red\"> (" . $weightDifference . " kg)</span>";
                    } else {
                        echo "<span class=\"small\"> (0 kg)</span>";
                    }
                }
            ?>
            </p>
        <?php endif; ?>

        <?php if ($trainingSession['calories_goal'] != NULL && $trainingSession['calories_goal'] != 0): ?>
            <p><span class="bold">Calories goal:</span> <?php echo $trainingSession['calories_goal']; ?> kcal
            <?php
                if ($previousSession != NULL) {
                    $previousCaloriesGoal = $previousSession['calories_goal'];
                    $currentCaloriesGoal = $trainingSession['calories_goal'];
                    $caloriesGoalDifference = $currentCaloriesGoal - $previousCaloriesGoal;
                    if ($caloriesGoalDifference > 0) {
                        echo "<span class=\"small green\"> (+" . $caloriesGoalDifference . " kcal)</span>";
                    } else if ($caloriesGoalDifference < 0) {
                        echo "<span class=\"small red\"> (" . $caloriesGoalDifference . " kcal)</span>";
                    } else {
                        echo "<span class=\"small\"> (0 kcal)</span>";
                    }
                }
            ?>
            </p>
        <?php endif; ?>

        <?php if ($trainingSession['proteins_goal'] != NULL && $trainingSession['proteins_goal'] != 0): ?>
            <p><span class="bold">Proteins goal:</span> <?php echo $trainingSession['proteins_goal']; ?> g
            <?php
                if ($previousSession != NULL) {
                    $previousProteinsGoal = $previousSession['proteins_goal'];
                    $currentProteinsGoal = $trainingSession['proteins_goal'];
                    $proteinsGoalDifference = $currentProteinsGoal - $previousProteinsGoal;
                    if ($proteinsGoalDifference > 0) {
                        echo "<span class=\"small green\"> (+" . $proteinsGoalDifference . " g)</span>";
                    } else if ($proteinsGoalDifference < 0) {
                        echo "<span class=\"small red\"> (" . $proteinsGoalDifference . " g)</span>";
                    } 
                }
            ?>
            </p>
        <?php endif; ?>

        <?php if ($trainingSession['carbs_goal'] != NULL && $trainingSession['carbs_goal'] != 0): ?>
            <p><span class="bold">Carbs goal:</span> <?php echo $trainingSession['carbs_goal']; ?> g
            <?php
                if ($previousSession != NULL) {
                    $previousCarbsGoal = $previousSession['carbs_goal'];
                    $currentCarbsGoal = $trainingSession['carbs_goal'];
                    $carbsGoalDifference = $currentCarbsGoal - $previousCarbsGoal;
                    if ($carbsGoalDifference > 0) {
                        echo "<span class=\"small green\"> (+" . $carbsGoalDifference . " g)</span>";
                    } else if ($carbsGoalDifference < 0) {
                        echo "<span class=\"small red\"> (" . $carbsGoalDifference . " g)</span>";
                    } 
                }
            ?>
            </p>
        <?php endif; ?>

        <?php if ($trainingSession['fats_goal'] != NULL && $trainingSession['fats_goal'] != 0): ?>
            <p><span class="bold">Fats goal:</span> <?php echo $trainingSession['fats_goal']; ?> g
            <?php 
                if ($previousSession != NULL) {
                    $previousFatsGoal = $previousSession['fats_goal'];
                    $currentFatsGoal = $trainingSession['fats_goal'];
                    $fatsGoalDifference = $currentFatsGoal - $previousFatsGoal;
                    if ($fatsGoalDifference > 0) {
                        echo "<span class=\"small green\"> (+" . $fatsGoalDifference . " g)</span>";
                    } else if ($fatsGoalDifference < 0) {
                        echo "<span class=\"small red\"> (" . $fatsGoalDifference . " g)</span>";
                    } 
                }
            ?>
            </p>
        <?php endif; ?>
    </div>

    <div id="exercisesTable" class="exercises-table">
        <table class="w100p">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Exercise</th>
                    <th>Reps</th>
                    <th>Weight
                        <?php if ($previousSession != NULL): ?>
                            <span class="small"> (difference)</span>
                        <?php endif; ?>
                    </th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    
                    foreach ($sortedExercises as $exercise) {
                        if ($previousSession != NULL) {
                            foreach ($previousSessionExercises as $previousExercise) {
                                if ($previousExercise['exercise_id'] == $exercise['exercise_id'] && $previousExercise['lp'] == $exercise['lp'] && $previousExercise['sub_id'] == $exercise['sub_id']) {
                                    $exercise['previous_weight'] = $previousExercise['weight'];
                                    $exercise['previous_reps'] = $previousExercise['reps'];
                                    break;
                                }
                            }
                        }
                        $setName = substr($exercise['lp'], 0, 1);
                        echo "<tr set_name=\"" . $setName . "\">";
                        echo "<td>" . $exercise['lp'] . "</td>";
                        echo "<td>" . $exercise['name'] . "</td>";
                        echo "<td>" . $exercise['reps'];
                        if ($previousSession != NULL) {
                            if (isset($exercise['previous_reps']) && $exercise['previous_reps'] != 0) {
                                $repsDifference = $exercise['reps'] - $exercise['previous_reps'];
                                if ($repsDifference > 0) {
                                    echo "<span class=\"small green\"> (+" . $repsDifference . ")</span>";
                                } else if ($repsDifference < 0) {
                                    echo "<span class=\"small red\"> (" . $repsDifference . ")</span>";
                                } 
                            }
                        }
                        echo "</td>";
                        
                        
                        echo "<td>" . $exercise['weight'] . "kg";
                        if ($previousSession != NULL) {
                            if (isset($exercise['previous_weight']) && $exercise['previous_weight'] != 0) {
                                $weightDifference = $exercise['weight'] - $exercise['previous_weight'];
                                if ($weightDifference > 0) {
                                    echo "<span class=\"small green\"> (+" . $weightDifference . " kg)</span>";
                                } else if ($weightDifference < 0) {
                                    echo "<span class=\"small red\"> (" . $weightDifference . " kg)</span>";
                                } else {
                                    echo "<span class=\"small\"> (0 kg)</span>";
                                }
                            }
                        }
                        echo "</td>";
                        
                        echo "<td>" . $exercise['comments'] . "</td>";
                        echo "</tr>";
                    }
                ?>
                
            </tbody>
        </table>
    </div>
    <div class="spcr"></div>



    
</div>