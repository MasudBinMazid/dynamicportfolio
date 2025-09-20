<?php
/**
 * Server Debug Script for Laravel Projects
 * Upload this file to your public_html directory and access it via browser
 * URL: https://yourdomain.com/debug-server.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Debug Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        h2 { color: #666; margin-top: 30px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; margin: 10px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Server Debug Report</h1>
        <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        // Helper function to check status
        function getStatus($condition) {
            return $condition ? '<span class="success">✓ OK</span>' : '<span class="error">✗ MISSING</span>';
        }

        function getFileStatus($path) {
            if (file_exists($path)) {
                return '<span class="success">✓ EXISTS</span>';
            } else {
                return '<span class="error">✗ MISSING</span>';
            }
        }

        function getPermissionStatus($path) {
            if (!file_exists($path)) return '<span class="error">N/A (File Missing)</span>';
            $perms = substr(sprintf('%o', fileperms($path)), -4);
            $writable = is_writable($path);
            $color = $writable ? 'success' : 'error';
            return "<span class=\"$color\">$perms " . ($writable ? '(Writable)' : '(Not Writable)') . "</span>";
        }
        ?>

        <!-- PHP Configuration -->
        <div class="section">
            <h2>📋 PHP Configuration</h2>
            <table>
                <tr><th>Setting</th><th>Value</th><th>Status</th></tr>
                <tr>
                    <td>PHP Version</td>
                    <td><?php echo PHP_VERSION; ?></td>
                    <td><?php echo version_compare(PHP_VERSION, '8.0.0', '>=') ? '<span class="success">✓ Compatible</span>' : '<span class="warning">⚠ Old Version</span>'; ?></td>
                </tr>
                <tr>
                    <td>Fileinfo Extension</td>
                    <td><?php echo extension_loaded('fileinfo') ? 'Enabled' : 'Disabled'; ?></td>
                    <td><?php echo getStatus(extension_loaded('fileinfo')); ?></td>
                </tr>
                <tr>
                    <td>GD Extension</td>
                    <td><?php echo extension_loaded('gd') ? 'Enabled' : 'Disabled'; ?></td>
                    <td><?php echo getStatus(extension_loaded('gd')); ?></td>
                </tr>
                <tr>
                    <td>File Uploads</td>
                    <td><?php echo ini_get('file_uploads') ? 'Enabled' : 'Disabled'; ?></td>
                    <td><?php echo getStatus(ini_get('file_uploads')); ?></td>
                </tr>
                <tr>
                    <td>Upload Max Filesize</td>
                    <td><?php echo ini_get('upload_max_filesize'); ?></td>
                    <td><?php echo getStatus(true); ?></td>
                </tr>
                <tr>
                    <td>Post Max Size</td>
                    <td><?php echo ini_get('post_max_size'); ?></td>
                    <td><?php echo getStatus(true); ?></td>
                </tr>
                <tr>
                    <td>Max Execution Time</td>
                    <td><?php echo ini_get('max_execution_time'); ?> seconds</td>
                    <td><?php echo getStatus(true); ?></td>
                </tr>
                <tr>
                    <td>Memory Limit</td>
                    <td><?php echo ini_get('memory_limit'); ?></td>
                    <td><?php echo getStatus(true); ?></td>
                </tr>
            </table>
        </div>

        <!-- Directory Structure -->
        <div class="section">
            <h2>📁 Directory Structure & Permissions</h2>
            <?php
            $checkPaths = [
                'Current Directory' => __DIR__,
                'Storage Directory' => __DIR__ . '/storage',
                'Storage App Directory' => __DIR__ . '/storage/app',
                'Storage Public Directory' => __DIR__ . '/storage/app/public',
                'Storage Projects Directory' => __DIR__ . '/storage/app/public/projects',
                'Public Directory' => __DIR__ . '/public',
                'Public Storage Link' => __DIR__ . '/public/storage',
                'Bootstrap Cache' => __DIR__ . '/bootstrap/cache',
                'Uploads Directory' => __DIR__ . '/public/uploads',
            ];
            ?>
            <table>
                <tr><th>Path</th><th>Status</th><th>Permissions</th><th>Full Path</th></tr>
                <?php foreach ($checkPaths as $name => $path): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo getFileStatus($path); ?></td>
                    <td><?php echo getPermissionStatus($path); ?></td>
                    <td><small><?php echo $path; ?></small></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Laravel Specific Checks -->
        <div class="section">
            <h2>🚀 Laravel Configuration</h2>
            <?php
            $laravelFiles = [
                '.env file' => __DIR__ . '/.env',
                'artisan file' => __DIR__ . '/artisan',
                'composer.json' => __DIR__ . '/composer.json',
                'vendor directory' => __DIR__ . '/vendor',
                'app directory' => __DIR__ . '/app',
                'config directory' => __DIR__ . '/config',
            ];
            ?>
            <table>
                <tr><th>Laravel Component</th><th>Status</th><th>Notes</th></tr>
                <?php foreach ($laravelFiles as $name => $path): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo getFileStatus($path); ?></td>
                    <td>
                        <?php if ($name === 'vendor directory' && !file_exists($path)): ?>
                            <span class="error">Run: composer install</span>
                        <?php elseif ($name === '.env file' && !file_exists($path)): ?>
                            <span class="error">Copy .env.example to .env</span>
                        <?php else: ?>
                            <span class="success">OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- File Upload Test -->
        <div class="section">
            <h2>📤 File Upload Test</h2>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_upload'])): ?>
                <div class="info">
                    <h3>Upload Test Results:</h3>
                    <?php
                    $uploadedFile = $_FILES['test_upload'];
                    echo "<strong>File Name:</strong> " . $uploadedFile['name'] . "<br>";
                    echo "<strong>File Size:</strong> " . $uploadedFile['size'] . " bytes<br>";
                    echo "<strong>File Type:</strong> " . $uploadedFile['type'] . "<br>";
                    echo "<strong>Temp File:</strong> " . $uploadedFile['tmp_name'] . "<br>";
                    echo "<strong>Error Code:</strong> " . $uploadedFile['error'] . "<br>";

                    if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = __DIR__ . '/storage/app/public/projects/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        $fileName = 'test_' . time() . '_' . $uploadedFile['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadPath)) {
                            echo "<br><span class='success'>✓ File uploaded successfully to: $uploadPath</span>";
                            echo "<br><span class='success'>✓ File size on disk: " . filesize($uploadPath) . " bytes</span>";
                            // Clean up test file
                            unlink($uploadPath);
                            echo "<br><span class='success'>✓ Test file cleaned up</span>";
                        } else {
                            echo "<br><span class='error'>✗ Failed to move uploaded file</span>";
                        }
                    } else {
                        echo "<br><span class='error'>✗ Upload error occurred</span>";
                    }
                    ?>
                </div>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data">
                    <p>Test file upload functionality:</p>
                    <input type="file" name="test_upload" accept="image/*">
                    <button type="submit">Test Upload</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Environment Variables -->
        <div class="section">
            <h2>🌍 Environment Check</h2>
            <table>
                <tr><th>Variable</th><th>Value</th></tr>
                <tr><td>DOCUMENT_ROOT</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not Set'; ?></td></tr>
                <tr><td>HTTP_HOST</td><td><?php echo $_SERVER['HTTP_HOST'] ?? 'Not Set'; ?></td></tr>
                <tr><td>SERVER_SOFTWARE</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Not Set'; ?></td></tr>
                <tr><td>Current Working Directory</td><td><?php echo getcwd(); ?></td></tr>
                <tr><td>Script Directory</td><td><?php echo __DIR__; ?></td></tr>
            </table>
        </div>

        <!-- Laravel Image Testing -->
        <div class="section">
            <h2>🖼️ Image URL Testing</h2>
            <div class="info">
                <p>This section tests how Laravel generates image URLs without using the Storage facade:</p>
                
                <?php
                // Test image URL generation like the Project model
                function testImageUrl($imagePath) {
                    if (!$imagePath) return null;
                    
                    // Check public uploads directory
                    $publicPath = __DIR__ . '/public/uploads/projects/' . $imagePath;
                    if (file_exists($publicPath)) {
                        return '/uploads/projects/' . $imagePath;
                    }
                    
                    // Check storage public directory  
                    $storagePath = __DIR__ . '/storage/app/public/' . $imagePath;
                    if (file_exists($storagePath)) {
                        return '/storage/' . $imagePath;
                    }
                    
                    return null;
                }
                
                // Test with sample image paths
                $testPaths = [
                    'test.jpg',
                    'projects/test.jpg',
                    'sample-image.png'
                ];
                
                echo "<strong>Image URL Generation Test:</strong><br>";
                foreach ($testPaths as $path) {
                    $url = testImageUrl($path);
                    $status = $url ? '<span class="success">✓ URL: ' . $url . '</span>' : '<span class="error">✗ No URL generated</span>';
                    echo "Path: $path → $status<br>";
                }
                ?>
            </div>
        </div>
        <div class="section">
            <h2>🔧 Available PHP Extensions</h2>
            <div class="code">
                <?php
                $extensions = get_loaded_extensions();
                sort($extensions);
                $important_extensions = ['fileinfo', 'gd', 'curl', 'openssl', 'mbstring', 'zip', 'xml', 'json'];
                
                echo "<strong>Important Extensions:</strong><br>";
                foreach ($important_extensions as $ext) {
                    $loaded = in_array($ext, $extensions);
                    $status = $loaded ? '<span class="success">✓</span>' : '<span class="error">✗</span>';
                    echo "$status $ext<br>";
                }
                
                echo "<br><strong>All Extensions (" . count($extensions) . "):</strong><br>";
                echo implode(', ', $extensions);
                ?>
            </div>
        </div>

        <!-- Quick Fixes -->
        <div class="section">
            <h2>🔨 Quick Fixes</h2>
            <div class="info">
                <h3>If fileinfo extension is missing:</h3>
                <div class="code">
                    1. Contact your hosting provider to enable the 'fileinfo' PHP extension<br>
                    2. Or in cPanel → PHP Selector → Extensions → Enable 'fileinfo'<br>
                    3. Alternative: Use the updated Laravel controller code that doesn't require fileinfo
                </div>

                <h3>Storage Link Command:</h3>
                <div class="code">
                    php artisan storage:link
                </div>

                <h3>Fix Permissions:</h3>
                <div class="code">
                    chmod 755 storage/<br>
                    chmod 755 storage/app/<br>
                    chmod 755 storage/app/public/<br>
                    chmod 755 bootstrap/cache/
                </div>
            </div>
        </div>

        <div class="info">
            <strong>Next Steps:</strong><br>
            1. If fileinfo is missing, ask your hosting provider to enable it<br>
            2. Ensure all storage directories have proper permissions<br>
            3. Run 'php artisan storage:link' on your server<br>
            4. Use the updated controller code provided<br>
            5. Delete this debug file after testing for security
        </div>
    </div>
</body>
</html>