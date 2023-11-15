<?php 


class TrainingController {

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

    public function handleSaveTrainingSession () {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if ($data) {
                $userModel = new UserModel($this->db);
                $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
                
                $date = date('Y-m-d');
                $todaysSession = $userModel->checkTrainingSessionByDate($this->db, $user['id'], $date);

                if ($todaysSession == true) {
                    $response = $userModel->updateTrainingSession($this->db, $user['id'], $data, $date);
                    $response = array(
                        "message" => "Training session updated successfully.",
                        "data" => $data
                    );

                } else {
                    $response = $userModel->saveTrainingSession($this->db, $user['id'], $data);
                    $response = array(
                        "message" => "Training session saved successfully.",
                        "data" => $data
                    );
                }
                
                
                
                

                header("Content-Type: application/json");

                echo json_encode($response);

            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("error" => "Invalid JSON data."));
            }

        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array("error" => "Invalid request method."));
        }

    }

    public function handleAddTrainingSession() {
        $errors = array();
        $userId = $_SESSION['user_id'];

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }

        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        $planId = preg_replace("/[^0-9]/", "", $planId);


        $userModel = new UserModel($this->db);
        $defaultPlan = $userModel->getPlanById($this->db, $planId);

        $this->index($defaultPlan);


    }


    public function index($defaultPlan = null) {
        $titlePage = 'GymNote - Add training session';

        $errors = array();
        $userId = $_SESSION['user_id'];

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }
        $userModel = new UserModel($this->db);
        $plans = $userModel->getPlans($this->db, $userId);

        if ($defaultPlan != null) {
            $chosenPlan = $defaultPlan;
            $days = $userModel->getDaysByPlanId($this->db, $chosenPlan['plan_id']);
            //make a loop to get all sets by day id
            $sets = array();
            foreach ($days as $day) {
                $sets[$day['day_id']] = $userModel->getSetsByDayId($this->db, $day['day_id']);
            }
            //loop to get all exercises by set id
            $exercises = array();
            foreach ($sets as $set) {
                foreach ($set as $s) {
                    $exercises[$s['set_id']] = $userModel->getExercisesBySetId($this->db, $s['set_id']);
                }
            }




            
        }

        require_once '../views/shared/head.php';
        require_once '../views/user/add_training_session.php';
        require_once '../views/shared/footer.php';
    }



}