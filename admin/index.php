<?php
session_start();
include 'includes/config.php';

// Redirect if already logged in
if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true){
    header("Location: dashboard.php");
    exit;
}

$login_err = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, username, password FROM admins WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if($stmt->rowCount() == 1){
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Compare plain text passwords (temporary)
        if($password === $admin['password']){
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $login_err = "Invalid username or password.";
        }
    } else {
        $login_err = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Elegant Furnishings</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1><i class="fas fa-lock"></i> Admin Login</h1>
            <?php if(!empty($login_err)): ?>
                <div class="alert alert-danger"><?php echo $login_err; ?></div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-key"></i> Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>