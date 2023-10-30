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
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function savePlan($db, $id, $data) {
        $response = [];
        $lastInsertedPlanId = 0;
        $lastInsertedDayId = 0;
        $lastInsertedSetId = 0;
        
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
            $query = "INSERT INTO mnp_days (plan_id, day_name) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("is", $lastInsertedPlanId, $day['dayNumber']);
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
                $stmt->bind_param("issi", $lastInsertedDayId, $set['setName'], $set['rest'], $set['comment']);
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
                    }
                }
            }
        }
        
        $response['success'] = "Data inserted successfully!";
        return $response;
    }

    public function getPlans ($db, $id) {
        $sql = "SELECT plan_id, plan_name, date_created, user_id FROM mnp_plans WHERE user_id = ?";
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

    public function getPlanById ($db, $planId) {
        $sql = "SELECT * FROM mnp_plans WHERE plan_id = ?";
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
}

// Create an instance of UserModel with the database connection
$userModel = new UserModel();