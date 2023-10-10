<?php

class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registerUser($username, $email, $password) {
        // Hash the password (you should use a secure hashing algorithm)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an SQL statement for inserting the user data
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        // Prepare the statement
        $stmt = $this->pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            // User registered successfully
            return true;
        } else {
            // Registration failed
            return false;
        }
    }
}

// Create an instance of UserModel with the database connection
$userModel = new UserModel($pdo);