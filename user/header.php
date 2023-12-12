<?php
   
    $patientFirstname ='';
if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];

    // Assuming $conn2 is your open connection
    if ($conn2) {
        $query = "SELECT firstname FROM tblpatients WHERE patient_id = ?";
        $stmt = $conn2->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $patientFirstname = $row['firstname'];
            }

            $stmt->close(); // Close the statement after use
        }
    }
}





?>