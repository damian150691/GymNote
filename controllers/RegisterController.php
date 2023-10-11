<?php 


class RegisterController {

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



    public function handleRegistration($db, $username, $email, $password, $confirm_password) {
        $userModel = new UserModel($db);
        // Validate the data (You can create validation functions)
        $errors = array();
        if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            array_push($errors, "Invalid username.");
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address.");
        }
        if (empty($password) || strlen($password) < 6) {
            array_push($errors, "Password must be at least 6 characters long.");
        }
        if ($password !== $confirm_password) {
            array_push($errors, "Passwords do not match.");
        }
        // Check if the username or email already exists in the database
        $user = $userModel->getUserByUsername($db, $username);
        if ($user) {
            if ($user['username'] === $username) {
                array_push($errors, "Username already exists.");
            }
            if ($user['email'] === $email) {
                array_push($errors, "Email already exists.");
            }
        }
    
        if (empty($errors)) {
            // All data is valid, proceed with registration
            
    
            // Call the user registration function in UserModel
            $registrationResult = $userModel->registerUser($db, $username, $email, $password);
    
            if ($registrationResult === true) {
                // Registration successful, redirect to login page
                header('Location: /login');
                exit;
            } else {
                return false;
            }
        } else {
            
            return $errors;
        }
    
        
    }

    public function index() {

        $titlePage = 'Strenghtify - Register';
        
       
        $registration_result = array(); // Initialize the $registration_result array

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];


        
            // Call the registration function
            $registrationResult = $this->handleRegistration($this->db, $username, $email, $password, $confirm_password);
            

            
            if ($registrationResult !== true) {
                // Registration failed, $registration_result contains validation errors
                $errors = $registrationResult;
            }

            

        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/register/register_form.php';
        require_once '../views/shared/footer.php';
    }
}



