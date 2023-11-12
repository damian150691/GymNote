<?php 


class MyPlansController {

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

    public function displayPlan () {
        $errors = array();
        $userId = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }

        $titlePage = 'GymNote - View plan';
        
        $userModel = new UserModel($this->db);

        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        $planId = preg_replace("/[^0-9]/", "", $planId);
        $plan = $userModel->getPlanById($this->db, $planId);
        $days = $userModel->getDaysByPlanId($this->db, $planId);
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

        

        require_once '../views/shared/head.php';
        require_once '../views/user/plan.php';
        require_once '../views/shared/footer.php';
    }

    public function deletePlan () {
        
        $errors = array();

        $userId = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {

            $_SESSION['message'] = "You need to login first.";
            header('Location: /login');
            exit();
        }

        $userModel = new UserModel($this->db);
        
        //check if there is a intiger in the url
        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        //remove all non numeric characters
        $planId = preg_replace("/[^0-9]/", "", $planId);
        
        $userModel->deletePlan($this->db, $userId, $planId);
        header('Location: /myplans');
        exit();
    }

    public function handleSetActivePlan () {
        $errors = array();

        $userId = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {

            $_SESSION['message'] = "You need to login first.";
            header('Location: /login');
            exit();
        }

        $userModel = new UserModel($this->db);
        
        //check if there is a intiger in the url
        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        //remove all non numeric characters
        $planId = preg_replace("/[^0-9]/", "", $planId);
        
        $result = $userModel->setActivePlan($this->db, $userId, $planId);

        if ($result) {
            $_SESSION['message'] = "Active plan changed successfully.";
        } else {
            $_SESSION['message'] = "Something went wrong. Please try again.";
        }
        header('Location: /myplans');
        exit();
    }


    public function index() {
        $titlePage = 'GymNote - My plans';
        $plansCount = 0;
        $userModel = new UserModel($this->db);
        

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "You need to login first.";
            header('Location: /login');
            exit();
        } 

        $plans = $userModel->getPlans($this->db, $_SESSION['user_id']);
        $plans = array_reverse($plans);
        $plansCount = count($plans);
        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        //add new key to plans array - created_by

        foreach ($plans as $key => $plan) {
            $created_by = $userModel->getUserById($this->db, $plan['user_id']);

            if (isset($created_by['first_name'])) {
                $created_by['username'] = $created_by['first_name'] . " (" . $created_by['username'] . ")";
            } 

            $plans[$key]['created_by'] = $created_by['username'];
        }

        

        if ($plansCount > 0) {
            $activePlan = array();
            foreach ($plans as $plan) {

            if ($plan['is_active'] == 1) {
                $activePlan = $plan;
                //remove active plan from plans array
                $plans = array_filter($plans, function ($p) use ($plan) {
                        return $p !== $plan;
                    });
                }
            }
        }
        



        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/my_plans.php';
        require_once '../views/shared/footer.php';
    }



}