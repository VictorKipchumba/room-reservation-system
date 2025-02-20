<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Check if the username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "User with this username or email already exists.";
        header("Location: register.html");
        exit();
    } else {
        // Insert the new user
        $insert_sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.html?registration=success");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
            header("Location: register.html");
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>