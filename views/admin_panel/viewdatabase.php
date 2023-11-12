<h3>mnp_plans table:</h3>
<table id="mnp_plans" class ="dbview stripped">
    <thead>
        <tr>
            <th>plan_id</th>
            <th>plan_name</th>
            <th>date_created</th>
            <th>user_id</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php 
                foreach ($mnpPlans as $mnpPlan) {
                    echo "<tr>";
                    echo "<td>" . $mnpPlan['plan_id'] . "</td>";
                    echo "<td>" . $mnpPlan['plan_name'] . "</td>";
                    echo "<td>" . $mnpPlan['date_created'] . "</td>";
                    echo "<td>" . $mnpPlan['user_id'] . "</td>";
                    echo "</tr>";
                } 
            ?>
        </tr>
    </tbody>
</table>

<h3>mnp_days table:</h3>
<table id="mnp_days" class ="dbview stripped">
    <thead>
        <tr>
            <th>day_id</th>
            <th>plan_id</th>
            <th>day_name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                foreach ($mnpDays as $mnpDay) {
                    echo "<tr>";
                    echo "<td>" . $mnpDay['day_id'] . "</td>";
                    echo "<td>" . $mnpDay['plan_id'] . "</td>";
                    echo "<td>" . $mnpDay['day_name'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </tr>
    </tbody>
</table>

<h3>mnp_sets table:</h3>
<table id="mnp_sets" class ="dbview stripped">
    <thead>
        <tr>
            <th>set_id</th>
            <th>day_id</th>
            <th>set_name</th>
            <th>rest</th>
            <th>comments</th>  
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                foreach ($mnpSets as $mnpSet) {
                    echo "<tr>";
                    echo "<td>" . $mnpSet['set_id'] . "</td>";
                    echo "<td>" . $mnpSet['day_id'] . "</td>";
                    echo "<td>" . $mnpSet['set_name'] . "</td>";
                    echo "<td>" . $mnpSet['rest'] . "</td>";
                    echo "<td>" . $mnpSet['comments'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </tr>
    </tbody>
</table>

<h3>mnp_exercises table:</h3>
<table id="mnp_exercises" class ="dbview stripped">
    <thead>
        <tr>
            <th>exercise_id</th>
            <th>set_id</th>
            <th>lp</th>
            <th>exercise_name</th>
            <th>sets</th>
            <th>repetitions</th>
            <th>weight</th>
            <th>rest</th>
            <th>comments</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                foreach ($mnpExercises as $mnpExercise) {
                    echo "<tr>";
                    echo "<td>" . $mnpExercise['exercise_id'] . "</td>";
                    echo "<td>" . $mnpExercise['set_id'] . "</td>";
                    echo "<td>" . $mnpExercise['lp'] . "</td>";
                    echo "<td>" . $mnpExercise['exercise_name'] . "</td>";
                    echo "<td>" . $mnpExercise['sets'] . "</td>";
                    echo "<td>" . $mnpExercise['repetitions'] . "</td>";
                    echo "<td>" . $mnpExercise['weight'] . "</td>";
                    echo "<td>" . $mnpExercise['rest'] . "</td>";
                    echo "<td>" . $mnpExercise['comments'] . "</td>";
                    echo "</tr>";
                }
            ?>
        </tr>
    </tbody>
</table>