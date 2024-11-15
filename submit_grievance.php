<?php
session_start();
include("./connect.php"); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the user's name and email from the session
$name = $_SESSION['name']; // Assuming you stored the name in the session during login
$email = $_SESSION['email']; // Email stored in session

// Create database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize the form data
    $rollno = htmlspecialchars(trim($_POST['rollno'])); // Optional
    $grievance_type = htmlspecialchars(trim($_POST['grievance_type']));
    $details = htmlspecialchars(trim($_POST['details']));

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO grievances (name, rollno, email, grievance_type, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $rollno, $email, $grievance_type, $details);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to grievance page
        header("Location: grievance.php");
        exit(); // Ensure no further code is executed
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
