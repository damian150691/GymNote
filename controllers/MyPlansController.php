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
        $titlePage = 'Strenghtify - My plans';

        require_once '../views/shared/head.php';
        require_once '../views/user/plan.php';
        require_once '../views/shared/footer.php';
    }

    public function deletePlan () {
        $errors = array();
        
        $userModel = new UserModel($this->db);
        
        //check if there is a intiger in the url
        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        //remove all non numeric characters
        $planId = preg_replace("/[^0-9]/", "", $planId);
        $userId = $_SESSION['user_id'];
        $userModel->deletePlan($this->db, $userId, $planId);
        header('Location: /myplans');
        

        
    }


    public function index() {
        $plansCount = 0;
        $userModel = new UserModel($this->db);
        $titlePage = 'Strenghtify - My plans';

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        } 

        $plans = $userModel->getPlans($this->db, $_SESSION['user_id']);
        $plansCount = count($plans);
        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        $createdBy = $user['username'];



        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/my_plans.php';
        require_once '../views/shared/footer.php';
    }



}