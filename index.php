
    <title>SizzleShare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">

    <?php 
    include 'includes/header.php'; 
    ?>

    <div class="container mt-5">
        <div class="jumbotron d-flex justify-content-center align-items-center">
            <div>
            <h1 class="display-4">Welcome to <span style="color: #F5A623">SizzleShare</span></h1>
            <p class="lead">Discover and share amazing recipes from around the world.</p>
            <hr class="my-4">
            <p>Join our community and start sharing your favorite recipes today!</p>
            <div class="d-flex mt-4">
            <a class="btn btn-primary btn-lg me-2" href="login.php" role="button">Login</a>
            <a class="btn btn-secondary btn-lg me-2" href="about.php" role="button">Learn More</a>
            
            </div>
            </div>
            <img src="imgs/banner.png" alt="Quote Image" class="img-fluid ms-3" style="max-width: 500px;">
        </div>
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4">Our Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/banner1.png" class="card-img-top" alt="Delicious Recipes">
                    <div class="card-body">
                        <h5 class="card-title">Delicious Recipes</h5>
                        <p class="card-text">Explore a wide variety of delicious recipes shared by our community.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/banner2.png" class="card-img-top" alt="Easy to Follow">
                    <div class="card-body">
                        <h5 class="card-title">Easy to Follow</h5>
                        <p class="card-text">Our recipes are easy to follow, with step-by-step instructions and tips.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/banner3.png" class="card-img-top" alt="Join the Community">
                    <div class="card-body">
                        <h5 class="card-title">Join the Community</h5>
                        <p class="card-text">Join our community and start sharing your favorite recipes today!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4">Our Popular Recipes</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/banana_shake.png" class="card-img-top" alt="banana">
                    <div class="card-body">
                        <h5 class="card-title">Banana Shake</h5>
                        <p class="card-text">A refreshing drink made by blending ripe bananas with yogurt, milk, and honey. Add ice for a chilled smoothie, or throw in some berries for added flavor. Perfect for breakfast or a snack!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/grill_cheese.png" class="card-img-top" alt="Sandwich">
                    <div class="card-body">
                        <h5 class="card-title">Grill Cheese Sandwich</h5>
                        <p class="card-text">A classic comfort food made by placing cheese between two slices of bread, then grilling it until the bread is golden brown and the cheese is melted. Serve with a side of tomato soup for a perfect meal.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <img src="imgs/eggs.png" class="card-img-top" alt="Scrambled Eggs">
                    <div class="card-body">
                        <h5 class="card-title">Scrambled Eggs</h5>
                        <p class="card-text">A quick and easy breakfast made with beaten eggs, cooked in butter until fluffy and soft. Add salt, pepper, and your choice of toppings like cheese or herbs for extra flavor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
