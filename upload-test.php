<?php
/**
 * Simple File Upload Test Script
 * Upload this to your server and test file uploads directly
 * URL: https://yourdomain.com/upload-test.php
 */

// Security check - remove this file after testing
if (!isset($_GET['test']) || $_GET['test'] !== 'upload') {
    die('Add ?test=upload to the URL to access this test script');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>File Upload Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #4CAF50; }
        .error { color: #f44336; }
        .info { background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>File Upload Test</h1>
        
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])): ?>
            <div class="info">
                <h3>Upload Results:</h3>
                <?php
                $uploadedFile = $_FILES['test_file'];
                
                echo "<strong>Original Name:</strong> " . htmlspecialchars($uploadedFile['name']) . "<br>";
                echo "<strong>Size:</strong> " . $uploadedFile['size'] . " bytes<br>";
                echo "<strong>Type:</strong> " . htmlspecialchars($uploadedFile['type']) . "<br>";
                echo "<strong>Error Code:</strong> " . $uploadedFile['error'] . "<br>";
                
                if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                    // Test the same logic as our controller
                    $extension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                    
                    if (in_array($extension, $allowedExtensions)) {
                        // Generate test filename
                        $filename = 'test_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                        
                        // Try to create directory
                        $uploadDir = __DIR__ . '/storage/app/public/projects/';
                        if (!is_dir($uploadDir)) {
                            if (mkdir($uploadDir, 0755, true)) {
                                echo "<span class='success'>✓ Created directory: $uploadDir</span><br>";
                            } else {
                                echo "<span class='error'>✗ Failed to create directory: $uploadDir</span><br>";
                            }
                        }
                        
                        $uploadPath = $uploadDir . $filename;
                        
                        // Test file_get_contents approach
                        $fileContents = file_get_contents($uploadedFile['tmp_name']);
                        if ($fileContents !== false) {
                            echo "<span class='success'>✓ Successfully read uploaded file</span><br>";
                            
                            $result = file_put_contents($uploadPath, $fileContents);
                            if ($result !== false) {
                                echo "<span class='success'>✓ File saved successfully to: $uploadPath</span><br>";
                                echo "<span class='success'>✓ File size on disk: " . filesize($uploadPath) . " bytes</span><br>";
                                
                                // Set permissions
                                if (chmod($uploadPath, 0644)) {
                                    echo "<span class='success'>✓ File permissions set</span><br>";
                                }
                                
                                // Clean up test file
                                if (unlink($uploadPath)) {
                                    echo "<span class='success'>✓ Test file cleaned up</span><br>";
                                }
                            } else {
                                echo "<span class='error'>✗ Failed to save file</span><br>";
                            }
                        } else {
                            echo "<span class='error'>✗ Failed to read uploaded file</span><br>";
                        }
                    } else {
                        echo "<span class='error'>✗ Invalid file extension: $extension</span><br>";
                    }
                } else {
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                        UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                        UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
                    ];
                    
                    $errorMsg = $errorMessages[$uploadedFile['error']] ?? 'Unknown error';
                    echo "<span class='error'>✗ Upload failed: $errorMsg</span><br>";
                }
                ?>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <h3>Test File Upload:</h3>
            <p>Select an image file to test upload functionality:</p>
            <input type="file" name="test_file" accept="image/*" required>
            <br><br>
            <button type="submit">Test Upload</button>
        </form>
        
        <div class="info">
            <h3>System Information:</h3>
            <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?><br>
            <strong>Fileinfo Extension:</strong> <?php echo extension_loaded('fileinfo') ? 'Enabled' : 'Disabled'; ?><br>
            <strong>Upload Max Filesize:</strong> <?php echo ini_get('upload_max_filesize'); ?><br>
            <strong>Post Max Size:</strong> <?php echo ini_get('post_max_size'); ?><br>
            <strong>Current Directory:</strong> <?php echo __DIR__; ?><br>
        </div>
        
        <div class="info">
            <strong>⚠️ Security Warning:</strong> Delete this file after testing!
        </div>
    </div>
</body>
</html>