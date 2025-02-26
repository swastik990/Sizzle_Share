<?php
    
include('includes/db.php');
include('includes/header.php'); 
 

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch the recipes uploaded by the logged-in user
$sql = "SELECT * FROM Recipes WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $userId]);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


    <title>Manage Uploads</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    

    <div class="container my-5">
        <h1 class="mb-4 text-center fw-bold">Manage Your Uploaded Recipes</h1>

        <!-- Display Recipes -->
        <?php if (count($recipes) > 0): ?>
            <div class="list-group">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <h5 class="mb-1"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                        <div>
                            <a href="edit_recipe.php?recipe_id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_recipe.php?recipe_id=<?php echo $recipe['recipe_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">You have not uploaded any recipes yet.</p>
        <?php endif; ?>

    </div>

    <?php include('includes/footer.php'); ?>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

