<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Make a new Training Plan</h2>

        <?php
        require_once "../views/shared/errors.php";
        ?>
            
        <form id="trainingForm">
            <input type="text" name="planName" id="planName" placeholder="Plan Name" required>
            <div id="trainingDays">
            <!-- Here will be added the training days tables -->
            </div>
            <button class="addDay" type="button" id="addDay">Add Day</button>
            <button class="savePlanBtn" type="submit" id="savePlan">Save Plan</button>
        </form>

    
    
</div>