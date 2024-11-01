<?php

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];


    $sql = "SELECT b.bid, a.aid FROM book b
            JOIN publish p ON b.bid = p.bid
            JOIN author a ON p.aid = a.aid
            WHERE b.title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bid = $row['bid'];

        // Delete the book and its publish records
        $conn->query("DELETE FROM publish WHERE bid = $bid");
        $conn->query("DELETE FROM book WHERE bid = $bid");

        // Check if authors are associated with any other books, and delete if not
        $result->data_seek(0); 
        while ($row = $result->fetch_assoc()) {
            $aid = $row['aid'];
            $check = $conn->query("SELECT 1 FROM publish WHERE aid = $aid");
            if ($check->num_rows === 0) {
                $conn->query("DELETE FROM author WHERE aid = $aid");
            }
        }

        if ($sql) {
            header('Location: deleted.php');
        }
        } else {
            echo "<a href='index.php' class='btn'>Home</a>" . "<br>" .  "<p class='error-message'>Book not found.</p>";
        }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Book</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn">Home</a>
        <h1>Delete a Book from the Library</h1>
        <form method="post" action="">
            <label>Book title <span style="color: red;">*</span></label>
            <input type="text" name="title" required>
            <button type="submit" class="delete-rec">Delete Record</button>
        </form>
        <footer>
            <p>Contact us: info@test.com</p>
        </footer>
    </div>
</body>
</html>
