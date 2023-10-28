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


    public function handleLogin($db, $loginInput, $password, $rememberMe) {
        
        $userModel = new UserModel($db);   
        $errors = array();

        // Validate the input
        if (empty($loginInput)) {
            array_push($errors, "Username or email is required.");
            return $errors;
            exit;
        }
        if (empty($password)) {
            array_push($errors, "Password is required.");
            return $errors;
            exit;
        }
        if ($userModel->IsInputEmailOrUsername($db, $loginInput) == "email") {
            $user = $userModel->getUserByEmail($db, $loginInput);
        } elseif ($userModel->IsInputEmailOrUsername($db, $loginInput) == "username") {
            $user = $userModel->getUserByUsername($db, $loginInput);
        } else {
            array_push($errors, "Wrong username or password.");
            return $errors;
            exit;
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
                if ($rememberMe) {
                    $token = $userModel->generateToken(100); 
                    $userModel->updateSessionToken($db, $user['id'], $token);
                    setcookie('remember_me', $token, time() + 30 * 24 * 3600, '/');
                }
                // Login was successful, redirect to dashboard 
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
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }
        header('Location: /');
        exit;
    }

    public function checkRememberMeCookie ($db) {
        if (isset($_COOKIE['remember_me'])) {
            $userModel = new UserModel($db);
            $token = $_COOKIE['remember_me'];
            $user = $userModel->getUserBySessionToken($db, $token);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['confirmed'] = $user['confirmed'];
                $_SESSION['date_registered'] = $user['date_registered'];
                $_SESSION['last_logged'] = $user['last_logged'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['last_activity'] = time();
                $userModel->updateLastLoggedIn($db, $user['id']);
                header('Location: /dashboard');
                exit;
            } else {
                setcookie('remember_me', '', time() - 3600, '/');
            }
        }
    }


    public function index() {
        $titlePage = 'GymNote - Login';
        $errors = array();

        $this->checkRememberMeCookie($this->db);

        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        } 


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $loginInput = $_POST['login_input'];
            $password = $_POST['password'];
            if (isset($_POST['remember_me'])) {
                $rememberMe = $_POST['remember_me'];
            } else {
                $rememberMe = false;
            }

            // Call the registration function
            $errors = $this->handleLogin($this->db, $loginInput, $password, $rememberMe);
        
        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/login/login_form.php';
        require_once '../views/shared/footer.php';
    }



}