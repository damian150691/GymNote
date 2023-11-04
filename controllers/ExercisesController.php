<?php 


class ExercisesController {

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

    public function handleGetExercisesQuery () {
        $userModel = new UserModel($this->db);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $searchQuery = $_POST['query'];
            $searchResult = $userModel->getExercisesQuery($this->db, $searchQuery);

            echo json_encode($searchResult);

        } 

    }

    public function index() {
        $titlePage = 'GymNote - Exercises';
        $userModel = new UserModel($this->db);
        
        $categories = $userModel->getCategories($this->db);
        
        //get exercises from all categories and save it into array
        $exercises = [];
        foreach ($categories as $category) {
            $exercises[$category['id']] = $userModel->getExercisesByCategory($this->db, $category['category']);
        }

        
        

        require_once '../views/shared/head.php';
        require_once '../views/user/exercises.php';
        require_once '../views/shared/footer.php';
    }



}