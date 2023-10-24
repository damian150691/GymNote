<?php 


class MakeNewPlanController {

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


    public function handleSavePlan () {
        
        // Check if the request method is POST
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Read and decode the JSON data from the request body
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if ($data) {
                
                $userModel = new UserModel($this->db);
                $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
                $userId = $user['id'];
                $userModel->savePlan($this->db, $userId, $data);
            
                
                $response = array(
                    "message" => "Data received on the server",
                    "data" => $data
                );

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


    public function index() {
        $titlePage = 'Strenghtify - Make a new Training Plan';
        $errors = array();
        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        } 

        




        
            
        

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/make_new_plan.php';
        require_once '../views/shared/footer.php';
    }



}