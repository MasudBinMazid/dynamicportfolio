<?php
/**
 * Debug Script - Check File Locations and Server Status
 * URL: https://yourdomain.com/debug.php
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Debug Information</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Server Debug Information</h1>
        <p><strong>Debug Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- PHP Information -->
        <div class="section">
            <h2>🐘 PHP Information</h2>
            <table>
                <tr><th>Setting</th><th>Value</th></tr>
                <tr><td>PHP Version</td><td><?php echo PHP_VERSION; ?></td></tr>
                <tr><td>Server Software</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td></tr>
                <tr><td>Document Root</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td></tr>
                <tr><td>Script Path</td><td><?php echo __FILE__; ?></td></tr>
                <tr><td>Current Directory</td><td><?php echo __DIR__; ?></td></tr>
                <tr><td>PHP Script Working</td><td><span class="success">✅ YES</span></td></tr>
            </table>
        </div>

        <!-- File System Check -->
        <div class="section">
            <h2>📁 File System Check</h2>
            <?php
            $targetImage = 'FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg';
            echo "<p><strong>Looking for:</strong> $targetImage</p>";
            
            $checkPaths = [
                'public/images/projects/' . $targetImage,
                'public/storage/projects/' . $targetImage,
                'storage/app/public/projects/' . $targetImage,
                'public/uploads/projects/' . $targetImage,
                'storage/app/public/' . $targetImage,
            ];
            
            echo "<table>";
            echo "<tr><th>Path</th><th>Exists</th><th>Readable</th><th>Size</th><th>Full Path</th></tr>";
            
            foreach ($checkPaths as $path) {
                $fullPath = __DIR__ . '/' . $path;
                $exists = file_exists($fullPath);
                $readable = $exists ? is_readable($fullPath) : false;
                $size = $exists ? filesize($fullPath) : 0;
                
                $existsText = $exists ? '<span class="success">✅ YES</span>' : '<span class="error">❌ NO</span>';
                $readableText = $readable ? '<span class="success">✅ YES</span>' : '<span class="error">❌ NO</span>';
                $sizeText = $size > 0 ? number_format($size) . ' bytes' : '0 bytes';
                
                echo "<tr>";
                echo "<td>$path</td>";
                echo "<td>$existsText</td>";
                echo "<td>$readableText</td>";
                echo "<td>$sizeText</td>";
                echo "<td style='font-size: 11px;'>$fullPath</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>

        <!-- Script Tests -->
        <div class="section">
            <h2>🧪 Script Tests</h2>
            <?php
            $scriptTests = [
                'img.php' => 'Bulletproof Image Server',
                'image.php' => 'Simple Image Server',
                'image-delivery.php' => 'Image Delivery Script'
            ];
            
            echo "<table>";
            echo "<tr><th>Script</th><th>Exists</th><th>Test URL</th><th>Purpose</th></tr>";
            
            foreach ($scriptTests as $script => $purpose) {
                $scriptPath = __DIR__ . '/' . $script;
                $exists = file_exists($scriptPath);
                $existsText = $exists ? '<span class="success">✅ YES</span>' : '<span class="error">❌ NO</span>';
                $testUrl = $exists ? "<a href='/$script?f=$targetImage' target='_blank'>Test $script</a>" : 'N/A';
                
                echo "<tr>";
                echo "<td>$script</td>";
                echo "<td>$existsText</td>";
                echo "<td>$testUrl</td>";
                echo "<td>$purpose</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>

        <!-- Directory Listing -->
        <div class="section">
            <h2>📋 Directory Contents</h2>
            <?php
            $dirsToCheck = [
                'public/storage/projects',
                'storage/app/public/projects',
                'public/images/projects',
                'public/uploads/projects'
            ];
            
            foreach ($dirsToCheck as $dir) {
                $fullDir = __DIR__ . '/' . $dir;
                echo "<h4>$dir/</h4>";
                
                if (is_dir($fullDir)) {
                    $files = glob($fullDir . '/*');
                    if (!empty($files)) {
                        echo "<ul>";
                        foreach ($files as $file) {
                            $filename = basename($file);
                            $size = is_file($file) ? filesize($file) : 0;
                            $sizeText = $size > 0 ? ' (' . number_format($size) . ' bytes)' : '';
                            echo "<li>$filename$sizeText</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p style='color: #999;'>Directory is empty</p>";
                    }
                } else {
                    echo "<p style='color: #f44336;'>Directory does not exist</p>";
                }
            }
            ?>
        </div>

        <!-- Direct Image Test -->
        <div class="section">
            <h2>🖼️ Live Image Test</h2>
            <p>Testing if we can find and display the image:</p>
            <?php
            $foundPath = null;
            foreach ($checkPaths as $path) {
                $fullPath = __DIR__ . '/' . $path;
                if (file_exists($fullPath) && filesize($fullPath) > 0) {
                    $foundPath = $fullPath;
                    $relativePath = '/' . $path;
                    break;
                }
            }
            
            if ($foundPath) {
                echo "<div class='info'>";
                echo "<p><span class='success'>✅ Image found at:</span> $foundPath</p>";
                echo "<p><strong>Trying to display image directly:</strong></p>";
                echo "<img src='$relativePath' style='max-width: 400px; border: 1px solid #ddd;' ";
                echo "onerror=\"this.style.border='2px solid red'; this.alt='Image failed to load';\">";
                echo "</div>";
            } else {
                echo "<div style='background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px;'>";
                echo "<p><span class='error'>❌ No image files found in any location!</span></p>";
                echo "<p>This means the images need to be copied to the correct location.</p>";
                echo "</div>";
            }
            ?>
        </div>

        <!-- Recommendations -->
        <div class="info">
            <h2>💡 Recommendations</h2>
            <?php if ($foundPath): ?>
                <p><strong>✅ Good news!</strong> Image files were found. If the image above loads, you can:</p>
                <ul>
                    <li>Update your Laravel Project model to use the working path</li>
                    <li>Test your projects page to see if banner images now display</li>
                    <li>Clean up any debug files</li>
                </ul>
            <?php else: ?>
                <p><strong>⚠️ Action needed:</strong> No image files found. You should:</p>
                <ul>
                    <li>Run the locate-and-fix-images.php script to copy images to accessible location</li>
                    <li>Check if images were uploaded correctly to your server</li>
                    <li>Verify that file uploads in Laravel admin are working</li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h2>🚀 Quick Actions</h2>
            <p>Based on the debug results above:</p>
            <ul>
                <li><a href="/locate-and-fix-images.php?action=fix" target="_blank">🔍 Run Image Locator & Fixer</a></li>
                <li><a href="/simple-test.html" target="_blank">🧪 Simple Image Test</a></li>
                <li><a href="/test-laravel-images.php" target="_blank">🎯 Test Laravel Integration</a></li>
                <li><a href="/projects" target="_blank">📋 Check Projects Page</a></li>
            </ul>
        </div>
    </div>
</body>
</html>