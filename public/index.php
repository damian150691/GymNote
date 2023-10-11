<?php
// Load any necessary configurations or autoloaders here
require_once '../config/database.php'; 

// Define a basic routing mechanism based on the URL
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query parameters from the URL
$request_uri = strtok($request_uri, '?');

// Define a base URL for your application
$base_url = '/public/';


// Remove the base URL from the request URI to get the route
$route = str_replace($base_url, '', $request_uri);



// Define routes and their corresponding controllers and actions
$routes = [
    '/' => ['controller' => 'HomeController', 'action' => 'index'],
    '/login' => ['controller' => 'LoginController', 'action' => 'index', 'params' => ['db' => $db]],
    '/forgotpassword' => ['controller' => 'ForgotPasswordController', 'action' => 'index'],
    '/register' => ['controller' => 'RegisterController', 'action' => 'index', 'params' => ['db' => $db]],
    '/dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    '/profile' => ['controller' => 'UserProfileController', 'action' => 'index'],
    '/admin' => ['controller' => 'AdminPanelController', 'action' => 'index'],
    '/myplans' => ['controller' => 'MyPlansController', 'action' => 'index'],
    '/makenewplan' => ['controller' => 'MakeNewPlanController', 'action' => 'index'],
];



// Check if the requested route exists in the defined routes
if (isset($routes[$route])) {
    $controller_name = $routes[$route]['controller'];
    $action_name = $routes[$route]['action'];

    // Include the appropriate controller file
    require_once '../controllers/' . $controller_name . '.php';
    // Create an instance of the controller and call the action method
    $controller = new $controller_name();

    // Check if "params" exist in the route definition
    if (isset($routes[$route]['params'])) {
        $params = $routes[$route]['params'];
        
        // Pass the "params" to the controller (if applicable)
        if (method_exists($controller, 'setParams')) {
            $controller->setParams($params);
        }
    }

    $controller->$action_name();
} else {
    // Handle 404 Not Found error
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    // You can include a custom error view here if you like
}