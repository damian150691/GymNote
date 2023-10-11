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
    
    public function registerUser($db, $username, $email, $password) {

        
        // Hash the password (you should use a secure hashing algorithm)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // generate token
        $token = bin2hex(random_bytes(50));

        // set date_registered and last_logged_in to current date and time
        $date_registered = date("Y-m-d H:i:s");
        $last_logged_in = $date_registered;

        //setting user role to user
        $user_role = "user";


        $sql = "INSERT INTO users (username, email, password, token, date_registered, last_logged, user_role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $token, $date_registered, $last_logged_in, $user_role);
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

    public function loginUser($db, $username, $password) {
        
        // Retrieve the user with the given username
        $user = $this->getUserByUsername($db, $username);

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


}

// Create an instance of UserModel with the database connection
$userModel = new UserModel();