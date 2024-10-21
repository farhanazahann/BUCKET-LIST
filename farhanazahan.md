# 3420 Assignment #4 - Fall 2023

Name(s):Farhana Zahan, Parmeet Kaur, Manpreet Kaur
Student Id: 0691212,0732389, 0719583

Live Loki link(s):https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/index.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/search.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/public.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/login.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/register.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/forgot.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/reset.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/checkusername.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/delete_account.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/delete-item.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/edit-item.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/edit_account.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/logout.php
https://loki.trentu.ca/~farhanazahan/3420/ASSIGNMENTS/Assignment_4/view-item.php
## Rubric

| Component                                                    | Grade |
| :----------------------------------------------------------- | ----: |
| Edit List Validation                                         |    /4 |
| Register Validation                                          |    /4 |
| Delete confirmation                                          |    /3 |
| Details modal                                                |    /5 |
|                                                              |       |
| Copy Public Link to Clipboard                                |    /3 |
| *Unique Username                                              |    /3 |
| *Password Strength                                            |    /3 |
| *Show Password                                                |    /3 |
| *Limiting Description Field                                   |    /3 |
| Star Rating                                                  |    /3 |
|                                                              |       |
| Code Quality (tidyness, validity, efficiency, etc)           |    /4 |
| Documentation                                                |    /3 |
| Testing                                                      |    /3 |
|                                                              |       |
| Bonus                                                        |  /3.5 |
| Deductions (readability, submission guidelines, originality) |       |
|                                                              |       |
| Total                                                        |   /35 |

## Things to consider for Bonus Marks (if any)



## Code & Testing

Put your code and screenshots here, **with proper heading organization**. You don't need to include html/php code (or testing) for any pages that aren't affected by your javascript for this assignment.

## Register

### PHP/JS


```php
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

```
```js
 //register.js

document.addEventListener("DOMContentLoaded", function () {
  const usernameInput = document.getElementById("username");
  const xhr = new XMLHttpRequest();

  usernameInput.addEventListener("blur", (event) => {
      const errorExists = document.getElementById("username_error");
      if (errorExists) {
          errorExists.remove();
      }

      const usernameValue = usernameInput.value.trim(); // Trim to remove leading/trailing spaces

      if (usernameValue !== "") {
          xhr.open("GET", `checkusername.php?username=${encodeURIComponent(usernameValue)}`);
          xhr.addEventListener("load", (ev) => {
              if (xhr.status === 200) {
                  if (xhr.responseText === "error" || xhr.responseText === "true") {
                      const errorSpan = document.createElement("span");
                      errorSpan.classList.add("error");
                      errorSpan.id = "username_error";
                      errorSpan.innerText = "Username already exists. Please choose a different one.";
                      usernameInput.insertAdjacentElement('afterend', errorSpan);
                  }
              } else {
                  console.error("Connection Failed");
              }
          });
          xhr.send();
      }
  });

  const clickPassword = document.getElementById("clickPassword");
  const passwordInput = document.getElementById("password");

  clickPassword.addEventListener("click", (ev) => {
      passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
  });

  const descInput = document.getElementById("list_desc");
  const count = document.getElementById("charCounter");
  const maxChars = 2500;

  descInput.addEventListener("input", function () {
      const remainingChars = maxChars - descInput.value.length;
      count.innerText = `${remainingChars} characters remaining`;

      if (remainingChars < 50) {
          count.style.color = "red";
      } else {
          count.style.color = "blue";
      }
  });
});
```
```js
// password.js
function updatePasswordStrengthIndicator(password) {
    const strengthTextElement = document.getElementById("password-strength-indicator-text");

    if (password.length === 0) {
        strengthTextElement.textContent = '';
    } else {
        const strength = checkPasswordStrength(password);

        if (strength.length === 0) {
            strengthTextElement.textContent = 'Strong';
            strengthTextElement.style.color = 'green';
        } else if (password.length < 6) {
            strengthTextElement.textContent = 'Weak';
            strengthTextElement.style.color = 'red';
        } else if (password.length < 8) {
            strengthTextElement.textContent = 'Moderate';
            strengthTextElement.style.color = 'orange';
        } else {
            strengthTextElement.textContent = 'Strong';
            strengthTextElement.style.color = 'green';
        }
    }
}

function checkPasswordStrength(password) {
    const requirements = [
        {
            condition: password.length >= 8,
            message: 'Password must be at least 8 characters long.'
        },
        {
            condition: /[a-z]/.test(password),
            message: 'Password must contain at least one lowercase letter.'
        },
        {
            condition: /[A-Z]/.test(password),
            message: 'Password must contain at least one uppercase letter.'
        },
        {
            condition: /\d/.test(password),
            message: 'Password must contain at least one digit.'
        },
        {
            condition: /[@$!%*?&]/.test(password),
            message: 'Password must contain at least one special character (@$!%*?&).'
        }
    ];

    return requirements.filter(req => !req.condition).map(req => req.message);
}

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");

    passwordInput.addEventListener('input', function () {
        updatePasswordStrengthIndicator(this.value);
    });
});

```
#### Browser Testing

