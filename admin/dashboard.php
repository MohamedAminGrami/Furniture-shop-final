<?php
include 'includes/config.php';
include 'includes/auth.php';
include 'includes/header.php';
?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">
            <h2>Products</h2>
            <p>Manage your furniture products</p>
            <a href="products/index.php" class="btn">View Products</a>
        </div>
        <div class="card">
            <h2>Add New</h2>
            <p>Create a new product listing</p>
            <a href="products/create.php" class="btn">Add Product</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>