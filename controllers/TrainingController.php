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