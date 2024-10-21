
<?php
require_once("./includes/library.php");
$pdo = connectDB();

$searchResults = [];

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    // Get the search query
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Search for matching items in the database
    $query = "SELECT l.*, u.list_name
    FROM user_items l
    JOIN user_lists u ON l.list_id = u.id
    WHERE l.item_name LIKE ?";    
    $stmt = $pdo->prepare($query);
    $stmt->execute(["%" . $searchQuery . "%"]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to get a random item from the user's lists
function getRandomItem($pdo)
{
    $query = "SELECT l.*, u.list_name
    FROM user_items l
    JOIN user_lists u ON l.list_id = u.id
    ORDER BY RAND()
    LIMIT 1";
    $stmt = $pdo->query($query);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if the random search button is clicked
if (isset($_GET['random'])) {
    $searchResults = [getRandomItem($pdo)];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bucket List</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
<?php include './includes/header.php' ?>

    <h2 id="search-title">Search Bucket List</h2>
    <?php include './includes/nav.php' ?>
    <div class="container">
        <main class="page-content">
            <form method="get">
                <div class="search_bar">
                    <input id="search" type="text" name="search" placeholder="Search the bucket list..." required value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    <button type="submit" id="submit" class="searchButton"><i class="fa-solid fa-magnifying-glass fa-xs"></i>Search</button>
                </div>
            </form>
            <form>

                <div class="generate_random">
                <button type="submit" name="random" id="random">Generate Random Suggestions</button>
                </div> 
            </form>

            <!-- Display search results -->
            <section>
                <h2>Search Results</h2>
                <ul>
                    <?php foreach ($searchResults as $result) : ?>
                        <li>
                            <div class="search_result">
                                <h3><?php echo htmlspecialchars($result['item_name']); ?></h3>
                                <p>List Name: <?php echo htmlspecialchars($result['list_name']); ?></p>
                                <p>Description: <?php echo htmlspecialchars($result['item_description']); ?></p>
                                <p>Category: <?php echo htmlspecialchars($result['category']); ?></p>
                                <p>State: <?php echo htmlspecialchars($result['state']); ?></p>
                                <p>Starting Date: <?php echo htmlspecialchars($result['starting_date']); ?></p>
                                <p>Completion Date: <?php echo htmlspecialchars($result['completion_date']); ?></p>

                                    <!-- Display Image -->
                                    <?php if ($result['image']) : ?>
                                        <div class="image-container">
                                            <label for="itemImage">Image:</label>
                                            <img src="data:image/<?php echo $result['image_type']; ?>;base64,<?php echo base64_encode($result['image']); ?>" alt="Item Image" />
                                        </div>
                                    <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </div>
    <?php include './includes/footer.php' ?>

</body>
</html>