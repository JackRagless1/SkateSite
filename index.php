
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateBox</title>

    <!-- Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Keep footer at bottom of page */
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
        }

        /* Navbar */
        .navbar {
            padding: 15px 0;
        }

        .navbar-brand {
            letter-spacing: 1px;
        }

        #navbarContent {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .navbar-nav {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .navbar-nav .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 5px;
            min-width: 180px;
            z-index: 1000;
        }

        /* Welcome section */
        .welcome {
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .welcome h1 {
            font-size: 45px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .welcome p {
            font-size: 18px;
            color: #ced4da;
            margin-bottom: 30px;
        }

        /* Search bar */
        .search-section {
            background-color: #e9ecef;
            padding: 20px;
        }

        .search-form {
            width: 600px;
            margin: auto;
        }

        /* Footer */
        footer {
            background-color: #212529;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">SkateBox</a>

            <div id="navbarContent">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="products.php?category=0">Decks</a></li>
                            <li><a class="dropdown-item" href="products.php?category=1">Trucks</a></li>
                            <li><a class="dropdown-item" href="products.php?category=2">Wheels</a></li>
                            <li><a class="dropdown-item" href="products.php?category=3">Bearings</a></li>
                            <li><a class="dropdown-item" href="products.php?category=4">Grip Tape</a></li>
                            <li><a class="dropdown-item" href="products.php?category=5">Accessories</a></li>
                            <li><a class="dropdown-item" href="products.php?category=6">Boards</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="products.php">All Products</a>
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="wishlist.php" class="btn btn-outline-light">Wishlist</a>
                    <a href="basket.php" class="btn btn-outline-light">Basket</a>
                    <a href="login.php" class="btn btn-warning">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <!-- Search bar -->
        <section class="search-section">
            <div class="search-form">
                <form action="search.php" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search for skateboard parts..." required>
                        <button type="submit" class="btn btn-dark">Search</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- welcome thing -->
        <section class="welcome">
            <div class="container">
                <h1>Welcome to SkateBox</h1>
                <p>Shop for all your skateboard needs in one place.</p>
                <a href="products.php" class="btn btn-warning btn-lg">Browse Products</a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p class="mb-0">&copy; 2026 SkateBox. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
