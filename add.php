<?php

require_once "database.php";


function sanitizeData($data) {
    $data = trim($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = sanitizeData($_POST['title']);
    $pages = sanitizeData($_POST['pages']);
    $category = sanitizeData($_POST['category']);
    $publisher = sanitizeData($_POST['publisher']);
    $year = sanitizeData($_POST['year']);
    $author1 = sanitizeData($_POST['author1']);
    $email1 = sanitizeData($_POST['email1']);
    $author2 = !empty($_POST['author2']) ? sanitizeData($_POST['author2']) : null;
    $email2 = !empty($_POST['email2']) ? sanitizeData($_POST['email2']) : null;

    

    // Insert into books table
    $sql_book = "INSERT INTO book (title, pages, category, year) VALUES (?, ?, ?, ?)";
    $stmt_book = $conn->prepare($sql_book);
    $stmt_book->bind_param("siss", $title, $pages, $category, $year);
    $stmt_book->execute();

    // Get the last inserted book ID
    $bid = $stmt_book->insert_id;

    // Insert into authors table
    $sql_author1 = "INSERT INTO author (name, email) VALUES (?, ?)";
    $stmt_author1 = $conn->prepare($sql_author1);
    $stmt_author1->bind_param("ss", $author1, $email1);
    $stmt_author1->execute();

    $aid1 = $stmt_author1->insert_id;

    // Insert into publish table
    $sql_publish1 = "INSERT INTO publish (bid, aid) VALUES (?, ?)";
    $stmt_publish1 = $conn->prepare($sql_publish1);
    $stmt_publish1->bind_param("ii", $bid, $aid1);
    $stmt_publish1->execute();

    // Optional second author
    if (!empty($author2) && !empty($email2)) {
        $sql_author2 = "INSERT INTO author (name, email) VALUES (?, ?)";
        $stmt_author2 = $conn->prepare($sql_author2);
        $stmt_author2->bind_param("ss", $author2, $email2);
        $stmt_author2->execute();

        $aid2 = $stmt_author2->insert_id;

        // Insert into publish table for the second author
        $sql_publish2 = "INSERT INTO publish (bid, aid) VALUES (?, ?)";
        $stmt_publish2 = $conn->prepare($sql_publish2);
        $stmt_publish2->bind_param("ii", $bid, $aid2);
        $stmt_publish2->execute();
    }

    if ($sql_book && $sql_author1) {
        header('Location: recorded.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>
    <div class="container">
    <a href="index.php" class="btn">Home</a>
        <h1>Add New Book to the Library</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="title">Book title <span style="color: red;">*</span></label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label for="pages">Pages <span style="color: red;">*</span></label>
                <input type="number" name="pages" required>
            </div>

            <div class="form-group">
                <label for="category">Category <span style="color: red;">*</span></label>
                <input type="text" name="category" required>
            </div>

            <div class="form-group">
                <label for="publisher">Publisher <span style="color: red;">*</span></label>
                <input type="text" name="publisher" required>
            </div>

            <div class="form-group">
                <label for="year">Year <span style="color: red;">*</span></label>
                <input type="date" name="year" required>
            </div>

            <h4>Author 1 <span style="color: red;">*</span></h4>
            <div class="form-group">
                <label for="author1">Name <span style="color: red;">*</span></label>
                <input type="text" name="author1" required>
            </div>

            <div class="form-group">
                <label for="email1">Email <span style="color: red;">*</span></label>
                <input type="email" name="email1" required>
            </div>

            <h4>Author 2 (Optional)</h4>
            <div class="form-group">
                <label for="author2">Name</label>
                <input type="text" name="author2">
            </div>

            <div class="form-group">
                <label for="email2">Email</label>
                <input type="email" name="email2">
            </div>

            <button type="submit">Add Record</button>
        </form>
        <footer>
            <p>Contact us: info@test.com</p>
        </footer>
    </div>
</body>
</html>

<?php
$conn->close();
?>
