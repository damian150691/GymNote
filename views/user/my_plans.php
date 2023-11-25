<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php"; 
    if (!$plansCount) {
        echo "<h2>You don't have any plans yet.</h2>";
        echo "<button><a href='/makenewplan'>Make new plan</a></button>";
        exit();
    }
    ?>
    
    <h2>
        Plans
    </h2>
    <?php 
        if ($plansForOthersCount > 0) {
            echo '<div class="plans-nav-bar">';
            echo '    <a href="/myplans" class="active">Plans for me (' . $plansCount . ')</a>';
            echo '    |';
            echo '    <a href="/plansforothers">Plans for others (' . $plansForOthersCount . ')</a>';
            echo '</div>';
        }
    ?>

   
    <div id="plans">
        <table class="myPlansTable stripped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id</th>
                    <th>Plan Name</th>
                    <th>Date Created</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                    if (isset($activePlan) && $activePlan != null) {
                        $i = 1;
                        echo "<tr>";
                        echo "<td colspan=\"6\"><h3>Active plan:</h3></td>";
                        echo "</tr>";


                        echo "<tr class=\"activePlan\">";
                        echo "<td>" . $i . "</td>";
                        echo "<td>#" . $activePlan['plan_id'] . "</td>";
                        echo "<td>" . $activePlan['plan_name'] . "</td>";
                        echo "<td>" . $activePlan['date_created'] . "</td>";
                        echo "<td>" . $activePlan['created_by'] . "</td>";
                        echo "<td><a href='/plan/" . $activePlan['plan_id'] . "'>View</a><a href='/deleteplan/" . $activePlan['plan_id'] . "'>Delete</a><a href='/addtrainingsession/" . $activePlan['plan_id'] . "'>Add training session</a></td>";
                        echo "</tr>";
                    }
                    ?>
                <tr>
                    <td colspan="6"><h3>Other plans:</h3></td>
                </tr>
                <?php
                        if (isset($i) && $i == 1) {
                            $i = 2;
                        } else {
                            $i = 1;
                        }
                    foreach ($plans as $plan) {
                        
                        
                        echo "<tr>";
                        //echo column with number
                        echo "<td>" . $i . "</td>";
                        echo "<td>#" . $plan['plan_id'] . "</td>";
                        echo "<td>" . $plan['plan_name'] . "</td>";
                        echo "<td>" . $plan['date_created'] . "</td>";
                        echo "<td>" . $plan['created_by'] . "</td>";
                        echo "<td><a href='/plan/" . $plan['plan_id'] . "'>View</a><a href='/deleteplan/" . $plan['plan_id'] . "'>Delete</a><a href='/setactiveplan/" . $plan['plan_id'] . "'>Set as active</a></td>";
                        echo "</tr>";
                        $i++;
                    }
                ?>
            </tbody>
        </table>
        <a href='/makenewplan'><button class="centerbtn">Make new plan</button></a>
    </div>

    
    
</div>