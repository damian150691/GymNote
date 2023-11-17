<?php

class UserModel {

    public function getAllUsers ($db) {
        $sql = "SELECT * FROM users";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        return $users;
    }

    public function getUserByUsername ($db, $username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getUserByEmail ($db, $email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getUserByUsernameOrEmail ($db, $loginInput) {
        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getUserById ($db, $id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getUserByToken ($db, $token) {
        $sql = "SELECT * FROM users WHERE token = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getUserBySessionToken ($db, $token) {
        $sql = "SELECT * FROM users WHERE session_token = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function getTableAll ($db, $table) {
        $sql = "SELECT * FROM " . $table;
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $table = $result->fetch_all(MYSQLI_ASSOC);
        return $table;
    }

    public function getUserBioById ($db, $id) {
        $sql = "SELECT * FROM users_bio WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userBio = $result->fetch_assoc();
        return $userBio;
    }

    public function IsInputEmailOrUsername ($db, $loginInput) {
        //check if the input is email or username
        //if email return email
        //if username return username
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $loginInput);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user) {
            return "email";
        } else {
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $loginInput);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user) {
                return "username";
            } else {
                return false;
            }
        }
    }

    public function sendEmail ($to, $subject, $message, $headers) {
        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    public function generateToken($tokenLength) {
        if ($tokenLength % 2 !== 0) {
            throw new Exception("Token length must be an even number.");
        }      
        return bin2hex(random_bytes($tokenLength / 2));
    }

    public function updateToken($db, $id, $token) {
        $sql = "UPDATE users SET token = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $token, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSessionToken ($db, $id, $token) {
        $sql = "UPDATE users SET session_token = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $token, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword ($db, $id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLastLoggedIn ($db, $id) {
        $last_logged_in = date("Y-m-d H:i:s");
        $sql = "UPDATE users SET last_logged = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("si", $last_logged_in, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserAccount ($db, $id, $data) {
        $username = $data['username'];
        $email = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $first_name, $last_name, $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserBio ($db, $userId, $data) {

        //check if there is already a row with user_id in users_bio
        $sql = "SELECT * FROM users_bio WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userBio = $result->fetch_assoc();
        if (!$userBio) {
            //insert the data into the row
            $sql = "INSERT INTO users_bio (user_id, age, gender, height, weight, calories_goal) VALUES (?, ?, ?, ?, ?, ?) ";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iisidi", $userId, $data['age'], $data['gender'], $data['height'], $data['weight'], $data['calories_goal']);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            //update the row
            $sql = "UPDATE users_bio SET age = ?, gender = ?, height = ?, weight = ?, calories_goal = ? WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("isidii", $data['age'], $data['gender'], $data['height'], $data['weight'], $data['calories_goal'], $userId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function updateProfilePicture ($db, $id, $pictureName) {
        //check if there is already a row with user_id in users_bio
        $sql = "SELECT * FROM users_bio WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userBio = $result->fetch_assoc();
        if ($userBio) {
            //if there is update the row
            $sql = "UPDATE users_bio SET profile_picture = ? WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $pictureName, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            //if there isn't insert a new row
            $sql = "INSERT INTO users_bio (user_id, profile_picture) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("is", $id, $pictureName);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    public function registerUser($db, $username, $email, $password) {
        // Hash the password (you should use a secure hashing algorithm)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // generate token
        $token = $this->generateToken(100);
        // set date_registered and last_logged_in to current date and time
        $date_registered = date("Y-m-d H:i:s");
        //setting user role to user
        $user_role = "user";
        $sql = "INSERT INTO users (username, email, password, token, date_registered, user_role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $token, $date_registered, $user_role);
            if ($stmt->execute()) {
                // Insertion was successful
                return true;
            } else {
                // Insertion failed
                return false;
            }
        } else {
            // Statement preparation failed
            return false;
        }
    }

    public function confirmUser($db, $token) {
        $sql = "UPDATE users SET confirmed = 1 WHERE token = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
        

    public function loginUser($db, $loginInput, $password) {
        
        // Retrieve the user with the given username
        $user = $this->getUserByUsernameOrEmail($db, $loginInput);

        if ($user) {
            // User found, check if the password is correct
            if (password_verify($password, $user['password'])) {
                // Password is correct, log the user in
                // Update the last_logged column in the database
                $last_logged_in = date("Y-m-d H:i:s");
                $sql = "UPDATE users SET last_logged = ? WHERE id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("si", $last_logged_in, $user['id']);
                $stmt->execute();

                
                return true;
            } else {
                // Password is incorrect
                return false;
            }
        } else {
            // User not found
            return false;
        }
    }

    public function addUser ($db, $data) {
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $userRole = $data['user_role'];
        $confirmed = $data['confirmed'];
        // Hash the password (you should use a secure hashing algorithm)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // generate token
        $token = $this->generateToken(100);
        // set date_registered to current date and time
        $date_registered = date("Y-m-d H:i:s");
        $sql = "INSERT INTO users (username, email, password, token, date_registered, first_name, last_name, user_role, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssssi", $username, $email, $hashedPassword, $token, $date_registered, $firstName, $lastName, $userRole, $confirmed);
            if ($stmt->execute()) {
                // Insertion was successful
                return true;
            } else {
                // Insertion failed
                return false;
            }
        } else {
            // Statement preparation failed
            return false;
        }
    }

    public function editUser ($db, $data) {
        $id = $data['id'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $userRole = $data['user_role'];
        $confirmed = $data['confirmed'];
        // Hash the password (you should use a secure hashing algorithm)
        if ($password !== "") {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $hashedPassword = $this->getUserById($db, $id)['password'];
        }
        $sql = "UPDATE users SET username = ?, email = ?, password = ?, first_name = ?, last_name = ?, user_role = ?, confirmed = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssssi", $username, $email, $hashedPassword, $firstName, $lastName, $userRole, $confirmed, $id);
            if ($stmt->execute()) {
                // Insertion was successful
                return true;
            } else {
                // Insertion failed
                return false;
            }
        } else {
            // Statement preparation failed
            return false;
        }
    }

    public function deleteUser ($db, $id) {
        $sql = "DELETE FROM users_bio WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $sql = "DELETE FROM notifications WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

    }

    public function saveTrainingSession ($db, $userId, $data, $date) {

        $planIdKey = array_keys($data)[0]; // This gets the first key of the array, which should be your plan_id key
        $planId = str_replace("plan_id: ", "", $planIdKey); // Removing the prefix to get the actual planId
        $lastInsertedSessionId = 0;

        //convert planId to int
        $planId = (int)$planId;

        $dayId = $data[$planIdKey][0]['day_id'];

        $sql = "INSERT INTO training_sessions (user_id, plan_id, session_date, current_body_weight, calories_goal, proteins_goal, carbs_goal, fats_goal, day_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisdiiiii", $userId, $planId, $date, $data['userInputs']['bodyWeight'], $data['userInputs']['caloriesGoal'], $data['userInputs']['proteinsGoal'], $data['userInputs']['carbsGoal'], $data['userInputs']['fatsGoal'], $dayId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $lastInsertedSessionId = $db->insert_id;
        } else {
            return false;
        }
        
        // Extracting the data associated with this planId
        $trainingData = $data[$planIdKey];

        foreach ($trainingData as $row) {
            // Insert into training_sessions_exercises
            $sql = "INSERT INTO training_session_exercises (session_id, exercise_id, reps, weight, lp, comments, set_id, sub_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iiidssii", $lastInsertedSessionId, $row['reference_id'], $row['reps'], $row['weight'], $row['lp'], $row['comments'], $row['set_id'], $row['sub_id']);
            $stmt->execute();  
        }
    }

    public function updateTrainingSession ($db, $userId, $data, $date) {
        $planIdKey = array_keys($data)[0]; // This gets the first key of the array, which should be your plan_id key
        $planId = str_replace("plan_id: ", "", $planIdKey); // Removing the prefix to get the actual planId
        $sessionId = 0;

        //convert planId to int
        $planId = (int)$planId;

        $dayId = $data[$planIdKey][0]['day_id'];

        // Select the session ID that matches your criteria
        $sql = "SELECT session_id FROM training_sessions WHERE user_id = ? AND session_date = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $userId, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize $sessionId to null
        $sessionId = null;

        // Fetch the session ID if it exists
        if ($row = $result->fetch_assoc()) {
            $sessionId = $row['session_id'];
        }

        // Check if a session was found
        if ($sessionId != null) {
            // Perform the update
            $sql = "UPDATE training_sessions SET plan_id = ?, current_body_weight = ?, calories_goal = ?, proteins_goal = ?, carbs_goal = ?, fats_goal = ?, day_id = ? WHERE user_id = ? AND session_date = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("idiiiiiis", $planId, $data['userInputs']['bodyWeight'], $data['userInputs']['caloriesGoal'], $data['userInputs']['proteinsGoal'], $data['userInputs']['carbsGoal'], $data['userInputs']['fatsGoal'], $dayId, $userId, $date,);
            $stmt->execute();

        } else {
            return false; // No session found with the given criteria
        }

        // Extracting the data associated with this planId
        $trainingData = $data[$planIdKey];

        foreach ($trainingData as $row) {

            $doesExist = $this->checkTrainingSessionExercise ($db, $sessionId, $row['reference_id'], $row['set_id'], $row['sub_id']);

            if ($doesExist) {
                // Insert into training_sessions_exercises
                $sql = "UPDATE training_session_exercises SET reps = ?, weight = ?, comments = ? WHERE session_id = ? AND exercise_id = ? AND set_id = ? AND sub_id = ? AND lp = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("idsiiiis", $row['reps'], $row['weight'], $row['comments'], $sessionId, $row['reference_id'], $row['set_id'], $row['sub_id'], $row['lp']);
                $stmt->execute();   
            } else {
                // Insert into training_sessions_exercises
                $sql = "INSERT INTO training_session_exercises (session_id, exercise_id, reps, weight, lp, comments, set_id, sub_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("iiidssii", $sessionId, $row['reference_id'], $row['reps'], $row['weight'], $row['lp'], $row['comments'], $row['set_id'], $row['sub_id']);
                $stmt->execute();  
            }
            
            
        }
    }

    private function checkTrainingSessionExercise ($db, $sessionId, $exerciseId, $setId, $subId) {
        $sql = "SELECT * FROM training_session_exercises WHERE session_id = ? AND exercise_id = ? AND set_id = ? AND sub_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiii", $sessionId, $exerciseId, $setId, $subId);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainingSessionExercise = $result->fetch_assoc();
        //count the rows returned
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkTrainingSessionByDate ($db, $userId, $date) {
        $sql = "SELECT * FROM training_sessions WHERE user_id = ? AND session_date = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("is", $userId, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainingSession = $result->fetch_assoc();
        //count the rows returned
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTrainingSessionsByUserId ($db, $userId) {
        $sql = "SELECT * FROM training_sessions WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainingSessions = $result->fetch_all(MYSQLI_ASSOC);
        return $trainingSessions;
    }

    public function getPreviousTrainingSession($db, $sessionId, $userId, $planId) {
        $sql = "SELECT * FROM training_sessions WHERE session_id < ? AND user_id = ? AND plan_id = ? ORDER BY session_id DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $sessionId, $userId, $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $previousSession = $result->fetch_assoc();
        return $previousSession;
    }

    public function getNextTrainingSession ($db, $sessionId, $userId, $planId) {
        $sql = "SELECT * FROM training_sessions WHERE session_id > ? AND user_id = ? AND plan_id = ? ORDER BY session_id ASC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $sessionId, $userId, $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $nextSession = $result->fetch_assoc();
        return $nextSession;
    }

    public function getTrainingSessionById ($db, $sessionId, $userId) {
        $sql = "SELECT * FROM training_sessions WHERE session_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainingSession = $result->fetch_assoc();
        return $trainingSession;
    }

    public function getExercisesByTrainingSessionId ($db, $sessionId) {
        $sql = "SELECT * FROM training_session_exercises WHERE session_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        return $exercises;
    }


    public function savePlan($db, $id, $data, $userInputs) {
        $response = [];
        $lastInsertedPlanId = 0;
        $lastInsertedDayId = 0;
        $lastInsertedSetId = 0;
        $lastInsertedExerciseId = 0;
        $lastInsertedExercise2Id = 0;
        
        // Insert into mnp_plans
        $plan_name = "MyPlan";
        $date_created = date('Y-m-d H:i:s'); // Current date and time
        $query = "INSERT INTO mnp_plans (plan_name, date_created, user_id) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssi", $plan_name, $date_created, $id);
        if ($stmt->execute()) {
            $lastInsertedPlanId = $db->insert_id;
        } else {
            $response['error'] = "Error inserting into mnp_plans: " . $stmt->error;
            return $response;
        }
    
        // Iterate through the data and insert into other tables
        foreach ($data as $day) {
            // Insert into mnp_days
            $query = "INSERT INTO mnp_days (plan_id, day_name, day_of_the_week) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iss", $lastInsertedPlanId, $day['dayNumber'], $day['dayOfTheWeek']);
            if ($stmt->execute()) {
                $lastInsertedDayId = $db->insert_id;
            } else {
                $response['error'] = "Error inserting into mnp_days: " . $stmt->error;
                return $response;
            }
            
            foreach ($day['sets'] as $set) {
                // Insert into mnp_sets
                $query = "INSERT INTO mnp_sets (day_id, set_name, rest, comments) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("isss", $lastInsertedDayId, $set['setName'], $set['rest'], $set['comment']);
                if ($stmt->execute()) {
                    $lastInsertedSetId = $db->insert_id;
                } else {
                    $response['error'] = "Error inserting into mnp_sets: " . $stmt->error;
                    return $response;
                }
                
                foreach ($set['exercises'] as $exercise) {
                    // Insert into mnp_exercises
                    $query = "INSERT INTO mnp_exercises (set_id, lp, exercise_name, sets, repetitions, weight, rest, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("isssssss", $lastInsertedSetId, $exercise['id'], $exercise['exercise'], $exercise['sets'], $exercise['repetitions'], $exercise['weight'], $exercise['rest'], $exercise['comment']);
                    if (!$stmt->execute()) {
                        $response['error'] = "Error inserting into mnp_exercises: " . $stmt->error;
                        return $response;
                    } else {
                        $lastInsertedExerciseId = $db->insert_id;
                    }
                    $doesExist = $this->getApprovedExercises ($db, $exercise['exercise']);
                    $userExercises = $this->getUserExercises ($db, $id, $exercise['exercise']);
                    if ($doesExist != false || $userExercises != false) {
                        if ($doesExist != false) {
                            $query = "UPDATE mnp_exercises SET reference_id = ? WHERE exercise_id = ?";
                            $stmt = $db->prepare($query);
                            $stmt->bind_param("ii", $doesExist['id'], $lastInsertedExerciseId);
                            if (!$stmt->execute()) {
                                $response['error'] = "Error updating mnp_exercises: " . $stmt->error;
                                return $response;
                            }
                        } else {
                            $query = "UPDATE mnp_exercises SET reference_id = ? WHERE exercise_id = ?";
                            $stmt = $db->prepare($query);
                            $stmt->bind_param("ii", $userExercises['id'], $lastInsertedExerciseId);
                            if (!$stmt->execute()) {
                                $response['error'] = "Error updating mnp_exercises: " . $stmt->error;
                                return $response;
                            }
                        }
                    } else {
                        $query = "INSERT INTO list_of_exercises (name, added_by, isApproved) VALUES (?, ?, 0)";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("si", $exercise['exercise'], $id);
                        if (!$stmt->execute()) {
                            $response['error'] = "Error inserting into list_of_exercises: " . $stmt->error;
                            return $response;
                        } else {
                            $lastInsertedExercise2Id = $db->insert_id;
                        }

                        $query = "UPDATE mnp_exercises SET reference_id = ? WHERE exercise_id = ?";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("ii", $lastInsertedExercise2Id, $lastInsertedExerciseId);
                        if (!$stmt->execute()) {
                            $response['error'] = "Error updating mnp_exercises: " . $stmt->error;
                            return $response;
                        }
                        
                    }
                    
                }
            }
        }

        if ($userInputs != NULL) {
            $sql = "UPDATE mnp_plans SET plan_name = ?, initial_weight = ?, calories_goal = ?, proteins_goal = ?, carbs_goal = ?, fats_goal = ?, created_for = ? WHERE user_id = ? AND plan_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sdiiiiiii", $userInputs['planName'], $userInputs['initialWeight'], $userInputs['caloriesGoal'], $userInputs['proteinsGoal'], $userInputs['carbsGoal'], $userInputs['fatsGoal'], $userInputs['makePlanFor'], $id, $lastInsertedPlanId);
            $stmt->execute();
            
            if ($userInputs['isActive'] == "1") {
                $this->setActivePlan($db, $id, $lastInsertedPlanId);
            }
        }
        
        $response['success'] = "Data inserted successfully!";
        return $response;
    }

    public function getPlanByPlanId ($db, $planId) {
        $sql = "SELECT * FROM mnp_plans WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();
        return $plan;
    }

    public function getPlans ($db, $id) {
        $sql = "SELECT plan_id, plan_name, date_created, is_active, user_id FROM mnp_plans WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plans = $result->fetch_all(MYSQLI_ASSOC);
        return $plans;
    }

    public function deletePlan ($db, $userId, $planId) {
        $sql = "DELETE FROM mnp_plans WHERE user_id = ? AND plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $planId);
        $stmt->execute();

        $sql = "SELECT day_id FROM mnp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $dayIds = $stmt->get_result();
        $dayIdArray = [];
        while ($row = $dayIds->fetch_assoc()) {
            $dayIdArray[] = $row['day_id'];
        }

        $sql = "SELECT set_id FROM mnp_sets WHERE day_id IN (" . implode(',', array_fill(0, count($dayIdArray), '?')) . ")";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($dayIdArray)), ...$dayIdArray);
        $stmt->execute();
        $setIds = $stmt->get_result();
        $setIdArray = [];
        while ($row = $setIds->fetch_assoc()) {
            $setIdArray[] = $row['set_id'];
        }
        
        $sql = "DELETE FROM mnp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();

        if (!empty($dayIdArray)) {
            $sql = "DELETE FROM mnp_sets WHERE day_id IN (" . implode(',', array_fill(0, count($dayIdArray), '?')) . ")";
            $stmt = $db->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($dayIdArray)), ...$dayIdArray);
            $stmt->execute();
        }
        
        if (!empty($setIdArray)) {
            $sql = "DELETE FROM mnp_exercises WHERE set_id IN (" . implode(',', array_fill(0, count($setIdArray), '?')) . ")";
            $stmt = $db->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($setIdArray)), ...$setIdArray);
            $stmt->execute();
        }
    }

    public function setActivePlan ($db, $userId, $planId) {
        //update mnp_plans. Set is_active to 1 where user_id = $userId and plan_id = $planId
        $sql = "UPDATE mnp_plans SET is_active = 1 WHERE user_id = ? AND plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $planId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //set all other user plans to is_active = 0
            $sql = "UPDATE mnp_plans SET is_active = 0 WHERE user_id = ? AND plan_id != ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ii", $userId, $planId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getPlanById ($db, $planId) {
        $sql = "SELECT * FROM mnp_plans WHERE plan_id = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();
        return $plan;
    }

    public function getDaysByPlanId ($db, $planId) {
        $sql = "SELECT * FROM mnp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $days = $result->fetch_all(MYSQLI_ASSOC);
        return $days;
    }

    public function getDayOfTheWeekByDayId ($db, $dayId) {
        $sql = "SELECT day_of_the_week FROM mnp_days WHERE day_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $dayId);
        $stmt->execute();
        $result = $stmt->get_result();
        $dayOfTheWeek = $result->fetch_assoc();
        return $dayOfTheWeek;
    }

    public function getDayNameByDayId ($db, $dayId) {
        $sql = "SELECT day_name FROM mnp_days WHERE day_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $dayId);
        $stmt->execute();
        $result = $stmt->get_result();
        $dayName = $result->fetch_assoc();
        return $dayName;
    }

    public function getSetsByDayId ($db, $dayId) {
        $sql = "SELECT * FROM mnp_sets WHERE day_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $dayId);
        $stmt->execute();
        $result = $stmt->get_result();
        $sets = $result->fetch_all(MYSQLI_ASSOC);
        return $sets;
    }

    public function getExercisesBySetId ($db, $setId) {
        $sql = "SELECT * FROM mnp_exercises WHERE set_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $setId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        return $exercises;
    }



    public function createNotification ($db, $notification) {
        $sql = "INSERT INTO notifications (user_id, content, type, related_object_id) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("issi", $notification['user_id'], $notification['content'], $notification['type'], $notification['related_object_id']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getNotificationsforUser ($db, $userId, $status = NULL) {
        if ($status == NULL) {
            $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $userId);
        } else {
            $sql = "SELECT * FROM notifications WHERE user_id = ? AND status = ? ORDER BY created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("is", $userId, $status);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        return $notifications;
    }

    public function markNotificationAsRead ($db, $notificationId) {
        $sql = "UPDATE notifications SET status = 'read' WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $notificationId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchFriend ($db, $userId, $searchQuery) {
        //get all users except the logged in user and users that are already friends

        $sql = "SELECT id, username FROM users WHERE id != ? AND id NOT IN (SELECT user_id2 FROM friends WHERE user_id1 = ?) AND id NOT IN (SELECT user_id1 FROM friends WHERE user_id2 = ?) AND username LIKE ?";
        $stmt = $db->prepare($sql);
        $searchPattern = $searchQuery . '%';  // Append % wildcard to search for usernames that start with $searchQuery
        $stmt->bind_param("iiis", $userId, $userId, $userId, $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        //add profile-picture to each user
        foreach ($users as $key => $user) {
            $profilePicture = $this->getProfilePicture($db, $user['id']);
            $users[$key]['profile_picture'] = $profilePicture;
        }
        return $users;
    }

    public function addFriend ($db, $userId, $friendId) {
        $status = "pending";
        
        $sql = "INSERT INTO friends (user_id1, user_id2, status, action_user_id) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iisi", $userId, $friendId, $status, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cancelFriendRequest ($db, $userId, $friendId) {
        $sql = "DELETE FROM friends WHERE user_id1 = ? AND user_id2 = ? AND status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $friendId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //delete notification
            $sql = "DELETE FROM notifications WHERE user_id = ? AND related_object_id = ? AND type = 'friend_request'";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ii", $friendId, $userId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function acceptFriendRequest ($db, $userId, $friendId) {
        $sql = "UPDATE friends SET status = 'accepted' WHERE user_id1 = ? AND user_id2 = ? AND status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $friendId, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //create notification
            $user = $this->getUserById($db, $userId);
            $notification = [
                'user_id' => $friendId,
                'content' => $user['username'] . " has accepted your friend request",
                'type' => "friend_accepted",
                'related_object_id' => $userId
            ];
            $this->createNotification($db, $notification);
            return true;
        } else {
            return false;
        }
    }

    public function denyFriendRequest ($db, $userId, $friendId) {
        $sql = "DELETE FROM friends WHERE user_id1 = ? AND user_id2 = ? AND status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $friendId, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFriend ($db, $userId, $friendId) {
        $sql = "DELETE FROM friends WHERE (user_id1 = ? AND user_id2 = ?) OR (user_id1 = ? AND user_id2 = ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiii", $userId, $friendId, $friendId, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getFriendsList ($db, $userId) {
        $sql = "SELECT * FROM friends WHERE (user_id1 = ? OR user_id2 = ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $friends = $result->fetch_all(MYSQLI_ASSOC);
        return $friends;
    }

    public function getFriendRequests ($db, $userId) {
        $sql = "SELECT * FROM friends WHERE user_id2 = ? AND status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $friendRequests = $result->fetch_all(MYSQLI_ASSOC);
        return $friendRequests;
    }

    public function getSentRequests ($db, $userId) {
        $sql = "SELECT * FROM friends WHERE user_id1 = ? AND status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $sentRequests = $result->fetch_all(MYSQLI_ASSOC);
        return $sentRequests;
    }

    public function getProfilePicture ($db, $userId) {
        $sql = "SELECT profile_picture FROM users_bio WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $profilePicture = $result->fetch_assoc();
        //return only value of profile_picture
        if ($profilePicture != NULL) {
            $profilePicture = $profilePicture['profile_picture'];
        }
        return $profilePicture;
    }

    public function getAcceptedFriends ($db, $userId) {
        $sql = "SELECT * FROM friends WHERE (user_id1 = ? OR user_id2 = ?) AND status = 'accepted'";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $friends = $result->fetch_all(MYSQLI_ASSOC);
        return $friends;
    }

    public function getExercisesQuery ($db, $query) {
        $sql = "SELECT * FROM list_of_exercises WHERE name LIKE ?";
        $stmt = $db->prepare($sql);
        $searchPattern = '%' . $query . '%'; 
        $stmt->bind_param("s", $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        return $exercises;
    }

    public function getApprovedExercises ($db, $exerciseName = NULL) {
        if ($exerciseName == NULL) {
            $sql = "SELECT * FROM list_of_exercises WHERE isApproved = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $exercises = $result->fetch_all(MYSQLI_ASSOC);
            if ($exercises == NULL) {
                return false;
            } else {
                return $exercises;
            }
        } else {
            $sql = "SELECT * FROM list_of_exercises WHERE name = ? AND isApproved = 1";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $exerciseName);
            $stmt->execute();
            $result = $stmt->get_result();
            $exercise = $result->fetch_assoc();
            if ($exercise == NULL) {
                return false;
            } else {
                return $exercise;
            }

        }
    }

    public function getUserExercises ($db, $userId, $exerciseName = NULL) {
        if ($exerciseName == NULL) {
            $sql = "SELECT * FROM list_of_exercises WHERE added_by = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $exercises = $result->fetch_all(MYSQLI_ASSOC);
            if ($exercises == NULL) {
                return false;
            } else {
                return $exercises;
            }
        } else {
            $sql = "SELECT * FROM list_of_exercises WHERE name = ? AND added_by = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $exerciseName, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $exercise = $result->fetch_assoc();
            if ($exercise == NULL) {
                return false;
            } else {
                return $exercise;
            }

        }
    }

    public function getExercisesByCategory ($db, $category) {
        $sql = "SELECT * FROM list_of_exercises WHERE category = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        return $exercises;
    }


    public function getCategories ($db) {
        $sql = "SELECT * FROM list_of_exercises";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        //count how many unique catergories there is
        $categories = array_column($categories, 'category');
        $categories = array_unique($categories);
        //set id to each category
        $categories = array_values($categories);
        $categories = array_map(function($category, $index) {
            return [
                'id' => $index + 1,
                'category' => $category
            ];
        }, $categories, array_keys($categories));
        return $categories;
    }

    public function getExerciseNameByExerciseId ($db, $exerciseId) {
        $sql = "SELECT name FROM list_of_exercises WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $exerciseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exerciseName = $result->fetch_assoc();
        return $exerciseName;
    }
}



// Create an instance of UserModel with the database connection
$userModel = new UserModel();