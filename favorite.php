
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_id'])) {
    $recipeId = $_POST['recipe_id'];
    $stmt = $conn->prepare("SELECT 1 FROM Favorites WHERE user_id = :user_id AND recipe_id = :recipe_id");
    $stmt->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);

    if ($stmt->fetch()) {
        $conn->prepare("DELETE FROM Favorites WHERE user_id = :user_id AND recipe_id = :recipe_id")
            ->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);
        echo json_encode(['status' => 'removed']);
    } else {
        $conn->prepare("INSERT INTO Favorites (user_id, recipe_id) VALUES (:user_id, :recipe_id)")
            ->execute(['user_id' => $userId, 'recipe_id' => $recipeId]);
        echo json_encode(['status' => 'added']);
    }
    exit;
}
?>

