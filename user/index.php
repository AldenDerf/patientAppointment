<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login-page.php");
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

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>

    <?php include './index_func.php'; ?>




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

    <!-- Upcoming appointment alert -->
    <div class="alert alert-success" id='upcoming-alert' display='none' role="alert">
        <div class='container text-center'>
            You have an upcoming appointment!
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">View</button>
        </div>
    </div>

    <!-- Modal For the view upcoming-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <div class="modal-title fs-5" id="staticBackdropLabel"><i class="bi bi-calendar-event"></i> Upcoming</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class='table-responsive-sm'>
                        <table class="table ">

                            <tbody>
                                <tr>
                                    <!-- Appointment time -->
                                    <td><i class="bi bi-alarm"></i>: </td>
                                    <td>
                                        <span id='appointment-time' style="font-weight: bold;"></span>
                                    </td>

                                </tr>
                                <tr>
                                    <!-- Appointment date -->
                                    <td><i class="bi bi-calendar-event"></i>: </td>
                                    <td><span id='appointment-date' style='font-weight:bold;'></span></td>
                                </tr>
                                <tr>
                                    <!--  Doctor -->
                                    <td>Doctor:</td>
                                    <td><span id='doctor_name' style='font-weight:bold;'></span></td>
                                </tr>
                                <tr>
                                    <!--  status -->
                                    <td>Remarks:</td>
                                    <td><span id='remarks' style='font-weight:bold;'></span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class='container'>
                            <a href='./change_appointment_page.php?appointment_id=<?php echo $appointmentID; ?>&doctor_id=<?php echo $doctor_id; ?>' type="button" class="btn btn-warning"><i class="bi bi-pencil"></i> Change</a>
                            <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i> Cancel Appointment</button>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap table for doctor details -->
    <div class="container mt-4">
        <h2>Avalilable Doctor</h2>
        <div class='table-responsive-sm'>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>License</th>
                        <th>Service_Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    <!-- Table body will be populated dynamically using JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript to retrieve and display doctor data -->
    <script>
   
        // Parse the JSON string into a JavaScript array
        var doctorsArray = (<?php echo json_encode($allDoctors); ?>);
        console.log(doctorsArray);

        // Function to create and populate table rows with doctor data
        function populateTable() {
            var tableBody = document.getElementById('doctorTableBody');

            // Clear existing table content
            tableBody.innerHTML = '';

            // Populate table with doctor data
            doctorsArray.forEach(function(doctor) {
                var row = document.createElement('tr');
                row.innerHTML = `
                    <td>${doctor.fullname}</td>
                    <td>${doctor.license}</td>
                    <td>${doctor.type_service}</td>
                    <td>
                        <a href='./booking_page.php?doctor_id=${doctor.doctor_id}' type="button" class="btn btn-dark">Book</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Call the function to populate the table on page load
        populateTable();

        // Function that convert time to 12 hour format
        function convertTo12HourFormat(time) {
            var formattedTime = new Date("2000-01-01T" + time);
            var hours = formattedTime.getHours();
            var minutes = formattedTime.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            // Format the time to hh:mm AM/PM
            var formattedTimeString = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + ampm;

            return formattedTimeString;
        }

        function formatAppointmentDate(appointmentDate) {
            var parts = appointmentDate.split('-');
            var year = parseInt(parts[0]);
            var month = parseInt(parts[1]) - 1; // Months are zero-indexed
            var day = parseInt(parts[2]);

            var appointment = new Date(year, month, day);
            var currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);

            var options = {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            };

            var dayDifference = Math.floor((appointment - currentDate) / (1000 * 60 * 60 * 24));

            if (dayDifference === 0) {
                return 'Today, ' + appointment.toLocaleDateString(undefined, options).replace('Today, ', '');
            } else if (dayDifference === 1) {
                return 'Tomorrow, ' + appointment.toLocaleDateString(undefined, options).replace('Tomorrow, ', '');
            } else if (dayDifference >= 2 && dayDifference <= 6) {
                return 'On ' + appointment.toLocaleDateString(undefined, options);
            } else {
                return appointment.toLocaleDateString(undefined, options);
            }
        }

        // For the upcoming alert.. When the window loads...
        window.addEventListener('load', function() {
            // Get the first appointment
            let firstAppointment = <?php echo json_encode($firstAppointment); ?>;
            console.log(firstAppointment);

            //  Get the doctor name
            let doctorName = <?php echo json_encode($doctorName); ?>;


            // Get the boolean value from PHP and assign it to hasAppointment
            var hasAppointment = <?php echo json_encode($hasAppointment ? true : false); ?>;
            console.log(hasAppointment);

            // If there's an appointment...
            if (hasAppointment) {
                // Display the 'upcoming-alert' element
                document.getElementById('upcoming-alert').style.display = 'block';

            } else {
                // If there's no appointment, hide the 'upcoming-alert' element
                document.getElementById('upcoming-alert').style.display = 'none';
            }
            
            // Displaying the appointment time
            document.getElementById('appointment-time').textContent = convertTo12HourFormat(firstAppointment.appointment_time);

            // Displaying the converted date
            document.getElementById('appointment-date').textContent = formatAppointmentDate(firstAppointment.appointment_date);

            // Displaying the Doctor name
            document.getElementById('doctor_name').textContent = `Dr. ${doctorName}`;

            // Displaying the remarks
            document.getElementById('remarks').textContent = firstAppointment.remarks;



        });
    </script>
    <!-- Footer -->
    <footer class=" footer mt-auto py-3" style='bottom: 0;'>
        <div class="container text-center">
            <span class="text-muted"> ©2023 Batanes General Hospital </span>
        </div>
    </footer>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
</body>