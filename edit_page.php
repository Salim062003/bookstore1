<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$jsonData = file_get_contents('books.json');
$books = json_decode($jsonData, true);

// Get the book title from the query string
$titleToEdit = $_GET['title'] ?? '';
$bookToEdit = null;

// Find the book to edit
foreach ($books as &$book) {
    if ($book['title'] === $titleToEdit) {
        $bookToEdit = &$book;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookToEdit['title'] = $_POST['title'];
    $bookToEdit['description'] = $_POST['description'];
    $bookToEdit['category'] = $_POST['category'];
    $bookToEdit['price'] = (float)$_POST['price'];

    // Check if a new cover image is uploaded
    if (!empty($_FILES['cover']['tmp_name'])) {
        $coversFolder = 'covers/';
        $coverPath = $coversFolder . basename($_FILES['cover']['name']);

        // Ensure the covers folder exists
        if (!file_exists($coversFolder)) {
            mkdir($coversFolder, 0777, true);
        }

        $uploaded = move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath);
        if (!$uploaded) {
            echo "Failed to upload cover image.";
            exit;
        }
        $bookToEdit['cover'] = $coverPath;
    }

    // Save the updated book data
    file_put_contents('books.json', json_encode($books, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Book - <?= htmlspecialchars($bookToEdit['title']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="edit-body">
        <a href="index.php">Back to Home</a>
        <h1>Edit Book - <?= htmlspecialchars($bookToEdit['title']); ?></h1>
        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div class="book-cover" style="background-image: url('<?= htmlspecialchars($bookToEdit['cover']); ?>')"></div>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($bookToEdit['title']); ?>" required>
            <br>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($bookToEdit['description']); ?></textarea>
            <br>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?= htmlspecialchars($bookToEdit['category']); ?>" required>
            <br>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($bookToEdit['price']); ?>" required>
            <br>

            <label for="cover">Cover Image:</label>
            <input type="file" name="cover" id="cover">
            <br>

            <button type="submit">Save Changes</button>
        </form>
</body>

</html>