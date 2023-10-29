<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    
    
    <h2>Profile</h2>
    <?php require_once "../views/shared/errors.php"; ?>
    <div class="profile-section">
        
        <h3 class="center">Account Information</h3>
        <?php
        //display user info
        echo "<p><span class=\"userProfileRowName\" >Username:</span> " . $user['username'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Email:</span> " . $user['email'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >First Name:</span> " . $user['first_name'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Last Name:</span> " . $user['last_name'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Member since:</span> " . $user['date_registered'] . "</p>";
        ?>
        <button class="editAccountButton">Edit account</button>
    </div>
    <div class="profile-section">
        <h3 class="center">My bio</h3>
        <div class="bio">
            <p><span class="userProfileRowName">Age: </span><?php echo $userBio['age'] ?></p>
            <p><span class="userProfileRowName">Gender: </span><?php echo $userBio['gender'] ?></p>
            <p><span class="userProfileRowName">Height: </span><?php echo $userBio['height'] ?></p>
            <p><span class="userProfileRowName">Weight: </span><?php echo $userBio['weight'] ?></p>
            <p><span class="userProfileRowName">Calories goal: </span><?php echo $userBio['calories_goal'] ?></p>
            <button class="editBioButton">Edit Bio</button>
        </div>
        <form action="/profile" method="post" id="edit_bio" class="hidden">
            <div class="input-group">
                <label for="age">Age:</label>
                <input type="number" name="age" value="<?php echo $userBio['age'] ?>">
            </div>
            <!--dropdown list for gender-->
            <div class="input-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="male" <?php echo ($userBio['gender'] == 'male' ? 'selected' : ''); ?>>Male</option>
                    <option value="female" <?php echo ($userBio['gender'] == 'female' ? 'selected' : ''); ?>>Female</option>
                </select>
            </div>
            <div class="input-group">
                <label for="height">Height (cm):</label>
                <input type="number" name="height" value="<?php echo $userBio['height'] ?>">    
            </div>
            <div class="input-group">
                <label for="weight">Weight (kg):</label>
                <input type="number" step="0.1" name="weight" value="<?php echo $userBio['weight'] ?>">
            </div>
            <div class="input-group">
                <label for="calories_goal">Calories goal:</label>
                <input type="number" name="calories_goal" value="<?php echo $userBio['calories_goal'] ?>">
            </div>
            <input type="submit" value="Save" name="save_bio">
            <input type="submit" value="Cancel" name="cancel" id="cancelEditBioButton">
        </form>

        
    </div>
    <div class="profile-section">
        <h3 class="center">Profile picture</h3>

            <?php
            if ($userBio['profile_picture'] == NULL) {
                echo "<img src=\"../img/uploads/profile_pictures/default.png\" alt=\"Profile picture\" class=\"profile-picture\" />";
            } else {
                echo "<img src=\"../img/uploads/profile_pictures/" . $userBio['profile_picture'] . "\" alt=\"Profile picture\" class=\"profile-picture\" />";
            }
            ?>
            <form action="/profile" method="post" enctype="multipart/form-data" class="uploadPictureForm" id="upload_picture">
                <div class="file-upload-wrapper">
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <label for="fileToUpload" class="custom-file-upload">Choose File</label>
                    <span class="file-name">No file chosen</span>
                </div>
                <input type="submit" value="Change profile picture" name="submit_picture">
                <?php
                    if ($userBio['profile_picture'] != NULL) {
                        echo "<input type=\"submit\" value=\"Delete profile picture\" name=\"delete_picture\">";
                    }
                ?>
                
            </form>
            
    
        
    </div>
    <div class="spcr"></div>
</div>