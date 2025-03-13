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

    <div class="container-fluid px-5 mb-5 vh-100" id="Home" data-aos="fade-in">
        <div class="p-5"></div>

        <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between mt-3">
                    <h5 class="mb-0">Welcome Admin!</h5>
                    <p class="mb-0">Last Updated: <?php
                    date_default_timezone_set("Asia/Manila");
                    echo date("h:i:sa", )?></p>
        </div>

        <div class="row row-cols-2 mt-3">
            <div class="col">
                <div class="p-4 rounded bg-success-subtle shadow d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="mb-0">Books Borrowed:</h3>
                    <h4 class="mb-0"><?php echo COUNTbb($pdo)?></h4>
                </div>
            </div>
            <div class="col">
                <div class="p-4 rounded bg-success-subtle shadow d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="mb-0">Books Available: </h3>
                    <h4 class="mb-0"><?php echo countBooksAvailable($pdo)  ?></h4>
                </div>
            </div>
            <div class="col mt-3">
                <div class="p-4 rounded bg-success-subtle shadow d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="mb-0">Users: </h3>
                    <h4 class="mb-0"><?php echo countMembers($pdo)?></h4>
                </div>
            </div>
            <div class="col mt-3">
                <div class="p-4 rounded bg-success-subtle shadow d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="mb-0">Missing Books: </h3>
                    <h4 class="mb-0"><?php echo countBooksMissing($pdo)  ?></h4>
                </div>
            </div>
        </div>

        <div class="row row-cols-3 mt-3">
            <div class="col">
                <a href="./db_books.php" class="btn btn-success p-3 w-100">Manage Books</a>
            </div>
            <div class="col">
                <a href="./db_users.php" class="btn btn-success p-3 w-100">Manage Users</a>
            </div>
            <div class="col">
                <a href="./db_Records_BorrowBooks.php" class="btn btn-success p-3 w-100">Manage Borrowed Books</a>
            </div>
        </div>
    </div>

        <!--Footer-->
        <footer class="footer bg-secondary-subtle">
        <div class="container-fluid p-5">
            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-4">
                <!--First Order, Brand name & Addr-->
                <div class="footer-addr col order-1 text-center text-md-start">
                    <img src="../../src/Images/logo.png" alt="logo" class="img-fluid rounded-5 rounded-circle mb-2"
                        width="80" height="80">
                    <h4>Library Management System</h4>
                </div>
                <!--Second Order, Quick Links-->
                <div class="col order-2 text-center text-md-start">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="../../index.php" class="nav-link">Home</a></li>
                        <li><a href="#" class="nav-link">Manage</a></li>
                        <li><a href="../../index.php" class="nav-link">Overview</a></li>
                        <li><a href="#" class="nav-link">About</a></li>
                    </ul>
                </div>
                <!--Third Order, Additional Info-->
                <div class="col order-3 text-center text-md-start">
                    <h5>Additional Info</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="nav-link">Privacy Policy</a></li>
                        <li><a href="#" class="nav-link">Terms of Service</a></li>
                    </ul>
                </div>

            </div>
        </div>
        <hr>
        <!--Final part of footer, copyright and links of tools used and the repo it self.-->
        <div class="text-center justify-content-between d-sm-flex d-block mx-5 link-col pb-2 pb-md-0">
            <div class="order-1">
                <a href="https://github.com" class="order-3 text-secondary"><i class="bi bi-github"></i></a>
                <a href="https://getbootstrap.com/"><i class="bi bi-bootstrap-fill text-secondary"></i></a>
                <a href="https://github.com/Harleythetech/LMS"
                    class="link-underline link-underline-opacity-0 text-secondary">Website Repository <i
                        class="bi bi-box-arrow-up-right"></i></a>
            </div>
            <p class="order-2 fs-6">&copy; 2024 - 2025 LMS</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="../../src/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>