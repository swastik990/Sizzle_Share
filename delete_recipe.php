<?php
session_start();
require_once 'includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Handle deletion
if (isset($_GET['recipe_id'])) {
    $recipeId = $_GET['recipe_id'];

    // Ensure the recipe belongs to the logged-in user
    $sql = "DELETE FROM Recipes WHERE recipe_id = :recipe_id AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':recipe_id' => $recipeId, ':user_id' => $userId]);

    // Redirect after deletion
    header("Location: manage_uploads.php");
    exit;
} else {
    echo "No recipe selected.";
    exit;
}
?>
