<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php"; 
    if (!$otherPlansCount) {
        echo "<h2>You didn't make any plans for other users yet.</h2>";
        echo "<button><a href='/makenewplan'>Make new plan</a></button>";
        exit();
    }
    ?>
    
    <h2>
        Plans
    </h2>

    <div class="plans-nav-bar">
        <a href="/myplans">Plans for me (<?php echo $myPlansCount; ?>)</a>
        |
        <a href="/plansforothers" class="active">Plans for others (<?php echo $otherPlansCount; ?>)</a>
    </div>
   
    <div id="plans">
        <table class="myPlansTable stripped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id</th>
                    <th>Plan Name</th>
                    <th>Date Created</th>
                    <th>Created For</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <?php
                        $i = 1;
                    foreach ($otherPlans as $plan) {
                        
                        
                        echo "<tr>";
                        //echo column with number
                        echo "<td>" . $i . "</td>";
                        echo "<td>#" . $plan['plan_id'] . "</td>";
                        echo "<td>" . $plan['plan_name'] . "</td>";
                        echo "<td>" . $plan['date_created'] . "</td>";
                        echo "<td>" . $plan['created_for'] . "</td>";
                        echo "<td><a href='/plan/" . $plan['plan_id'] . "'>View</a><a href='/deleteplan/" . $plan['plan_id'] . "'>Delete</a></td>";
                        echo "</tr>";
                        $i++;
                    }
                ?>
            </tbody>
        </table>
        <a href='/makenewplan'><button class="centerbtn">Make new plan</button></a>
    </div>

    
    
</div>