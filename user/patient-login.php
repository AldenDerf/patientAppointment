<?php

include './config.php';

session_start();

// Check if user is already logged in
if (isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit;
}

function getPatientFirstName($conn, $patient_id)
{
    if (isset($_SESSION['patient_id'])) {


        // Query to retrieve the firstname based on patient_id
        $query = "SELECT firstname FROM tblpatients WHERE patient_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['firstname'];
        }
    }
    return null;
}


$loginErr = '';



// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientID = $_POST['patient-id'];
    $patientPass = $_POST['patient-password'];

    // Check login credentials against the database
    $sql = "SELECT patient_id, password FROM patient_login WHERE patient_id = '$patientID'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        //Verify the password
        if (password_verify($patientPass, $hashed_password)) {
            // Successful login

            $_SESSION['patient_id'] = $row['patient_id'];
            header("Location: ./index.php"); // Redirect to patient dashboard
            exit();
        } else {
            // Incorrect password
            echo "<div class='container mt-3'><div class='alert alert-danger'>Invalid password.</div></div>";
        }
    } else {
        // Username not found
        echo "<div class='container mt-3'><div class='alert alert-danger'>Patient ID not found.</div></div>";
    }
}

$conn->close();
