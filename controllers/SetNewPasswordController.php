<?php 


class SetNewPasswordController {

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

    public function handleSetNewPassword ($db, $password, $confirm_password, $token) {
        $userModel = new UserModel($db);
        $errors = array();
        if (empty($password)) {
            array_push($errors, "Password is required.");
        } elseif (strlen($password) < 6 || strlen($password) > 20) {
            array_push($errors, "Password must be between 6 and 20 characters long.");
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9!@#$%^&*])/', $password)) {
            array_push($errors, "Password must include at least one uppercase letter, one lowercase letter, and either one digit or one of the special characters: !@#$%^&*");
        }
        if ($password !== $confirm_password) {
            array_push($errors, "Passwords do not match.");
        }
        if (empty($errors)) {
            $user = $userModel->getUserByToken($db, $token);
            
            if (!$user) {
                array_push($errors, "Error occured. Please try again.");
                return $errors;
            }
            $userModel->updatePassword($db, $user['id'], $password);
            $userModel->updateToken($db, $user['id'], null);
            $_SESSION['message'] = "Your password has been changed. You can now log in.";
            header('location: /login');
            exit;
            return true;
        } else {
            return $errors;
        }
    }


    public function index() {
        $errors = array();
        $titlePage = 'Strenghtify - Set new password';

        $url = "$_SERVER[REQUEST_URI]";
        $requestUri = $_SERVER['REQUEST_URI'];
        $parts = parse_url($requestUri);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $queryParameters);
            if (isset($queryParameters['token'])) {
                $token = $queryParameters['token'];
            } else {
                $_SESSION['message'] = "Invalide or expired token. Please try again.";
                header('location: /forgotpassword');
                exit;
            }
        } else {
            $_SESSION['message'] = "Invalide or expired token. Please try again.";
            header('location: /forgotpassword');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve POST data
            $password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_new_password'];
            $errors = $this->handleSetNewPassword($this->db, $password, $confirm_password, $token);
        }        

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/forgot_password/set_new_password_form.php';
        require_once '../views/shared/footer.php';
    }



}