<?php

require_once "database.php";

$sql = "SELECT b.title, a.name AS author, b.category FROM book b JOIN publish p ON b.bid = p.bid JOIN author a ON p.aid = a.aid";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Library</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to CSS file -->
</head>
<body>
    <div class="container">
    <a href="index.php" class="btn">Home</a>
        <h1>Welcome to the Book Library</h1>
        <h2>Currently there are <?php echo $result->num_rows; ?> books in the library:</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author/s</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["title"]). "</td><td>" . htmlspecialchars($row["author"]). "</td><td>" . htmlspecialchars($row["category"]). "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No books found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="buttons">
            <a href="add.php" class="btn add-btn">Add Record</a>
            <a href="delete.php" class="btn delete-btn">Delete Record</a>
        </div>
        <footer>
            <p>Contact us: info@test.com</p>
        </footer>
    </div>
</body>
</html>

<?php
$conn->close();
?>
