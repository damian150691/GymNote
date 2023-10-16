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



        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/my_plans.php';
        require_once '../views/shared/footer.php';
    }



}