<?php 


class LoginController {

    public function __construct() {
        // Set the include path
        set_include_path(get_include_path() . PATH_SEPARATOR . '../models');
        set_include_path(get_include_path() . PATH_SEPARATOR . '../config');

        // Load the model file
        require_once 'UserModel.php';
        require_once 'database.php';
    }

    


    // Handle the login form submission
    public function login(Request $request) {
        

    }



    public function index() {
        $titlePage = 'Strenghtify - Login';

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/login/login_form.php';
        require_once '../views/shared/footer.php';
    }



}




/*
    // Log the user out
    public function logout() {
        // Implement this function to log the user out and redirect
    }

    // Redirect to the home page if authenticated
    public function redirectToHomeIfAuthenticated() {
        // Implement this function to check if the user is authenticated and redirect
    }

    // Handle a failed login attempt
    protected function handleFailedLogin() {
        // Implement this function to handle a failed login attempt
    }

    // Reset a user's password
    public function resetPassword(Request $request) {
        // Implement this function to handle password reset
    }

    // Change a user's password
    public function changePassword(Request $request) {
        // Implement this function to handle password change
    }

    // Handle two-factor authentication
    public function twoFactorAuth(Request $request) {
        // Implement this function to handle two-factor authentication
    }

    // Handle social login callback
    public function socialLoginCallback(Request $request) {
        // Implement this function to handle social login callbacks
    }

    // Remember the user's session
    protected function rememberMe(Request $request) {
        // Implement this function to handle "Remember Me" functionality
    }

    // Add error handling functions
    protected function handleAuthenticationError() {
        // Implement this function to handle authentication errors
    }
*/