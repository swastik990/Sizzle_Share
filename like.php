<?php
session_start();
include 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You must be logged in to like a recipe.']);
    exit;
}

// Get the recipe ID from the POST request
$recipeId = $_POST['recipe_id'] ?? null;

if (!$recipeId) {
    echo json_encode(['error' => 'Invalid recipe ID.']);
    exit;
}

// Get the current user ID
$userId = $_SESSION['user_id'];

try {
    // Debugging: Log the inputs
    error_log("User ID: $userId, Recipe ID: $recipeId");

    // Check if the user has already liked the recipe
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = :user_id AND recipe_id = :recipe_id");
    $stmt->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);
    $likeExists = $stmt->fetch();

    if ($likeExists) {
        // Unlike the recipe (delete the like)
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND recipe_id = :recipe_id");
        $stmt->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);
        error_log("Unlike successful for User ID: $userId, Recipe ID: $recipeId");
    } else {
        // Like the recipe (insert a new like)
        $stmt = $conn->prepare("INSERT INTO likes (user_id, recipe_id) VALUES (:user_id, :recipe_id)");
        $stmt->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);
        error_log("Like successful for User ID: $userId, Recipe ID: $recipeId");
    }

    // Get the updated like count for the recipe
    $stmt = $conn->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE recipe_id = :recipe_id");
    $stmt->execute(['recipe_id' => $recipeId]);
    $likeCount = $stmt->fetch(PDO::FETCH_ASSOC)['like_count'];

    // Return the updated like count as JSON
    echo json_encode(['new_like_count' => $likeCount]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(['error' => 'An error occurred while processing your request.']);
}