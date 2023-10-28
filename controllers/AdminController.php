<?php 


class AdminController {

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

    

    public function handleAddUser ($db, $data) {
        $userModel = new UserModel($db);
        
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $confirm_password = $data['confirm_password'];
        //check if there is already a user with the same username
        $userModel = new UserModel($db);
        // Validate the data (You can create validation functions)

        $errors = array();
        if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username) || strlen($username) < 3 || strlen($username) > 20) {
            array_push($errors, "Invalid username. Username must be between 3 and 20 characters long and can only contain letters, numbers and underscores.");
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address.");
        }
        if ($password !== $confirm_password) {
            array_push($errors, "Passwords do not match.");
        }
        if (empty($password)) {
            array_push($errors, "Password is required.");
        } elseif (strlen($password) < 6 || strlen($password) > 20) {
            array_push($errors, "Password must be between 6 and 20 characters long.");
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9!@#$%^&*])/', $password)) {
            array_push($errors, "Password must include at least one uppercase letter, one lowercase letter, and either one digit or one of the special characters: !@#$%^&*");
        }
        
        // Check if the username or email already exists in the database
        $user = $userModel->getUserByUsername($db, $username);
        if ($user) {
            if ($user['username'] === $username) {
                array_push($errors, "Username already exists.");
            }
        }
        $user = $userModel->getUserByEmail($db, $email);
        if ($user) {
            if ($user['username'] === $username) {
                array_push($errors, "Email already exists.");
            }
        }
        if (empty($errors)) {
            $userModel->addUser($db, $data);
            return $errors;
        } else {
            return $errors;
        }
    }

    public function handleDeleteUser () {
        $userModel = new UserModel($this->db);
        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        $userRole = $user['user_role'];
        if (!$userRole == "admin") {
            http_response_code(403); // Forbidden
            echo json_encode(array("error" => "You are not authorized to perform this action."));
            exit();
        }
        //check if there is a intiger in the url
        $deleteUserId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        //remove all non numeric characters
        $deleteUserId = preg_replace("/[^0-9]/", "", $deleteUserId);
        
        $userModel->deleteUser($this->db, $deleteUserId);
        header('Location: /admin');
        exit();
    }

    public function handleEditUser () {
        $userModel = new UserModel($this->db);

        $errors = array();

        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $userId = end($url);
        $user = $userModel->getUserById($this->db, $userId);

        $titlePage = 'GymNote - Edit User';

        $username = $user['username'];
        $email = $user['email'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $user_role = $user['user_role'];
        $confirmed = $user['confirmed'];
        $date_registered = $user['date_registered'];
        $last_logged = $user['last_logged'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $data = $_POST;
            
            $username = $data['username'];
            $email = $data['email'];
            $password = $data['password'];
            $confirm_password = $data['confirm_password'];


            $errors = array();
            if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username) || strlen($username) < 3 || strlen($username) > 20) {
                array_push($errors, "Invalid username. Username must be between 3 and 20 characters long and can only contain letters, numbers and underscores.");
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Invalid email address.");
            }
            //check if password and confirm_password are not empty and if they match
            if (!empty($password) || !empty($confirm_password)) {
                if ($password !== $confirm_password) {
                    array_push($errors, "Passwords do not match.");
                } elseif (strlen($password) < 6 || strlen($password) > 20) {
                    array_push($errors, "Password must be between 6 and 20 characters long.");
                } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9!@#$%^&*])/', $password)) {
                    array_push($errors, "Password must include at least one uppercase letter, one lowercase letter, and either one digit or one of the special characters: !@#$%^&*");
                }
            }
            
            
            if (empty($errors)) {
                $userModel->editUser($this->db, $data);
                $_SESSION['message'] = "User successfully edited.";
                header('Location: /admin');
                exit();
            } else {
                return $errors;
            }
        
        }

        $isEdit = true;

        require_once '../views/shared/head.php';
        require_once '../views/admin_panel/admin.php';
        require_once '../views/shared/footer.php';
    }


    public function index() {
        $titlePage = 'GymNote - Admin Panel';

        if (!isset($_SESSION['user_id']) ) {
            header('Location: /login');
            exit();
        }
        $userModel = new UserModel($this->db);
        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        if (!$user['user_role'] == "admin") {
            header('Location: /dashboard');
            exit();
        }

        
        $errors = array();
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('/', $url);
        $url = end($url);
        
        
        if ($url == 'userlist' || $url == 'admin') {
            $titlePage = 'GymNote - User List';
            $users = $userModel->getAllUsers($this->db);
        } else if ($url == 'adduser') {
            $titlePage = 'GymNote - Add User';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve POST data
                $data = $_POST;
                $errors = $this->handleAddUser($this->db, $data);
                if (empty($errors)) {
                    $_SESSION['message'] = "User successfully added.";
                }
            }

        } else if ($url == 'edituser') {
            $titlePage = 'GymNote - Edit User';
        } else if ($url == 'viewdatabase') {
            $titlePage = 'GymNote - View Database';
        }





        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/admin_panel/admin.php';
        require_once '../views/shared/footer.php';
    }



}