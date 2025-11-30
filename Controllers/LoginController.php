<?php
// controllers/LoginController.php

// 1. Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Retrieve and sanitize user input
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 3. Simple Hardcoded Check (No DB integration, for initial development)
    $HARDCODED_USERNAME = 'Admin';
    $HARDCODED_PASSWORD = 'pass123';

    if ($username === $HARDCODED_USERNAME && $password === $HARDCODED_PASSWORD) {

        // --- SUCCESSFUL LOGIN LOGIC ---

        // session_start();
        // $_SESSION['loggedin'] = true;
        // $_SESSION['user_role'] = 'Teacher';

        // For now, just redirecting to temporary success page
        header("Location: ../admin/dashboard.php");
        exit();

    } else {

        // --- FAILED LOGIN LOGIC ---

        // For this simple controller, we redirect back to the login page with an error flag.
        header("Location: ../login.php?error=invalid");
        exit();
    }
} else {
    // If someone tries to access the controller directly (not via form submission)
    header("Location: ../login.php");
    exit();
}