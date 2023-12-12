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
</head>

<body>

    <?php include './booking.php'; ?>




    <!-- Header -->
    <nav class="navbar navbar-light bg-light" style='height: 80px; padding: 5px'>
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

    <div class='container mt-3 mb-5'>
        <!-- Header -->
        <div class='container'>
            <div class='container mb-4'>
                <h3 class='text-center'>Appointment with</h3>
            </div>

            <!-- Doctor -->
            <div class="row mb-4">
                <div class=" text-center d-flex justify-content-center align-content-center mb-0 " style=" margin: auto;">
                    <div class='col-auto d-flex justify-content-center p-1 me-1'>
                        <img src='./images/icon-blue-paper-camera.jpg' class='img-fluid' style='border-radius: 50%; height: 50px;'>
                    </div>

                    <div>
                        <!-- Displaying doctor information -->
                        <h5 class='mb-0' id='doctor-name'>DR. <span id='doctor-fullname'></span></h5>
                        <p class='mb-0' id='type-service' style='font-size:11px; text-align: left;'></p>
                        <p style='font-size:11px; text-align: left;'>License: <span id='license'></span></p>
                    </div>
                </div>
                <div class='container mt-0 text-center'>
                    <a class="icon-link" href="#">
                        Change Doctor
                    </a>
                </div>
            </div>

            <div class='row mb-4'>
                <p>The appointment time must be at least 1 hour ahead of the current time, and appointments are only available on weekdays between 9 AM and 4 PM.</p>
            </div>

            <!-- Form -->
            <form method='POST' action='booking_page.php?doctor_id=<?php echo urlencode($doctor_id); ?>'>
                <!-- Date and Time -->
                <div class="row mb-3">
                    <!-- Appointment Date -->
                    <div class="col-sm g3 mb-3">
                        <div class='input-group'>
                            <span class="input-group-text">Date:</span>
                            <input type="date" name='appointment-date' id='appointment-date' class="form-control" aria-label="Appointment date" require>
                        </div>
                        <div class='invalid-feedback' id='appointment-date-error'></div>
                    </div>


                    <!-- Appointment Time -->
                    <div class="col-sm g3 mb-3">
                        <div class='input-group'>
                            <span class="input-group-text">Time:</span>
                            <input type="time" id='appointment-time' name='appointment-time' class="form-control" aria-label="Appointment time" required>
                        </div>

                    </div>
                </div>

                <!-- Remarks Optional -->
                <div class='row mb-4'>
                    <div class="form-floating p-1">
                        <textarea class="form-control" name='remarks' placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Remarks <i>(Optional)</i></label>
                    </div>
                </div>
        </div>

        <div class='cotainer text-center'>
            <button type="submit" class="btn btn-secondary me-5">Submit</button>
            <button type="button" class="btn btn-light">Cancel</button>
        </div>
        </form>
    </div>




    <!-- Footer -->
    <footer class=" footer mt-auto py-3" style='bottom: 0;'>
        <div class="container text-center">
            <span class="text-muted"> ©2023 Batanes General Hospital </span>
        </div>
    </footer>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // Retrieve the JSON-encoded doctor data from the hidden div
        var doctorInfo = <?php echo json_encode($doctorInfo); ?>;

        // Function to set the text content of elements
        function setDoctorInfo() {
            // Set the doctor's full name
            document.getElementById('doctor-fullname').textContent = ' ' + doctorInfo.fullname;

            // Set the type of service offered by the doctor
            document.getElementById('type-service').textContent = doctorInfo.type_service;

            // Set the doctor's license information
            document.getElementById('license').textContent = doctorInfo.license;
        }

        // Run the function when the DOM content is loaded
        document.addEventListener('DOMContentLoaded', setDoctorInfo);

        // Validation for the appointment date
        function validateAppointmentDate() {
            const selectedDate = new Date(document.getElementById('appointment-date').value);
            const currentDate = new Date();

            // Check if the selected date is today and if the current time is after 3 PM
            const isToday = selectedDate.toDateString() === currentDate.toDateString();
            const isAfter3PM = currentDate.getHours() >= 15 && isToday;

            // Check if the selected date is a weekend (Saturday = 6, Sunday = 0)
            const dayOfWeek = selectedDate.getDay();
            const isWeekend = dayOfWeek === 6 || dayOfWeek === 0; // Saturday or Sunday

            // Get the appointment date input element and its error message element
            const appointmentDateInput = document.getElementById('appointment-date');
            const appointmentDateError = document.getElementById('appointment-date-error');

            // Validate the selected date and time
            if (selectedDate < currentDate || isAfter3PM || isWeekend) {
                // Add 'is-invalid' class to the input if the conditions are not met
                appointmentDateInput.classList.add('is-invalid');
                // Set the invalid feedback
                appointmentDateError.textContent = "Invalid date or time. Please choose a valid date after the current date, before 3 PM on weekdays, and not on weekends.";
                // Remove 'is-valid' class if the conditions are not met
                appointmentDateInput.classList.remove('is-valid');
            } else {
                // Remove 'is-invalid' class if the conditions are met
                appointmentDateInput.classList.remove('is-invalid');
                // Set the valid feedback
                appointmentDateError.textContent = "";
                // Add 'is-valid' class to the input if the conditions are met
                appointmentDateInput.classList.add('is-valid');
            }
        }

        // Attach the onchange event listener to the appointment date input
        document.getElementById('appointment-date').onchange = validateAppointmentDate;


       
        // Attach the onchange event listener to the appointment time input
        document.getElementById('appointment-time').onchange = validateAppointmentTime;
    </script>
</body>

</html>