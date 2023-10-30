<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
    
    
    <h2>Profile</h2>
    <?php require_once "../views/shared/errors.php"; ?>
    <div class="profile-section">
        
        <h3 class="center">Account Information</h3>

        <div class="accountDetails">
            <p><span class="userProfileRowName">Username: </span><?php echo $user['username'] ?></p>
            <p><span class="userProfileRowName">Email: </span><?php echo $user['email'] ?></p>
            <p><span class="userProfileRowName">First Name: </span><?php echo $user['first_name'] ?></p>
            <p><span class="userProfileRowName">Last Name: </span><?php echo $user['last_name'] ?></p>
            <p><span class="userProfileRowName">Member since: </span>
                <?php 
                $day = ordinal(date('j', strtotime($user['date_registered'])));
                $month = date('F', strtotime($user['date_registered']));
                $year = date('Y', strtotime($user['date_registered']));
                echo $day . " " . $month . " " . $year; 
                ?>
            </p>
            <button class="editAccountButton">Edit account</button>
        </div>

        <form action="/profile" method="post" id="edit_account" class="hidden">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $user['username'] ?>">
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $user['email'] ?>">
            </div>
            <div class="input-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo $user['first_name'] ?>">
            </div>
            <div class="input-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo $user['last_name'] ?>">
            </div>
            <input type="submit" value="Save" name="save_account">
            <input type="submit" value="Cancel" name="cancel" id="cancelEditAccountButton">
        </form>

        <h3 class="center">Change password</h3>
        <form action="/profile" method="post" id="change_password">
            <div class="input-group">
                <label for="old_password">Old password:</label>
                <input type="password" name="old_password" required>
            </div>
            <div class="input-group">
                <label for="password">New password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <input type="submit" value="Change password" name="change_password">
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


    
    <div class="spcr"></div>
</div>