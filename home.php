    <title>Home | SizzleShare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">

<?php
include('includes/db.php');
include('includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetching all recipes along with like counts 
$sql = "SELECT 
            r.*, 
            u.first_name, 
            u.last_name, 
            (SELECT COUNT(*) FROM likes l WHERE l.recipe_id = r.recipe_id) AS like_count,
            CASE 
                WHEN l.user_id IS NOT NULL THEN 1 
                ELSE 0 
            END AS is_liked,
            CASE 
                WHEN f.user_id IS NOT NULL THEN 1 
                ELSE 0 
            END AS is_favorited
        FROM Recipes r 
        JOIN Users u ON r.user_id = u.user_id 
        LEFT JOIN likes l ON l.user_id = :current_user_id AND l.recipe_id = r.recipe_id 
        LEFT JOIN Favorites f ON f.user_id = :current_user_id AND f.recipe_id = r.recipe_id 
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);

// Bind the current user ID to the placeholder
if (isset($_SESSION['user_id'])) {
    $stmt->bindValue(':current_user_id', $_SESSION['user_id'], PDO::PARAM_INT);
} else {
    $stmt->bindValue(':current_user_id', 0, PDO::PARAM_INT); // Default for guests
}

try {
    $stmt->execute();
    $allRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all recipes
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $allRecipes = []; // Initialize as an empty array if there's an error
}

// Handle search
$searchResults = [];
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchQuery = htmlspecialchars($_GET['search']);
    foreach ($allRecipes as $recipe) {
        if (stripos($recipe['title'], $searchQuery) !== false) {
            $searchResults[] = $recipe;
        }
    }
}

// Separate recipes into "Most Popular" and "Recently Uploaded"
$mostPopularRecipes = array_slice($allRecipes ?? [], 0, 5); // Use null coalescing operator to handle null values
usort($mostPopularRecipes, function ($a, $b) {
    return $b['like_count'] <=> $a['like_count']; // Sort by like count descending
});

$recentRecipes = array_slice($allRecipes ?? [], 0, 5); // Use null coalescing operator to handle null values
usort($recentRecipes, function ($a, $b) {
    return strtotime($b['created_at']) <=> strtotime($a['created_at']); // Sort by creation date descending
});
?>

<div class="row d-flex justify-content-left my-5">
    <h1 class="mb-4 text-center fw-bold">Explore Recipes</h1>

    <!-- Search Bar -->
    <form method="GET" class="mb-4 d-flex justify-content-center">
        <input type="text" name="search" placeholder="Search recipes..." class="form-control w-50" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary ms-2">
            <i class="fas fa-search me-1"></i> <!-- FontAwesome search icon -->
            Search
        </button>
    </form>

    <!-- Features -->
    <div class="d-flex justify-content-center gap-5 mb-5">
        <!-- Upload Recipe Button -->
        <a href="upload.php" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-upload me-2"></i> <!-- FontAwesome upload icon -->
            <span>Upload Recipe</span>
        </a>
        <!-- Manage Recipe Button -->
        <a href="manage_uploads.php" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-edit me-2"></i> <!-- FontAwesome edit icon -->
            <span>Manage Recipes</span>
        </a>
        <!-- Favorite Recipes Button -->
        <a href="show_favorite.php" class="btn btn-info d-flex align-items-center">
            <i class="fas fa-heart me-2"></i> <!-- FontAwesome heart icon for favorites -->
            <span>Favorites</span>
        </a>
        </a>
        <!-- Community Chat Button -->
        <a href="group_chat.php" class="btn btn-info d-flex align-items-center">
            <i class="fas fa-comments me-2"></i> <!-- FontAwesome comments icon -->
            <span>Community Chat</span>
        </a>

    </div>

        <!-- Searched Recipes (only If applicable) -->
        <?php if (!empty($searchResults)): ?>
        <div class="mt-5">
            <h2 class="mb-4 text-center fw-bold">Search Results</h2>
            <div class="d-flex flex-column align-items-center">
                <?php foreach ($searchResults as $recipe): ?>
                    <div class="card mb-4 shadow" style="width: 40rem;">
                        <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" class="card-img-top" alt="Recipe Image" style="height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                            <p class="text-muted">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                            <form id="likeForm_<?php echo $recipe['recipe_id']; ?>" class="d-inline">
                                <input type="hidden" name="recipe_id" value='<?php echo htmlspecialchars($recipe['recipe_id']); ?>'>
                                <button type="button" class="btn <?php echo $recipe['is_liked'] ? 'btn-success' : 'btn-outline-primary'; ?> like-btn" data-recipe-id="<?php echo $recipe['recipe_id']; ?>">
                                    <i class="fas fa-thumbs-up"></i> <span class="like-count"><?php echo $recipe['like_count']; ?></span> Like(s)
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

<!-- Recipes Display -->
<div class="row">
    <!-- Most Popular Recipes -->
