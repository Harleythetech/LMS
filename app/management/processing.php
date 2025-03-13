<?php
// Start session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include "../database.php";

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Get form data and sanitize inputs
    $email = filter_var($_POST["exampleInputEmail1"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["exampleInputPassword1"];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: login.php");
        exit();
    }
    
    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT id, email, password, name, role FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        // Check if user exists
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            
            // Verify password (assuming passwords are hashed with password_hash())
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;
                
                // Log successful login
                $logQuery = "INSERT INTO login_logs (user_id, login_time, ip_address) VALUES (:user_id, NOW(), :ip)";
                $logStmt = $pdo->prepare($logQuery);
                $ip = $_SERVER['REMOTE_ADDR'];
                $logStmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                $logStmt->bindParam(':ip', $ip, PDO::PARAM_STR);
                $logStmt->execute();
                
                // Redirect to dashboard
                header("Location: db_home.php");
                exit();
            } else {
                // Invalid password
                $_SESSION['error'] = "Invalid email or password";
                header("Location: db_home.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['error'] = "Invalid email or password";
            header("Location: db_home.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: db_home.php");
        exit();
    }
}

// Function to display error messages
function displayError() {
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
}

// COUNTING QUERY
function countBooksAvailable($pdo) {
    $query = "SELECT COUNT(*) as total 
              FROM Books B
              WHERE B.BookID NOT IN (
                  SELECT BR.BookID 
                  FROM BorrowingRecords BR 
                  WHERE BR.ReturnDate IS NULL
              )";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// Count missing books
function countBooksMissing($pdo) {
    $query = "SELECT COUNT(*) as total 
              FROM Books B
              JOIN BorrowingRecords BR ON B.BookID = BR.BookID
              WHERE BR.ReturnDate IS NULL
              AND BR.DueDate < CURDATE()";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function countMembers($pdo) {
    $query = "SELECT COUNT(*) FROM members"; 
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn(); // Fetch the count directly
}

function COUNTbb($pdo) {
    $query = "SELECT COUNT(*) 
              FROM BorrowingRecords BR
              JOIN Books B ON BR.BookID = B.BookID
              JOIN Members M ON BR.MemberID = M.MemberID";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn(); // Fetch the count directly
}

// CREATE FUNCTION - Add a Borrow Record
function createBorrowRecord($pdo, $memberID, $bookID, $borrowDate, $dueDate) {
    try {
        // Ensure the book is not already borrowed
        $checkQuery = "SELECT COUNT(*) FROM BorrowingRecords WHERE BookID = :bookID AND ReturnDate IS NULL";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':bookID', $bookID, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->fetchColumn() > 0) {
            return "Error: This book is already borrowed.";
        }

        // Insert borrowing record
        $query = "INSERT INTO BorrowingRecords (MemberID, BookID, BorrowDate, DueDate) 
                  VALUES (:memberID, :bookID, :borrowDate, :dueDate)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':memberID', $memberID, PDO::PARAM_INT);
        $stmt->bindParam(':bookID', $bookID, PDO::PARAM_INT);
        $stmt->bindParam(':borrowDate', $borrowDate, PDO::PARAM_STR);
        $stmt->bindParam(':dueDate', $dueDate, PDO::PARAM_STR);
        $stmt->execute();

        return "Borrow record added successfully!";
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["memberID"])) {
    header("Content-Type: application/json"); // Set response as JSON

    try {
        $memberID = $_POST["memberID"];
        $isbn = $_POST["ISBN"];
        $borrowDate = $_POST["BorrowDate"];
        $dueDate = $_POST["DueDate"];

        if (empty($memberID) || empty($isbn) || empty($borrowDate) || empty($dueDate)) {
            echo json_encode(["success" => false, "message" => "All fields are required."]);
            exit();
        }

        // Convert ISBN to BookID
        $stmt = $pdo->prepare("SELECT BookID FROM Books WHERE ISBN = :isbn");
        $stmt->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$book) {
            echo json_encode(["success" => false, "message" => "Book with this ISBN not found."]);
            exit();
        }

        $bookID = $book['BookID'];

        // Ensure the book is not already borrowed
        $checkQuery = "SELECT COUNT(*) FROM BorrowingRecords WHERE BookID = :bookID AND ReturnDate IS NULL";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->bindParam(':bookID', $bookID, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            echo json_encode(["success" => false, "message" => "This book is already borrowed."]);
            exit();
        }

        // Insert into BorrowingRecords
        $query = "INSERT INTO BorrowingRecords (MemberID, BookID, BorrowDate, DueDate) 
                  VALUES (:memberID, :bookID, :borrowDate, :dueDate)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':memberID', $memberID, PDO::PARAM_INT);
        $stmt->bindParam(':bookID', $bookID, PDO::PARAM_INT);
        $stmt->bindParam(':borrowDate', $borrowDate, PDO::PARAM_STR);
        $stmt->bindParam(':dueDate', $dueDate, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Borrow record added successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add borrow record."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }

    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_borrow'])) {
        $memberID = $_POST['MemberID'];
        $borrowDate = $_POST['BorrowDate'];
        $dueDate = $_POST['DueDate'];

        $stmt = $pdo->prepare("UPDATE borrowingrecords SET BorrowDate = ?, DueDate = ? WHERE MemberID = ?");
        if ($stmt->execute([$borrowDate, $dueDate, $memberID])) {
            header("Location: db_Records_BorrowBooks.php?success=edit");
        } else {
            header("Location: db_Records_BorrowBooks.php?error=edit");
        }
        exit();
    }

    if (isset($_POST['delete_borrow'])) {
        $memberID = $_POST['MemberID'];

        $stmt = $pdo->prepare("DELETE FROM borrowingrecords WHERE MemberID = ? ");
        if ($stmt->execute([$memberID])) {
            header("Location: db_Records_BorrowBooks.php?success=delete");
        } else {
            header("Location: db_Records_BorrowBooks.php?error=delete");
        }
        exit();
    }
}

