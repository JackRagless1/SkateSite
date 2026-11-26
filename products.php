
<?php
session_start();
require_once 'connection.php';

//categories list
$categories = [
    0 => 'Decks',
    1 => 'Trucks',
    2 => 'Wheels',
    3 => 'Bearings',
    4 => 'Grip Tape',
    5 => 'Accessories',
    6 => 'Boards'
];

// Check whether a category has been selected
$category = null;
$pageTitle = "All Products";

if (isset($_GET['category']) && ctype_digit($_GET['category'])) {
    $selectedCategory = (int)$_GET['category'];

    if (isset($categories[$selectedCategory])) {
        $category = $selectedCategory;
        $pageTitle = $categories[$category];
    }
}

// Retrieve products and their images from the database
$sql = "SELECT i.itemID, i.itemName, i.descrip, i.price, i.stock,
        (SELECT p.photoLink
         FROM tblitemhasphoto ip
         INNER JOIN tblphotos p ON ip.photoID = p.photoID
         WHERE ip.itemID = i.itemID
         LIMIT 1) AS photoLink
        FROM tblitems i";

// Apply category filter if needed
if ($category !== null) {
    $sql .= " WHERE i.catID = :category";
}

$sql .= " ORDER BY i.itemID DESC";

$stmt = $conn->prepare($sql);

if ($category !== null) {
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?> - SkateBox</title>

    <!-- Bootstrap: https://www.w3schools.com/bootstrap5/ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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

        /* Search bar */
        .search-section {
            background-color: #e9ecef;
            padding: 20px;
        }

        .search-form {
            width: 600px;
            margin: auto;
        }

        /* Products */
        .products-section {
            padding: 45px 0;
        }

        .product-card {
            height: 100%;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            padding: 15px;
        }

        .no-image {
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e9ecef;
            color: #6c757d;
        }

        .product-card .card-body {
            display: flex;
            flex-direction: column;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
        }

        .product-card .btn {
            margin-top: auto;
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>

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

        <!-- Product listings -->
        <section class="products-section">
            <div class="container">

                <h2 class="text-center fw-bold mb-4">
                    <?php echo htmlspecialchars($pageTitle); ?>
                </h2>

                <div class="row row-cols-4 g-4">

                    <?php if (count($products) > 0): ?>

                        <?php foreach ($products as $product): ?>

                            <div class="col">
                                <div class="card product-card">

                                    <!-- Product image -->
                                    <?php if (!empty($product['photoLink'])): ?>
                                        <img src="<?php echo htmlspecialchars($product['photoLink']); ?>"
                                             class="card-img-top product-image"
                                             alt="<?php echo htmlspecialchars($product['itemName']); ?>">
                                    <?php else: ?>
                                        <div class="no-image">No image available</div>
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
                                            <?php if ($product['stock'] > 0): ?>
                                                <?php echo (int)$product['stock']; ?> in stock
                                            <?php else: ?>
                                                Out of stock
                                            <?php endif; ?>
                                        </p>

                                        <a href="product.php?id=<?php echo (int)$product['itemID']; ?>"
                                           class="btn btn-dark">
                                            View Product
                                        </a>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="col-12 text-center">
                            <p class="text-muted">There are currently no products in this category.</p>
                            <a href="products.php" class="btn btn-dark">View All Products</a>
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
