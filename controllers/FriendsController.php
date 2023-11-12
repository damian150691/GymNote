<?php 


class FriendsController {

    private $userModel;
    private $db;

    public function __construct() {

        
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');
        // Load the model file
        require_once 'UserModel.php';
        
    }

    public function setParams($params)
    {
        if (isset($params['db'])) {
            $this->db = $params['db'];
        }
    }

    public function handleSearchFriend () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $searchQuery = $_POST['query'];
            $searchResult = $userModel->searchFriend($this->db, $_SESSION['user_id'], $searchQuery);

            echo json_encode($searchResult);

        } 
    }

    public function handleAddFriend () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $friendId = $_POST['friendId'];
            $result = $userModel->addFriend($this->db, $_SESSION['user_id'], $friendId);

            if ($result) {
                
                $notification = [
                    'user_id' => $friendId,
                    'content' => 'You have a new friend request from ' . $_SESSION['username'] . '. Click here to accept or deny.',
                    'type' => 'friend_request',
                    'related_object_id' => $_SESSION['user_id']
                ];
                $userModel->createNotification($this->db, $notification);
                
                echo json_encode(array("success" => "Friend added successfully."));
            } else {
                echo json_encode(array("error" => "Error adding friend."));
            }
            
        }
    }

    public function handleCancelFriendRequest () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $friendId = $_POST['friendId'];
            $result = $userModel->cancelFriendRequest($this->db, $_SESSION['user_id'], $friendId);

            if ($result) {
                echo json_encode(array("success" => "Friend request cancelled successfully."));
            } else {
                echo json_encode(array("error" => "Error cancelling friend request."));
            }
            
        }
    }

    public function handleAcceptFriendRequest () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $friendId = $_POST['friendId'];
            $result = $userModel->acceptFriendRequest($this->db, $_SESSION['user_id'], $friendId);

            if ($result) {
                echo json_encode(array("success" => "Friend request accepted successfully."));
            } else {
                echo json_encode(array("error" => "Error accepting friend request."));
            }
            
        }
    }

    public function handleDenyFriendRequest () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $friendId = $_POST['friendId'];
            $result = $userModel->denyFriendRequest($this->db, $_SESSION['user_id'], $friendId);

            if ($result) {
                echo json_encode(array("success" => "Friend request denied successfully."));
            } else {
                echo json_encode(array("error" => "Error denying friend request."));
            }
            
        }
    }

    public function handleRemoveFriend () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $friendId = $_POST['friendId'];
            $result = $userModel->removeFriend($this->db, $_SESSION['user_id'], $friendId);

            if ($result) {
                echo json_encode(array("success" => "Friend removed successfully."));
            } else {
                echo json_encode(array("error" => "Error removing friend."));
            }
            
        }
    }


    public function index() {
        $titlePage = 'GymNote - Friends';

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $userModel = new UserModel($this->db);
        $friends = $userModel->getFriendsList($this->db, $_SESSION['user_id']);
        $friendRequests = $userModel->getFriendRequests($this->db, $_SESSION['user_id']);
        //add username to each row in $friendRequests
        foreach ($friendRequests as $key => $friendRequest) {
            $friendUserObject = $userModel->getUserById($this->db, $friendRequest['user_id1']);
            $friendRequests[$key]['username'] = $friendUserObject['username'];
            $friendRequests[$key]['profile_picture'] = $userModel->getProfilePicture($this->db, $friendRequest['user_id1']);
        }

        $sentRequests = $userModel->getSentRequests($this->db, $_SESSION['user_id']);
        //add username to each row in $sentRequests
        foreach ($sentRequests as $key => $sentRequest) {
            $sentUserObject = $userModel->getUserById($this->db, $sentRequest['user_id2']);
            $sentRequests[$key]['username'] = $sentUserObject['username'];
            $sentRequests[$key]['profile_picture'] = $userModel->getProfilePicture($this->db, $sentRequest['user_id2']);
        }

        $acceptedFriends = $userModel->getAcceptedFriends($this->db, $_SESSION['user_id']);
        //add username to each row in $acceptedFriends
        foreach ($acceptedFriends as $key => $acceptedFriend) {
            if ($acceptedFriend['user_id1'] == $_SESSION['user_id']) {
                $acceptedUserObject = $userModel->getUserById($this->db, $acceptedFriend['user_id2']);
            } else {
                $acceptedUserObject = $userModel->getUserById($this->db, $acceptedFriend['user_id1']);
            }
            $acceptedFriends[$key]['username'] = $acceptedUserObject['username'];
            $acceptedFriends[$key]['profile_picture'] = $userModel->getProfilePicture($this->db, $acceptedUserObject['id']);
        }
        

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/friends.php';
        require_once '../views/shared/footer.php';
    }



}