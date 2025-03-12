<?php
include  "./processing.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../src/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>LMS | DASHBOARD</title>
</head>
<body class="imgcont">
<nav
        class="navbar navbar-expand-sm navbar-toggleable-sm box-shadow d-flex justify-content-between text-white px-3 align-items-center py-3 px-4 mx-0 mx-sm-5 mt-0 mt-sm-3 rounded-pill bg-body-tertiary shadow fixed-top">
        <div class="d-flex flex-wrap align-items-center">
            <div class="ms-1 d-flex flex-wrap align-items-center">
                <img src="../../src/Images/logo.png" width="30">
            </div>
            <div class="collapse navbar-collapse ms-3" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./db_home.php">Manage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
            </div>

        </div>
        <div class="d-none d-sm-flex flex-wrap align-items-center">
            <p class="text-black-50 mb-0" id="time">00:00</p>
        </div>
    </nav>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="../../src/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>