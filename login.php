    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet"> <!-- Link to custom CSS -->

<?php
include('includes/db.php');
include('includes/header.php');

// Check if the user is already logged in and redirect to the profile page
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare the query to find the user
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and set user information
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                
                // Redirect to profile page after successful login
                header("Location: home.php");
                exit;
            } else {
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            $error_message = "User not found. Please check your email or register.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>


<div class="container justify-content-center mt-5">
    <div class="login-form" style="max-width: 400px; margin: 0 auto; background-color:rgb(255, 255, 255); padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 class="text-center mb-4">Login</h2>

        <?php if (isset($_GET['message'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_GET['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>


        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <form action="login.php" method="POST">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-50 mx-auto d-block">Login</button>
        </form>

        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>



<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>







