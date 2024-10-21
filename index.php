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