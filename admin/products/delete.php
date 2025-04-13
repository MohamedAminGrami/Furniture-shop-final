<?php
include '../includes/config.php';
include '../includes/auth.php';

// Check if ID parameter exists
if(empty($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Fetch product to get image path
$sql = "SELECT image_path FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if($stmt->rowCount() == 1){
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $image_path = $product['image_path'];
    
    // Delete the product
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if($stmt->execute()){
        // Delete the image file if it exists
        if(!empty($image_path) && file_exists('../' . $image_path)){
            unlink('../' . $image_path);
        }
        header("Location: index.php");
        exit();
    }
}

header("Location: index.php");
exit();
?>