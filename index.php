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


<?php
  // Set variables for dynamic content
  $storeName = "John's Book Emporium";
  $categories = ["Fiction", "Non-Fiction", "Children's Books", "Science Fiction","Cars book","Financial book"];
  // Book data

$books = [
  [
    "title" => "The Great Gatsby",
    "description" => "A story of the fabulously wealthy Jay Gatsby and his love for Daisy Buchanan.",
    "cover" => "https://libris.to/media/jacket/02989496_the-great-gatsby-der-grosse-gatsby-englische-ausgabe.jpg",
    "price" => 10.99

  ],
  [
    "title" => "1984",
    "description" => "A dystopian novel set in a totalitarian society ruled by Big Brother.",
    "cover" => "https://libris.to/media/jacket/35477898o.jpg",
    "price" => 8.99
  ],
  [
    "title" => "To Kill a Mockingbird",
    "description" => "A novel about the serious issues of rape and racial inequality.",
    "cover" => "https://libris.to/media/jacket/04016243_to-kill-a-mockingbird.jpg",
    "price" => 12.50
  ],
  [
    "title" => "The Catcher in the Rye",
    "description" => "The experiences of a young boy who leaves his boarding school to wander New York City.",
    "cover" => "https://libris.to/media/jacket/00069392_catcher-in-the-rye.jpg",
    "price" => 9.75
  ],
  [
    "title" => "BMW M",
    "description" => "BMW M-Series celebrates the 50th anniversary of these legendary performance cars , detailing both its production and motorsport stories with expert commentary and fascinating photography",
    "cover" => "https://libris.to/media/jacket/33583303o.jpg",
    "price" => 12.75
  ],
  [
    "title" => "Rich Dad Poor Dad",
    "description" => "Robert's story of growing up with two dads — his real father and the father of his best friend, his rich dad — and the ways in which both men shaped his thoughts about money and investing.",
    "cover" => "https://libris.to/media/jacket/37301707o.jpg",
    "price" => 7.1
  ],



];


// Load the book data from the JSON file
$jsonData = file_get_contents('books.json');

// Decode the JSON data into a PHP array
$books = json_decode($jsonData, true);





  $featuredCategoryIndex=array_rand($categories,1);
  $featuredCategory=$categories[$featuredCategoryIndex];
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
  <?php foreach ($books as $book): ?>
    <div class='book'>
        <h3><?= $book['title']; ?></h3>
        <img src="<?= $book['cover']; ?>" alt="<?= $book['title']; ?> Cover" />
        <p><strong>Description:</strong> <?= $book['description']; ?></p>
        <p><strong>Category:</strong> <?= $book['category']; ?></p>
        <p><strong>Price:</strong> $<?= number_format($book['price'], 2); ?></p>

        <!-- Show edit link if logged in -->
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <p><a href="edit_book.php?title=<?= urlencode($book['title']); ?>">Edit</a></p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to <?php echo $storeName; ?></title>

    <style>
  .book-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  .book {
    border: 1px solid #ddd;
    padding: 10px;
    width: 200px;
  }
  .book img {
    width: 100%;
    height: auto;
  }
</style>

    </head>
  <body>
    <h1>
      <?php
        echo $greeting . ", welcome to " . $storeName;
      ?>!
    </h1>
    <p>Here are some of our book categories:</p>
    <ul>
      <?php
      // Loop through categories array
      // and display each one
      foreach ($categories as $category) {
        echo "<li>" . $category . "</li>";
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
            <p><strong>Category:</strong> <?= $book['category']; ?></p>
            <?php if (isset($book['discount_price'])): ?>
                <p><strong>Price:</strong> <span style='text-decoration: line-through;'>$<?= number_format($book['price'], 2); ?></span>
                <strong>Discounted Price:</strong> $<?= number_format($book['discount_price'], 2); ?></p>
            <?php else: ?>
                <p><strong>Price:</strong> $<?= number_format($book['price'], 2); ?></p>
            <?php endif; ?>
            <p><strong>Category:</strong>
    <a href="category.php?category=<?= urlencode($book['category']); ?>">
        <?= $book['category']; ?>
    </a>
</p>
        </div>
    <?php endforeach; ?>
</div>

  
  </body>
  </html>