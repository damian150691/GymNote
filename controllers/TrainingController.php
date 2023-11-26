<?php 


class TrainingController {

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

    private function sortExercisesByLP($exercises) {
        usort($exercises, function ($a, $b) {
            // Split 'lp' into numeric and letter parts
            preg_match('/(\d+)([a-zA-Z]*)/', $a['lp'], $matchesA);
            preg_match('/(\d+)([a-zA-Z]*)/', $b['lp'], $matchesB);
    
            // Compare the numeric parts
            if ($matchesA[1] == $matchesB[1]) {
                // If numeric parts are equal, compare the letter parts
                return strcmp($matchesA[2], $matchesB[2]);
            }
    
            return $matchesA[1] - $matchesB[1];
        });
    
        return $exercises;
    }

    private function convertNumberToOrdinal($number) {
        $ends = ['th','st','nd','rd','th','th','th','th','th','th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function handleSaveTrainingSession () {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);

            if ($data) {
                $userModel = new UserModel($this->db);
                $user = $userModel->getUserById($this->db, $_SESSION['user_id']);
                

                $planIdKey = array_keys($data)[0]; // This gets the first key of the array, which should be your plan_id key
                $planId = str_replace("plan_id: ", "", $planIdKey); // Removing the prefix to get the actual planId
                $lastInsertedSessionId = 0;

                //convert planId to int
                $planId = (int)$planId;
                $userSessionId = $userModel->getPlanByPlanId($this->db, $planId)['created_for'];
                
                //get date that is under data->userInputs->date
                $date = $data['userInputs']['date'];
                $doesSessionExist = $userModel->checkTrainingSessionByDate($this->db, $userSessionId, $date);

                if ($doesSessionExist == true) {
                    $response = $userModel->updateTrainingSession($this->db, $userSessionId, $data, $date);
                    $response = array(
                        "message" => "Training session updated successfully.",
                        "data" => $data
                    );

                } else {
                    $response = $userModel->saveTrainingSession($this->db, $userSessionId, $data, $date);
                    $response = array(
                        "message" => "Training session saved successfully.",
                        "data" => $data
                    );
                }
                
                
                
                

                header("Content-Type: application/json");

                echo json_encode($response);

            } else {
                http_response_code(400); // Bad Request
                echo json_encode(array("error" => "Invalid JSON data."));
            }

        } else {
            http_response_code(405); // Method Not Allowed
            echo json_encode(array("error" => "Invalid request method."));
        }

    }

    public function handleAddTrainingSession() {
        $titlePage = 'GymNote - Add training session';
        $errors = array();
        $userId = $_SESSION['user_id'];

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }

        $planId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        $planId = preg_replace("/[^0-9]/", "", $planId);


        $userModel = new UserModel($this->db);
        $defaultPlan = $userModel->getPlanById($this->db, $planId);

        $plans = $userModel->getPlans($this->db, $userId);

        $activePlan = $userModel->getActivePlan($this->db, $userId);

        if ($defaultPlan != null) {
            $chosenPlan = $defaultPlan;
            $days = $userModel->getDaysByPlanId($this->db, $chosenPlan['plan_id']);
            //make a loop to get all sets by day id
            $sets = array();
            foreach ($days as $day) {
                $sets[$day['day_id']] = $userModel->getSetsByDayId($this->db, $day['day_id']);
            }
            //loop to get all exercises by set id
            $exercises = array();
            foreach ($sets as $set) {
                foreach ($set as $s) {
                    $exercises[$s['set_id']] = $userModel->getExercisesBySetId($this->db, $s['set_id']);
                }
            }

            $trainingSessions = $userModel->getTrainingSessionsByUserId($this->db, $userId);
            // Sort the array by session_date
            usort($trainingSessions, function($a, $b) {
                return strtotime($a['session_date']) - strtotime($b['session_date']);
            });
            $latestSession = end($trainingSessions);

            


            //loop to get all reps by exercise id
            $daysCount = count($days);
            // Map day names to numeric values, using lowercase for consistency
            $dayMap = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7];
            // Getting the current day of the week as a number (1 for Monday, 7 for Sunday)
            $currentDayOfWeek = date('N');
            // Default selected day
            $selectedDayId = null;
            $closestDifference = PHP_INT_MAX;

            // Initialize today's date
            $todayDate = new DateTime(); // Today's date

            // Parse the latest session date
            $latestSessionDate = new DateTime($latestSession['session_date']);
            $latestSessionDayOfWeek = $latestSessionDate->format('N');

            // Calculate the difference in days between today and the last session date
            $interval = $todayDate->diff($latestSessionDate);
            $daysSinceLastSession = $interval->days;

