
    <title>upload</title>
</head>
<body>

<?php

include('includes/header.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<section class="container mt-5">
    <h2 class="mb-4">Upload Your Recipe</h2>
    <form action="upload_process.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Recipe Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn">Submit Recipe</button>
    </form>
</section>

<?php include('includes/footer.php'); ?>
    
</body>
</html>

