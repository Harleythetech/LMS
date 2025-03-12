<?php
include "./processing.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/styles.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>LMS | LOGIN</title>
</head>

<body class="imgcont">

    <div class="container-fluid vh-100 d-flex flex-wrap align-content-around justify-content-center" data-aos="fade-in">
        <div class="row row-cols-1 bg-success-subtle rounded-5 shadow p-5 m-5">
            <div class="col mb-3">
                <img src="../../src/Images/logo.png" height="50" alt="logo">
            </div>
            <div class="col mb-3">
                <h1 class="text-start text-success">Sign In</h1>
                <p>Use your Administrator account to sign in and manage.</p>
            </div>
            <div class="col">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php displayError(); // Display any error messages ?>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="exampleInputEmail1" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="admin.test@university.edu.ph" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="exampleInputPassword1" class="form-control"
                            id="exampleInputPassword1" placeholder="********" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid fixed-bottom d-flex justify-content-end pb-5 pe-5">
        <a href="#" class="fs-3 btn bi bi-question-circle-fill text-success"></a>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="../../src/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>