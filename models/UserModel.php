<?php

class UserModel {

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


    public function savePlan($db, $id, $data) {
        $response = [];
        $lastInsertedPlanId = 0;
        $lastInsertedDayId = 0;
        $lastInsertedSetId = 0;
        
        // Insert into mkp_plans
        $plan_name = "MyPlan";
        $date_created = date('Y-m-d H:i:s'); // Current date and time
        $query = "INSERT INTO mkp_plans (plan_name, date_created, user_id) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssi", $plan_name, $date_created, $id);
        if ($stmt->execute()) {
            $lastInsertedPlanId = $db->insert_id;
        } else {
            $response['error'] = "Error inserting into mkp_plans: " . $stmt->error;
            return $response;
        }
    
        // Iterate through the data and insert into other tables
        foreach ($data as $day) {
            // Insert into mkp_days
            $query = "INSERT INTO mkp_days (plan_id, day_name) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("is", $lastInsertedPlanId, $day['dayNumber']);
            if ($stmt->execute()) {
                $lastInsertedDayId = $db->insert_id;
            } else {
                $response['error'] = "Error inserting into mkp_days: " . $stmt->error;
                return $response;
            }
            
            foreach ($day['sets'] as $set) {
                // Insert into mkp_sets
                $query = "INSERT INTO mkp_sets (day_id, set_name, rest, comments) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("issi", $lastInsertedDayId, $set['setName'], $set['rest'], $set['comment']);
                if ($stmt->execute()) {
                    $lastInsertedSetId = $db->insert_id;
                } else {
                    $response['error'] = "Error inserting into mkp_sets: " . $stmt->error;
                    return $response;
                }
                
                foreach ($set['exercises'] as $exercise) {
                    // Insert into mkp_exercises
                    $query = "INSERT INTO mkp_exercises (set_id, lp, exercise_name, sets, repetitions, weight, rest, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("isssssss", $lastInsertedSetId, $exercise['id'], $exercise['exercise'], $exercise['sets'], $exercise['repetitions'], $exercise['weight'], $exercise['rest'], $exercise['comment']);
                    if (!$stmt->execute()) {
                        $response['error'] = "Error inserting into mkp_exercises: " . $stmt->error;
                        return $response;
                    }
                }
            }
        }
        
        $response['success'] = "Data inserted successfully!";
        return $response;
    }

    public function getPlans ($db, $id) {
        $sql = "SELECT plan_id, plan_name, date_created FROM mkp_plans WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plans = $result->fetch_all(MYSQLI_ASSOC);
        return $plans;
    }

    public function deletePlan ($db, $userId, $planId) {
        $sql = "DELETE FROM mkp_plans WHERE user_id = ? AND plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $userId, $planId);
        $stmt->execute();

        $sql = "SELECT day_id FROM mkp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $dayIds = $stmt->get_result();
        $dayIdArray = [];
        while ($row = $dayIds->fetch_assoc()) {
            $dayIdArray[] = $row['day_id'];
        }

        $sql = "SELECT set_id FROM mkp_sets WHERE day_id IN (" . implode(',', array_fill(0, count($dayIdArray), '?')) . ")";
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('i', count($dayIdArray)), ...$dayIdArray);
        $stmt->execute();
        $setIds = $stmt->get_result();
        $setIdArray = [];
        while ($row = $setIds->fetch_assoc()) {
            $setIdArray[] = $row['set_id'];
        }
        
        $sql = "DELETE FROM mkp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();

        if (!empty($dayIdArray)) {
            $sql = "DELETE FROM mkp_sets WHERE day_id IN (" . implode(',', array_fill(0, count($dayIdArray), '?')) . ")";
            $stmt = $db->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($dayIdArray)), ...$dayIdArray);
            $stmt->execute();
        }
        
        if (!empty($setIdArray)) {
            $sql = "DELETE FROM mkp_exercises WHERE set_id IN (" . implode(',', array_fill(0, count($setIdArray), '?')) . ")";
            $stmt = $db->prepare($sql);
            $stmt->bind_param(str_repeat('i', count($setIdArray)), ...$setIdArray);
            $stmt->execute();
        }


    }

    public function getPlanById ($db, $planId) {
        $sql = "SELECT * FROM mkp_plans WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $plan = $result->fetch_assoc();
        return $plan;
    }

    public function getDaysByPlanId ($db, $planId) {
        $sql = "SELECT * FROM mkp_days WHERE plan_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $planId);
        $stmt->execute();
        $result = $stmt->get_result();
        $days = $result->fetch_all(MYSQLI_ASSOC);
        return $days;
    }

    public function getSetsByDayId ($db, $dayId) {
        $sql = "SELECT * FROM mkp_sets WHERE day_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $dayId);
        $stmt->execute();
        $result = $stmt->get_result();
        $sets = $result->fetch_all(MYSQLI_ASSOC);
        return $sets;
    }

    public function getExercisesBySetId ($db, $setId) {
        $sql = "SELECT * FROM mkp_exercises WHERE set_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $setId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercises = $result->fetch_all(MYSQLI_ASSOC);
        return $exercises;
    }
}

// Create an instance of UserModel with the database connection
$userModel = new UserModel();