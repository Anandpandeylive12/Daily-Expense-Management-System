<?php
include("connect.php");
session_start();

// Check if the user is logged in; if not, redirect to login
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$sess_email = $_SESSION["email"];

// Prepare and execute a SQL statement to prevent SQL injection
$stmt = $con->prepare("SELECT userid, firstname, lastname, email, profile_path FROM users WHERE email = ?");
$stmt->bind_param("s", $sess_email);

// Check if the query execution was successful
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $userid = $row["userid"];  // Change user_id to userid (as per your table structure)
        $firstname = $row["firstname"];
        $lastname = $row["lastname"];
        $username = $firstname . " " . $lastname;
        $useremail = $row["email"];

        // Check if profile path is empty
        if (empty($row["profile_path"])) {
            $userprofile = "uploads/default_profile.png";  // Default image if not set
        } else {
            $userprofile = "uploads/" . $row["profile_path"];
        }

    } else {
        // Default values if no user data is found
        $userid = "";
        $username = "";
        $useremail = "";
        $userprofile = "uploads/default_profile.png";
    }

    // Free result and close statement
    $result->free();
} else {
    // Query execution failed
    die("Error executing query: " . $stmt->error);
}

$stmt->close();
?>
