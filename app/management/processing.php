<?php
// Start session
session_start();

// Include the database connection file
include "../database.php";

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $email = filter_var($_POST["exampleInputEmail1"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["exampleInputPassword1"];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: login.php");
        exit();
    }
    
    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT id, email, password, name, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        // Check if user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            
            // Verify password (assuming passwords are hashed with password_hash())
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                
                // Log successful login
                $logQuery = "INSERT INTO login_logs (user_id, login_time, ip_address) VALUES (:user_id, NOW(), :ip)";
                $logStmt = $pdo->prepare($logQuery);
                $ip = $_SERVER['REMOTE_ADDR'];
                $logStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                $logStmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                $logStmt->execute();
                
                // Redirect to dashboard
                header("Location: db_home.php");
                exit();
            } else {
                // Invalid password
                $_SESSION['error'] = "Invalid email or password";
                header("Location: login.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['error'] = "Invalid email or password";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
}

// Function to display error messages
function displayError() {
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
}

?>