if (isset($_POST['edit_member'])){
    $memberID = $_POST['member_id'];
    $lastname = $_POST['last_name'];
    $firstname = $_POST['first_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $join_date = $_POST['join_date'];

    $stmt = $pdo->prepare("UPDATE members SET LastName = ?, FirstName = ?, Email = ?, PhoneNumber = ?, JoinDate = ? WHERE MemberID = ?");
    if ($stmt->execute([$lastname, $firstname, $email, $contact, $join_date, $memberID])) {
        header("Location: db_users.php?success=edit");
    } else {
        header("Location: db_users.php?error=edit");
    }
}

if (isset($_POST["delete_member"])) {
    $memberID = $_POST['member_id'];

    $stmt = $pdo->prepare("DELETE FROM members WHERE MemberID = ?");
    if ($stmt->execute([$memberID])) {
        header("Location: db_users.php?success=delete");
    } else {
        header("Location: db_users.php?error=delete");
    }
}

if (isset($_POST["create_member"])) {
    $memberID = $_POST['member_id'];
    $lastname = $_POST['last_name'];
    $firstname = $_POST['first_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $join_date = $_POST['join_date'];

    $stmt = $pdo->prepare("INSERT INTO members (MemberID, LastName, FirstName, Email, PhoneNumber, JoinDate) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$memberID, $lastname, $firstname, $email, $contact, $join_date])) {
        header("Location: db_users.php?success=create");
    } else {
        header("Location: db_users.php?error=create");
    }
}


if (isset($_POST["delete_book"])) {
    $bookID = $_POST['book_id'];
    
    if (!empty($bookID)) {
        try {
            // Begin transaction to ensure atomicity
            $pdo->beginTransaction();

            // Get the AuthorID(s) linked to the book
            $stmt1 = $pdo->prepare("SELECT AuthorID FROM bookauthors WHERE BookID = ?");
            $stmt1->execute([$bookID]);
            $authorIDs = $stmt1->fetchAll(PDO::FETCH_COLUMN);

            // Delete relations from BookAuthors table
            $stmt2 = $pdo->prepare("DELETE FROM bookauthors WHERE BookID = ?");
            $stmt2->execute([$bookID]);

            // Delete the book itself
            $stmt3 = $pdo->prepare("DELETE FROM books WHERE BookID = ?");
            $stmt3->execute([$bookID]);

            // Now check if any authors are left without books, and delete them
            foreach ($authorIDs as $authorID) {
                $stmt4 = $pdo->prepare("SELECT COUNT(*) FROM bookauthors WHERE AuthorID = ?");
                $stmt4->execute([$authorID]);
                $count = $stmt4->fetchColumn();

                if ($count == 0) { // No more books linked to this author
                    $stmt5 = $pdo->prepare("DELETE FROM authors WHERE AuthorID = ?");
                    $stmt5->execute([$authorID]);
                }
            }

            // Commit transaction
            $pdo->commit();
            header("Location: db_books.php?success=delete");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            header("Location: db_books.php?error=exception");
            exit();
        }
    } else {
        header("Location: db_books.php?error=missing_id");
        exit();
    }
}

if (isset($_POST["edit_book"])) {

    // Retrieve the form data
    $bookID = $_POST["book_id"] ?? null;
    $isbn = $_POST["isbn"] ?? null;
    $title = $_POST["title"] ?? null;
    $year = $_POST["year"] ?? null;
    $author = $_POST["author"] ?? null; // Full name (FirstName LastName)

    if (!$bookID || !$isbn || !$title || !$year || !$author) {
        header("Location: db_books.php?error=missing_fields");
        exit();
    }

    try {
        $pdo->beginTransaction(); // Start transaction for atomic updates

        // Update the Books table
        $stmt = $pdo->prepare("UPDATE books SET ISBN = ?, Title = ?, PublishedYear = ? WHERE BookID = ?");
        $stmt->execute([$isbn, $title, $year, $bookID]);

        // Split full name into first and last name
        $authorParts = explode(" ", $author);
        $firstName = $authorParts[0] ?? "";
        $lastName = isset($authorParts[1]) ? $authorParts[1] : "";

        // Check if author exists
        $stmt = $pdo->prepare("SELECT AuthorID FROM bookauthors WHERE BookID = ?");
        $stmt->execute([$bookID]);
        $authorID = $stmt->fetchColumn();

        if ($authorID) {
            // Update the author if already linked
            $stmt = $pdo->prepare("UPDATE authors SET FirstName = ?, LastName = ? WHERE AuthorID = ?");
            $stmt->execute([$firstName, $lastName, $authorID]);
        } else {
            // Insert new author if none exists
            $stmt = $pdo->prepare("INSERT INTO authors (FirstName, LastName) VALUES (?, ?)");
            $stmt->execute([$firstName, $lastName]);
            $newAuthorID = $pdo->lastInsertId();

            // Link the new author to the book
            $stmt = $pdo->prepare("INSERT INTO bookauthors (BookID, AuthorID) VALUES (?, ?)");
            $stmt->execute([$bookID, $newAuthorID]);
        }

        $pdo->commit(); // Commit transaction

        header("Location: db_books.php?success=edit");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback if any error occurs
        error_log("Edit Error: " . $e->getMessage());
        header("Location: db_books.php?error=edit_failed");
        exit();
    }
}

if (isset($_POST["create_book"])) {

    // Retrieve form data
    $isbn = $_POST["isbn"] ?? null;
    $title = $_POST["title"] ?? null;
    $year = $_POST["year"] ?? null;
    $author = $_POST["author"] ?? null; // Full name

    if (!$isbn || !$title || !$year || !$author) {
        header("Location: db_books.php?error=missing_fields");
        exit();
    }

    try {
        $pdo->beginTransaction(); // Start transaction

        // Insert into Books table
        $stmt = $pdo->prepare("INSERT INTO books (ISBN, Title, PublishedYear) VALUES (?, ?, ?)");
        $stmt->execute([$isbn, $title, $year]);
        $bookID = $pdo->lastInsertId();

        // Split full name into first and last name
        $authorParts = explode(" ", $author);
        $firstName = $authorParts[0] ?? "";
        $lastName = isset($authorParts[1]) ? $authorParts[1] : "";

        // Check if author already exists
        $stmt = $pdo->prepare("SELECT AuthorID FROM authors WHERE FirstName = ? AND LastName = ?");
        $stmt->execute([$firstName, $lastName]);
        $existingAuthor = $stmt->fetch();

        if ($existingAuthor) {
            $authorID = $existingAuthor["AuthorID"];
        } else {
            // Insert new author
            $stmt = $pdo->prepare("INSERT INTO authors (FirstName, LastName) VALUES (?, ?)");
            $stmt->execute([$firstName, $lastName]);
            $authorID = $pdo->lastInsertId();
        }

        // Link book and author in BookAuthors table
        $stmt = $pdo->prepare("INSERT INTO bookauthors (BookID, AuthorID) VALUES (?, ?)");
        $stmt->execute([$bookID, $authorID]);

        $pdo->commit(); // Commit transaction

        header("Location: db_books.php?success=create");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback on error
        error_log("Create Error: " . $e->getMessage());
        header("Location: db_books.php?error=create_failed");
        exit();
    }
}



?>
