<?php
session_start();
include('includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);

    // Handle image upload
    $target_dir = "imgs/recipe_images/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name;
    $uploadOk = move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    if ($uploadOk) {
        // Insert recipe into database
        $sql = "INSERT INTO Recipes (user_id, title, description, image_url) 
                VALUES (:user_id, :title, :description, :image_url)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'image_url' => $target_file
        ]);

        // Redirect to home page with success message
        $_SESSION['success'] = "Recipe uploaded successfully!";
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['error'] = "Image upload failed!";
        header("Location: upload.php");
        exit();
    }
}
