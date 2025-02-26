<?php
include('includes/header.php');
include('includes/db.php'); // Include the database connection

$error_message = ''; // To store error messages
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $description = trim($_POST['description']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;


    // Validate user login and form data
    if (!$user_id) {
        $error_message = "You must be logged in to submit feedback.";
    } elseif (empty($description)) {
        $error_message = "Description cannot be empty.";
    } elseif ($rating < 1 || $rating > 5) {
        $error_message = "Rating must be between 1 and 5.";
    }

    // If no errors, proceed with inserting the feedback into the database
    if (empty($error_message)) {
        try {
            // Prepare the SQL query to insert the feedback
            $sql = "INSERT INTO feedback (user_id, description, rating) VALUES (:user_id, :description, :rating)";
            
            // Prepare statement
            $stmt = $conn->prepare($sql);

            // Bind parameters to statement
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            
            // Execute the query
            $stmt->execute();

            if (($stmt->execute() === true) === true) {
                $success_message = "Submitted Successfully.";
            } else {

            }

        } catch (PDOException $e) {
            // Handle database connection errors
            $error_message = "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<section class="feedback-section">
    <div class="container">
        <h1>Leave Your Feedback</h1>
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <form action="feedback.php" method="POST" class="feedback-form">
            <!-- Hidden input for User ID -->
            <?php if ($user_id): ?>
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <?php endif; ?>

            <!-- Star Rating -->
            <div class="form-group">
                <label for="rating" style="font-size: 1.2rem; font-weight: bold;">Rate Us (1 to 5 Stars)</label>
                <div class="star-rating d-flex justify-content-center">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                </div>
            </div>

            <!-- Feedback Description -->
            <div class="form-group">
                <label for="description" style="font-size: 1.2rem; font-weight: bold;">Your Feedback</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Write your feedback here..." required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg me-2">Submit Feedback</button>
            </div>
        </form>
    </div>
</section>

<!-- CSS -->
<style>
    /* Additional styling for the star rating */
    .star-rating {
        display: flex;
        justify-content: center;
        gap: 10px;
        direction: rtl;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 2rem;
        color: #333333;
        cursor: pointer;
    }

    .star-rating input:checked ~ label {
        color: #ffcc00;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffcc00;
    }

    /* Reverse the hover effect */
    .star-rating label:hover,
    .star-rating input:checked ~ label:hover {
        color: #ffcc00;
    }

    /* Styling for form heading */
    h1 {
        color: #ffcc66;
        font-size: 2.5rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php include('includes/footer.php'); ?>

</body>
</html>