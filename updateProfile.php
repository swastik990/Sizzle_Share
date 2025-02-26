<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    
    // Handle profile photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $target_dir = "assets/images/";
        $file_name = basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "File is not an image.";
            header("Location: profile.php");
            exit;
        }

        // Allow only certain formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: profile.php");
            exit;
        }

        // Move the uploaded file
        if (!move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $_SESSION['error'] = "Failed to upload profile picture.";
            header("Location: profile.php");
            exit;
        }

        // Update database with new profile photo
        $sql = "UPDATE users SET profile_photo = :profile_photo WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['profile_photo' => $file_name, 'user_id' => $user_id]);
    }

    // Update user info
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'user_id' => $user_id
    ]);
    
    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: profile.php?success=1");
    exit;

} else {
    header("Location: profile.php");
    exit;
}
?>