### Windows
### Password Strength
## weak Password
<img src="./img/weak_pass.png" alt="weak password" >

<img src="./img/moderste_pass.png" alt="moderate pass" >
<img src="./img/strongpass.png" alt="strong pass" >


### Validation if the user exists
<img src="./img/Userexist.png" alt="strong pass" >
<img src="./img/Userexistdatabase.png" alt="strong pass" >


### **Validation
<img src="./img/registerValidity.png" alt="strong pass" >


### Details field limited to 2500 characters,
<img src="./img/2500words.png" alt="strong pass" >
<img src="./img/less50.png" alt="strong pass" >

### Show Password


```js

  const clickPassword = document.getElementById("clickPassword");
  const passwordInput = document.getElementById("password");

  clickPassword.addEventListener("click", (ev) => {
      passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
  });


```


### **Validation
<img src="./img/shopass1.png" alt="strong pass" >
<img src="./img/shopass2.png" alt="strong pass" >

## EditList

### PHP/JS


```php
<?php
$errors = array();
// Start the session to check user authentication
session_start();
require("./includes/library.php");
$pdo = connectDB();

$category = $_POST['options'] ?? null;
$state  = $_POST['state'] ?? null;
$starting_date = $_POST ['starting_date'] ?? null;
$completion_date = $_POST ['completion_date'] ?? null;

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

$item_id = $_GET['id'];
echo "Received item_id: $item_id";

// Check if the item id is provided in the URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Fetch item details from the database based on the item id
    $query = "SELECT * FROM user_items WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$item_id]);
    $row = $stmt->fetch();

    // Check if the item belongs to the logged-in user
    if ($row) {
        // Fetch existing data
        $title = $row['item_name'];
        $description = $row['item_description'];
        $category = $row['category'];
        $state = $row['state'];
        $starting_date = $row['starting_date'];
        $completion_date = $row['completion_date'];

        // Handle form submission
        if (isset($_POST['submit'])) {
            $title = $_POST['title'] ?? "";
            $state = $_POST['state'] ?? "";
            $description = $_POST['description'] ?? "";
            $category = $_POST['options'] ?? "";
            $starting_date = $_POST['starting_date'] ? $_POST['starting_date'] : null; 
            $completion_date = $_POST['completion_date'] ? $_POST['completion_date'] : null; 

            $valid_state = ['Completed', 'In Progress', 'Not Yet Started'];
            if (!in_array($state, $valid_state)) {
                $errors['state'] = true;
            }

            // Image handling
            $image = $_FILES['proof'];

            if ($image['size'] > 0) {
                // Check if the file is an image
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                $imageType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

                if (in_array($imageType, $allowedTypes)) {
                    // Read image data
                    $imageData = file_get_contents($image['tmp_name']);
                } else {
                    $errors['image'] = true;
                }
            } else {
                // If no new image is uploaded, retain the existing image data
                $imageData = $row['image'];
                $imageType = $row['image_type'];
            }

            if ($state == 'Completed' && (!$starting_date || !$completion_date)) {
                $errors['missing_dates'] = true;
            } elseif ($state == 'In Progress' && !$starting_date) {
                $errors['missing_start_date'] = true;
            } elseif ($state == 'Completed' && strtotime($completion_date) < strtotime($starting_date)) {
                $errors['wrong_date'] = true;
            } else {
                // Update the item in the database
                $update_query = "UPDATE user_items SET item_name = ?, item_description = ?, category = ?, state = ?, starting_date = ?,completion_date = ?, image = ?, image_type = ? WHERE id = ?";
                $stmt_query = $pdo->prepare($update_query);

                if ($state == 'Not Yet Started') {
                    $params = [$title, $description, $category, $state, null, null, null, null, $item_id];
                    if ($stmt_query->execute($params)) {
                        // Handle image upload if necessary
                        // Redirect to the main page after successful update
                        header("Location: index.php");
                        exit();
                    } else {
                        // Handle error
                        echo "Error updating item.";
                    }
                } else {
                    $params = [$title, $description, $category, $state, $starting_date, $completion_date, $imageData, $imageType, $item_id];
                    if ($stmt_query->execute($params)) {
                        // Handle image upload if necessary
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
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect to the main page if no item id is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit List Item</title>
    <link rel="stylesheet" href="styles/main.css">
    <script defer src="./scripts/edit_item.js"></script>
    <script defer src="./scripts/edit_des.js"></script>
</head>

<body>
    <?php include './includes/header.php' ?>
    
    <h2 id="edit-title">Edit Bucket List Item</h2>
    
    <div class="container">
        <?php include './includes/nav.php' ?>
        <main class="page-content">
            <!-- Error container for displaying general error messages -->
            <div id="error-container" class="error-container">
                <form id="edit-form" method="post" enctype="multipart/form-data">
                    
                    <div class="form-element">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" placeholder="Adventure and Travel" value="<?= $title ?>" >
                        <!-- Error span for title -->
                        <span class="error hidden" id="title-error"></span>
                    </div>

                    <fieldset class="form-element">
                        <legend>Category</legend>
                        <div>
                            <input type="radio" name="options" id="travel_adventure" value="Travel_and_Adventure" <?= $category === 'Travel_and_Adventure' ? 'checked' : '' ?>/>
                            <label for="travel_adventure">Travel and Adventure</label>
                        </div>
                        <div>
                            <input type="radio" name="options" id="unique_experiences" value="Unique_Experiences" <?= $category === 'Unique_Experiences' ? 'checked' : '' ?>/>
                            <label for="unique_experiences">Unique Experiences</label>
                        </div>
                        <div>
                            <input type="radio" name="options" id="financial_milestones" value="Financial_Milestones" <?= $category === 'Financial_Milestones' ? 'checked' : '' ?>/>
                            <label for="financial_milestones">FinancialMilestones</label>
                        </div>
                        <div>
                            <input type="radio" name="options" id="career_success" value="Career_Success" <?= $category === 'Career_Success' ? 'checked' : '' ?>/>
                            <label for="career_success">Career Success</label>
                        </div>
                    </fieldset>

                    <fieldset class="form-element">
                        <legend>State</legend>
                        <div>
                            <input type="radio" name="state" id="completed" value="Completed" <?= $state == 'Completed' ? 'checked' : '' ?>>
                            <label for="completed">Completed</label>
                        </div>

                        <div>
                            <input type="radio" name="state" id="in-progress" value="In Progress"  <?= $state == 'In Progress' ? 'checked' : '' ?>>
                            <label for="in-progress">In Progress</label>
                        </div>
                        <div>
                            <input type="radio" name="state" id="not-started" value="Not Yet Started" <?= $state == 'Not Yet Started' ? 'checked' : '' ?> >
                            <label for="not-started">Not Started Yet</label>
                        </div>
                    </fieldset>
                        <div class="form-element">
                            <label for="starting-date">Starting Date: </label>
                            <input type="date" id="starting_date" name="starting_date" value="<?= $starting_date ?>">
                            <!-- Error span for starting date -->
                            <span class="error hidden" id="starting-date-error"></span>
                        </div>
                
                    
                        <div class="form-element">
                            <label for="completion-date">Completion Date: </label>
                            <input type="date" id="completion_date" name="completion_date" value="<?= $completion_date ?>">
                            <!-- Error span for completion date -->
                            <span class="error hidden" id="completion-date-error"></span>
                
                        </div>
                            <span class="error <?= !isset($errors['missing_start_date']) ? 'hidden' : '' ?>">Please insert a valid Start Date.</span>
                            <span class="error <?= !isset($errors['missing_dates']) ? 'hidden' : '' ?>">Please insert a valid Date.</span>
                            <span class="error <?= !isset($errors['wrong_date']) ? 'hidden' : '' ?>"> Starting date can't be before the Completion Date.</span>
                    <div class="form-element">
                    <label for="description">Description:</label>
                        <textarea name="description" id="description" required></textarea>
                        <span class="error hidden" id="description-error"></span>
                        <span id="charCount">2500 characters remaining</span>
                
                    </div>
                    
                    <div class="form-element">
                        <label for="proof">Proof (Image Upload): </label>
                        <input type="file" id="proof" name="proof">
                        <!-- Error span for proof -->
                        <span class="error hidden" id="proof-error"></span>
                
                    </div>

                    <div class="form-element">
                        <!-- Display existing image if it exists -->
                        <?php if ($row['image']): ?>
                            <label for="existingImage">Image:</label>
                            <div class="image-box">
                                <img src="data:image/<?php echo $row['image_type']; ?>;base64,<?php echo base64_encode($row['image']); ?>" alt="Item Image" />
                            </div>
                        <?php endif; ?>
                    </div>

                    <input type="submit" name="submit" value="Save Changes">
                </form>
            </div>
        </main>
    </div>

</body>
<?php include './includes/footer.php' ?>

</html>

```
```js
//edit_js
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('edit-form');
    editForm.addEventListener('submit', function (event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    function validateForm() {
        resetErrorMessages();

        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const startDateInput = document.getElementById('starting_date');
        const completionDateInput = document.getElementById('completion_date');
        const stateInput = document.querySelector('input[name="state"]:checked');
        const proofInput = document.getElementById('proof');

        let isValid = true;

        if (titleInput.value.trim() === '') {
            displayError(titleInput, 'Please enter a title.');
            isValid = false;
        }

        if (descriptionInput.value.trim() === '') {
            displayError(descriptionInput, 'Please enter a description.');
            isValid = false;
        }

        if (!stateInput) {
            const stateRadioGroup = document.querySelector('input[name="state"]');
            displayError(stateRadioGroup, 'Please select a state.');
            isValid = false;
        }

        if (stateInput && stateInput.value === 'In Progress' && startDateInput.value.trim() === '') {
            displayError(startDateInput, 'Please enter a starting date.');
            isValid = false;
        }

        if (stateInput && stateInput.value === 'Completed' && (startDateInput.value.trim() === '' || completionDateInput.value.trim() === '')) {
            displayError(startDateInput, 'Please enter a starting date.');
            displayError(completionDateInput, 'Please enter a completion date.');
            isValid = false;
        }

        // Additional date validation
        const today = new Date().toISOString().split('T')[0];

        if (startDateInput.value.trim() !== '' && !isValidDateFormat(startDateInput.value)) {
            displayError(startDateInput, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        if (completionDateInput.value.trim() !== '' && !isValidDateFormat(completionDateInput.value)) {
            displayError(completionDateInput, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        
            const todayDate = new Date(today);

            if (startDateInput.value.trim() !== '') {
                const startDate = new Date(startDateInput.value);
            
                if (startDate > todayDate) {
                    displayError(startDateInput, 'Starting date can\'t be after today.');
                    isValid = false;
                }
            }
            
            if (completionDateInput.value.trim() !== '') {
                const completionDate = new Date(completionDateInput.value);
            
                if (completionDate > todayDate) {
                    displayError(completionDateInput, 'Completion date can\'t be after today.');
                    isValid = false;
                }
            }
            
            if (startDateInput.value.trim() !== '' && completionDateInput.value.trim() !== '') {
                const startDate = new Date(startDateInput.value);
                const completionDate = new Date(completionDateInput.value);
            
                if (startDate > completionDate) {
                    displayError(startDateInput, 'Starting date can\'t be after the completion date.');
                    displayError(completionDateInput, 'Completion date can\'t be before the starting date.');
                    isValid = false;
                }
            }
        else if (stateInput.value === 'Completed') {
            // If state is 'Completed' and no completion date is provided, show an error
            displayError(completionDateInput, 'Completion date is required for Completed state.');
            isValid = false;
        } else if (stateInput.value === 'In Progress' && startDateInput.value.trim() === '') {
            // If state is 'In Progress' and no starting date is provided, show an error
            displayError(startDateInput, 'Starting date is required for In Progress state.');
            isValid = false;
        }

        // Add more validation for proofInput if needed (e.g., file type, size)

        return isValid;
    }

    function resetErrorMessages() {
        const errorMessages = document.querySelectorAll('.error');
        errorMessages.forEach(function (error) {
            error.classList.add('hidden');
        });
    }

    function displayError(inputElement, errorMessage) {
        const errorSpan = inputElement.nextElementSibling;
        errorSpan.innerText = errorMessage;
        errorSpan.classList.remove('hidden');
    }

    function isValidDateFormat(dateString) {
        // Check if the date string is in the format YYYY-MM-DD
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        return dateRegex.test(dateString);
    }
});
```
```js
//edit_des.js
"use strict";
document.addEventListener("DOMContentLoaded", function () {
    const descInput = document.getElementById("description");
    const charCountSpan = document.getElementById("charCount");
    const maxChars = 2500;

    descInput.addEventListener("input", function () {
        const remainingChars = maxChars - descInput.value.length;
        charCountSpan.innerText = `Characters left: ${remainingChars}`;

        if (remainingChars < 50) {
            charCountSpan.style.color = "red";
        } else {
            charCountSpan.style.color = "blue";
        }

        // Validate word limit
        const words = descInput.value.trim().split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;
        const wordLimit = 200; // You can adjust the word limit

        if (wordCount > wordLimit) {
            showError("description-error", "Description exceeds the word limit");
        } else {
            resetError("description-error");
        }
    });

    function showError(spanId, errorMessage) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = errorMessage;
        errorSpan.style.color = "red";
    }

    function resetError(spanId) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = "";
    }
});
```
#### Browser Testing
### editlist
## Date validation
<img src="./img/datevalid.png" alt="weak password" >
<img src="./img/dateinpros.png" alt="moderate pass" >


