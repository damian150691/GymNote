<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Dashboard</h2>
    <div class="grid">
        <div class="two-thirds">
            <div class="section">
                <h3 class="center">My latest plans</h2>
                <?php
                    if (count($plans) == 0) {
                        echo "<p class=\"center\">You don't have any plans yet.</p><a href=\"/makenewplan\"><button class=\"centerbtn\">Make new plan</button></a>";
                    } else {

                        //display the latest 5 plans
                        $plans = array_reverse($plans);
                        $plans = array_slice($plans, 0, 5);


                        echo "<table class=\"myPlansTable dashboardTable\">";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Plan Name</th>";
                        echo "<th>Date Created</th>";
                        echo "<th>Created By</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($plans as $plan) {
                            $link = "/plan/" . $plan['plan_id'];
                            echo "<tr onclick=\"window.location.href='$link'\" style=\"cursor:pointer\">";
                            echo "<td>" . $plan['plan_name'] . "</td>";
                            echo "<td>" . $plan['date_created'] . "</td>";
                            echo "<td>" . $createdBy . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "<a href=\"/myplans\"><button class=\"centerbtn\">See all plans</button></a>";

                    }
                ?>
                
            </div>
            <div class="section">
                <h3 class="center">My progress</h2>
            </div>
        </div>

        <div class="one-third">
            <div class="section">
                <h3 class="center">My profile</h2>
                <?php
                    if ($userBio['profile_picture'] != NULL) {
                        echo "<img src=\"../img/uploads/profile_pictures/" . $userBio['profile_picture'] . "\" alt=\"Profile picture\" class=\"profile-picture dashboard-picture\" />";
                    }
                ?>

                <div class="bio bio-dashboard">
                    <p><span class="userProfileRowName">Email: </span><?php echo $user['email'] ?></p>
                    <?php
                        if ($user['first_name'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">First name: </span>" . $user['first_name'] . "</p>";
                        }
                        if ($user['last_name'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Last name: </span>" . $user['last_name'] . "</p>";
                        }
                    ?>
                    <p><span class="userProfileRowName">Member since: </span>
                        <?php 
                        $day = ordinal(date('j', strtotime($user['date_registered'])));
                        $month = date('F', strtotime($user['date_registered']));
                        $year = date('Y', strtotime($user['date_registered']));
                        echo $day . " " . $month . " " . $year; 
                        ?>
                    </p>
                    <?php
                        if ($userBio['age'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Age: </span>" . $userBio['age'] . "</p>";
                        }
                        if ($userBio['gender'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Gender: </span>" . $userBio['gender'] . "</p>";
                        }
                        if ($userBio['height'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Height: </span>" . $userBio['height'] . "</p>";
                        }
                        if ($userBio['weight'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Weight: </span>" . $userBio['weight'] . "</p>";
                        }
                        if ($userBio['calories_goal'] != NULL) {
                            echo "<p><span class=\"userProfileRowName\">Calories goal: </span>" . $userBio['calories_goal'] . "</p>";
                        }
                    ?>
                    <a href="/profile"><button class="editBioButton">Edit profile</button></a>
                </div>
            </div>
        
            <div class="section">
                <h3 class="center">Latest notifications</h2>
            </div>
        </div>
    </div>
    
</div>