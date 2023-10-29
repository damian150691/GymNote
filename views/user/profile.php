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
    </div>
    <div class="profile-section">
        <h3 class="center">My bio</h3>
        <?php
        
        echo "<p><span class=\"userProfileRowName\" >Age:</span> " . $userBio['age'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Gender:</span> " . $userBio['age'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Height:</span> " . $userBio['height'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Weight:</span> " . $userBio['weight'] . "</p>";
        echo "<p><span class=\"userProfileRowName\" >Calories goal:</span> " . $userBio['calories_goal'] . "</p>";
        ?>
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
            <form action="/profile" method="post" enctype="multipart/form-data" class="uploadPictureForm">
                <div class="file-upload-wrapper">
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <label for="fileToUpload" class="custom-file-upload">Choose File</label>
                    <span class="file-name">No file chosen</span>
                </div>
                <input type="submit" value="Change profile picture" name="submit">
            </form>
    
        
    </div>
    <div class="spcr"></div>
</div>