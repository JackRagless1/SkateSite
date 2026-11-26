
<?php
session_start();
require_once 'connection.php';
//find bestselling products

$sql = "SELECT tblitems.itemID, tblitems.itemName, tblitems.descrip, tblitems.price,
        tblphotos.photoLink,
        SUM(tblorderitems.quantity) AS unitsSold
        FROM tblitems
        INNER JOIN tblorderitems ON tblitems.itemID = tblorderitems.itemID
        LEFT JOIN tblitemhasphoto ON tblitems.itemID = tblitemhasphoto.itemID
        LEFT JOIN tblphotos ON tblitemhasphoto.photoID = tblphotos.photoID
        GROUP BY tblitems.itemID, tblitems.itemName, tblitems.descrip,
                 tblitems.price, tblphotos.photoLink
        ORDER BY unitsSold DESC
        LIMIT 4";

$stmt = $conn->prepare($sql);
$stmt->execute();
$bestSellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkateBox</title>

    <!-- Bootstrap CSS: https://www.w3schools.com/bootstrap5/ -->
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

        /* Bestselling products */
        .bestsellers {
            padding: 45px 0;
        }

        .product-card {
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            padding: 15px;
        }

        .product-card .card-body {
            display: flex;
            flex-direction: column;
        }

        .product-card .btn {
            margin-top: auto;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
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
                    <?php if (isset($_SESSION["loggedinuser"])): ?>

                        <a href="logout.php" class="btn btn-outline-light">
                            Logout
                        </a>

                    <?php else: ?>

                        <a href="login.php" class="btn btn-warning">
                            Login
                        </a>

                    <?php endif; ?>
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

        <!-- Welcome section -->
        <section class="welcome">
            <div class="container">
                <h1>Welcome to SkateBox</h1>
                <p>Shop for all your skateboard needs in one place.</p>
                <a href="products.php" class="btn btn-warning btn-lg">Browse Products</a>
            </div>
        </section>

        <!-- Bestselling products-->
        <section class="bestsellers">
            <div class="container">
                <h2 class="text-center fw-bold mb-4">Bestselling Products</h2>

                <div class="row row-cols-4 g-4">

                    <?php if (count($bestSellers) > 0): ?>

                        <?php foreach ($bestSellers as $product): ?>

                            <div class="col">
                                <div class="card product-card">

                                    <!-- Product image -->
                                    <?php if (!empty($product['photoLink'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['photoLink']); ?>"
                                    class="card-img-top product-image"
                                    alt="<?php echo htmlspecialchars($product['itemName']); ?>">
                                    <?php else: ?>
                                    <div class="text-center p-4">No image available</div>
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?php echo htmlspecialchars($product['itemName']); ?>
                                        </h5>

                                        <p class="card-text text-muted">
                                            <?php echo htmlspecialchars($product['descrip']); ?>
                                        </p>

                                        <p class="product-price">
                                            £<?php echo number_format($product['price'], 2); ?>
                                        </p>

                                        <p class="text-muted">
                                            <?php echo (int)$product['unitsSold']; ?> units sold
                                        </p>

                                        <a href="product.php?id=<?php echo (int)$product['itemID']; ?>" class="btn btn-dark">
                                            View Product
                                        </a>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="col-12 text-center">
                            <p class="text-muted">No bestselling products yet.</p>
                        </div>

                    <?php endif; ?>

                </div>
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
