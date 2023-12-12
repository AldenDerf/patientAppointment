<!DOCTYPE html>
<html>

<head>
    <title>Insert Patient Login Credentials</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include './add_patient_cred.php';?>
    <div class="container mt-5">
        <h2 class='text-center'>Insert Patient Login Credentials</h2>
        <form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> ">
            <div class="form-group">
                <label for="patient_id">Patient ID:</label>
                <input type="text" class="form-control" id="patient_id" name="patient_id" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>