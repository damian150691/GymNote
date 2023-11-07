<div id="top">
    <?php require_once "../views/shared/logo.php"; ?>
    <?php require_once "../views/shared/navbar.php"; ?>
</div>
<div id="content">
   
    <h2>Friends</h2>
    <div class="flex-wrapper">
        <div class="one-third">
            <h3><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg><span class="textIcon">Find a friend</span></h3>
            <input type="text" id="searchFriend" placeholder="Start typing your friends username">
            <div id="suggestions">
                
            </div>
        </div>
        <div class="one-third">
            <h3><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M48 48h88c13.3 0 24-10.7 24-24s-10.7-24-24-24H32C14.3 0 0 14.3 0 32V136c0 13.3 10.7 24 24 24s24-10.7 24-24V48zM175.8 224a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm-26.5 32C119.9 256 96 279.9 96 309.3c0 14.7 11.9 26.7 26.7 26.7h56.1c8-34.1 32.8-61.7 65.2-73.6c-7.5-4.1-16.2-6.4-25.3-6.4H149.3zm368 80c14.7 0 26.7-11.9 26.7-26.7c0-29.5-23.9-53.3-53.3-53.3H421.3c-9.2 0-17.8 2.3-25.3 6.4c32.4 11.9 57.2 39.5 65.2 73.6h56.1zm-89.4 0c-8.6-24.3-29.9-42.6-55.9-47c-3.9-.7-7.9-1-12-1H280c-4.1 0-8.1 .3-12 1c-26 4.4-47.3 22.7-55.9 47c-2.7 7.5-4.1 15.6-4.1 24c0 13.3 10.7 24 24 24H408c13.3 0 24-10.7 24-24c0-8.4-1.4-16.5-4.1-24zM464 224a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm-80-32a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM504 48h88v88c0 13.3 10.7 24 24 24s24-10.7 24-24V32c0-17.7-14.3-32-32-32H504c-13.3 0-24 10.7-24 24s10.7 24 24 24zM48 464V376c0-13.3-10.7-24-24-24s-24 10.7-24 24V480c0 17.7 14.3 32 32 32H136c13.3 0 24-10.7 24-24s-10.7-24-24-24H48zm456 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H608c17.7 0 32-14.3 32-32V376c0-13.3-10.7-24-24-24s-24 10.7-24 24v88H504z"/></svg><span class="textIcon">My friends<span></h3>
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
            <h3><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320H234.7c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7H106.7C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2H261.3C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7H455.2c-2.1-5.2-3.2-10.9-3.2-16.4v-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192H490.7c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3v-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6v3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3L574.4 311c-8.9-7.6-19.1-13.6-30.4-17.6v-21c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9v21c-11.2 4-21.5 10-30.4 17.6l-18.2-10.5c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l18.2 10.5c-1 5.7-1.6 11.6-1.6 17.6s.6 11.9 1.6 17.5l-18.2 10.5c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3L449.6 457c8.9 7.6 19.2 13.6 30.4 17.6v21c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9v-21c11.2-4 21.5-10 30.4-17.6l18.2 10.5c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-18.2-10.5c1-5.7 1.6-11.6 1.6-17.5s-.6-11.9-1.6-17.6l18.2-10.5zM472 384a40 40 0 1 1 80 0 40 40 0 1 1 -80 0z"/></svg><span class="textIcon">Friend requests</span></h3>
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


        
    
</div>