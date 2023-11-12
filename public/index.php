<?php
session_start();
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
    '/logout' => ['controller' => 'LoginController', 'action' => 'handleLogout'],

    '/forgotpassword' => ['controller' => 'ForgotPasswordController', 'action' => 'index', 'params' => ['db' => $db]],

    '/setnewpassword' => ['controller' => 'SetNewPasswordController', 'action' => 'index', 'params' => ['db' => $db]],

    '/register' => ['controller' => 'RegisterController', 'action' => 'index', 'params' => ['db' => $db]],
    '/verify' => ['controller' => 'RegisterController', 'action' => 'verifyUser', 'params' => ['db' => $db]],

    '/dashboard' => ['controller' => 'DashboardController', 'action' => 'index', 'params' => ['db' => $db]],

    '/admin' => ['controller' => 'AdminController', 'action' => 'index', 'params' => ['db' => $db]],
    '/admin/adduser' => ['controller' => 'AdminController', 'action' => 'index', 'params' => ['db' => $db]],
    '/admin/edituser/(\d+)' => ['controller' => 'AdminController', 'action' => 'index', 'params' => ['db' => $db]],
    '/admin/userlist' => ['controller' => 'AdminController', 'action' => 'index', 'params' => ['db' => $db]],
    '/admin/viewdatabase' => ['controller' => 'AdminController', 'action' => 'index', 'params' => ['db' => $db]],
    '/admin/deleteuser/(\d+)' => ['controller' => 'AdminController', 'action' => 'handleDeleteUser', 'params' => ['db' => $db]],
    '/admin/edituser/(\d+)' => ['controller' => 'AdminController', 'action' => 'handleEditUser', 'params' => ['db' => $db]],

    '/myplans' => ['controller' => 'MyPlansController', 'action' => 'index', 'params' => ['db' => $db]],
    '/plan/(\d+)' => ['controller' => 'MyPlansController', 'action' => 'displayPlan', 'params' => ['db' => $db]],
    '/deleteplan/(\d+)' => ['controller' => 'MyPlansController', 'action' => 'deletePlan', 'params' => ['db' => $db]],
    '/setactiveplan/(\d+)' => ['controller' => 'MyPlansController', 'action' => 'handleSetActivePlan', 'params' => ['db' => $db]],

    '/addtrainingsession' => ['controller' => 'TrainingController', 'action' => 'index', 'params' => ['db' => $db]],
    '/addtrainingsession/(\d+)' => ['controller' => 'TrainingController', 'action' => 'handleAddTrainingSession', 'params' => ['db' => $db]],

    '/makenewplan' => ['controller' => 'MakeNewPlanController', 'action' => 'index', 'params' => ['db' => $db]],
    '/saveplan' => ['controller' => 'MakeNewPlanController', 'action' => 'handleSavePlan', 'params' => ['db' => $db]],

    '/profile' => ['controller' => 'UserProfileController', 'action' => 'index', 'params' => ['db' => $db]],

    '/friends' => ['controller' => 'FriendsController', 'action' => 'index', 'params' => ['db' => $db]],
    '/searchfriend' => ['controller' => 'FriendsController', 'action' => 'handleSearchFriend', 'params' => ['db' => $db]],
    '/addfriend' => ['controller' => 'FriendsController', 'action' => 'handleAddFriend', 'params' => ['db' => $db]],
    '/cancelfriendrequest' => ['controller' => 'FriendsController', 'action' => 'handleCancelFriendRequest', 'params' => ['db' => $db]],
    '/acceptfriendrequest' => ['controller' => 'FriendsController', 'action' => 'handleAcceptFriendRequest', 'params' => ['db' => $db]],
    '/denyfriendrequest' => ['controller' => 'FriendsController', 'action' => 'handleDenyFriendRequest', 'params' => ['db' => $db]],
    '/removefriend' => ['controller' => 'FriendsController', 'action' => 'handleRemoveFriend', 'params' => ['db' => $db]],

    '/exercises' => ['controller' => 'ExercisesController', 'action' => 'index', 'params' => ['db' => $db]],
    '/exercises/getexercises' => ['controller' => 'ExercisesController', 'action' => 'handleGetExercisesQuery', 'params' => ['db' => $db]],
];



$matched = false;
foreach ($routes as $pattern => $routeInfo) {
    // Use preg_match to match the pattern with the requested route
    if (preg_match('#^' . $pattern . '$#', $route, $matches)) {
        $controller_name = $routeInfo['controller'];
        $action_name = $routeInfo['action'];

        // Include the appropriate controller file
        require_once '../controllers/' . $controller_name . '.php';
        // Create an instance of the controller and call the action method
        $controller = new $controller_name();

        // Check if "params" exist in the route definition
        if (isset($routeInfo['params'])) {
            $params = $routeInfo['params'];
            
            // Pass the "params" to the controller (if applicable)
            if (method_exists($controller, 'setParams')) {
                $controller->setParams($params);
            }
        }

        if (isset($matches[1])) {
            $controller->$action_name($matches[1]);
        } else {
            $controller->$action_name();
        }

        $matched = true;
        break;
    }
}

if (!$matched) {
    // Handle 404 Not Found error
    header('HTTP/1.0 404 Not Found');
    echo '404 Not Found';
    // You can include a custom error view here if you like
}