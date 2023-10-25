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

    

    public function handleAddUser () {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Read and decode the JSON data from the request body
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if ($data) {
                
                $userModel = new UserModel($this->db);
                $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
                $userRole = $user['user_role'];
                if (!$userRole == "admin") {
                    http_response_code(403); // Forbidden
                    echo json_encode(array("error" => "You are not authorized to perform this action."));
                    exit();
                }
                
                $response = $userModel->addUser($this->db, $data);

                if ($response) {
                    $response = array(
                        "message" => "Data received on the server",
                        "data" => $data
                    );
                } else {
                    http_response_code(500); // Internal Server Error
                    $response = array("error" => "An error occurred while saving data.");
                }
                
                // Set the response content type to JSON
                header("Content-Type: application/json");

                // Send the JSON response
                echo json_encode($response);
            } else {
                // Handle JSON parsing error
                http_response_code(400); // Bad Request
                echo json_encode(array("error" => "Invalid JSON data."));
            }
        } else {
            // Handle invalid request method
            http_response_code(405); // Method Not Allowed
            echo json_encode(array("error" => "Invalid request method."));
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


    public function index() {
        $titlePage = 'GymNote - Admin Panel';

        if (!isset($_SESSION['user_id']) ) {
            header('Location: /login');
            exit();
        }

        $userModel = new UserModel();

        $users = $userModel->getAllUsers($this->db);



        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/admin_panel/admin.php';
        require_once '../views/shared/footer.php';
    }



}