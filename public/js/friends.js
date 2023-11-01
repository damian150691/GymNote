document.addEventListener("DOMContentLoaded", function () {
   console.log("friends.js loaded");

   
    function searchFriend () {
        document.getElementById('searchFriend').addEventListener('keyup', function() {
            let input = this.value;
            // Only trigger AJAX if input length is greater than or equal to 3
            if(input.length >= 3) {
                // Delay the function by 1 second
                clearTimeout(window.timer);
                window.timer = setTimeout(function() {
                    fetch('/searchfriend', {
                        method: 'POST',
                        body: new URLSearchParams('query=' + input),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        let suggestions = document.getElementById('suggestions');
                        suggestions.innerHTML = '';
                        data.forEach(user => {
                            const suggestedFriendDiv = document.createElement('div');
                            suggestedFriendDiv.className = 'suggestedFriend user-row';
    
                            const avatarDiv = document.createElement('div');
                            avatarDiv.className = 'suggestedFriendAvatar user-row-element';

                            const avatarImg = document.createElement('img');

                            if (user.profile_picture == null) {
                                user.profile_picture = 'default.png';
                            }
                            avatarImg.src = "../img/uploads/profile_pictures/" + user.profile_picture;
                            
                            //set image width and height to 50px\
                            avatarImg.style.width = "50px";
                            avatarImg.style.height = "50px";
                            avatarImg.alt = "Profile picture";

                            avatarDiv.appendChild(avatarImg);

                            suggestedFriendDiv.appendChild(avatarDiv);
    
                            const usernameDiv = document.createElement('div');
                            usernameDiv.className = 'suggestedFriendUsername user-row-element';
    
                            const usernameP = document.createElement('p');
                            usernameP.textContent = user.username; // Assuming you have a user object with a username property
                            usernameDiv.appendChild(usernameP);
                            suggestedFriendDiv.appendChild(usernameDiv);
    
                            const addFriendDiv = document.createElement('div');
                            addFriendDiv.className = 'suggestedFriendAdd user-row-element';
    
                            const addButton = document.createElement('button');
                            addButton.id = "afb" + user.id;
                            addButton.textContent = 'Add friend';
    
                            addButton.addEventListener('click', function(event) {
                                event.preventDefault();
                                let friendId = this.id.substring(3);
    
                                fetch('/addfriend', {
                                    method: 'POST',
                                    body: new URLSearchParams('friendId=' + friendId),
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.success) {
                                        addButton.textContent = 'Friend request sent';
                                        addButton.disabled = true;


                                        // append new div to friend requests
                                        const sentRequestsDiv = document.getElementById('sentRequests');

                                        //check if there is a <p> element with the text "You have no sent friend requests"
                                        //if there is, remove it
                                        const noSentRequestsP = document.getElementById('noSentRequests');
                                        if (noSentRequestsP) {
                                            noSentRequestsP.remove();
                                        }

                                        const friendRequestDiv = document.createElement('div');
                                        friendRequestDiv.className = 'friendRequest user-row';
                                        
                                        const avatarDiv = document.createElement('div');
                                        avatarDiv.className = 'friendRequestAvatar user-row-element';

                                        const avatarImg = document.createElement('img');

                                        if (user.profile_picture == null) {
                                            user.profile_picture = 'default.png';
                                        }
                                        avatarImg.src = "../img/uploads/profile_pictures/" + user.profile_picture;
                                        
                                        //set image width and height to 50px\
                                        avatarImg.style.width = "50px";
                                        avatarImg.style.height = "50px";
                                        avatarImg.alt = "Profile picture";
                                        avatarDiv.appendChild(avatarImg);
                                        friendRequestDiv.appendChild(avatarDiv);

                                        const usernameDiv = document.createElement('div');
                                        usernameDiv.className = 'friendRequestUsername user-row-element';  

                                        const usernameP = document.createElement('p');
                                        usernameP.textContent = user.username;
                                        usernameDiv.appendChild(usernameP);
                                        friendRequestDiv.appendChild(usernameDiv);

                                        const cancelDiv = document.createElement('div');
                                        cancelDiv.className = 'friendRequestCancel user-row-element';
                                        
                                        const cancelButton = document.createElement('button');
                                        cancelButton.className = 'cancelFriendRequest';
                                        cancelButton.textContent = 'Cancel';
                                        cancelButton.setAttribute('data-user-id', user.id);
                                        cancelDiv.appendChild(cancelButton);
                                        friendRequestDiv.appendChild(cancelDiv);

                                        sentRequestsDiv.appendChild(friendRequestDiv);
                                        cancelFriendRequest();


    
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
    
                               
                            });
    
                            addFriendDiv.appendChild(addButton);
                            suggestedFriendDiv.appendChild(addFriendDiv);
    
                            suggestions.appendChild(suggestedFriendDiv);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }, 500);
            } else {
                let suggestions = document.getElementById('suggestions');
                suggestions.innerHTML = '';
            }
        });
    }

    function cancelFriendRequest () {
        cancelButtons = document.querySelectorAll('.cancelFriendRequest');
    
        cancelButtons.forEach(cancelButton => {
            cancelButton.addEventListener('click', function(event) {
                event.preventDefault();
                let friendId = cancelButton.getAttribute('data-user-id');
                

                fetch('/cancelfriendrequest', {
                    method: 'POST',
                    body: new URLSearchParams('friendId=' + friendId),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cancelButton.textContent = 'Friend request canceled';
                        friendRequestDiv = cancelButton.parentElement.parentElement;
                        // Set the friend request message
                        friendRequestDiv.innerHTML = '<p class="center" style="width: 100%">Friend request canceled</p>';

                        friendRequestDiv.remove(); // This will remove the friendRequestDiv from the DOM


                        //check if there are any more friend requests
                        const sentRequestsDiv = document.getElementById('sentRequests');
                        

                        if (sentRequestsDiv.children.length == 1) {
                            //if there are no more friend requests, append a <p> element with the text "You have no sent friend requests"
                            const noSentRequestsP = document.createElement('p');
                            noSentRequestsP.id = 'noSentRequests';
                            noSentRequestsP.textContent = 'You have not sent any friend requests';
                            sentRequestsDiv.appendChild(noSentRequestsP);
                        }
                    }
                  })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    function acceptFriendRequest () {
        acceptButtons = document.querySelectorAll('.acceptFriendRequest');
    
        acceptButtons.forEach(acceptButton => {
            acceptButton.addEventListener('click', function(event) {
                event.preventDefault();
                let friendId = acceptButton.getAttribute('data-user-id');
                

                fetch('/acceptfriendrequest', {
                    method: 'POST',
                    body: new URLSearchParams('friendId=' + friendId),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        
                        friendRequestDiv = acceptButton.parentElement.parentElement;
                        friendDiv = friendRequestDiv.cloneNode(true);

                        //remove the accept and deny buttons

                        friendDiv.removeChild(friendDiv.lastChild);
                        
                        //add div with remove friend button
                        const removeDiv = document.createElement('div');
                        removeDiv.className = 'friendButtons user-row-element';

                        const removeButton = document.createElement('button');
                        removeButton.className = 'removeFriend';
                        removeButton.textContent = 'Remove';
                        removeButton.setAttribute('data-user-id', friendId);
                        removeDiv.appendChild(removeButton);
                        friendDiv.appendChild(removeDiv);

                        //change class of friendDiv to friend
                        friendDiv.className = 'friend user-row';

                        //change class of avatarDiv to friendAvatar
                        const avatarDiv = friendDiv.firstChild;
                        avatarDiv.className = 'friendAvatar user-row-element';

                        //change class of usernameDiv to friendUsername
                        const usernameDiv = avatarDiv.nextSibling;
                        usernameDiv.className = 'friendUsername user-row-element';

                        noFriendsP = document.getElementById('noFriends');
                        if (noFriendsP) {
                            noFriendsP.remove();
                        }

                        //append new div to friends
                        const friendsDiv = document.getElementById('friends');
                        friendsDiv.appendChild(friendDiv);

                        removeFriend();

                        // Set the friend request message
                        friendRequestDiv.innerHTML = '<p class="center" style="width: 100%">Friend request accepted</p>';
                  

                        friendRequestDiv.remove(); // This will remove the friendRequestDiv from the DOM


                        //check if there are any more friend requests
                        const receivedRequestsDiv = document.getElementById('receivedRequests');
                        

                        if (receivedRequestsDiv.children.length == 1) {
                            //if there are no more friend requests, append a <p> element with the text "You have no received friend requests"
                            const noReceivedRequestsP = document.createElement('p');
                            noReceivedRequestsP.id = 'noReceivedRequests';
                            noReceivedRequestsP.textContent = 'You have not received any friend requests.';
                            receivedRequestsDiv.appendChild(noReceivedRequestsP);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    function denyFriendRequest () {
        denyButtons = document.querySelectorAll('.denyFriendRequest');


        denyButtons.forEach(denyButton => { 
            denyButton.addEventListener('click', function(event) {
                event.preventDefault();

                let friendId = denyButton.getAttribute('data-user-id');


                fetch('/denyfriendrequest', {
                    method: 'POST',
                    body: new URLSearchParams('friendId=' + friendId),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        
                        friendRequestDiv = denyButton.parentElement.parentElement;
                        // Set the friend request message
                        friendRequestDiv.innerHTML = '<p class="center" style="width: 100%">Friend request denied</p>';


                        friendRequestDiv.remove(); // This will remove the friendRequestDiv from the DOM

                        //wait for the animation to finish before checking if there are any more friend requests

                            //check if there are any more friend requests
                            const receivedRequestsDiv = document.getElementById('receivedRequests');
                            

                            if (receivedRequestsDiv.children.length == 1) {
                                
                                //if there are no more friend requests, append a <p> element with the text "You have no received friend requests"
                                const noReceivedRequestsP = document.createElement('p');
                                noReceivedRequestsP.id = 'noReceivedRequests';
                                noReceivedRequestsP.textContent = 'You have no friend requests.';
                                receivedRequestsDiv.appendChild(noReceivedRequestsP);
                            }
                        }
                    })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    function removeFriend () {
        removeButtons = document.querySelectorAll('.removeFriend');


        removeButtons.forEach(removeButton => { 
            removeButton.addEventListener('click', function(event) {
                event.preventDefault();

                let friendId = removeButton.getAttribute('data-user-id');


                fetch('/removefriend', {
                    method: 'POST',
                    body: new URLSearchParams('friendId=' + friendId),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        
                        friendDiv = removeButton.parentElement.parentElement;
                        // Set the friend request message
                        friendDiv.innerHTML = '<p class="center" style="width: 100%">Friend removed</p>';
                  

                        friendDiv.remove(); 
                        
                        //check if there are any more friend requests
                        const friendsDiv = document.getElementById('friends');

                        if (friendsDiv.children.length == 0) {
                            //if there are no more friends, append a <p> element with the text "You have no friends"
                            const noFriendsP = document.createElement('p');
                            noFriendsP.id = 'noFriends';
                            noFriendsP.textContent = 'You have no friends yet. You can use the search bar to find new friends.';
                            friendsDiv.appendChild(noFriendsP);
                        }
                       
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }


    searchFriend();
    cancelFriendRequest();
    acceptFriendRequest();
    denyFriendRequest();
    removeFriend();
  
});