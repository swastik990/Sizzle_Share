<?php
include('includes/header.php');
include('includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; 

// Prepare and execute the query to get favorite recipes
$sql = "SELECT r.recipe_id, r.title, r.description, r.image_url, u.first_name, u.last_name
        FROM Favorites f
        JOIN Recipes r ON f.recipe_id = r.recipe_id
        JOIN Users u ON r.user_id = u.user_id
        WHERE f.user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle deleting favorite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_favorite'])) {
    $recipe_id = $_POST['recipe_id'];
    
    // Corrected DELETE query
    $sql = 'DELETE FROM Favorites WHERE user_id = :user_id AND recipe_id = :recipe_id';
    $deleteStmt = $conn->prepare($sql);
    $deleteStmt->execute(['user_id' => $user_id, 'recipe_id' => $recipe_id]);
    
    // Redirect to refresh the page after deleting
    header("Location: show_favorite.php");
    exit();
}

?>


    <title>My Favorites</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <div class="container mt-5">
        <h2 class="mb-4 text-center fw-bold">My Favorite Recipes</h2>
        <div class="row">
            <?php if (!empty($favorites)): ?>
                <?php foreach ($favorites as $recipe): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" class="card-img-top" alt="Recipe Image" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                                <p class="text-muted">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></p>
                                <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                                
                                <!-- Delete Button -->
                                <form action="show_favorite.php" method="POST">
                                    <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe['recipe_id']); ?>">
                                    <button type="submit" name="delete_favorite" class="btn btn-danger">
                                        <i class="fas fa-heart-broken"></i> Remove from Favorites
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">You have no favorite recipes yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php include('includes/footer.php'); ?>
