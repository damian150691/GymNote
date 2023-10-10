<?php 


class ForgotPasswordController {

    public function __construct() {
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../config');

        // Load the model file
        require_once 'UserModel.php';
        require_once 'database.php';
    }

    






    public function index() {
        $titlePage = 'Strenghtify - Register';

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/forgot_password/forgot_password_form.php';
        require_once '../views/shared/footer.php';
    }



}