<div class="col-md-7">
    <h2 class="mb-4 text-center fw-bold">Most Popular Recipes</h2>
    <div class="d-flex flex-column align-items-center justify-content-center">
        <?php if (!empty($mostPopularRecipes)): ?>
            <?php foreach ($mostPopularRecipes as $recipe): ?>
                <div class="card mb-4 shadow" style="width: 35rem;">
                    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" class="card-img-top" alt="Recipe Image" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                        <p class="text-muted">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

                        <!-- Buttons -->
                        <div class="d-flex gap-4">
                            <!-- Like Button -->
                            <form id="likeForm_<?php echo $recipe['recipe_id']; ?>" class="d-inline">
                                <input type="hidden" name="recipe_id" value='<?php echo htmlspecialchars($recipe['recipe_id']); ?>'>
                                <button type="button" class="btn <?php echo $recipe['is_liked'] ? 'btn-success' : 'btn-outline-primary'; ?> like-btn" data-recipe-id="<?php echo $recipe['recipe_id']; ?>">
                                    <i class="fas fa-thumbs-up"></i> <span class="like-count"><?php echo $recipe['like_count']; ?></span> Like(s)
                                </button>
                            </form>

                            <!-- Favorite Button -->
                            <form id="favoriteForm_<?php echo $recipe['recipe_id']; ?>" class="d-inline">
                                <input type="hidden" name="recipe_id" value='<?php echo htmlspecialchars($recipe['recipe_id']); ?>'>
                                <button type="button" class="btn <?php echo $recipe['is_favorited'] ?> favorite-btn" data-recipe-id="<?php echo $recipe['recipe_id']; ?>">
                                    <i class="fas fa-heart"></i> <span class="favorite-text"><?php echo $recipe['is_favorited'] ? 'Favorited' : 'Favorite'; ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No popular recipes available.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Recently Uploaded Recipes -->
<div class="col-md-5">
    <h2 class="mb-4 text-center fw-bold">Recently Uploaded</h2>
    <div class="d-flex flex-column align-items-center justify-content-center">
        <?php if (!empty($recentRecipes)): ?>
            <?php foreach ($recentRecipes as $recipe): ?>
                <div class="card mb-3 shadow" style="width: 25rem;">
                    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" class="card-img-top" alt="Recipe Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($recipe['title']); ?></h5>
                        <p class="text-muted">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

                        <!-- Buttons -->
                        <div class="d-flex gap-4">
                            <!-- Like Button -->
                            <form id="likeForm_<?php echo $recipe['recipe_id']; ?>" class="d-inline">
                                <input type="hidden" name="recipe_id" value='<?php echo htmlspecialchars($recipe['recipe_id']); ?>'>
                                <button type="button" class="btn <?php echo $recipe['is_liked'] ? 'btn-success' : 'btn-outline-primary'; ?> like-btn" data-recipe-id="<?php echo $recipe['recipe_id']; ?>">
                                    <i class="fas fa-thumbs-up"></i> <span class="like-count"><?php echo $recipe['like_count']; ?></span> Like(s)
                                </button>
                            </form>

                            <!-- Favorite Button -->
                            <form id="favoriteForm_<?php echo $recipe['recipe_id']; ?>" class="d-inline">
                                <input type="hidden" name="recipe_id" value='<?php echo htmlspecialchars($recipe['recipe_id']); ?>'>
                                <button type="button" class="btn <?php echo $recipe['is_favorited'] ?> favorite-btn" data-recipe-id="<?php echo $recipe['recipe_id']; ?>">
                                    <i class="fas fa-heart"></i> <span class="favorite-text"><?php echo $recipe['is_favorited'] ? 'Favorited' : 'Favorite'; ?></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No recent recipes available.</p>
        <?php endif; ?>
        </div>
    </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Handling Real-Time Like Updates -->
<script>
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();  // Prevent form submission
            const recipeId = this.getAttribute('data-recipe-id');
            const likeCountSpan = this.querySelector('.like-count');
            const button = this;

            console.log('Recipe ID:', recipeId);

            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'like.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                console.log('AJAX Response:', xhr.responseText);
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.error) {
                            alert(response.error);  // Display error if something went wrong
                        } else {
                            // Update the like count on the button
                            likeCountSpan.textContent = response.new_like_count;
                            button.classList.toggle('btn-success');  // Toggle button color to green
                        }
                    } catch (e) {
                        console.error('Invalid JSON Response:', e);
                    }
                } else {
                    alert('Something went wrong, please try again later.');
                }
            };

            // Send the recipe_id in the POST request
            xhr.send('recipe_id=' + encodeURIComponent(recipeId));
        });
    });
</script>
<!-- for favorite button -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        if (button.dataset.isFavorited === 'true') {
            button.classList.add('btn-danger');
        }

        button.addEventListener('click', async () => {
            const recipeId = button.dataset.recipeId;
            const response = await fetch('favorite.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `recipe_id=${recipeId}`
            });
            const result = await response.json();

            button.classList.toggle('btn-danger', result.status === 'added');
            button.querySelector('.favorite-text').innerText = result.status === 'added' ? 'Favorited' : 'Favorite';
        });
    });
});
</script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
