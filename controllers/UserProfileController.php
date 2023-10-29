<?php 


class UserProfileController {

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

    
    public function handleUploadPicture ($db, $picture, $user) {
        $errors = array();
        $userModel = new UserModel($db);
        $userBio = $userModel->getUserBioById($db, $user['id']);

        //check if there is a file chosen and if it is valid
        if ($picture['name'] == "") {
            array_push($errors, "No file chosen.");
            return $errors;
        }   

        $target_dir = "../public/img/uploads/profile_pictures/";
        
        //set picture name to user id
        $target_file = $target_dir . basename($picture["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $pictureName = $user['id'] . "." . $imageFileType;
        $target_file = $target_dir . $pictureName;
        
        
        
        
        //check file size (max 5mb)
        if ($picture["size"] > 5000000) {
            array_push($errors, "Your file is too large. Max size is 5MB.");
            return $errors;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            array_push($errors, "Only JPG, JPEG, PNG files are allowed.");
            return $errors;
        }

        //if there is a profile picture with other extension, delete it
        if ($userBio['profile_picture'] != NULL) {
            $oldPicture = $target_dir . $userBio['profile_picture'];
            unlink($oldPicture);
        }

        if (empty($errors)) {
            if (move_uploaded_file($picture["tmp_name"], $target_file)) {
                

                $userModel->updateProfilePicture($db, $user['id'], $pictureName);
                $_SESSION['profile_picture'] = $pictureName;
                $_SESSION['message'] = "Profile picture changed successfully.";
                

            } else {
                array_push($errors, "Sorry, there was an error uploading your file.");
            }
        }
        
        return $errors;
        
                
    }
    


    public function index() {
        $titlePage = 'GymNote - Profile';

        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel($this->db);
            $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $picture = $_FILES['fileToUpload'];
                $errors = $this->handleUploadPicture($this->db, $picture, $user);
                if (empty($errors)) {
                    $_SESSION['message'] = "Profile picture changed successfully.";
                    header('Location: /profile');
                    exit;
                }
                
                
            }

            $userBio = $userModel->getUserBioById($this->db, $_SESSION['user_id']);
            if ($userBio == Null) {
                //assign array with empty values
                $userBio = [
                    'user_id' => $_SESSION['user_id'],
                    'age' => NULL,
                    'gender' => NULL,
                    'height' => NULL,
                    'weight' => NULL,
                    'calories_goal' => NULL,
                    'profile_picture' => NULL

                ]
            ;}

            
        } else {
            header('Location: /login');
            exit;
        }



        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/profile.php';
        require_once '../views/shared/footer.php';
    }



}