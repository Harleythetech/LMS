<?php 
include __DIR__ . "/database.php"; // Ensure correct database connection
header("Content-Type: text/html; charset=UTF-8");
// Query Borrowed Books
function data_BB($pdo){
    try {
        $BB_Query = $pdo->prepare("SELECT M.MemberID, M.FirstName, M.LastName, 
                            B.BookID, B.Title, B.ISBN, 
                            BR.BorrowDate, BR.DueDate, BR.ReturnDate
                            FROM BorrowingRecords BR
                            JOIN Books B ON BR.BookID = B.BookID
                            JOIN Members M ON BR.MemberID = M.MemberID
                            ORDER BY B.BookID, BR.BorrowDate;");
        $BB_Query->execute();
        return $BB_Query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error for debugging
        return []; // Return an empty array instead of killing the script
    }
}

// Query = Books with Authors DB
function db_BA($pdo){
    try{
        $BA_Query = $pdo->prepare("SELECT B.Title, B.ISBN, B.PublishedYear, CONCAT(A.FirstName, ' ', A.LastName) AS Author
                                FROM Books B
                                JOIN BookAuthors BA ON B.BookID = BA.BookID
                                JOIN Authors A ON BA.AuthorID = A.AuthorID
                                ORDER BY B.Title;");
        $BA_Query->execute();
        return $BA_Query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage()); // Log error for debugging
        return []; // Return an empty array instead of killing the script
    }
}



try {
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        
        $search_query = $pdo->prepare('SELECT M.MemberID, M.LastName, M.FirstName, B.Title 
                FROM BorrowingRecords BR
                JOIN Members M ON BR.MemberID = M.MemberID
                JOIN Books B ON BR.BookID = B.BookID
                WHERE M.LastName LIKE :search
                ORDER BY M.LastName, M.FirstName, B.Title');
        
        $search_query->execute(['search' => "%$search%"]);
        $result = $search_query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            foreach ($result as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['MemberID']) . "</td>
                        <td>" . htmlspecialchars($row['LastName']) . "</td>
                        <td>" . htmlspecialchars($row['FirstName']) . "</td>
                        <td>" . htmlspecialchars($row['Title']) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No records found</td></tr>";
        }
    }
} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    echo "<tr><td colspan='4' class='text-center'>Error fetching data</td></tr>";
}


?>
