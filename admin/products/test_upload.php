<?php
$target_dir = __DIR__ . '/../images/products/';
echo "Target directory: " . $target_dir . "<br>";

// Check if directory exists
if (!file_exists($target_dir)) {
    echo "Directory doesn't exist. Attempting to create...<br>";
    if (mkdir($target_dir, 0777, true)) {
        echo "Directory created successfully!<br>";
    } else {
        die("Failed to create directory. Check permissions.");
    }
}

// Check if writable
if (is_writable($target_dir)) {
    echo "Directory is writable!<br>";
} else {
    die("Directory is not writable. Check permissions.");
}

// Test file creation
$test_file = $target_dir . 'test.txt';
if (file_put_contents($test_file, "Test content")) {
    echo "File created successfully!<br>";
    unlink($test_file); // Clean up
} else {
    die("Failed to create test file.");
}

echo "Your upload directory is properly configured!";
?>