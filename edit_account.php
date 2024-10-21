<?php
$errors = array();
// Start the session to check user authentication
session_start();
require("./includes/library.php");
$pdo = connectDB();
$user_name = $_SESSION['username'] ?? "";


// Check if the item id is provided in the URL
if (isset($_SESSION['username'])) {
    $user_name = $_SESSION['username'];

    // Fetch item details from the database based on the item id
    $query = "SELECT * FROM assn_users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_name]);
    $row = $stmt->fetch();

    // Check if the item belongs to the logged-in user
    if ($row) {
        // Fetch existing data
        $username = $row['username'] ?? "";
        $name = $row['name'] ?? "";
        $email = $row['email'] ?? "";
        $hashedPassword = $row['password'] ?? "";

        // Handle form submission
        if (isset($_POST['submit'])) {
            // Verify the entered password
            $enteredPassword = $_POST['entered_password'] ?? "";
           
            if (!password_verify($enteredPassword, $hashedPassword)) {
                $errors['password_verification'] = true;
            } else {
                // Continue with the update process
                $username = $_POST['username'] ?? "";
                $name = $_POST['name'] ?? "";
                $email = $_POST['email'] ?? "";
                $newPassword = $_POST['password'] ?? "";
                $confirmPassword = $_POST['confirm_password'] ?? "";
                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $errors['email'] = true;
                  }
                // Handle password validation
                if (strlen($newPassword) === 0) {
                    $errors['password'] = true;
                    
                } elseif (strlen($newPassword) < 8) {
                    $errors['password_length'] = true;
                } elseif (!preg_match('/[a-z]/', $newPassword)) {
                    $errors['password_lowercase'] = true;
                } elseif (!preg_match('/[A-Z]/', $newPassword)) {
                    $errors['password_uppercase'] = true;
                } elseif (!preg_match('/\d/', $newPassword)) {
                    $errors['password_digit'] = true;
                } elseif (!preg_match('/[@$!%*?&]/', $newPassword)) {
                    $errors['password_special'] = true;
                }

                if (strlen($confirmPassword) === 0) {
                    $errors['confirm_password'] = true;
                }

                if ($newPassword !== $confirmPassword && strlen($newPassword) !== 0 && strlen($confirmPassword) !== 0) {
                    $errors['pswd_match'] = true;
                }
                var_dump($errors);
                if (count($errors) === 0) {
                    echo "This happend";
                    // Hash the password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the item in the database
                    $update_query = "UPDATE assn_users SET 
                        username = ?,
                        name = ?,
                        email = ?,
                        password = ?
                        WHERE username = ?";

                    // Prepare the statement
                    $stmt_query = $pdo->prepare($update_query);
                    // Execute the update query
                    if ($stmt_query->execute([$username, $name, $email, $hashedPassword, $user_name])) {
                        // Redirect to the main page after successful update
                        header("Location: index.php");
                        exit();
                    } else {
                        // Handle error
                        echo "Error updating item.";
                    }
                }
            }
        }
    } else {
        // Redirect to the main page if the item doesn't belong to the user
        echo "Item doesn't exist.";
        exit();
    }
} else {
    // Redirect to the main page if no item id is provided
    echo "No item id provided.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit List Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
<?php include './includes/header.php' ?>

    <h2 id="edit-title">Edit Account</h2>

    <main class="page-content">
    <form id="page-form" class="page-form" method="post">
            <div class="page-container">
        <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" name="username" id="username" placeholder="Enter your username" value="<?= $username ?>">
            </div>
           
           <!-- Add a field for password verification -->
           <div class="form-group">
                <label for="entered_password">Enter Your Password:</label>
                <input type="password" name="entered_password" id="entered_password" placeholder="Enter your password">
                <span class="error <?= !isset($errors['password_verification']) ? 'hidden' : '' ?>">Incorrect password. Please try again.</span>
            </div>
          <div>
            <span class="error <?= !isset($errors['login']) ? 'hidden' : '' ?>">Please insert a valid username or password.</span>
          </div>


           <!-- Email -->
           <div class="page-group">
                    <label class="page-label" for="email">Email:</label>
                    <input class="page-input" type="email" name="email" id="email" placeholder="Enter your email" value="<?= $email ?>"
                    >
                        <span class="error <?= !isset($errors['email']) ? 'hidden' : '' ?>">Please enter your email.</span>
                </div>


            <!-- Password -->
            <div class="page-group">

                <label class="page-label" for="password">Password:</label>
                <input class="page-input" type="password" name="password" id="password" 
                    placeholder="Enter your password" >
                    <span class="error <?= !isset($errors['password']) ? 'hidden' : '' ?>">Please enter your password.</span>
                </div>

                <div class="page-group">
                <ul class="password-requirements">
                    <li class="error <?= !isset($errors['password_length']) ? 'hidden' : '' ?>">Password must be at least 8 characters long.</li>
                    <li class="error <?= !isset($errors['password_lowercase']) ? 'hidden' : '' ?>">Password must contain at least one lowercase letter.</li>
                    <li class="error <?= !isset($errors['password_uppercase']) ? 'hidden' : '' ?>">Password must contain at least one uppercase letter.</li>
                    <li class="error <?= !isset($errors['password_digit']) ? 'hidden' : '' ?>">Password must contain at least one digit.</li>
                    <li class="error <?= !isset($errors['password_special']) ? 'hidden' : '' ?>">Password must contain at least one special character (@$!%*?&).</li>
                </ul>
                </div>

                <!-- Confirm Password -->
                <div class="page-group">
                <label class="page-label" for="confirm_password">Confirm Password:</label>
                <input class="page-input" type="password" name="confirm_password" id="confirm_password"
                    placeholder="Confirm your password" >
                    <span class="error <?= !isset($errors['confirm_password']) ? 'hidden' : '' ?>">Please confirm your password.</span>
                    <span class="error <?= !isset($errors['pswd_match']) ? 'hidden' : '' ?>">Password does not match.</span>
                </div>


            <!-- Submit Button -->
            <input type="submit" name="submit" value="Save Changes">
        </div>
        </form>
        <div class="back-to-link">
          <h3><i class="fa fa-angle-right" aria-hidden="true"></i><a href="./login.php">Back to Login</a></h3>
        </div>
    </main>


    <?php include './includes/footer.php' ?>
</body>


</html>