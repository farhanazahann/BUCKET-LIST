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
