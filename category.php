<?php
// Load the book data from the JSON file
$jsonData = file_get_contents('books.json');
$books = json_decode($jsonData, true);

// Get the category from the query string
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Filter books by category
$filteredBooks = array_filter($books, function ($book) use ($selectedCategory) {
  return $book['category'] === $selectedCategory;
});

$categories = array_unique(array_column($books, 'category'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books in <?= htmlspecialchars($selectedCategory); ?> Category</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="category-body">
    <a href="index.php">Back to Home</a>
    <h2>Select a Category</h2>
    <form action="category.php" method="get">
      <label for="category">Choose a category:</label>
      <select name="category" id="category">
        <?php foreach ($categories as $category): ?>
          <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
        <?php endforeach; ?>
        <!-- Add more categories as needed -->
      </select>
      <button type="submit">Filter Books</button>
    </form>
    <h1>Books in <?= htmlspecialchars($selectedCategory); ?> Category</h1>
    <div class="book-list">
      <?php if (count($filteredBooks) > 0): ?>
        <?php foreach ($filteredBooks as $book): ?>
          <div class='book'>
            <h3><?= $book['title']; ?></h3>
            <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?> Cover" />
            <p><strong>Description:</strong> <?= $book['description']; ?></p>
            <p><strong>Price:</strong> $<?= number_format($book['price'], 2); ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No books found in this category.</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>