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
        <div id="friends">
            <?php
                if (count($acceptedFriends) > 0) {
                    foreach ($acceptedFriends as $friend) {
                        if ($friend['user_id1'] === $_SESSION['user_id']) {
                            $friend['user_id'] = $friend['user_id2'];
                        } else {
                            $friend['user_id'] = $friend['user_id1'];
                        }
                        //convert $friend['profile_picture'] to string
                        
                        echo '<div class="friend user-row">';
                        echo '    <div class="friendAvatar user-row-element">';

                        if ($friend['profile_picture'] !== null) {
                            echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/' . $friend['profile_picture'] . '" alt="Profile picture">';
                        } else {
                            echo '        <img width="50px" height="50px" src="../img/uploads/profile_pictures/default.png" alt="Profile picture">';
                        }

                        echo '    </div>';
                        echo '    <div class="friendUsername user-row-element">';
                        echo '        <p>' . $friend['username'] . '</p>';
                        echo '    </div>';
                        echo '    <div class="friendButtons user-row-element">';
                        echo '        <button class="removeFriend" data-user-id="' . $friend['user_id'] . '">Remove</button>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p id="noFriends">You have no friends yet. You can use the search bar to find new friends.</p>';
                }
            ?>
        </div>
    </div>
    <div class="one-third">
        <h3>Friend requests</h3>
        <div id="friendRequests">
            <div id="receivedRequests">
                <h4>Received requests</h4>
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
                            echo '        <button class="denyFriendRequest" data-user-id="' . $friendRequest['user_id1'] . '">Deny</button>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>You have not received any friend requests.</p>';
                    }
                ?>
            </div>
            <div id="sentRequests">
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
                        echo '<p id="noSentRequests">You have not sent any friend requests</p>';
                    }
                ?>
            </div>
        </div>
    </div>


        
    
</div>