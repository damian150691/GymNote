<?php

class UserModel {
    // Database connection
    private $db;

    // constructor receives database connection as parameter
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    // select user by email
    public function getUserByEmail($email) {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Fetch the user data
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle database query errors gracefully
            // You can log the error or return an appropriate response
            throw new Exception("Error fetching user by email: " . $e->getMessage());
        }
    }




    public function verifyPassword($user, $password) {
        // Implement logic to verify the password of a user
        // Example: $hashedPassword = $user['password']; // Retrieve the hashed password from the user data
        // Use password_verify() to check if the provided password matches the hashed password

        // Replace this example code with your actual password verification logic
        return password_verify($password, $user['password']);
    }
}