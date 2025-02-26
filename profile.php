
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet"> <!-- Custom CSS -->

<?php

include('includes/header.php');
include('includes/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$profile_photo = !empty($user['profile_photo']) ? "assets/images/" . $user['profile_photo'] : "assets/images/default.png";
?>

<section class="container mt-5">
    
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= $profile_photo ?>" alt="Profile Photo" class="rounded-circle img-fluid" style="width: 200px; height:200px;">
                    <h5 class="my-3"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                    <div class="d-flex justify-content-center mb-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit</button>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Full Name</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Email</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3"><p class="mb-0">Phone</p></div>
                        <div class="col-sm-9"><p class="text-muted mb-0"><?= htmlspecialchars($user['phone']) ?></p></div>
                    </div>
                </div>
                <?php 
                if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>Success!</strong> Profile updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</section>




<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="updateProfile.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include('includes/footer.php'); ?>

