<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Friends</h2>
    
    <div class="one-third">
        <h3>Find a friend</h3>
        <input type="text" id="searchFriend" placeholder="Start typing your friends username">
        <div id="suggestions">
            
        </div>
    </div>
    <div class="one-third">
        <h3>My friends</h3>
    </div>
    <div class="one-third">
        <h3>Friend requests</h3>
        <div id="friendRequests">
            <div class="recievedRequests">
                <h4>Recieved requests</h4>
                <?php
                    if (count($friendRequests) > 0) {
                        foreach ($friendRequests as $friendRequest) {
                            //convert $friendRequest['profile_picture'] to string
                            
                            echo '<div class="friendRequest user-row">';
                            echo '    <div class="friendRequestAvatar user-row-element">';

                            if ($friendRequest['profile_picture'] !== null) {
                                echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/' . $friendRequest['profile_picture'] . '" alt="Profile picture">';
                            } else {
                                echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/default.png" alt="Profile picture">';
                            }

                            echo '    </div>';
                            echo '    <div class="friendRequestUsername user-row-element">';
                            echo '        <p>' . $friendRequest['username'] . '</p>';
                            echo '    </div>';
                            echo '    <div class="friendRequestButtons user-row-element">';
                            echo '        <button class="acceptFriendRequest" data-user-id="' . $friendRequest['user_id1'] . '">Accept</button>';
                            echo '        <button class="denyFriendRequest" data-user-id="' . $friendRequest['user_id2'] . '">Deny</button>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>You have no friend requests.</p>';
                    }
                ?>
            </div>
            <div class="sentRequests">
                <h4>Sent requests</h4>
                <?php
                    if (count($sentRequests) > 0) {
                        foreach ($sentRequests as $sentRequest) {
                            //convert $friendRequest['profile_picture'] to string
                            
                            echo '<div class="friendRequest user-row">';
                            echo '    <div class="friendRequestAvatar user-row-element">';

                            if ($sentRequest['profile_picture'] !== null) {
                                echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/' . $sentRequest['profile_picture'] . '" alt="Profile picture">';
                            } else {
                                echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/default.png" alt="Profile picture">';
                            }

                            echo '    </div>';
                            echo '    <div class="friendRequestUsername user-row-element">';
                            echo '        <p>' . $sentRequest['username'] . '</p>';
                            echo '    </div>';
                            echo '    <div class="friendRequestButtons user-row-element">';
                            echo '        <button class="cancelFriendRequest" data-user-id="' . $sentRequest['user_id2'] . '">Cancel</button>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>You have no sent requests.</p>';
                    }
                ?>
            </div>
        </div>
    </div>


        
    
</div>