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
    $query = "SELECT CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname, license, type_service FROM tbldoctors WHERE doctor_id = ?" ;

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


// Create appointment
function createAppointment($conn, $patientID, $doctorID, $appointmentDate, $appointmentTime, $status, $dateRequested, $remarks)
{
    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status, date_requested, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssss", $patientID, $doctorID, $appointmentDate, $appointmentTime, $status, $dateRequested, $remarks);

    if ($stmt->execute()) {
        return $conn->insert_id; // Successful insertion
    } else {
        return false; // Failed insertion
    }
}

// Example usage:
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $appointmentDate = $_POST['appointment-date'];
    $appointmentTime = $_POST['appointment-time'];
    $remarks = $_POST['remarks'];
    if (isset($_SESSION['patient_id']) && isset($_GET['doctor_id']) && isset($_POST['appointment-date']) && isset($_POST['appointment-time'])) {
        // Set other required variables like status, dateToday, remarks
        $status = "Upcoming"; // Example status
        $dateRequested = date("Y-m-d"); // Example date today
        $patientID = $_SESSION['patient_id'];
        $doctorID = $_GET['doctor_id'];


        // Assuming createAppointment returns the newly inserted appointment ID
        $appointmentID = createAppointment($conn, $patientID, $doctorID, $appointmentDate, $appointmentTime, $status, $dateRequested, $remarks);

        if ($appointmentID !== false) {
            header("Location: appointment-request-sucessPage.php?appointment_id=$appointmentID");
            exit();
        } else {
            echo "Failed to create appointment.";
        }
    }
}

// Close the database connection
$conn->close();
$conn2->close();
?>