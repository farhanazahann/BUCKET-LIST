<?php
$errors = array();

 // Get user input
 $username = $_POST['username'] ?? "";
 $name = $_POST['name'] ?? "";
 $email = $_POST['email'] ?? "";
 $password = $_POST['password'] ?? "";
 $confirmPassword = $_POST['confirm_password'] ?? "";
 $pswd_match = $_POST['pswd_match'] ?? "";
 $list_name = $_POST['list_name'] ?? "";
 $list_desc = $_POST['list_desc'] ?? "";
 $public_visibility = $_POST['public_visibility'] ?? "N";

 // Insert user data into the database
 require("./includes/library.php");
 $pdo = connectDB();
 //build query

 
if (isset($_POST['submit'])) {
   
// Validate that the user has entered a name (since names are a string that
  // could be just about anything, its validation is simple)
    if (strlen($name) === 0) {
      $errors['name'] = true;
    }
    if (strlen($username) === 0) {
      $errors['username'] = true;
    }
  // Ensure that the user has entered a valid email address
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $errors['email'] = true;
    }

    if (strlen($password) === 0 ){
        $errors['password']= true;
      } elseif (strlen($password) < 8) {
          $errors['password_length'] = true;
      } elseif (!preg_match('/[a-z]/', $password)) {
          $errors['password_lowercase'] = true;
      } elseif (!preg_match('/[A-Z]/', $password)) {
          $errors['password_uppercase'] = true;
      } elseif (!preg_match('/\d/', $password)) {
          $errors['password_digit'] = true;
      } elseif (!preg_match('/[@$!%*?&]/', $password)) {
          $errors['password_special'] = true;
      }

    if (strlen($confirmPassword) === 0 ){
        $errors['confirm_password']= true;
      }

    if ($password!== $confirmPassword && strlen($password) !== 0 && strlen($confirmPassword) !== 0){
        $errors['pswd_match']= true;
      }

    if (strlen($list_name) === 0) {
        $errors['list_name'] = true;
      }

    if (strlen($list_desc) === 0) {
        $errors['list_desc'] = true;
      }
    if (count($errors) === 0) {
        // Hash the password
      
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO assn_users(username, password, name, email)
        VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username,$hashedPassword, $name, $email]);
        
        $query1 = "INSERT INTO user_lists(username, list_name, list_desc, public_visibility)
        VALUES (?, ?, ?, ?)";
        $stat = $pdo->prepare($query1);
        $stat->execute([$username,$list_name,$list_desc,$public_visibility]);

       // Then redirect:
        header("Location: login.php");
        exit;
    }  
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="./scripts/register.js"></script>
    <script defer src="./scripts/password.js"></script>
</head>

<body>
<?php include './includes/header.php' ?>

    <main class="page-content">
        <form id="page-form" class="page-form" method="post">
            <div class="page-container">
                <div class="heading">
                    <h2> Create Account</h2>
                </div>

                <!-- Username -->
                <div class="page-group">
                    <label class="page-label" for="username">Username:</label>
                    <input class="page-input" type="text" name="username" id="username"
                        placeholder="Enter your username" value="<?= $username ?>" >
                        <span class="error <?= !isset($errors['username']) ? 'hidden' : '' ?>">Please enter your username.</span>
                </div>

                <!-- Name -->
                <div class="page-group">
                    <label class="page-label" for="name">Name:</label>
                    <input class="page-input" type="text" name="name" id="name" placeholder="Enter your name" value="<?= $name ?>" >
                    <span class="error <?= !isset($errors['name']) ? 'hidden' : '' ?>">Please fill this field.</span>
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
                    <label class="page-label" for="password">Password: </label>
                    <input class="page-input" type="password" name="password" id="password" 
                        placeholder="Enter your password"> 
                        <i class="fa fa-eye" aria-hidden="true" id="clickPassword"></i>
                    <span class="error <?= !isset($errors['password']) ? 'hidden' : '' ?>">Please enter your password.</span>
                </div>
                <div id="password-strength-indicator">
  <span id="password-strength-indicator-text"></span>
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

                <div class = "page-divider">
                    <!-- "In your list??" item -->
                <div class="page-group">
                    <h2 class="in-your-list">Include a Fresh Entry in Your List</h2>
                </div>
                <!-- List Information Fieldset -->
                <fieldset id="list-info-fieldset" class="page-fieldset">
                    <legend class="page-legend"><strong>List Information</strong></legend>
                    <!-- List Name -->
                    <div class="page-group">
                        <label class="page-label" for="list_name">List Name:</label>
                        <input class="page-input" type="text" name="list_name" id="list_name"
                            placeholder="Enter list name" value="<?= $list_name ?>">
                            <span class="error <?= !isset($errors['list_name']) ? 'hidden' : '' ?>">You haven't specified the list name.</span>
                    </div>
                    <!-- List Description -->
                    <div class="page-group">
                        <label class="page-label" for="list_desc">List Description:</label>
                        <br>
                        <textarea class="page-textarea" name="list_desc" id="list_desc"
                            placeholder="Enter list description" rows="5" maxlength="2500"> <?= $list_desc ?> </textarea>
                            
                            <span class="error <?= !isset($errors['list_desc']) ? 'hidden' : '' ?>">Give the description for the list created.</span>
                            <span id="charCounter">2500 characters remaining</span>
                    </div>
                    <!-- Public Visibility Checkbox -->
                    <div class="page-group">
                        <input class="page-checkbox" type="checkbox" name="public_visibility" id="public_visibility"
                             value="Y" <?= $public_visibility === 'Y' ? 'checked' : '' ?>>
                        <label for="public_visibility">Make List Publicly Viewable</label>
                        
                    </div>
                </fieldset>
                </div>
                  <!-- Display errors if any -->
                 <?php
                if (!empty($errors)) {
                    echo '<div class="error-message">';
                    if (isset($errors['empty_fields'])) {
                        echo 'Please fill in all fields.';
                    }
                    if (isset($errors['email_invalid'])) {
                        echo 'Invalid email address.';
                    }
                    if (isset($errors['password_mismatch'])) {
                        echo 'Passwords do not match.';
                    }
                    // Add more error messages as needed
                    echo '</div>';
                }
                ?>
                <!-- Submit Button -->
                <button class="page-button" type="submit" name="submit">Register</button>

                <div class="back-to-link">
                    <h3><i class="fa fa-angle-right" aria-hidden="true"></i><a href="./login.php">Back to Login</a></h3>
                </div>
            </div>
        </form>
    </main>
    <?php include './includes/footer.php' ?>
</body>

</html>