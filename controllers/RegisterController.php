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

        // Validate the data (You can create validation functions)
        $errors = [];
        if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Invalid username.';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }
        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }
    
        if (empty($errors)) {
            // All data is valid, proceed with registration
            $userModel = new UserModel($db);
    
            // Call the user registration function in UserModel
            $registration_result = $userModel->registerUser($db, $username, $email, $password);
    
            if ($registration_result === true) {
                // Registration successful, redirect to login page
                header('Location: /login');
                exit;
            } else {
                // Registration failed, handle the error (e.g., display an error message)
                $error_message = 'Registration failed. Please try again later.';
            }
        }
    
        // If we get here, something went wrong, display the error message
        if (!empty($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
    }
    
    public function index() {
        

        $titlePage = 'Strenghtify - Register';
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];


        
            // Call the registration function
            $this->handleRegistration($this->db, $username, $email, $password, $confirm_password);
            

        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/register/register_form.php';
        require_once '../views/shared/footer.php';
    }
}



