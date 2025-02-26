<?php
include('includes/header.php');
require_once 'includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch recipe details
if (isset($_GET['recipe_id'])) {
    $recipeId = $_GET['recipe_id'];
    $stmt = $conn->prepare("SELECT * FROM Recipes WHERE recipe_id = :recipe_id AND user_id = :user_id");
    $stmt->execute([':recipe_id' => $recipeId, ':user_id' => $userId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        echo "Recipe not found or unauthorized access.";
        exit;
    }
} else {
    echo "No recipe selected.";
    exit;
}

// Handling form submission to update recipe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Handle image upload
    if (!empty($_FILES['image_url']['name'])) {
        $imagePath = 'imgs/recipe_images' . basename($_FILES['image_url']['name']);
        move_uploaded_file($_FILES['image_url']['tmp_name'], $imagePath);
    } else {
        $imagePath = $recipe['image_url'];
    }

    $updateSql = "UPDATE Recipes SET title = :title, description = :description, image_url = :image_url WHERE recipe_id = :recipe_id AND user_id = :user_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->execute([':title' => $title, ':description' => $description, ':image_url' => $imagePath, ':recipe_id' => $recipeId, ':user_id' => $userId]);

    header("Location: manage_uploads.php");
    exit;
}
?>

    <title>Edit Recipe | SizzleShare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <div class="container my-5">
        <h1 class="text-center mb-4">Edit Recipe</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image_url" class="form-label">Upload New Image (Leave blank to keep current)</label>
                <input type="file" class="form-control" id="image_url" name="image_url">
            </div>
            <p>Current Image:</p>
            <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="Current Recipe Image" class="img-fluid mb-3" style="max-height: 300px;">
            <button type="submit" class="btn">Update Recipe</button>
        </form>
    </div>
    <?php include('includes/footer.php'); ?>
