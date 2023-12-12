<?php
include './config.php';

// get the firtsname of the log in patient
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
    return '';
}

// get the patient name based on the patient id that was logged in
function getPatientName($conn, $patient_id)
{

    // Query to retrieve the firstname based on the patient_id
    $query = "SELECT CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname FROM tblpatients WHERE patient_id = ?;";

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return null; // Return null in case of query preparation failure
    }

    $stmt->bind_param("i", $patient_id);
    if (!$stmt->execute()) {
        return null; // Return null in case of query execution failure
    }

    $result = $stmt->get_result();

    // Check if there's a result
    if ($result->num_rows > 0) {
        // Fetch the result as an associative array
        $patientName = $result->fetch_assoc();
        return $patientName['fullname'];
    } else {
        return ""; // Return an empty string if the doctor is not found
    }
}

// Get the appointment details base on appointment ID
function getAppointments($appointmenID, $conn)
{
    $sql = 'SELECT * FROM appointments WHERE appointment_id = ? ';

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param('i', $appointmenID); // "i" for integer type

    // Execute the query
    $stmt->execute();

    // Get result 
    $result = $stmt->get_result();

    if ($result === false) {
        return 'Error:' . $conn->error;
    }

    $apppoinment = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $apppoinment[] = $row;
        }
    }
    return $apppoinment;
}


// Get the doctors fullname (Firstname M. Lastname)
function getDoctorName($dbConnection, $doctorId)
{
    $query = "SELECT CONCAT(firstname, ' ', IFNULL(CONCAT(LEFT(middlename, 1), '. '), ''), lastname) AS fullname FROM tbldoctors WHERE doctor_id = ?";

    // Prepare and execute the SQL query
    $stmt = $dbConnection->prepare($query);
    if (!$stmt) {
        return null; // Return null in case of query preparation failure
    }

    $stmt->bind_param("i", $doctorId);
    if (!$stmt->execute()) {
        return null; // Return null in case of query execution failure
    }

    $result = $stmt->get_result();

    // Check if there's a result
    if ($result->num_rows > 0) {
        // Fetch the result as an associative array
        $doctorDetails = $result->fetch_assoc();
        return $doctorDetails['fullname'];
    } else {
        return ""; // Return an empty string if the doctor is not found
    }
}

// Convert date from yyyy-mm-dd to Month dd, yy
function convertDateFormat($dateString) {
    // Convert the string date to a DateTime object
    $date = new DateTime($dateString);

    //Format the date to the desire format (e.g., December 12, 2023)
    return $date->format('F j, Y');
}
