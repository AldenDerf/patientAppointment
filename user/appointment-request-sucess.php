<?php
include './getFunctions/getdetails.php';

// Retrieving the appointment_id from the URL parameter
$appointmentID = isset($_GET['appointment_id']) ? $_GET['appointment_id'] :'';

// Retrieving the patient_id from the $_SESSION[];
$patient_id = isset($_SESSION['patient_id']) ? $_SESSION['patient_id'] : '';

// Sanitized the appointmentID bfore passing it to JavaScript
$sanitizedAppointmentID = htmlspecialchars($appointmentID, ENT_QUOTES, 'UTF-8');

// Call the getPatientFirstName() function to hey the fistname of the logged in patient
$patientFirstname = getPatientFirstName($conn2, $patient_id);

// Call the getPatientName($conn, $patient_id) function to get the full name of the patient
$patientName = getPatientName($conn2, $patient_id);

// Call the function to get the appointment base on the header appointment_id
$appointmentDetails = json_encode(getAppointments($appointmentID, $conn));


// Get the Appointment ID
$doctor_id = getAppointments($appointmentID, $conn)[0]['doctor_id'] ? getAppointments($appointmentID, $conn)[0]['doctor_id'] : 0;

// Cal the convertDateFormat($dateString) function to get the date.
$appointmentDate = getAppointments($appointmentID, $conn)[0]['appointment_date'] ? convertDateFormat(getAppointments($appointmentID, $conn)[0]['appointment_date']) : '';

// Doctor firstname
$doctorName = htmlspecialchars(getDoctorName($conn2, $doctor_id), ENT_QUOTES, 'UTF-8');

// Close the database connection
$conn->close();
$conn2->close();
