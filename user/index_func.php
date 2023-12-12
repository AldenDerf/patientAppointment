<?php
include './config.php';


// Function to fetch all doctors and their details from the database
function getAllDoctors($dbConnection)
{
    $query = "SELECT doctor_id, CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname, license, type_service FROM tbldoctors ORDER BY firstname, middlename, lastname";

    // Prepare and execute the SQL query
    $result = $dbConnection->query($query);
    if ( !$result ) {
        die("Failed to execute query:" . $dbConnection->error);
    }

    $doctors = []; // Array to store doctor details
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row; // Store each doctor's details in the $doctors array
        }
    }

    return $doctors;
}

$allDoctors = empty(getAllDoctors($conn2)) ? [] : getAllDoctors($conn2);

// Get first name form the 
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


// Check if ther is an upcoming appointment to the current user
function checkAppointment($dbConnection)
{
    
    if (!isset($_SESSION['patient_id'])) {
        return array('hasAppointment' => false); // If patient ID is not set, return false
    }

    // Get the patient_id from the session
    $patientId = $_SESSION['patient_id'];

    // Query to check if there are upcoming appointments for the patient
    $query = "SELECT appointment_id, patient_id, doctor_id, appointment_date, appointment_time, remarks FROM appointments WHERE patient_id = ? AND status = 'Upcoming'";
    $stmt = $dbConnection->prepare($query);
    if (!$stmt) {
        die('Failed to prepare query ' . $dbConnection->error);
    }
    $stmt->bind_param("i", $patientId);
    if (!$stmt->execute()) {
        die("Fialed to execute query". $stmt->error);
    }
    $result = $stmt->get_result();

    // Initialize arrays to hold all appointments and only the first one
    $allAppointments = [];
    $firstAppointment = null;

    // Check if there are any upcoming appointments
    if ($result->num_rows > 0) {
        // Fetch all matching appointments
        while ($row = $result->fetch_assoc()) {
            $allAppointments[] = $row;
            // Capture the first appointment (if needed)
            if ($firstAppointment === null) {
                $firstAppointment = $row;
            }
        }
    }

    // Return all appointments and the first appointment
    return array('hasAppointment' => count($allAppointments) > 0, 'appointments' => $allAppointments, 'firstAppointment' => $firstAppointment);
}

// Example usage assuming $dbConnection is your database connection
$appointmentResult = checkAppointment($conn);

// Access the boolean, all appointments, and the first appointment
$hasAppointment = $appointmentResult['hasAppointment'];
$allAppointments = $appointmentResult['appointments'];
$firstAppointment = $appointmentResult['firstAppointment'];
$appointmentID = (empty($firstAppointment['appointment_id'])) ? '' : $firstAppointment['appointment_id'] ;
$doctor_id = empty($firstAppointment['doctor_id']) ? 0 : $firstAppointment['doctor_id'];


function getDoctorDetails($dbConnection, $doctorId)
{
    $query = "SELECT CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname FROM tbldoctors WHERE doctor_id = ?";

    // Prepare and execute the SQL query
    $stmt = $dbConnection->prepare($query);
    if (!$stmt) {
        die('Failed to prepare query:' . $dbConnection->error);
    }

    $stmt->bind_param("i", $doctorId);
    if (!$stmt->execute()) {
        die("Failed to execute query" . $stmt->error);
    }
    $result = $stmt->get_result();

    // Check if there's a result
    if ($result->num_rows > 0) {
        // Fetch the result as an associative array
        $doctorDetails = $result->fetch_assoc();
        return $doctorDetails['fullname'];
    } else {
        return "Doctor Not Found"; // Handle the case where the doctor is not found
    }
}

$doctorName = '';

if ($doctor_id !== null) {
    $doctorDetails = getDoctorDetails($conn2, $doctor_id);
    $doctorName = !empty($doctorDetails) ? $doctorDetails : '';
}




// Close the database connection
$conn->close();
$conn2->close();
