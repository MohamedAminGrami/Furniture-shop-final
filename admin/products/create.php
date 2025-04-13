<?php
// Authentication check
require '../includes/config.php';
require '../includes/auth.php';

// Initialize variables
$name = $description = $price = $category = '';
$name_err = $price_err = $image_err = '';

// Process form data when submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate name
    if (empty(trim($_POST['name']))) {
        $name_err = 'Please enter a product name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate price
    if (empty(trim($_POST['price']))) {
        $price_err = 'Please enter a price.';
    } elseif (!is_numeric(trim($_POST['price']))) {
        $price_err = 'Please enter a valid price.';
    } else {
        $price = trim($_POST['price']);
    }

    // Get other fields
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    // Process image upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = '../../images/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_size = $_FILES['image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Generate unique filename
        $new_filename = 'product_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_filename;
        
        // Check if image file is valid
        $check = getimagesize($file_tmp);
        if ($check !== false) {
            // Check file size (max 2MB)
            if ($file_size > 2000000) {
                $image_err = 'Image size must be less than 2MB.';
            } else {
                // Allow certain file formats
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($file_ext, $allowed_exts)) {
                    if (move_uploaded_file($file_tmp, $upload_path)) {
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
        $image_err = 'Please select an image file.';
    }

    // Insert into database if no errors
    if (empty($name_err) && empty($price_err) && empty($image_err)) {
        $sql = "INSERT INTO products (name, description, price, category, image_path) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = $pdo->prepare($sql)) {
            if ($stmt->execute([$name, $description, $price, $category, $image_path])) {
                header("Location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }
}

// Include header
include '../includes/header.php';
?>

<div class="container">
    <h1>Add New Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
            <label>Product Name *</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
            <span class="help-block"><?php echo $name_err; ?></span>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5"><?php echo $description; ?></textarea>
        </div>
        
        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
            <label>Price *</label>
            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
            <span class="help-block"><?php echo $price_err; ?></span>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
        </div>
        
        <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
            <label>Product Image *</label>
            <input type="file" name="image" class="form-control">
            <span class="help-block"><?php echo $image_err; ?></span>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>