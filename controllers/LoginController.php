<?php 


class LoginController {

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


    public function handleLogin($db, $loginInput, $password) {
        
        $userModel = new UserModel($db);   
        $errors = array();
        if (empty($loginInput)) {
            array_push($errors, "Username or email is required.");
        }
        if (empty($password)) {
            array_push($errors, "Password is required.");
        }
        
        if ($userModel->IsInputEmailOrUsername($db, $loginInput) == "email") {
            $user = $userModel->getUserByEmail($db, $loginInput);
        } elseif ($userModel->IsInputEmailOrUsername($db, $loginInput) == "username") {
            $user = $userModel->getUserByUsername($db, $loginInput);
        } else {
            array_push($errors, "Wrong username or password.");
                return $errors;
        }

        //check if user is verified
        if ($user['confirmed'] == 0) {
            array_push($errors, "You need to verify your account first.");
        }
        

        if (empty($errors)) {
            // All data is valid, proceed with login
            // Call the user login function in UserModel
            $loginResult = $userModel->loginUser($db, $loginInput, $password);
            if ($loginResult === true) {
                session_start();
                
                
                             
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['confirmed'] = $user['confirmed'];
                $_SESSION['date_registered'] = $user['date_registered'];
                $_SESSION['last_logged'] = $user['last_logged'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['last_activity'] = time();

                


                
                header('Location: /dashboard');
                exit;
            } else {
                array_push($errors, "Wrong username or password.");
                return $errors;
            }
        } else {
            return $errors;
        }
    }

    public function handleLogout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }


    public function index() {
        $titlePage = 'Strenghtify - Login';

        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $loginInput = $_POST['login_input'];
            $password = $_POST['password'];

            // Call the registration function
            $loginResult = $this->handleLogin($this->db, $loginInput, $password);
            

            
            if ($loginResult !== true) {
                // Registration failed, $registration_result contains validation errors
                
                $errors = $loginResult;

            }
        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/login/login_form.php';
        require_once '../views/shared/footer.php';
    }



}




/*
    // Log the user out
    public function logout() {
        // Implement this function to log the user out and redirect
    }

    // Redirect to the home page if authenticated
    public function redirectToHomeIfAuthenticated() {
        // Implement this function to check if the user is authenticated and redirect
    }

    // Handle a failed login attempt
    protected function handleFailedLogin() {
        // Implement this function to handle a failed login attempt
    }

    // Reset a user's password
    public function resetPassword(Request $request) {
        // Implement this function to handle password reset
    }

    // Change a user's password
    public function changePassword(Request $request) {
        // Implement this function to handle password change
    }

    // Handle two-factor authentication
    public function twoFactorAuth(Request $request) {
        // Implement this function to handle two-factor authentication
    }

    // Handle social login callback
    public function socialLoginCallback(Request $request) {
        // Implement this function to handle social login callbacks
    }

    // Remember the user's session
    protected function rememberMe(Request $request) {
        // Implement this function to handle "Remember Me" functionality
    }

    // Add error handling functions
    protected function handleAuthenticationError() {
        // Implement this function to handle authentication errors
    }
*/