<?php
// Include database connection
require 'php/db_connect.php';

// Get products from database
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique categories for filtering
$categories = [];
foreach ($products as $product) {
    if (!empty($product['category'])) {
        $categories[$product['category']] = $product['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Elegant Furnishings</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Elegant Furnishings</h1>
                <p>Since 1995</p>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="shop.php" class="active">Shop</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="about.html">About</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <section class="shop-hero">
        <div class="container">
            <h1>Our Furniture Collection</h1>
            <p>Quality craftsmanship for every home</p>
        </div>
    </section>

    <section class="shop-section">
        <div class="container">
            <div class="shop-controls">
                <div class="search-filter">
                    <input type="text" id="searchInput" placeholder="Search products...">
                    <select id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>">
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button id="filterBtn" class="btn">Filter</button>
                </div>
                <div class="sort-options">
                    <label for="sortSelect">Sort by:</label>
                    <select id="sortSelect">
                        <option value="newest">Newest First</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="name_asc">Name: A-Z</option>
                        <option value="name_desc">Name: Z-A</option>
                    </select>
                </div>
            </div>

            <div class="products-grid" id="productsContainer">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" 
                             data-name="<?php echo htmlspecialchars(strtolower($product['name'])); ?>"
                             data-category="<?php echo htmlspecialchars(strtolower($product['category'])); ?>"
                             data-price="<?php echo $product['price']; ?>"
                             data-date="<?php echo strtotime($product['created_at']); ?>">
                            <div class="product-image">
                                <?php if (!empty($product['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image Available</div>
                                <?php endif; ?>
                                <div class="product-overlay">
                                    <a href="product.php" class="btn btn-view">View Details</a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="product-meta">
                                    <?php if (!empty($product['category'])): ?>
                                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                    <?php endif; ?>
                                    <span class="product-price">$<?php echo number_format($product['price'], 2); ?></span>
                                </div>
                                <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                                <button class="btn btn-add-to-cart">Add to Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products">
                        <p>No products found. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pagination">
                <button class="btn btn-prev" disabled>Previous</button>
                <span class="page-info">Page 1 of 1</span>
                <button class="btn btn-next" disabled>Next</button>
            </div>
        </div>
    </section>

    <?php include '../admin/includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/shop.js"></script>
</body>
</html>