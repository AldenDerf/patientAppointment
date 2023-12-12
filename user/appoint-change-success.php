<?php
include './config.php';

// Get first name form the bgh_database.tblpatiens
function getPatientFirstName($conn)
{
    if (isset($_SESSION['patient_id'])) {
        $patient_id = $_SESSION['patient_id'];

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

$patientFirstname = getPatientFirstName($conn2);


// Close the database connection
$conn->close();
$conn2->close();
