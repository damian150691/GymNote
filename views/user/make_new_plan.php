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
            <div class="flex-wrapper">
                <div class="fifty-fifty">
                    <label for="makePlanFor">I am making plan for:<span class="small"></span></label>
                    <select name="makePlanFor" id="makePlanFor">
                        <option value="<?php echo $user['id'] ?>" selected>Myself</option>
                        <?php
                        foreach ($friendsList as $friend) {
                            echo "<option value=\"" . $friend['id'] . "\">" . $friend['username'] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="planName">Plan name <span class="small">(optional):</span></label>
                    <input type="text" name="planName" id="planName" placeholder="MyPlan"  class="MNPheaderInputs">

                    <label for="initialWeight">Initial weight <span class="small">(optional):</span></label>
                    <input type="number" name="initialWeight" id="initialWeight" class="MNPheaderInputs" placeholder="kg" min="0" step="0.1">
                    
                </div>
                <div class="fifty-fifty">
                    <label for="caloriesGoal">Calories goal <span class="small">(optional):</span></label>
                    <input type="number" name="caloriesGoal" id="caloriesGoal" class="MNPheaderInputs" placeholder="kcal">

                    <div class="spcr"></div>   

                    <div class="flex-wrapper">
                        <div class="one-third">
                            <label for="proteinsGoal">Proteins goal <span class="small">(optional):</span></label>
                            <input type="number" name="proteinsGoal" id="proteinsGoal" class="MNPheaderNutrientsInputs" placeholder="g">
                        </div>

                        <div class="one-third">
                            <label for="carbsGoal">Carbohydrates goal <span class="small">(optional):</span></label>
                            <input type="number" name="carbsGoal" id="carbsGoal" class="MNPheaderNutrientsInputs" placeholder="g">
                        </div>

                        <div class="one-third">
                            <label for="fatsGoal">Fats goal <span class="small">(optional):</span></label>
                            <input type="number" name="fatsGoal" id="fatsGoal" class="MNPheaderNutrientsInputs" placeholder="g">
                        </div>
                    </div>

                    <label for="isActive" id="isActiveLabel">Set as active plan:</label>
                    <input type="checkbox" name="isActive" id="isActive">
                </div>

            </div>
            
            
            <div id="trainingDays">
            <!-- Here will be added the training days tables -->
            </div>
            <button class="addDay" type="button" id="addDay">Create Day</button>
            <button class="savePlanBtn" type="submit" id="savePlan">Save Plan</button>
        </form>

    
    
</div>