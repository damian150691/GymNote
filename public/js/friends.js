document.addEventListener("DOMContentLoaded", function () {
   console.log("friends.js loaded");

   
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
                    console.log(data);
                    let suggestions = document.getElementById('suggestions');
                    suggestions.innerHTML = '';
                    data.forEach(user => {
                    
                        const suggestedFriendDiv = document.createElement('div');
                        suggestedFriendDiv.className = 'suggestedFriend user-row';

                        const avatarDiv = document.createElement('div');
                        avatarDiv.className = 'suggestedFriendAvatar user-row-element';
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
                            console.log(friendId);

                            fetch('/addfriend', {
                                method: 'POST',
                                body: new URLSearchParams('friendId=' + friendId),
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                if(data.success) {
                                    addButton.textContent = 'Friend request sent';
                                    addButton.disabled = true;

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
  
});