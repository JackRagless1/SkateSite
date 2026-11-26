
<?php
require_once 'connection.php';

// Get the product ID from the URL

$product = false;

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $itemID = (int)$_GET['id'];

    // Find the product and its image
    $sql = "SELECT i.itemID, i.itemName, i.descrip, i.price, i.stock,
            c.category,
            (SELECT p.photoLink
             FROM tblitemhasphoto ip
             INNER JOIN tblphotos p ON ip.photoID = p.photoID
             WHERE ip.itemID = i.itemID
             LIMIT 1) AS photoLink
            FROM tblitems i
            LEFT JOIN tblcategories c ON i.catID = c.catID
            WHERE i.itemID = :itemID";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':itemID', $itemID, PDO::PARAM_INT);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product ? htmlspecialchars($product['itemName']) : 'Product Not Found'; ?> - SkateBox</title>

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

        /* Product deets */
        .product-section {
            padding: 50px 0;
        }

        .product-image-box {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
        }

        .product-price {
            font-size: 30px;
            font-weight: bold;
            margin: 20px 0;
        }

        .product-description {
            font-size: 17px;
            color: #6c757d;
            margin-bottom: 25px;
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

        <!-- Product details -->
        <section class="product-section">
            <div class="container">

                <?php if ($product): ?>

                    <div class="row">

                        <!-- Product image -->
                        <div class="col-6">
                            <div class="product-image-box">

                                <?php if (!empty($product['photoLink'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['photoLink']); ?>"
                                         class="product-image"
                                         alt="<?php echo htmlspecialchars($product['itemName']); ?>">
                                <?php else: ?>
                                    <p class="text-muted">No image available</p>
                                <?php endif; ?>

                            </div>
                        </div>

                        <!-- Product information -->
                        <div class="col-6">
                            <h1 class="fw-bold">
                                <?php echo htmlspecialchars($product['itemName']); ?>
                            </h1>

                            <p class="text-muted">
                                Category: <?php echo htmlspecialchars($product['category'] ?? 'Unknown'); ?>
                            </p>

                            <p class="product-price">
                                £<?php echo number_format($product['price'], 2); ?>
                            </p>

                            <p class="product-description">
                                <?php echo htmlspecialchars($product['descrip']); ?>
                            </p>

                            <?php if ($product['stock'] > 0): ?>
                                <p class="text-success fw-bold">
                                    In Stock (<?php echo (int)$product['stock']; ?> available)
                                </p>

                                <button type="button" class="btn btn-warning btn-lg">
                                    Add to Basket
                                </button>
                            <?php else: ?>
                                <p class="text-danger fw-bold">Out of Stock</p>
                                <button type="button" class="btn btn-secondary btn-lg" disabled>
                                    Out of Stock
                                </button>
                            <?php endif; ?>

                            <div class="mt-4">
                                <a href="products.php" class="btn btn-outline-dark">
                                    Back to Products
                                </a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="text-center">
                        <h2>Product Not Found</h2>
                        <p class="text-muted">The product you are looking for does not exist.</p>
                        <a href="products.php" class="btn btn-dark">Back to Products</a>
                    </div>

                <?php endif; ?>

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
