<?php
include '../includes/config.php';
include '../includes/auth.php';

// Check if ID parameter exists
if(empty($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$name = $description = $price = $category = $current_image = '';
$name_err = $price_err = $image_err = '';

// Fetch existing product data
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if($stmt->rowCount() == 1){
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $product['name'];
    $description = $product['description'];
    $price = $product['price'];
    $category = $product['category'];
    $current_image = $product['image_path'];
} else {
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Validate name
    if(empty(trim($_POST['name']))){
        $name_err = 'Please enter a product name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate price
    if(empty(trim($_POST['price']))){
        $price_err = 'Please enter a price.';
    } elseif(!is_numeric(trim($_POST['price']))){
        $price_err = 'Please enter a valid price.';
    } else {
        $price = trim($_POST['price']);
    }

    // Get other fields
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // Process image upload if new image is provided
    $image_path = $current_image;
    if(!empty($_FILES['image']['name'])){
        if($_FILES['image']['error'] == UPLOAD_ERR_OK){
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_size = $_FILES['image']['size'];
            
            // Check if image file is a actual image
            $check = getimagesize($file_tmp);
            if($check !== false){
                // Generate unique filename
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $new_filename = uniqid('product_', true) . '.' . $file_ext;
                $upload_path = UPLOAD_DIR . $new_filename;
                
                // Check file size (max 2MB)
                if($file_size > 2000000){
                    $image_err = 'Image size must be less than 2MB.';
                } else {
                    // Allow certain file formats
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                    if(in_array($file_ext, $allowed_exts)){
                        if(move_uploaded_file($file_tmp, $upload_path)){
                            // Delete old image if it exists
                            if(!empty($current_image) && file_exists('../' . $current_image)){
                                unlink('../' . $current_image);
                            }
                            $image_path = 'images/products/' . $new_filename;
                        } else {
                            $image_err = 'Sorry, there was an error uploading your file.';
                        }
                    } else {
                        $image_err = 'Only JPG, JPEG, PNG & GIF files are allowed.';
                    }
                }
            } else {
                $image_err = 'File is not an image.';
            }
        } else {
            $image_err = 'There was an error uploading your file.';
        }
    }

    // Check input errors before updating database
    if(empty($name_err) && empty($price_err) && empty($image_err)){
        $sql = "UPDATE products SET name = :name, description = :description, price = :price, category = :category, image_path = :image_path WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if($stmt->execute()){
            header("Location: index.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
}

include '../includes/header.php';
?>

<div class="container">
    <h1>Edit Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label>Product Name *</label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="help-block"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5"><?php echo $description; ?></textarea>
        </div>
        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
            <label>Price *</label>
            <input type="text" name="price" value="<?php echo $price; ?>">
            <span class="help-block"><?php echo $price_err; ?></span>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?php echo $category; ?>">
        </div>
        <div class="form-group">
            <label>Current Image</label>
            <?php if(!empty($current_image)): ?>
                <img src="../<?php echo $current_image; ?>" alt="Current Product Image" width="100">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
        </div>
        <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
            <label>New Product Image (Leave blank to keep current)</label>
            <input type="file" name="image">
            <span class="help-block"><?php echo $image_err; ?></span>
        </div>
        <button type="submit" class="btn">Update Product</button>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>