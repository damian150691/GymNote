<?php 


class DashboardController {

    private $userModel;
    private $db;

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


    public function index() {
        $titlePage = 'GymNote - Dashboard';

        if (!isset($_SESSION['user_id']) ) {
            header('Location: /login');
            exit();
        }

        $userModel = new UserModel($this->db);
        $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
        if ($user) {
            $userBio = $userModel->getUserBioById($this->db, $user['id']);
            if ($userBio == Null) {
                //assign array with empty values
                $userBio = [
                    'user_id' => $_SESSION['user_id'],
                    'age' => NULL,
                    'gender' => NULL,
                    'height' => NULL,
                    'weight' => NULL,
                    'calories_goal' => NULL,
                    'profile_picture' => NULL

                ]
            ;}

            $plans = $userModel->getPlans($this->db, $user['id']);
            $createdBy = $user['username'];

            function ordinal($number) {
                $ends = array('th','st','nd','rd','th','th','th','th','th','th');
                if ((($number % 100) >= 11) && (($number % 100) <= 13))
                    return $number . 'th';
                else
                    return $number . $ends[$number % 10];
            }

            $notifications = $userModel->getNotificationsforUser($this->db, $user['id']);
            
        }

        // Load the login view with any necessary data
        require_once '../views/shared/head.php';
        require_once '../views/user/dashboard.php';
        require_once '../views/shared/footer.php';
    }



}