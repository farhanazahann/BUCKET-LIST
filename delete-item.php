<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get item ID from the query string
$item_id = $_GET['id'] ?? null;

echo "Item ID: ";
var_dump($item_id);

// Check if item ID is provided
if (!$item_id) {
    echo "user doesnt exist";
    //header("Location: index.php");
    //exit();
}

// Connect to the database
require("./includes/library.php");
$pdo = connectDB();

// Fetch item details from the database based on the item ID
$query = "SELECT item_name FROM user_items WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$item_id]);
$item = $stmt->fetch();


// Check if the item exists and belongs to the logged-in user
if (!$item || $item['item_name'] !== $_SESSION['username']) {
    // Redirect if the item doesn't exist or doesn't belong to the user

    // Delete the item from the database
    $delete_query = "DELETE FROM user_items WHERE id = ?";
    $delete_stmt = $pdo->prepare($delete_query);

    if ($delete_stmt->execute([$item_id])) {
        // Redirect to the main page after successful deletion
        echo"success deletion";
        //header("Location: index.php");
        //exit();
    } else {
        // Handle error if deletion fails
        echo "Error deleting item: ";
    }

    header("Location: index.php");
    exit();
}


?>