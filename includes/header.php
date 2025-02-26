<?php
session_start();

// Default values for user data
$user_id = null;
$user_first_name = 'Guest';
$user_profile_picture = 'assets/images/default.png';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in
    $user_id = $_SESSION['user_id'];

    // Include database connection
    include('includes/db.php');

    // Query user data from database
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Set user data
        $user_first_name = htmlspecialchars($user['first_name']);
        $user_profile_picture = !empty($user['profile_photo']) ? "assets/images/" . $user['profile_photo'] : "assets/images/default.png";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SizzleShare</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg custom-navbar">
            <div class="container mt-6">
                <div class="container-fluid">
                    <!-- Show Default Logo or User Info -->
                    <?php if ($user_id): ?>
                        <!-- User is logged in -->
                        <a class="navbar-brand text-white" href="profile.php">
                            <img src="<?= $user_profile_picture ?>" alt="Profile Photo" class="rounded-circle" width="40" height="40">
                            <?= $user_first_name ?>
                        </a>
                    <?php else: ?>
                        <!-- User is not logged in -->
                        <a class="navbar-brand" href="index.php">
                            <img src="imgs/fulllogo.png" alt="Logo" width="100" height="50">
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Toggler Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="feedback.php">Feedback</a>
                        </li>
                        <?php if ($user_id): ?>
                        <li class="nav-item">
                            <!-- Settings Button -->
                            <a href="profile.php" class="btn btn-secondary me-2">
                                <i class="fas fa-cog"></i> <!-- FontAwesome settings icon -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- Logout Button -->
                            <a href="logout.php" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> <!-- FontAwesome logout icon -->
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
