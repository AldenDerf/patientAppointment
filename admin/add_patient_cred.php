<?php

include './config.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    $patient_id = $_POST['patient_id'];
    $raw_password = $_POST['password'];
    $password = password_hash($raw_password, PASSWORD_DEFAULT); // Hash the password
    $date_created = date("Y-m-d H:i:s");
    $created_by = "admin"; // Replace with the appropriate creator

    // Check if patient_id already exists
    $check_query = "SELECT * FROM patient_login WHERE patient_id = '$patient_id'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "<div class='container mt-3'><div class='alert alert-danger'>Patient ID already exists. Cannot create.</div></div>";
    } else {
        // Insert user credentials
        $insert_query = "INSERT INTO patient_login (patient_id, password, date_created, created_by) VALUES ('$patient_id', '$password', '$date_created', '$created_by')";
        if ($conn->query($insert_query) === TRUE) {
            echo "<div class='container mt-3'><div class='alert alert-success'>User credentials inserted successfully.</div></div>";
        } else {
            echo "<div class='container mt-3'><div class='alert alert-danger'>Error: " . $conn->error . "</div></div>";
        }
    }

    $conn->close();
}
?>