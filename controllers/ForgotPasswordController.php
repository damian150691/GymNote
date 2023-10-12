<?php 


class ForgotPasswordController {

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

    public function handleResetPassword ($db, $email) {
        $userModel = new UserModel($db);
        $errors = array();
        if (empty($email)) {
            array_push($errors, "Email is required.");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Invalid email address.");
        }
        $user = $userModel->getUserByEmail($db, $email);
        if (!$user) {
            array_push($errors, "Email does not exist in our database.");
            return $errors;
        }
        if (empty($errors)) {
            $token = $userModel->generateToken(100);
            $id = $user['id'];
            //update token in database 
            $userModel->updateToken($db, $user['id'], $token);
            //send email with token
            $to = $user['email'];
            $subject = "Strenghtify - Reset password";
            $message = "Reset your password by clicking link below: \n\n http://localhost:8080/setnewpassword?token=$token \n\n If you did not request a password reset, please ignore this email.";
            $headers = "From: damian.miela@gmail.com";
            if ($userModel->sendEmail($to, $subject, $message, $headers)) {
                $_SESSION['message'] = "Email with reset link has been sent to your email address.";
                header('location: /login');
                exit;
                return true;
            } else {
                array_push($errors, "Email could not be sent.");
                array_push($errors, "$to, $subject, $message, $headers");
                return $errors;
            }
        } else {
            return $errors;
        }
    }


    public function index() {
        $titlePage = 'Strenghtify - Reset password';

        if (isset($_POST['forgotpassword'])) {
            $email = $_POST['reset_email'];
        
            $forgotPasswordResult = $this->handleResetPassword($this->db, $email);
 
            if ($forgotPasswordResult !== true) {
                // Registration failed, $registration_result contains validation errors
                $errors = $forgotPasswordResult;
            }
        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/forgot_password/forgot_password_form.php';
        require_once '../views/shared/footer.php';
    }



}