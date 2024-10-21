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
