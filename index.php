<?php
session_start();
// Set variables for dynamic content
$storeName = "John's Book Emporium";
$categories = ["Fiction", "Non-Fiction", "Children's Books", "Science Fiction", "Cars book", "Financial book"];
// Load the book data from the JSON file
$jsonData = file_get_contents('books.json');

// Decode the JSON data into a PHP array
$books = json_decode($jsonData, true);


$featuredCategoryIndex = array_rand($categories, 1);
$featuredCategory = $categories[$featuredCategoryIndex];
$currentTime = date("H");

// Determine greeting based on time of day
if ($currentTime < 12) {
  $greeting = "Good morning";
} elseif ($currentTime < 18) {
  $greeting = "Good afternoon";
} else {
  $greeting = "Good evening";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to <?php echo $storeName; ?></title>

  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="index-body">
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>

    <h2>Select a Category</h2>
    <form action="category.php" method="get">
      <label for="category">Choose a category:</label>
      <select name="category" id="category">
        <option value="Fiction">Fiction</option>
        <option value="Science Fiction">Science Fiction</option>
        <option value="Non-Fiction">Non-Fiction</option>
        <option value="Cars Book">Cars book</option>
        <option value="Financial Book">Financial book</option>
        <option value="Children's Book">Children's Book</option>
        <!-- Add more categories as needed -->
      </select>
      <button type="submit">Filter Books</button>
    </form>
    <h1>
      <?php
      echo $greeting . ", welcome to " . $storeName;
      ?>!
    </h1>
    <p>Here are some of our book categories:</p>
    <ul class="category-list">
      <?php
      // Loop through categories array
      // and display each one
      foreach ($categories as $category) {
        echo "<a href='category.php?category=" . urlencode($category) . "'><li>" . $category . "</li></a>";
      }
      ?>

    </ul>

    <h2>Our Featured Books</h2>
    <div class="book-list">
      <?php foreach ($books as $book): ?>
        <div class='book'>
          <h3><?= $book['title']; ?></h3>
          <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?> Cover" />
          <p><strong>Description:</strong> <?= $book['description']; ?></p>
          <?php if (isset($book['discount_price'])): ?>
            <p><strong>Price:</strong> <span style='text-decoration: line-through;'>$<?= number_format($book['price'], 2); ?></span></p>
            <p>

              <strong>Discounted Price:</strong> $<?= number_format($book['discount_price'], 2); ?>
            </p>
            </p>
          <?php else: ?>
            <p><strong>Price:</strong> $<?= number_format($book['price'], 2); ?></p>
          <?php endif; ?>
          <p><strong>Category:</strong>
            <a href="category.php?category=<?= urlencode($book['category']); ?>">
              <?= $book['category']; ?>
            </a>
          </p>

          <!-- Show edit link if logged in -->
          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <p><a href="edit_page.php?title=<?= urlencode($book['title']); ?>">Edit</a></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</body>

</html>