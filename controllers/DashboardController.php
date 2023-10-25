<?php 


class DashboardController {

    public function __construct() {
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');


        // Load the model file
        require_once 'UserModel.php';

    }

    




    public function index() {
        $titlePage = 'GymNote - Dashboard';

        if (!isset($_SESSION['user_id']) ) {
            header('Location: /login');
            exit();
        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/dashboard.php';
        require_once '../views/shared/footer.php';
    }



}