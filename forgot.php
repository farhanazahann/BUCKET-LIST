<?php
$errors = array();
$username = $_POST['username'] ?? "";
$email = $_POST['email'] ?? "";
$reset = $_POST['reset'] ?? "";
// include library and create a database connection
require "./includes/library.php";
$pdo = connectDB();
session_start(); // Start session

if(isset($_POST['submit'])){
    // Select data from the database based on username and email
    $query = "SELECT * FROM assn_users WHERE username = ? AND email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $email]);
    $results = $stmt->fetch();

    if (!$results) {
        // Set error flag for failed login
        $errors['reset'] = true;
    }  
    else {
                // Generate a unique token
                $token = md5(rand());
                // Set the token and expiration timestamp in the database
                $expiration = time() + 3600; // 1 hour
                $query1 = "UPDATE assn_users SET reset_token = ?, reset_token_expiration = FROM_UNIXTIME(?) WHERE email = ?";
                $stmt1 = $pdo->prepare($query1);
                $success = $stmt1->execute([$token, $expiration, $email]);
            
                if (!$success) {
                    echo "Token is not valid. Database update failed.";
                } else {

                
                    
                    // Send email with the token in the body
                    $to = $results['email'];

                    $subject = "Password Reset";
                    $body = "Click the following link to reset your password: https://loki.trentu.ca/~parmeetkaur/3420/assn/assn3/reset.php?token=$token";
            
                    // Add code for sending email here (use a library like PHPMailer or other mail libraries)
                    require_once "Mail.php";  
                    $from = "Password System Reset <noreply@loki.trentu.ca>";
                    $headers = array('From' => $from, 'To' => $to, 'Subject' => $subject);
                    $smtp = Mail::factory('smtp', array('host' => 'smtp.trentu.ca'));
                    $mail = $smtp->send($to, $headers, $body);
            
                    if (PEAR::isError($mail)) {
                        echo("<p>" . $mail->getMessage() . "</p>");
                    } else {
                        echo("<p>Message successfully sent!</p>");
                    }
                }
            }
            
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles/main.css">
    <style>
        .error {
            color: rgb(3, 130, 3);
            font-style: italic;
            margin: 1em;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="login.php">Back to the Login Page</a></h1>
    </header>
    <main class="page-content">
        <form id="page-form" method="post">
            <div class="heading"> <h2>Forget Password</h2></div>
    
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" value="<?= $username ?>">
            </div>
            
            <div class="form-group">
                <label class="page-label" for="email">Email Address:</label>
                <input type="email" name="email" id="email" value="<?= $email ?>">
            </div>
            
            <!-- Simplified error message display -->
            <?php if (isset($errors['reset'])): ?>
                <div><span class="error">Please enter a valid username or email.</span></div>
            <?php endif; ?>

            <button type="submit" name="submit">Reset</button>  
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>
</html>