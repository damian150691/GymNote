<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>
        Plan
    </h2>
   
    
    <table id="MNP1" class="trainingTable">
        <thead>
            <tr>
                <th></th>
                <th>Exercise*</th>
                <th>Sets*</th>
                <th>Repetitions*</th>
                <th>Weight[kg]*</th>
                <th>Interval[s]</th>
                <th>Comments</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="setRow">
                <td colspan="5">Set ${dayCount}</td>
                <td></td>
                <td></td>
                <td>
                    <button class="edit-set-button">Edit</button>
                    <button class="delete-set-button">Delete</button>
                </td>
            </tr>
            <tr class="tableRow">
                <td class="table-row-id">1</td>
                <td>2</td>
                <td>2</td>
                <td>2</td>
                <td>2</td>
                <td>2</td>
                <td>-</td>
                <td>
                    <button class="edit-button">Edit</button>
                    <button class="delete-button">Delete</button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="tfootAddExercise" colspan="8">
                    <button class="addExercise">Add Exercise</button>
                </td>
            </tr>
        </tfoot>
    </table>
    
    
</div>