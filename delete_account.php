<?php
// Start the session to check user authentication
session_start();
// Include your database connection file
require("./includes/library.php");
$pdo = connectDB();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
// Check if the form is submitted
if (isset($_POST['delete'])) {
    // Fetch the username of the logged-in user
    $username = $_SESSION['username'];

    // Delete related items first
    $delete_items_query = "DELETE FROM user_items WHERE list_id IN (SELECT id FROM user_lists WHERE username = ?)";
    $stmt_items = $pdo->prepare($delete_items_query);

    if ($stmt_items->execute([$username])) {

        // Delete related records first
        $delete_lists_query = "DELETE FROM user_lists WHERE username = ?";
        $stmt_lists = $pdo->prepare($delete_lists_query);

        if ($stmt_lists->execute([$username])) {
            // Now delete the user account
            $delete_query = "DELETE FROM assn_users WHERE username = ?";
            $stmt = $pdo->prepare($delete_query);

            if ($stmt->execute([$username])) {
                // Logout the user and redirect to the login page after successful deletion
                session_destroy();
                echo "Account deleted";
                header("Location: login.php");
                exit();
            } else {
                // Handle error
                echo "Error deleting user account.";
            }
        } else {
            // Handle error
            echo "Error deleting related records.";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="styles/main.css">
    <script src="./scripts/delete-account.js"></script>

</head>
<body>
    <header>
        <h1><a href="./login.php">Back to Login</a></h1>
    </header>
    <main class="page-content">
        <form id="page-form" class="page-form" method="post">
            <div class="page-container">
                <div class="heading">
                    <h2> Delete Account</h2>
                </div>
                    <p>Are you sure you want to delete your account?</p>
                    <input type="submit" name="delete" value="Delete Account">
                    
            </div>
        </form>
    </main>
    
</body>
<?php include './includes/footer.php' ?>
</html>