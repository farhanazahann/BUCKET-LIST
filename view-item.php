<?php

//connecting using pdo using connectDB()
require("./includes/library.php");
$pdo = connectDB();

// Check if the item ID is provided in the URL
if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    // Fetch item details based on the provided ID
    $itemQuery = "SELECT * FROM user_items WHERE id = ?";
    $itemStmt = $pdo->prepare($itemQuery);
    $itemStmt->execute([$itemId]);
    $item = $itemStmt->fetch();

    if ($item) {
        // Set variables for item details
        $itemTitle = $item['item_name'];
        $itemCategory = $item['category'];
        $itemState = $item['state'];
        $startingDate = $item['starting_date'];
        $completionDate = $item['completion_date'];
        $itemDescription = $item['item_description'];

        // Include the HTML code for the view-item page
        
        
    } else {
        // Item not found, you can redirect or display an error message
        echo "Item not found.";
        exit();
    }
} else {
    // Item ID not provided in the URL, you can redirect or display an error message
    echo "Item ID not provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <?php include './includes/header.php' ?>

    <div class="container">

        <?php include './includes/nav.php' ?>

        <main class="page-content">
            <div class="page-container">

                <div id="view_item">
                    <h2>View Item</h2>
                    <ul class="item-details">
                        <li><h3> <?= $itemTitle ?></h3></li>
                        <div class= "view_item_class">
                        <li><strong>Category:</strong> <?= $itemCategory ?></li>
                        <li><strong>State:</strong> <?= $itemState ?></li>
                        <li><strong>Starting Date:</strong> <?= $startingDate ?></li>
                        <li><strong>Completion Date:</strong> <?= $completionDate ?></li>
                        <li><strong>Description:</strong> <?= $itemDescription ?></li>
                        </div>
                        

                        <!-- Display Image -->
                        <?php if ($item['image']) : ?>
                            <div class="image-box">
                                <img src="data:image/<?= $item['image_type']; ?>;base64,<?= base64_encode($item['image']); ?>" alt="Item Image" />
                            </div>
                        <?php endif; ?>

                        <!-- Add more details as needed -->
                    </ul>

                    <div class="back-to-link">
                    <h3><i class="fa fa-angle-right" aria-hidden="true"></i><a href="./index.php">Back to Home</a></h3>
                </div>
                </div>

            </div>
        </main>

    </div>

    <?php include './includes/footer.php' ?>
</body>

</html>