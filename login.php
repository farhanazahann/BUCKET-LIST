<?php
$errors = array();
//get data from post
// Get username and password from the form
$username = $_POST['username'] ?? "";
$password = $_POST['password'] ?? "";
$login = $_POST['login'] ?? "";
$agree = $_POST['agree'] ?? 'N';
if(isset($_COOKIE['BucketList'])){
$username = $_COOKIE['BucketList'];}


// *****************************************************************************
// Process form here:
// *****************************************************************************
//include library and create a database connection
require "./includes/library.php";
$pdo = connectDB();
// ...
if(isset($_POST['submit'])){
  session_start(); // Start session

  $username = $_POST['username'] ?? "";

  //Select data from database based on username
  $query = "SELECT * FROM assn_users WHERE username = ?";
  $stmt = $pdo->prepare($query);
  //run query
  $stmt->execute([$username]);
  //get all the data
  //fetch row from result set - make sure to check that something was returned
  $results = $stmt->fetch();

  if (!$results || !password_verify($password, $results['password'])) {
    // Set error flag for failed login
    $errors['login'] = true;
  } else{
    $_SESSION['username'] = $results['username'];
    if(isset($_POST["agree"])) {
      // Set username cookie securely
      $mybucketlist = time() + 60 * 60 * 24 * 30 * 12; // 1 year
      setcookie("BucketList", $username, $mybucketlist);

      echo "Username Cookie Set Successfully";
  
    }
   
      // Redirect to the main page
     
      header("Location:index.php");
      exit();
  }
}


//check session for whatever user info was stored
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <header>
        <h1><a href="./register.php">Create Account</a></h1>
    </header>
    <main class="page-content">

        <!-- Login form -->
        <form id="page-form" method="post">
            <div class="heading"> <h2>Login</h2></div>
            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" name="username" id="username" placeholder="Enter your username" value="<?= $username ?>">
            </div>
            
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" name="password" id="password"  >
            </div>

          <div>
            <span class="error <?= !isset($errors['login']) ? 'hidden' : '' ?>">Please insert a valid username or password.</span>
            
          </div>
          
            <div id="checkbox" class="form-group">
              <input type="checkbox" name="agree" id="rememberme" value="Y" <?= $agree === 'Y' ? 'checked' : '' ?>>
              <label for="rememberme"> Remember Me</label>
            </div>
        
            <button type="submit" name="submit">Log In</button>

        
          </form>          
        <!-- Link to the forgot password page -->
        <a href="./forgot.php">Forgot Password?</a>
    </main>
    <?php include './includes/footer.php' ?>
</body>

</html>