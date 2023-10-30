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

    public function handleEditAccount ($db, $user, $data) {
        $errors = array();
        $userModel = new UserModel($db);
        $username = $user['username'];
        $newUsername = $data['username'];
        $email = $user['email'];
        $newEmail = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];

        //check if user fills anything, then check if that value is between 0 and 99
        if ($username != NULL && (strlen($username) < 3 || strlen($username) > 20)) {
            array_push($errors, "Username must be between 3 and 20 characters.");
        }
        
        if ($email != NULL && (strlen($email) < 3 || strlen($email) > 50)) {
            array_push($errors, "Email must be between 3 and 50 characters.");
        }

        if ($first_name != NULL && (strlen($first_name) < 3 || strlen($first_name) > 20)) {
            array_push($errors, "First name must be between 3 and 20 characters.");
        }

        if ($last_name != NULL && (strlen($last_name) < 3 || strlen($last_name) > 20)) {
            array_push($errors, "Last name must be between 3 and 20 characters.");
        }

        if ($username != NULL && $username != $newUsername) {
            $userExists = $userModel->getUserByUsername($db, $newUsername);
            if ($userExists != NULL && $userExists['id'] != $user['id']) {
                array_push($errors, "Username already exists.");
            }
        }

        if ($email != NULL && $email != $newEmail) {
            $emailExists = $userModel->getUserByEmail($db, $newEmail);
            if ($emailExists != NULL && $emailExists['id'] != $user['id']) {
                array_push($errors, "Email already exists.");
            }
        }

        if ($errors == NULL) {
            $userModel->updateUserAccount($db, $user['id'], $data);
        } else {
            return $errors;
        }
    } 

    public function handleChangePassword ($db, $user, $data) {
        $errors = array();
        $userModel = new UserModel($db);
        $oldPassword = $data['old_password'];
        $newPassword = $data['password'];
        $confirmPassword = $data['confirm_password'];

        //check if the old password is correct
        if (!password_verify($oldPassword, $user['password'])) {
            array_push($errors, "Old password is incorrect.");
        }

        if ($newPassword != $confirmPassword) {
            array_push($errors, "New password and confirm password must match.");
        }

        if ($errors == NULL) {
            $userModel->updatePassword($db, $user['id'], $newPassword);
        } else {
            return $errors;
        }
    }

    public function handleEditBio ($db, $user, $data) {
        $errors = array();
        $userModel = new UserModel($db);
        $age = $data['age'];
        $gender = $data['gender'];
        $height = $data['height'];
        $weight = $data['weight'];
        $calories_goal = $data['calories_goal'];

        //check if user fills anything, then check if that value is between 0 and 99
        if ($age != NULL && ($age < 0 || $age > 99)) {
            array_push($errors, "Age must be between 0 and 99.");
        }
        
        if ($height != NULL && ($height < 0 || $height > 300)) {
            array_push($errors, "Height must be between 0 and 300.");
        }

        if ($weight != NULL && ($weight < 0 || $weight > 1000)) {
            array_push($errors, "Weight must be between 0 and 1000.");
        }

        if ($calories_goal != NULL && ($calories_goal < 0 || $calories_goal > 15000)) {
            array_push($errors, "Calories goal must be between 0 and 15000.");
        }

        if ($errors == NULL) {
            $userModel->updateUserBio($db, $user['id'], $data);
        } else {
            return $errors;
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
    
    public function handleDeletePicture ($db, $user) {
        $userModel = new UserModel($db);
        $userBio = $userModel->getUserBioById($db, $user['id']);
        $target_dir = "../public/img/uploads/profile_pictures/";
        $oldPicture = $target_dir . $userBio['profile_picture'];
        unlink($oldPicture);
        $userModel->updateProfilePicture($db, $user['id'], NULL);
        $_SESSION['profile_picture'] = NULL;
        $_SESSION['message'] = "Profile picture deleted successfully.";
    }


    public function index() {
        $titlePage = 'GymNote - Profile';

        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel($this->db);
            $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
            $errors = array();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (isset($_POST['delete_picture'])) {
                    $this->handleDeletePicture($this->db, $user);
                    header('Location: /profile');
                    exit;
                }

                if (isset($_POST['submit_picture'])) {
                    $picture = $_FILES['fileToUpload'];
                    $errors = $this->handleUploadPicture($this->db, $picture, $user);
                    if (empty($errors)) {
                        $_SESSION['message'] = "Profile picture changed successfully.";
                        header('Location: /profile');
                        exit;
                    }    
                }

                if (isset($_POST['save_bio'])) {
                    $data = $_POST;
                    $errors = $this->handleEditBio($this->db, $user, $data);
                    if (empty($errors)) {
                        $_SESSION['message'] = "Bio changed successfully.";
                        header('Location: /profile');
                        exit;
                    }
                }

                if (isset($_POST['save_account'])) {
                    $data = $_POST;
                    $errors = $this->handleEditAccount($this->db, $user, $data);
                    if (empty($errors)) {
                        $_SESSION['message'] = "Account info changed successfully.";
                        header('Location: /profile');
                        exit;
                    }
                }

                if (isset($_POST['change_password'])) {
                    $data = $_POST;
                    $errors = $this->handleChangePassword($this->db, $user, $data);
                    if (empty($errors)) {
                        $_SESSION['message'] = "Password changed successfully.";
                        header('Location: /profile');
                        exit;
                    }
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

            function ordinal($number) {
                $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                if ((($number % 100) >= 11) && (($number % 100) <= 13))
                    return $number . 'th';
                else
                    return $number . $ends[$number % 10];
            }

            
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