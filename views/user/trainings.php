<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    <?php require_once "../views/shared/errors.php";?>
    
    <h2>
        My training sessions
    </h2>
    <?php
    
    if ($trainingSessions == NULL) {
        echo "<p>You don't have any training sessions yet.</p>";
        echo "<button><a href=\"/myplans\">Add training session to a plan</a></button>";
        exit();
    } 

    ?>

    <div class="flex-wrapper flex-left">
        <div class="flex-item w200">
            <p class="mg5 bold">Session ID</p>
        </div>
        <div class="flex-item w200">
            <p class="mg5 bold">Plan name</p>
        </div>
        <div class="flex-item w200">
            <p class="mg5 bold">Date</p>
        </div>
        <div class="flex-item w200">
            <p class="mg5 bold">Actions</p>
        </div>
    </div>
    <?php foreach ($trainingSessions as $session): ?>
    <div class="flex-wrapper flex-left">
        <div class="flex-item w200">
            <p class="mg5">#<?php echo $session['session_id']; ?></p>
        </div>
        <div class="flex-item w200">
            <p class="mg5"><?php echo $session['plan_name']; ?></p>
        </div>
        <div class="flex-item w200">
            <p class="mg5"><?php echo $session['session_date']; ?></p>
        </div>
        <div class="flex-item w200">
            <a href="/trainingsession/<?php echo $session['session_id']; ?>"><button>View</button></a>

        </div>
    </div>
    <?php endforeach; ?>
</div>