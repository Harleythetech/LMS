<?php include "./app/overview.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./src/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Library Management System</title>
</head>

<body>
    <nav
        class="navbar navbar-expand-sm navbar-toggleable-sm box-shadow d-flex justify-content-between text-white px-3 align-items-center py-3 px-4 mx-0 mx-sm-5 mt-0 mt-sm-3 rounded-pill bg-body-tertiary shadow fixed-top">
        <div class="d-flex flex-wrap align-items-center">
            <div class="ms-1 d-flex flex-wrap align-items-center">
                <img src="./src/Images/logo.png" width="30">
            </div>
            <div class="collapse navbar-collapse ms-3" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#Home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Manage</a>
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

    <!-- Main Content -->
    <div class="container-fluid vh-100 d-flex flex-wrap align-content-around" id="Home">
        <div class="p-5"></div>
        <div class="row row-cols-2 px-5">
            <div class="col d-flex flex-wrap align-items-center">
                <div>
                    <h1 class="mb-3">Welcome to the Library Management System</h1>
                    <p class="mt-0">Effortlessly organize, track, and manage your library with ease. Keep records of
                        books, monitor check-ins and check-outs, and provide a seamless experience for both librarians
                        and readers—all in one convenient system.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center">
                    <button class="btn btn-success">Manage Data</button>
                    <p class="mb-0 ms-2">|</p>
                    <a href="#Overview" class="btn btn-outline-dark ms-2">View Overview</a>
                </div>
            </div>
            <div class="col d-flex flex-wrap justify-content-center">
                <img src="./src/images/logo.png" width="250">
            </div>
        </div>
        <div class="d-flex flex-wrap text-center justify-content-center w-100 align-items-center">
            <h3 class="bi bi-arrow-90deg-down mb-0"></h3>
            <h5 class="mb-2">Keep Exploring</h5>
        </div>
    </div>

    <!-- Overview -->
    <div class="container-fluid vh-100 px-5" id="Overview">
        <div class="p-5"></div> <!-- Spacer -->

        <div class="row row-cols-1 mt-3">
            <div class="col">
                <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between">
                    <h5 class="mb-0">Overview: Borrowed Books</h5>
                    <p class="mb-0"> <?php echo $success ?></p>
                </div>
                <table class="table table-striped table-hover mt-2 shadow">
                    <thead>
                        <tr>
                            <th>MemberID</th>
                            <th>LastName</th>
                            <th>Title</th>
                            <th>ISBN</th>
                            <th>BorrowDate</th>
                            <th>Due</th>
                            <th>Date Returned</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // Check if there are any borrowed books
                        $BorrowedBooks = data_BB($pdo);
                        if (count($BorrowedBooks) > 0) {
                            foreach ($BorrowedBooks as $row) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['MemberID']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['BorrowDate']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['DueDate']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['ReturnDate']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Books with Authors -->
            <div class="col">
                <div class="row row-cols-2">
                    <div class="col">
                        <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between">
                            <h5 class="mb-0">Book Records</h5>
                        </div>

                        <table class="table table-striped table-hover mt-2 shadow overflow-scroll">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>ISBN</th>
                                    <th>Year</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $BR = db_BA($pdo);
                                if (count($BR) > 0) {
                                    foreach ($BR as $row) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Title'] . "</td>";
                                        echo "<td>" . $row['ISBN'] . "</td>";
                                        echo "<td>" . $row['PublishedYear'] . "</td>";
                                        echo "<td>" . $row['Author'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col">
                        <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between">
                            <h5 class="mb-0">Quick Search</h5>
                            <form method="GET" class="input-group ms-5">
                                <input type="text" class="form-control" name="search" placeholder="Search by Last Name"
                                    aria-label="Search by Last Name" required>
                                <button class="btn btn-success" type="submit">Search</button>
                            </form>
                        </div>

                        <table class="table table-striped table-hover mt-2 shadow">
                            <thead>
                                <tr>
                                    <th>MemberID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Book Title</th>
                                </tr>
                            </thead>
                            <tbody id="search-results">
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>

      <!--Footer-->
      <footer class="footer bg-success-subtle">
        <hr class="hrline">
        <div class="container-fluid p-5">
          <div class="row row-cols-1 row-cols-sm-1 row-cols-md-4">
          <!--First Order, Brand name & Addr-->
          <div class="footer-addr col order-1 text-center text-md-start">
            <img src="./src/images/logo.png" alt="PLSP Game Enthusiat" class="img-fluid rounded-5 rounded-circle mb-2" width="80"height="80">
            <h4>Library Management System</h4>
          </div>
          <!--Second Order, Quick Links-->
          <div class="col order-2 text-center text-md-start">
            <h5>Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="#Home" class="nav-link">Home</a></li>
              <li><a href="#Manage" class="nav-link">Manage</a></li>
              <li><a href="#Overview" class="nav-link">Overview</a></li>
              <li><a href="#about" class="nav-link">About</a></li>
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
          <a href="https://github.com/Harleythetech/LMS" class="link-underline link-underline-opacity-0 text-secondary">Website Repository <i class="bi bi-box-arrow-up-right"></i></a>
        </div>
        <p class="order-2 fs-6">&copy; 2024 - 2025 LMS</p>
      </div>
      </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="./src/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>