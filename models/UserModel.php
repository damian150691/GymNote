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
        try {
            // First, save to mkp_plans
            $sql = "INSERT INTO mkp_plans (plan_name, date_created, created_by, user_id) VALUES (?, NOW(), ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute(['MyPlan', $id, $id]);
            $planId = $db->lastInsertId();
    
            // Save each day
            foreach ($data['mnp_days'] as $dayIndex => $day) {
                $sql = "INSERT INTO mkp_days (plan_id, day_name) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$planId, $day['day_name']]);
                $dayId = $db->lastInsertId();
    
                // Save the sets associated with this day
                $set = $data['mnp_sets'][$dayIndex];
                $sql = "INSERT INTO mkp_sets (day_id, set_name, rest, comments) VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$dayId, $set['set_name'], $set['rest'], $set['comments']]);
                $setId = $db->lastInsertId();
    
                // Save the exercises associated with this set
                foreach ($data['mnp_exercises'] as $exercise) {
                    $sql = "INSERT INTO mkp_exercises (set_id, lp, exercise_name, sets, repetitions, weight, rest, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$setId, $exercise['lp'], $exercise['exercise_name'], $exercise['sets'], $exercise['repetitions'], $exercise['weight'], $exercise['rest'], $exercise['comments']]);
                }
            }
            $response['status'] = 'success';
            $response['message'] = 'Plan saved successfully!';
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'Failed to save plan: ' . $e->getMessage();
        }
        return $response;
    }

    public function getPlans ($db, $id) {
        $sql = "SELECT plan_id, plan_name, date_created FROM training_plans WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $plans = $result->fetch_all(MYSQLI_ASSOC);
        return $plans;
    }

      

}

// Create an instance of UserModel with the database connection
$userModel = new UserModel();