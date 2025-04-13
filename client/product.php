<?php
require 'php/db_connect.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = $_GET['id'];

// Get product details from database
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    header("Location: shop.php");
    exit();
}

$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Elegant Furnishings</title>
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
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="about.html">About</a></li>
                </ul>
            </nav>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail">
                <div class="product-images">
                    <div class="main-image">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <!-- Placeholder for thumbnail gallery -->
                    <div class="thumbnails">
                        <div class="thumbnail active">
                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Thumbnail 1">
                        </div>
                        <!-- Additional thumbnails would go here -->
                    </div>
                </div>
                
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <?php if (!empty($product['category'])): ?>
                        <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                    <?php endif; ?>
                    
                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                    
                    <div class="product-description">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                    
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus">-</button>
                            <input type="number" value="1" min="1" class="quantity-input">
                            <button class="quantity-btn plus">+</button>
                        </div>
                        <button class="btn btn-add-to-cart">Add to Cart</button>
                    </div>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="fas fa-truck"></i>
                            <span>Free shipping on orders over $100</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-undo"></i>
                            <span>30-day return policy</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>2-year warranty</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product specifications -->
            <div class="product-specs">
                <h2>Product Specifications</h2>
                <div class="specs-grid">
                    <div class="spec-item">
                        <h3>Dimensions</h3>
                        <p>Height: 32" | Width: 60" | Depth: 30"</p>
                    </div>
                    <div class="spec-item">
                        <h3>Materials</h3>
                        <p>Solid oak wood, premium upholstery fabric</p>
                    </div>
                    <div class="spec-item">
                        <h3>Weight</h3>
                        <p>45 lbs</p>
                    </div>
                    <div class="spec-item">
                        <h3>Assembly</h3>
                        <p>Required (tools included)</p>
                    </div>
                </div>
            </div>
            
            <!-- Related products -->
            <div class="related-products">
                <h2>You May Also Like</h2>
                <div class="products-grid">
                    <!-- PHP would fetch related products here -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/products/related-1.jpg" alt="Related Product">
                            <div class="product-overlay">
                                <a href="product.php?id=2" class="btn btn-view">View Details</a>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3>Modern Lounge Chair</h3>
                            <div class="product-price">$299.99</div>
                        </div>
                    </div>
                    <!-- More related products would go here -->
                </div>
            </div>
        </div>
    </section>

    <?php include '../admin/includes/footer.php'; ?>

    <script src="js/product.js"></script>
</body>
</html>