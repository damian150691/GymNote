<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>
        My Plans (<?php 
            if ($plansCount) {
                echo $plansCount;
            }
            ?>)
    </h2>
   
    <div id="plans">
        <table>
            <tr>
                <th>Plan Id</th>
                <th>Plan Name</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        <?php
            foreach ($plans as $plan) {
                echo "<tr>";
                echo "<td>" . $plan['plan_id'] . "</td>";
                echo "<td>" . $plan['plan_name'] . "</td>";
                echo "<td>" . $plan['date_created'] . "</td>";
                echo "<td><a href='/plan/" . $plan['plan_id'] . "'>View</a><a href='/deleteplan/" . $plan['plan_id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
        </table>
    </div>

    
    
</div>