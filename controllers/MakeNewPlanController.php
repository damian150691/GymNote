<?php 


class MakeNewPlanController {

    public function __construct() {
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../config');

        // Load the model file
        require_once 'UserModel.php';
        require_once 'database.php';
    }

    




    public function index() {
        $titlePage = 'Strenghtify - Make a new Training Plan';

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/make_new_plan.php';
        require_once '../views/shared/footer.php';
    }



}