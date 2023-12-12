<?php include './patient-login.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom CSS file (if used) -->
    <link href="./css/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <!-- Main content section -->
    <main class="form-signin w-100 m-auto">

        <form method='POST' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <!-- Title -->
            <h1 class="h3 mb-3 fw-normal text-center">Patient Login</h1>

            <!-- Patient Login -->
            <div class="form-floating">
                <input type="text" name='patient-id' class="form-control " id="floatingInput" placeholder="XXXXX">
                <label for="floatingInput">Patient ID</label>
            </div>

            <!-- Patient Password -->
            <div class="form-floating mb-5">
                <input type="password" name='patient-password' class="form-control " id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>


            <!-- Submit button -->
            <button class="btn btn-primary w-100 py-2" type="submit">Login in</button>

            <!-- Copyright -->
            <p class="mt-5 mb-3 text-body-secondary text-center">&copy; BGH Admin <?php echo date('Y') ?></p>
        </form>
    </main>

    <!-- Bootstrap JS -->
    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>