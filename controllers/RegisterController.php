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
            // All data is valid, proceed with registration
            
    
            // Call the user registration function in UserModel
            $registrationResult = $userModel->registerUser($db, $username, $email, $password);
            $user = $userModel->getUserByUsername($db, $username);
    
            if ($registrationResult === true) {
                $notification = [
                    'user_id' => $user['id'],
                    'content' => "We hope you will enjoy our app. If you have any questions, please contact us.",
                    'type' => 'registration',
                    'related_object_id' => 'contact_us'
                ];
                $userModel->createNotification($db, $notification);
                
                $token = $user['token'];
                $to = $email;
                $subject = "GymNote - Verify your email";
                $message = "Please click on the link below to verify your email address: \n\n http://damian.sadycelmerow.pl/verify?token=" . $token;


                if ($userModel->sendEmail($to, $subject, $message)) {
                    
                    

                    $_SESSION['message'] = "You have been registered successfully. E-mail with verification link has been sent to your e-mail address (" . $email . "). Please verify your account.";
                    header('Location: /login');
                    exit;
                } else {
                    array_push($errors, "Something went wrong with sending email. Please try again.");
                    return $errors;
                }

                
            } else {
                array_push($errors, "Something went wrong. Please try again.");
                return $errors;
            }
        } else {
            
            return $errors;
        }
    
        
    }

    public function verifyUser () {
        $errors = array();
        $db = $this->db;
        $userModel = new UserModel($db);
        $url = "$_SERVER[REQUEST_URI]";
        $requestUri = $_SERVER['REQUEST_URI'];
        $parts = parse_url($requestUri);

        if (isset($parts['query'])) {
            parse_str($parts['query'], $queryParameters);
            if (isset($queryParameters['token'])) {
                $token = $queryParameters['token'];
            } else {
                array_push($errors, "No token provided.");
            }
        } else {
            array_push($errors, "No token provided.");
        }
        if ($token === null || $token === false) {
            array_push($errors, "No token provided.");
            $_SESSION['message'] = "No token provided.";
            header('Location: /login');
        } else {
            $user = $userModel->getUserByToken($db, $token);
            if ($user) {
                if ($userModel->confirmUser($db, $token)) {
                    $_SESSION['message'] = "Your account has been verified. You can now log in.";
                    header('Location: /login');
                    exit;
                } else {
                    array_push($errors, "Something went wrong. Please try again.");
                    header('Location: /login');
                    exit;
                }
                
            } else {
                array_push($errors, "Invalid token. Please try again.");
                header('Location: /login');
                exit;
            }
        }
    }

    public function index() {

        $titlePage = 'GymNote - Register';
        
       
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



