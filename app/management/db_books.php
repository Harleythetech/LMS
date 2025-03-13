<?php
include "./processing.php";
include "../overview.php";
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
    <title>LMS | DASHBOARD - Books Record</title>
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


    <div class="container-fluid px-5" id="Overview">
        <div class="p-5"></div> <!-- Spacer -->

        <div class="row row-cols-1 mt-3">
            <div class="col" data-aos="fade-up">
                <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between">
                    <h5 class="mb-0">Books Database</h5>
                    <p class="mb-0">Last Updated: <?php
                    date_default_timezone_set("Asia/Manila");
                    echo date("h:i:sa", ) ?></p>
                </div>


                <div class="table-responsive shadow mb-4">
                    <table class="table table-striped table-hover mt-2 shadow overflow-scroll">
                        <thead>
                            <tr>
                                <th>BookID</th>
                                <th>ISBN</th>
                                <th>Title</th>
                                <th>Year</th>
                                <th>Author</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $BR = db_BA($pdo);
                            if (count($BR) > 0) {
                                foreach ($BR as $row) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['BookID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
                                    echo "<td class='title'>" . htmlspecialchars($row['Title']) . "</td>";
                                    echo "<td class='year'>" . htmlspecialchars($row['PublishedYear']) . "</td>";
                                    echo "<td class='author'>" . htmlspecialchars($row['Author']) . "</td>";
                                    echo "<td>
                        <div class='d-flex gap-2'>
<button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editBookModal' 
    data-bookid='" . htmlspecialchars($row['BookID'], ENT_QUOTES) . "'
    data-isbn='" . htmlspecialchars($row['ISBN'], ENT_QUOTES) . "'
    data-title='" . htmlspecialchars($row['Title'], ENT_QUOTES) . "'
    data-year='" . htmlspecialchars($row['PublishedYear'], ENT_QUOTES) . "'
    data-author='" . htmlspecialchars($row['Author'], ENT_QUOTES) . "'>
    <i class='bi bi-pencil'></i>
</button>


                            <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteBookModal' 
                                data-bookid='" . htmlspecialchars($row['BookID'], ENT_QUOTES) . "'>
                                <i class='bi bi-trash'></i>
                            </button>
                        </div>
                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid fixed-bottom d-flex justify-content-end pb-5 pe-5">
        <button class="fs-1 bi bi-patch-plus-fill text-success btn p-2" style="width: 60px; height: 60px;"
            data-bs-toggle="modal" data-bs-target="#createModal">
        </button>
    </div>
    <!-- Create Book Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add New Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="processing.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="createISBN" class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" id="createISBN" required>
                        </div>

                        <div class="mb-3">
                            <label for="createTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="createTitle" required>
                        </div>

                        <div class="mb-3">
                            <label for="createYear" class="form-label">Published Year</label>
                            <input type="number" class="form-control" name="year" id="createYear" required>
                        </div>

                        <div class="mb-3">
                            <label for="createAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" id="createAuthor" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="create_book">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="processing.php" method="POST">
                        <input type="hidden" name="book_id" id="editBookID">

                        <div class="mb-3">
                            <label for="editISBN" class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" id="editISBN" required>
                        </div>

                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="editTitle" required>
                        </div>

                        <div class="mb-3">
                            <label for="editYear" class="form-label">Published Year</label>
                            <input type="number" class="form-control" name="year" id="editYear" required>
                        </div>

                        <div class="mb-3">
                            <label for="editAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" id="editAuthor" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" name="edit_book">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="processing.php">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this book?</p>
                        <input type="hidden" name="book_id" id="deleteBookID">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" name="delete_book">Delete</button>
                    </div>
                </form>
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