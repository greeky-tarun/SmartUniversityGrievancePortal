<?php
include './connect.php';

if (isset($_POST['signUp'])) {
    // Get form data
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password

    // Validate mobile number length
    if (strlen($mobile) > 15) { // Adjust this length as per your database column definition
        echo "Mobile number is too long!";
        exit();
    }

    // Check if the email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        // Insert new user into the database
        $insertQuery = "INSERT INTO users (name, mobile, email, password) VALUES ('$name', '$mobile', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $conn->error; // Display any SQL error
        }
    }
}

if (isset($_POST['signIn'])) {
    // Get login data
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password

    // Check for valid credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name']; // Store full name in session
        header("Location: homepage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
