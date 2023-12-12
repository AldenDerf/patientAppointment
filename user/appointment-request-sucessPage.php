<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGH-Appointment</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- PHP -->
    <?php include './appointment-request-sucess.php'; ?>


    <!-- Header -->
    <nav class="navbar navbar-light bg-light mb-4" style='height: 80px; padding: 5px'>
        <div class='container'>
            <h5 class="m-0 ml-3 d-block">BGH Patient Appointment</h5>

            <!-- Drop Down -->
            <div class="btn-group">
                <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <?php echo $patientFirstname; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                    <li><button class="dropdown-item" type="button">View History</button></li>
                    <li><a href='logout.php' class="dropdown-item" type="button">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div class='container'>
        <div class='col-sm-6  m-auto'>
            <div class="card pb-3">
                <div class='row-4 text-center'>
                    <i class="bi bi-calendar2-check text-success " style='font-size: 70px;'></i>
                    <h4 class="card-title text-success text-center mt-0 ">Congratulations!</h4>
                </div>
                <div class="card-body p-3">

                    <p style='text-align: justify'>Dear <span id='patient-name' style='font-weight:bold;'></span>,</p>
                    <p class="card-text">Your appointment with <b><span id='doctor_name'></span></b> has been <span class='text-success' style='font-weight:bold;'>successfully booked</span>. We look forward to seeing you on <span style="font-weight: bold;" id='appointment-date'></span> at <span id='appointment-time' style='font-weight:bold'>[Time]</span>. If you have any questions, feel free to <a href='#'>contact us</a>.</p>
                    <p>Thank you!</p>
                    <p>Sincerely, <br>Batanes General Hospital</p>
                </div>
                <div class='container text-center'>
                    <a href="./index.php" class="btn btn-secondary"><i class="bi bi-house-door "></i> Back to Home</a>
                </div>
            </div>
        </div>

    </div>


    <!-- Footer -->
    <footer class=" footer mt-auto py-3" style='bottom: 0;'>
        <div class="container text-center">
            <span class="text-muted"> ©2023 Batanes General Hospital </span>
        </div>
    </footer>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
<script>
    // Doctor name
    let  doctor_name = <?php echo json_encode($doctorName); ?>;
    console.log(doctor_name);

    // Appointment Details
    let appointmentDetails = '<?php echo $appointmentDetails; ?>';
    console.log(appointmentDetails)

    // Appointment Date
    let appointmentDate = <?php echo json_encode($appointmentDate); ?>;
    console.log(appointmentDate);

    // Patient name
    const patientName = <?php echo  json_encode($patientName); ?>;
    console.log(patientName);


    // Document load
    document.addEventListener('DOMContentLoaded', function () {

        document.getElementById('patient-name').textContent = patientName;
        document.getElementById('doctor_name').textContent = doctor_name;
        document.getElementById('appointment-date').textContent =appointmentDate;

    });
</script>

</html>