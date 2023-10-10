<?php 


class HomeController {
    public function index() {
        // Redirect to the LoginController
        header('Location: /login'); 
        exit;
    }
}