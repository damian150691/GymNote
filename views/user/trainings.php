<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php";?>
    
    <h2>My training sessions</h2>

    <?php
    if ($trainingSessions == NULL) {
        echo "<p>You don't have any training sessions yet.</p>";
        echo "<button><a href=\"/myplans\">Add training session to a plan</a></button>";
        exit();
    } 

    // Sort the training sessions by date in descending order
    usort($trainingSessions, function($a, $b) {
        return strtotime($b['session_date']) - strtotime($a['session_date']);
    });

    // Group the training sessions by plan name
    $groupedPlans = [];
    foreach ($trainingSessions as $session) {
        $groupedPlans[$session['plan_name']][$session['day_name']][] = $session;
    }

    foreach ($groupedPlans as $planName => $sessionsByDay) {
        ?>
        <div class="dashed-border w90p mgb40">
            <h3>
                <?php
                // Using reset() to get the first element of $sessionsByDay
                $firstDaySessions = reset($sessionsByDay);
                echo "<a href=\"/plan/" . $firstDaySessions[0]['plan_id'] . "\">";
                echo "#" . $firstDaySessions[0]['plan_id'] . " ";
                echo htmlspecialchars($planName);
                echo "</a>";
                ?>
            </h3>
            <?php foreach ($sessionsByDay as $dayName => $sessions): ?>
                <h4>Day <?php echo htmlspecialchars($dayName) . " <span class=\"small\">(" . htmlspecialchars($sessions[0]['day_of_the_week']) . ")</span>"; ?></h4>
                <div class="flex-wrapper flex-left dark-bg ptb5">
                    <div class="flex-item w200"><p class="mg5 bold">No</p></div>
                    <div class="flex-item w200"><p class="mg5 bold">Date</p></div>
                    <div class="flex-item w200"><p class="mg5 bold">Actions</p></div>
                </div>
                <?php $sessionNumber = count($sessions); ?>
                <?php foreach ($sessions as $session): ?>
                    <div class="flex-wrapper flex-left" day_name="<?php echo $session['day_name']; ?>" day_id="<?php echo $session['day_id']; ?>" session_id="<?php echo $session['session_id']; ?>">
                        <div class="flex-item w200">
                            <p class="mg5">#<?php echo $sessionNumber--; ?></p>
                        </div>
                        <div class="flex-item w200">
                            <p class="mg5"><?php echo $session['session_date']; ?></p>
                        </div>
                        <div class="flex-item w200">
                            <a href="/trainingsession/<?php echo $session['session_id']; ?>"><button>View</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }
    ?>
</div>