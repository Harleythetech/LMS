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
    <title>LMS | DASHBOARD - Books Borrowed Record</title>
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

    <!--Books Record-->
    <div class="container-fluid px-5" id="Overview">
        <div class="p-5"></div> <!-- Spacer -->

        <div class="row row-cols-1 mt-3">
            <div class="col" data-aos="fade-up">
                <div class="d-flex rounded p-3 shadow bg-success-subtle justify-content-between">
                    <h5 class="mb-0">Borrowed Books</h5>
                    <p class="mb-0">Last Updated: <?php
                    date_default_timezone_set("Asia/Manila");
                    echo date("h:i:sa", ) ?></p>
                </div>

                <div class="table-responsive shadow mb-4">
                    <table class="table table-striped table-hover mt-2">
                        <thead>
                            <tr>
                                <th>MemberID</th>
                                <th>LastName</th>
                                <th>Title</th>
                                <th>ISBN</th>
                                <th>BorrowDate</th>
                                <th>Due</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $BorrowedBooks = data_BB($pdo);
                            if (count($BorrowedBooks) > 0) {
                                foreach ($BorrowedBooks as $row) {
                                    $modalID = htmlspecialchars($row['MemberID'] . $row['ISBN']); // Unique modal ID
                            
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['MemberID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['LastName']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['BorrowDate']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['DueDate']) . "</td>";
                                    echo "<td>";
                                    echo "<div class='d-flex gap-2'>";

                                    // Edit Button
                                    echo "<button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal' 
                        data-memberid='" . htmlspecialchars($row['MemberID']) . "' 
                        data-isbn='" . htmlspecialchars($row['ISBN']) . "' 
                        data-borrowdate='" . htmlspecialchars($row['BorrowDate']) . "' 
                        data-duedate='" . htmlspecialchars($row['DueDate']) . "'>";
                                    echo "<i class='bi bi-pencil'></i>";
                                    echo "</button>";

                                    // Delete Button
                                    echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                        data-memberid='" . htmlspecialchars($row['MemberID']) . "' 
                        data-isbn='" . htmlspecialchars($row['ISBN']) . "'>";
                                    echo "<i class='bi bi-trash'></i>";
                                    echo "</button>";

                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No records found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    

    <!-- Create Borrowing Record Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Borrow Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="createMessage"></div> <!-- To show success/error messages -->
                <form id="createForm">
                    <div class="mb-3">
                        <label for="memberID" class="form-label">Member ID</label>
                        <input type="number" class="form-control" id="memberID" name="memberID" required>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="ISBN" required>
                    </div>
                    <div class="mb-3">
                        <label for="borrowDate" class="form-label">Borrow Date</label>
                        <input type="date" class="form-control" id="borrowDate" name="BorrowDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="DueDate" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Floating Button -->
    <div class="container-fluid fixed-bottom d-flex justify-content-end pb-5 pe-5">
        <button class="fs-1 bi bi-patch-plus-fill text-success btn p-2" style="width: 60px; height: 60px;"
            data-bs-toggle="modal" data-bs-target="#createModal">
        </button>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Borrow Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="processing.php">
                    <div class="modal-body">
                        <input type="hidden" id="editMemberID" name="MemberID">
                        <input type="hidden" id="editISBN" name="ISBN">

                        <div class="mb-3">
                            <label class="form-label">Borrow Date</label>
                            <input type="date" class="form-control" id="editBorrowDate" name="BorrowDate" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="editDueDate" name="DueDate" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="edit_borrow">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="processing.php">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this record?</p>
                        <input type="hidden" id="deleteMemberID" name="MemberID">
                        <input type="hidden" id="deleteISBN" name="ISBN">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" name="delete_borrow">Yes, Delete</button>
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