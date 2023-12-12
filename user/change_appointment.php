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

// Function to fetch doctor data based on doctor_id
function getDoctorData($doctor_id, $conn)
{
    // Prepare the query
    $query = "SELECT CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname, license, type_service FROM tbldoctors WHERE doctor_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $doctor_id); // Assuming doctor_id is an integer

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch data into an array
    $doctorData = array();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $doctorData = $row; // Store the fetched row in the array
    }

    // Close the statement
    $stmt->close();

    return $doctorData; // Return the array containing doctor's data
}

// Assuming you have already established a database connection $conn

// Get the doctor_id from the header (or wherever it's coming from)
$doctor_id = $_GET['doctor_id']; // Example: Fetching doctor_id from URL query parameter

// Call the function to get doctor data
$doctorInfo = getDoctorData($doctor_id, $conn2);



function getAppointmentDetails($dbConnection, $appointmentId)
{
    // Assuming you already have a database connection $dbConnection

    // Get the patient_id from the session
    $patientId = $_SESSION['patient_id'];

    // Query to retrieve appointment details based on appointment ID and patient ID
    $query = "SELECT appointment_id, patient_id, doctor_id, appointment_date, appointment_time, remarks FROM appointments WHERE patient_id = ? AND appointment_id = ? AND status = 'Upcoming'";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("ii", $patientId, $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the appointment details
    $appointmentDetails = $result->fetch_assoc();

    // Return the appointment details
    return $appointmentDetails;
}

$appointmentDetails = getAppointmentDetails($conn, $_GET['appointment_id']);

function updateAppointment($dbConnection, $doctor_id, $appointment_date, $appointment_time, $remarks, $appointment_id)
{
    // Get the patient_id from the session
    $patientId = $_SESSION['patient_id'];

    // Prepare and execute the SQL query to update the appointment
    $query = "UPDATE appointments 
              SET doctor_id = ?, appointment_date = ?, appointment_time = ?, status = 'Upcoming', date_requested = CURDATE(), remarks = ?
              WHERE appointment_id = ? and patient_id= ?";
    $stmt = $dbConnection->prepare($query);
    $stmt->bind_param("isssii", $doctor_id, $appointment_date, $appointment_time, $remarks, $appointment_id, $appointment_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Insertion successful, redirect to index.php with appointment_id and doctor_id
        header("Location: ./appoint-change-successPage.php?appointment_id=$appointment_id&doctor_id=$doctor_id");
        exit();
    } else {
        // Insertion failed, display Bootstrap alert
        echo '<div class="alert alert-danger" role="alert">Appointment update failed: ' . $stmt->error . '</div>';
    }
}

// Assuming you have a form submission or some trigger event where you gather data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and validate/sanitize inputs
    $doctor_id = $_GET['doctor_id']; // Assuming you get this value from the form
    $appointment_date = $_POST['appointment-date'];
    $appointment_time = $_POST['appointment-time'];
    $remarks = $_POST['remarks'];
    $appointment_id = $_GET['appointment_id']; // Assuming you get this value from the URL or form

    // Call the function to update the appointment
    updateAppointment($conn, $doctor_id, $appointment_date, $appointment_time, $remarks, $appointment_id);
}
// Close the database connection
$conn->close();
$conn2->close();
