<?php
include '../includes/config.php';
include '../includes/auth.php';
include '../includes/header.php';

// Fetch all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Product Management</h1>
    <a href="create.php" class="btn">Add New Product</a>
    
    <div class="product-list">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <?php if($product['image_path']): ?>
                            <img src="<?php echo '../' . $product['image_path']; ?>" alt="<?php echo $product['name']; ?>" width="50">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm">Edit</a>
                        <a href="delete.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>