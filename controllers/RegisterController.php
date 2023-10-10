<?php 


class RegisterController {

    public function __construct() {
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../config');

        // Load the model file
        require_once 'UserModel.php';
        require_once 'database.php';
    }

    


    // Handle the login form submission
    public function register(Request $request) {
        

    }



    public function index() {
        $titlePage = 'Strenghtify - Register';

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/register/register_form.php';
        require_once '../views/shared/footer.php';
    }



}