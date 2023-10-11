<?php

class UserModel {
    
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
}

// Create an instance of UserModel with the database connection
$userModel = new UserModel();