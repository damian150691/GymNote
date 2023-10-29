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
        My Plans (<?php 
            
            echo $plansCount;
            
            ?>)
    </h2>
   
    <div id="plans">
        <table class="myPlansTable">
            <tr>
                <th>Plan Id</th>
                <th>Plan Name</th>
                <th>Date Created</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        <?php
            foreach ($plans as $plan) {
                echo "<tr>";
                echo "<td>" . $plan['plan_id'] . "</td>";
                echo "<td>" . $plan['plan_name'] . "</td>";
                echo "<td>" . $plan['date_created'] . "</td>";
                echo "<td>" . $createdBy . "</td>";
                echo "<td><a href='/plan/" . $plan['plan_id'] . "'>View</a><a href='/deleteplan/" . $plan['plan_id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
        </table>
        <button><a href='/makenewplan'>Make new plan</a></button>
    </div>

    
    
</div>