## DeleteList

### PHP/JS


```php
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
```

```
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const deleteItemLinks = document.querySelectorAll('.delete-item-link');

    deleteItemLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const itemId = link.getAttribute('data-item-id');
        
            // Show a confirmation dialog
            const confirmed = confirmDelete();

            console.log('Delete link clicked for item ID:', itemId);

            if (confirmed) {
                // Redirect to delete-item.php with the item ID
                console.log('User confirmed deletion. Redirecting...');
                window.location.href = 'delete-item.php?item_id=' + itemId;
            } else {
                console.log('Deletion canceled by the user.');
            }
        });
    });

    function confirmDelete() {
        // Show a custom confirmation dialog
        console.log('Showing confirmation dialog...');
        return window.confirm('Are you sure you want to delete this item?');
    }
});
```



### Delete Item

<img src="./img/del1.png" alt="moderate pass" >
<img src="./img/del2.png" alt="moderate pass" >
<img src="./img/del3.png" alt="moderate pass" >


## Mainpage

### PHP/JS


```php
<?php

session_start();
// Check if the user is logged in and the session variable is set
//connecting using pdo using connectDB()
require("./includes/library.php");
$pdo = connectDB();

$errors = array();

// Check if the username is in the session (user is logged in)
if (isset($_SESSION['username'])) {
    $currentUsername = $_SESSION['username'];

    // Fetch user's lists based on the username
    $userListsQuery = "SELECT * FROM user_lists WHERE username = ?";
    $userListsStmt = $pdo->prepare($userListsQuery);
    $userListsStmt->execute([$currentUsername]);

    // Fetch the user's lists
    $userLists = $userListsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the user's lists
    foreach ($userLists as $list) {
        echo '<h3>' . htmlspecialchars($list['list_name']) . '</h3>';
        // Fetch and display items for each list if needed
    }
} else {
    echo "User not found.";
}
$state = $_POST['state'] ?? "";
$starting_date = $_POST['starting_date'] ?? null;
$completion_date = $_POST['completion_date'] ?? null;

// Fetch user lists for the dropdown
$userListsQueryForDropdown = "SELECT * FROM user_lists WHERE username = ?";
$userListsStmtForDropdown = $pdo->prepare($userListsQueryForDropdown);
$userListsStmtForDropdown->execute([$currentUsername]);

// Fetch the user's lists for the dropdown
$userListsForDropdown = $userListsStmtForDropdown->fetchAll(PDO::FETCH_ASSOC);



// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $newItemTitle = $_POST["newItemTitle"];
    $category = $_POST["options"];
    $state = $_POST['state'] ?? "";
    $newItemDescription = $_POST["newItemDescription"] ?? "";
    $selectedList = $_POST["selectedList"]?? "";
    $starting_date = $_POST['starting_date'] ?? null;
    $completion_date = $_POST['completion_date'] ?? null;
    
    // Check if a new list is being created
    if ($selectedList === "new_list") {
        $newListName = $_POST["newListName"];
        $newListDesc = $_POST["newListDescription"];
        $publicVisibility = isset($_POST["publicVisibility"]) ? $_POST["publicVisibility"] : 'N';


        // Insert the new list into the user_lists table
        $query = "INSERT INTO user_lists (username, list_name, list_desc, public_visibility) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$currentUsername, $newListName, $newListDesc, $publicVisibility]);

        // After creating the new list, fetch its ID
        $selectedList = $pdo->lastInsertId();
    }

    if ($state == 'Completed' && (!$starting_date || !$completion_date)) {
        $errors['missing_dates'] = true;
    } elseif ($state == 'In Progress' && !$starting_date) {
        $errors['missing_start_date'] = true;
    } elseif ($state == 'Completed' && strtotime($completion_date) < strtotime($starting_date)) {
        $errors['wrong_date'] = true;
    } 




    // Image handling
    $image = $_FILES['image'];

    if ($image['size'] > 0) {
        // Read image data
    // Insert data into the database, including the image
        $query = "INSERT INTO user_items (list_id, title, category, state, starting_date, completion_date, description, image, image_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);

        if ($state == 'Not Yet Started') {
            // Insert without starting and completion dates
            $stmt->execute([$selectedList, $newItemTitle, $category, $state, null, null, $newItemDescription, $imageData, $imageType]);
        } else {
            // Insert with starting and completion dates
            $stmt->execute([$selectedList, $newItemTitle, $category, $state, $starting_date, $completion_date, $newItemDescription, $imageData, $imageType]);
        }
    } else {
        // Insert data into the database without the image
        $query = "INSERT INTO user_items (list_id, item_name, category, state, starting_date, completion_date, item_description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);

        if ($state == 'Not Yet Started') {
            // Insert without starting and completion dates
            $stmt->execute([$selectedList, $newItemTitle, $category, $state, null, null, $newItemDescription]);
        } else {
            // Insert with starting and completion dates
            $stmt->execute([$selectedList, $newItemTitle, $category, $state, $starting_date, $completion_date, $newItemDescription]);
        }
    }

    header("Location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bucket List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <script src="./scripts/index_2.js"></script>
    <script src="./scripts/modal.js"></script>
    <script src="./scripts/Index_3.js"></script>
    <script src="./scripts/index_des.js"></script>

</head>


<body>
    <header>
        <h1>Bucket List</h1>
    </header>
    <div class="container">
        
    <?php include './includes/nav.php' ?>

        <main class="page-content">
            <div class="page-container">
                <!-- Modal Container -->
                <div id="viewItemModal" class="modal">
                                <div class="modal-content">
                                    <!-- Content will be loaded here dynamically -->
                                    <span class="close-modal" onclick="closeModal()">&times;</span>
                                    <div id="modal-content"></div>
                                </div>
                </div>
                 <!-- Display user lists from the database -->
                 <div id="existing_items">
                    <?php if (!empty($userLists)) : ?>
                        <?php foreach ($userLists as $list) : ?>
                            <h3><?php echo htmlspecialchars($list['list_name']); ?></h3>
                            <?php
                            // Fetch list items for the current user list
                            $listItemsQuery = "SELECT * FROM user_items WHERE list_id = ? ";
                            $listItemsStmt = $pdo->prepare($listItemsQuery);
                            $listItemsStmt->execute([$list['id']]);
                            $listItems = $listItemsStmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <?php if (!empty($listItems)) : ?>
                                <ul class="list-items">
                                    <?php foreach ($listItems as $item) : ?>
                                        <li>
                                            <strong>Title:</strong> <?php echo htmlspecialchars($item['item_name']); ?>
                                            <strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?>
                                            <strong>State:</strong> <?php echo htmlspecialchars($item['state']); ?>
                                            <strong>Starting Date:</strong> <?php echo htmlspecialchars($item['starting_date']); ?>
                                            <strong>Completion Date:</strong> <?php echo htmlspecialchars($item['completion_date']); ?>
                                            <strong>Description:</strong><span class="description"> <?php echo htmlspecialchars($item['item_description']); ?>

                                             <!-- Display Image -->
                                             <?php if ($item['image']) : ?>
                                                <label for="itemImage">Image:</label>
                                                <img src="data:image/<?php echo $item['image_type']; ?>;base64,<?php echo base64_encode($item['image']); ?>" alt="Item Image" />
                                                <?php endif; ?>
                                                <a <?php echo ($item['state'] === 'Completed') ? 'href="view-item.php?id=' . $item['id'] . '" class="view-item-link" data-modal-target="viewItemModal"' : 'href="view-item.php?id=' . $item['id'] . '"'; ?>>
                                                        View
                                                    </a>



                                                                                                <!-- Edit Link -->
                                            <a href="edit-item.php?id=<?php echo $item['id']; ?>">Edit</a>
                                            <!-- Delete Link -->
                                            <a href="delete-item.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>No items found for this list.</p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No user lists found.</p>
                    <?php endif; ?>
                </div>

                <div id="adding_new_item">
                    <h2>Add New Item</h2>
                    <!-- Form for adding a new item -->
                    <form id="addlist_form" method="POST" action="">
                        <label for="newItemTitle">Title:</label>
                        <input type="text" name="newItemTitle" id="newItemTitle" required>
                        <span class="error hidden" id="newItemTitleError"></span>

                        <fieldset class=category_list>
                            <legend>Category</legend>
                            <div>
                                <input type="radio" name="options" id="travel_adventure" value="Travel_and_Adventure" />
                                <label for="travel_adventure">Travel and Adventure</label>
                            </div>
                            <div>
                                <input type="radio" name="options" id="unique_experiences" value="Unique_Experiences" />
                                <label for="unique_experiences">Unique Experiences</label>
                            </div>
                            <div>
                                <input type="radio" name="options" id="financial_milestones"
                                    value="Financial_Milestones" />
                                <label for="financial_milestones">FinancialMilestones</label>
                            </div>
                            <div>
                                <input type="radio" name="options" id="career_success" value="Career_Success" />
                                <label for="career_success">Career Success</label>
                            </div>
                        </fieldset>

                        
                        <fieldset class="form-element">
                            <legend>State</legend>
                            <div>
                                <input type="radio" name="state" id="completed" value="Completed" <?= $state == 'Completed' ? 'checked' : '' ?>>
                                <label for="completed">Completed</label>
                            </div>

                            <div>
                                <input type="radio" name="state" id="in-progress" value="In Progress" <?= $state == 'In Progress' ? 'checked' : '' ?>>
                                <label for="in-progress">In Progress</label>
                            </div>
                            <div>
                                <input type="radio" name="state" id="not-started" value="Not Yet Started" <?= $state == 'Not Yet Started' ? 'checked' : '' ?> >
                                <label for="not-started">Not Started Yet</label>
                            </div>
                            <span class="error hidden" id="stateError"></span>
                        </fieldset>
                        
                        <fieldset class="form-element">
                            <legend>Dates</legend>
                            <div>
                                <label for="startingDate">Starting Date:</label> 
                                <input type="date" name="starting_date" id="starting_date" value="<?= $starting_date ?>">
                                <span class="error hidden" id="startingDateError"></span>

                            </div>

                            <div>
                                <label for="completionDate">Completion Date:</label>
                                <input type="date" name="completion_date" id="completion_date"  value="<?= $completion_date ?>">
                                <span class="error hidden" id="completionDateError"></span>
                            </div>

                            <label for="newItemDescription">Description:</label>
                        <textarea name="newItemDescription" id="newItemDescription" required></textarea>
                        <span class="error hidden" id="newItemDescriptionError"></span>
                        <span id="charCount">2500 characters remaining</span>
                        <!-- Dropdown for selecting the list -->
                        <div>
                            <label for="selectedList">Select List:</label>
                            <select name="selectedList" id="selectedList" required>
                                <?php foreach ($userListsForDropdown as $list) : ?>
                                    <option value="<?php echo $list['id']; ?>"><?php echo htmlspecialchars($list['list_name']); ?></option>
                                <?php endforeach; ?>
                                <option value="new_list">Create New List</option>
                            </select>
                            <span class="error hidden" id="selectedListError"></span>
                        </div>
                        <!-- Input fields for the new list (initially hidden) -->
                        <label for="newListName" id="newListNameLabel" style="display: none;">New List Name:</label>
                        <input type="text" name="newListName" id="newListName" style="display: none;">
                        <span class="error hidden" id="newListNameError"></span>
                        
                        <!-- Checkbox for public visibility -->
                        <label for="publicVisibility" id="publicVisibilityLabel" style="display: none;">Publicly Accessible:</label>
                        <input type="checkbox" name="publicVisibility" id="publicVisibility" value="Y" style="display: none;">

                        <button type="submit">Add</button>
                    </form>
                </div>

            </div>
        </main>
                      


</body>

</html>
```
```js
//Index_1
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const newListNameLabel = document.getElementById('newListNameLabel');
    const newListNameInput = document.getElementById('newListName');
    const newListDescriptionLabel = document.getElementById('newListDescriptionLabel');
    const newListDescriptionInput = document.getElementById('newListDescription');
    const publicVisibilityLabel = document.getElementById('publicVisibilityLabel');
    const publicVisibilityCheckbox = document.getElementById('publicVisibility');

    const selectedListDropdown = document.getElementById('selectedList');
    selectedListDropdown.addEventListener('change', function () {
        const selectedValue = selectedListDropdown.value;

        // Check if the selected value is 'new_list'
        if (selectedValue === 'new_list') {
            // Show the input fields for creating a new list
            newListNameLabel.style.display = 'block';
            newListNameInput.style.display = 'block';
            newListDescriptionLabel.style.display = 'block';
            newListDescriptionInput.style.display = 'block';
            publicVisibilityLabel.style.display = 'block';
            publicVisibilityCheckbox.style.display = 'block';
        } else {
            // Hide the input fields for creating a new list
            newListNameLabel.style.display = 'none';
            newListNameInput.style.display = 'none';
            newListDescriptionLabel.style.display = 'none';
            newListDescriptionInput.style.display = 'none';
            publicVisibilityLabel.style.display = 'none';
            publicVisibilityCheckbox.style.display = 'none';
        }
    });
    });
    ```
    ###
    ```js
    //index_2.js
    "use strict";

document.addEventListener("DOMContentLoaded", function () {
  // Select all anchor tags for viewing completed items
  const viewItemLinks = document.querySelectorAll(".view-item-link");

  // Add event listener to each link
  viewItemLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      // Fetch the URL from the link's href attribute
      const url = this.getAttribute("href");

      // Fetch the item details and display in the modal window
      fetch(url)
        .then(function (response) {
          return response.text();
        })
        .then(function (data) {
          // Display the content in the modal
          document.getElementById("modal-content").innerHTML = data;

          // Show the modal
          document.getElementById("viewItemModal").style.display = "block";

          // Hide the header
          const header = document.getElementById("main-header");
          if (header) {
            header.style.display = "none";
          }

          // Hide the footer
          const footer = document.querySelector("footer");
          if (footer) {
            footer.style.display = "none";
          }
        })
        .catch(function (error) {
          console.error("Error fetching item details:", error);
        });
    });
  });

  // Function to close the modal
  window.closeModal = function () {
    document.getElementById("viewItemModal").style.display = "none";

    // Show the header
    const header = document.getElementById("main-header");
    if (header) {
      header.style.display = "block";
    }

    // Show the footer
    const footer = document.querySelector("footer");
    if (footer) {
      footer.style.display = "block";
    }
  };
});
```
```js
//modal.js
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('myModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.querySelector('.close');
    const modalContent = document.getElementById('modalContent');

    openModalBtn.addEventListener('click', function () {
        // Use AJAX to load content into the modal
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                modalContent.innerHTML = this.responseText;
                modal.style.display = 'block';
            }
        };
        xhr.open("GET", "modal-content.php", true);
        xhr.send();
    });

    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Close the modal if the user clicks outside the modal content
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
```
```js
//index_des.js
document.addEventListener("DOMContentLoaded", function () {
    const descInput = document.getElementById("newItemDescription");
    const charCountSpan = document.getElementById("charCount");
    const maxChars = 2500;

    descInput.addEventListener("input", function () {
        const remainingChars = maxChars - descInput.value.length;
        charCountSpan.innerText = `Characters left: ${remainingChars}`;

        if (remainingChars < 50) {
            charCountSpan.style.color = "red";
        } else {
            charCountSpan.style.color = "blue";
        }

        // Validate word limit
        const words = descInput.value.trim().split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;
        const wordLimit = 200; // You can adjust the word limit

        if (wordCount > wordLimit) {
            showError("newItemDescriptionError", "Description exceeds the word limit");
        } else {
            resetError("newItemDescriptionError");
        }
    });

    function showError(spanId, errorMessage) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = errorMessage;
        errorSpan.style.color = "red";
    }

    function resetError(spanId) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = "";
    }
});
```

