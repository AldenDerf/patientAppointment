 <?php
    define('DB_SERVER', "localhost");
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'bgh_appointments');
    define('DB_NAME_2', 'bgh_database');

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn === false) {
        die("ERROR: Could not connect to the server." . mysqli_connect_error());
    }

    // Second database connection
    $conn2 = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME_2);

    // Check connection for the second database
    if ($conn2 === false) {
        die("ERROR: Could not connect to the server for the second database." . mysqli_connect_error());
    }
?>