            foreach ($days as $day) {
                $dayOfWeek = isset($dayMap[strtolower($day['day_of_the_week'])]) ? $dayMap[strtolower($day['day_of_the_week'])] : null;

                if ($dayOfWeek) {
                    // If the last session was more than 7 days ago, ignore it
                    if ($daysSinceLastSession <= 7) {
                        // Check if the latest session day has passed in the current week
                        if ($todayDate > $latestSessionDate && $latestSessionDayOfWeek == $dayOfWeek) {
                            continue; // Skip this day as it has already been completed in the current week
                        }
                    }

                    // Calculate forward and backward differences as before
                    $forwardDifference = ($dayOfWeek >= $currentDayOfWeek) ? ($dayOfWeek - $currentDayOfWeek) : ($dayOfWeek + 7 - $currentDayOfWeek);
                    $backwardDifference = ($currentDayOfWeek > $dayOfWeek) ? ($currentDayOfWeek - $dayOfWeek) : ($currentDayOfWeek + 7 - $dayOfWeek);
                    $difference = min($forwardDifference, $backwardDifference);

                    if ($difference < $closestDifference) {
                        $closestDifference = $difference;
                        $selectedDayId = $day['day_id'];
                    }
                }
            }
        }

        

        require_once '../views/shared/head.php';
        require_once '../views/user/add_training_session.php';
        require_once '../views/shared/footer.php';
    }


    public function displayTrainingSession () {
        
        $errors = array();
        $userId = $_SESSION['user_id'];

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }

        $sessionId = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
        $sessionId = preg_replace("/[^0-9]/", "", $sessionId);

        $titlePage = 'GymNote - Workout #'.$sessionId.'';

        $userModel = new UserModel($this->db);

        $trainingSession = $userModel->getTrainingSessionById($this->db, $sessionId, $userId);

        $plan = $userModel->getPlanByPlanId($this->db, $trainingSession['plan_id']);
        $trainingSession['plan_name'] = $plan['plan_name'] ?? 'MyPlan';

        $trainingSessionExercises = $userModel->getExercisesByTrainingSessionId($this->db, $sessionId);
        // Sort all exercises
        $sortedExercises = $this->sortExercisesByLP($trainingSessionExercises);
        foreach ($sortedExercises as &$exercise) {
            $exerciseName = $userModel->getExerciseNameByExerciseId($this->db, $exercise['exercise_id']);
            if ($exerciseName) {
                $exercise['name'] = $exerciseName['name'];
            }
        }
        unset($exercise);

        $dayName = $userModel->getDayNameByDayId($this->db, $trainingSession['day_id'])['day_name'];
        
        $dayOfTheWeek = $userModel->getDayOfTheWeekByDayId($this->db, $trainingSession['day_id'])['day_of_the_week'];

        $sessionDayOfTheWeek = date('l', strtotime($trainingSession['session_date']));


        // Initialize the count of previous sessions
        $previousSessionCount = 0;

        // Use the current session as the starting point
        $currentSession = $trainingSession;

        // Loop to count the number of previous sessions
        while (true) {
            $previousSession = $userModel->getPreviousTrainingSession($this->db, $userId, $currentSession['plan_id'], $currentSession['day_id'], $currentSession['session_date']);
            
            // Check if a previous session exists
            if ($previousSession != NULL) {
                // Increment the count and set the current session to the previous one for the next iteration
                $previousSessionCount++;
                $currentSession = $previousSession;
            } else {
                // Exit the loop if no previous session is found
                break;
            }
        }

        $previousSession = $userModel->getPreviousTrainingSession($this->db, $userId, $trainingSession['plan_id'], $trainingSession['day_id'], $trainingSession['session_date']);
        if ($previousSession != NULL) {
            $previousSessionExercises = $userModel->getExercisesByTrainingSessionId($this->db, $previousSession['session_id']);
        }

        $nextSession = $userModel->getNextTrainingSession($this->db, $userId, $trainingSession['plan_id'], $trainingSession['day_id'], $trainingSession['session_date']);

        // Convert the count to a string like "1st", "2nd", "3rd", etc.
        $whichNo = $this->convertNumberToOrdinal($previousSessionCount + 1);


        require_once '../views/shared/head.php';
        require_once '../views/user/training_session.php';
        require_once '../views/shared/footer.php';
    }


    public function index() {
        $titlePage = 'GymNote - Add workout';

        $errors = array();
        $userId = $_SESSION['user_id'];

        if (!isset($_SESSION['user_id'])) {
            array_push($errors, "You need to login first.");
            $_SESSION['message'] = $errors;
            header('Location: /login');
            exit();
        }
        $userModel = new UserModel($this->db);

        $trainingSessions = $userModel->getTrainingSessionsByUserId($this->db, $userId);
        
        foreach ($trainingSessions as &$trainingSession) {
            $plan = $userModel->getPlanByPlanId($this->db, $trainingSession['plan_id']);
            $trainingSession['plan_name'] = $plan['plan_name'] ?? 'MyPlan';
        
            $trainingSessionExercises = $userModel->getExercisesByTrainingSessionId($this->db, $trainingSession['session_id']);
            // Sort all exercises
            $sortedExercises = $this->sortExercisesByLP($trainingSessionExercises);
            $trainingSession['exercises'] = $sortedExercises;
        
            // Extract unique exercises
            $uniqueExercises = [];
            foreach ($sortedExercises as $exercise) {
                $uniqueExercises[$exercise['exercise_id']] = $exercise;
            }
        
            // Assign the unique (and already sorted) exercises back to the training session
            $trainingSession['unique_exercises'] = array_values($uniqueExercises);

            $dayName = $userModel->getDayNameByDayId($this->db, $trainingSession['day_id'])['day_name'];
            $trainingSession['day_name'] = $dayName;

            $dayOfTheWeek = $userModel->getDayOfTheWeekByDayId($this->db, $trainingSession['day_id'])['day_of_the_week'];
            $trainingSession['day_of_the_week'] = $dayOfTheWeek;
        }
        unset($trainingSession);

        

        require_once '../views/shared/head.php';
        require_once '../views/user/trainings.php';
        require_once '../views/shared/footer.php';
    }



}