### Modal WIndow pop up



## Before clicking view

<img src="./img/modal1.png" alt="strong pass" >

## After clicking view
<img src="./img/modal2.png" alt="strong pass" >

## list which not in Completed state
<img src="./img/modal3.png" alt="strong pass" >



### **Public page 

```php
<?php
session_start();
require("./includes/library.php");
$pdo = connectDB();

// Fetch public lists from the database
$publicListsQuery = "SELECT * FROM user_lists WHERE public_visibility = 'Y'";
$publicListsStmt = $pdo->query($publicListsQuery);
$publicLists = $publicListsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bucket List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <script src="./scripts/publis.js"></script>
</head>

<body>
    <?php include './includes/header.php' ?>

    <h2 id="view-as-public-title">View As Public</h2>

    <div class="container">
        <?php include './includes/nav.php' ?>
        <main class="page-content">
            <div class="page-container">
                <!-- Modal Container -->
                <div id="viewItemModal" class="modal">
                    <div class="modal-content">
                        <!-- Content will be loaded here dynamically -->
                        <span class="close-modal" onclick="closeModal()">&times;</span>
                        <div id="modal-content"></div>
                    </div>
                </div>
                <?php foreach ($publicLists as $list) : ?>
                    <details>
                        <summary>
                            <h2><?php echo htmlspecialchars($list['list_name']); ?> <i class="fa fa-caret-down"
                                    aria-hidden="true"></i></h2>
                        </summary>
                        <section id="section-<?php echo strtolower(str_replace(' ', '_', $list['list_name'])) . '_' . $list['id']; ?>">
                            <h3>Details of List</h3>
                            <p><?php echo htmlspecialchars($list['list_desc']); ?></p>
                            <?php
                            // Fetch list items for the current user list
                            $listItemsQuery = "SELECT * FROM user_items WHERE list_id = ?";
                            $listItemsStmt = $pdo->prepare($listItemsQuery);
                            $listItemsStmt->execute([$list['id']]);
                            $listItems = $listItemsStmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <?php if (!empty($listItems)) : ?>
                                <ul class="list-items">
                                    <?php foreach ($listItems as $item) : ?>
                                        <li>
                                            <?php echo htmlspecialchars($item['item_name']); ?>
                                            <strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?>
                                            <strong>State:</strong> <?php echo htmlspecialchars($item['state']); ?>
                                            <strong>Starting Date:</strong> <?php echo htmlspecialchars($item['starting_date']); ?>
                                            <strong>Completion Date:</strong> <?php echo htmlspecialchars($item['completion_date']); ?>
                                            <strong>Description:</strong><span
                                                class="description"> <?php echo htmlspecialchars($item['item_description']); ?></span>
                                                <?php if ($item['state'] === 'Completed') : ?>
    <a href="#" class="view-item-link" data-modal-target="viewItemModal" onclick="openModal(<?php echo $item['id']; ?>)">View</a>
<?php else : ?>
    <a href="view-item.php?id=<?php echo $item['id']; ?>">View</a>
<?php endif; ?>


                                            <?php if ($item['image']) : ?>
                                                <label for="itemImage_<?php echo $item['id']; ?>">Image:</label>
                                                <input type="file" id="itemImage_<?php echo $item['id']; ?>"
                                                    name="itemImage">
                                                <img
                                                    src="data:image/<?php echo $item['image_type']; ?>;base64,<?php echo base64_encode($item['image']); ?>"
                                                    alt="Item Image" />
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>No items found for this list.</p>
                            <?php endif; ?>
                        </section>
                    </details>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    <?php include './includes/footer.php'?>
</body>

</html>
```
```js
//publis.js

"use strict";
function openModal(itemId) {
    // Use AJAX to load content into the modal
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById('modal-content').innerHTML = this.responseText;
            document.getElementById('viewItemModal').style.display = 'block';
        }
    };
    xhr.open("GET", "view-item.php?id=" + itemId, true);
    xhr.send();
}
```


### **Delete Account
```php
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
```
```js
//delete-account.js
"use strict";
document.addEventListener("DOMContentLoaded", function () {
    var deleteForm = document.getElementById("page-form");

    if (deleteForm) {
        deleteForm.addEventListener("submit", function (event) {
            // Display a confirmation dialog before submitting the form
            var isConfirmed = confirm("Are you sure you want to delete your account?");
            
            if (!isConfirmed) {
                // Prevent the form from being submitted if the user cancels
                event.preventDefault();
            }
        });
    }
});
```

### validation

## before delete
<img src="./img/deleteac1.png" alt="strong pass" >

## before when click to delete
<img src="./img/deleteac2.png" alt="strong pass" >

## datebase after deleting
<img src="./img/deleteac3.png" alt="strong pass" >
<img src="./img/public3.png" alt="strong pass" >


### All the validation in mac
<img src="./img/deleteac3.png" alt="strong pass" >