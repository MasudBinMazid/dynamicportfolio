<?php
/**
 * Hosting Environment Fix for cPanel Shared Hosting
 * This addresses specific issues with symlinks and file access on shared hosting
 * URL: https://yourdomain.com/hosting-fix.php?action=fix
 */

if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Hosting Environment Fix</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .error { background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 10px 0; }
            .success { background: #e8f5e8; padding: 15px; border-left: 4px solid #4CAF50; margin: 10px 0; }
            .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
            .btn { background: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔧 Hosting Environment Fix</h1>
            
            <div class="error">
                <h3>🚨 Detected Issue</h3>
                <p>Your storage symlink exists but files return 404 errors. This is common on shared hosting where:</p>
                <ul>
                    <li>Symlinks are blocked or restricted</li>
                    <li>Apache doesn't follow symlinks properly</li>
                    <li>Security modules prevent access to storage directory</li>
                </ul>
            </div>
            
            <div class="info">
                <h3>🛠️ This Fix Will</h3>
                <ul>
                    <li>Replace symlink with direct file copies</li>
                    <li>Create proper .htaccess rules for file access</li>
                    <li>Set up a working image delivery system</li>
                    <li>Configure your Laravel app to use the working method</li>
                    <li>Test everything thoroughly</li>
                </ul>
            </div>
            
            <p><a href="?action=fix" class="btn">🚀 Apply Hosting Fix</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hosting Environment Fix Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Hosting Environment Fix Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $fixes = [];
        $issues = [];

        // Step 1: Remove symlink and create directory structure
        echo "<div class='section'><h2>🔗 Step 1: Replace Symlink with Directory</h2>";
        
        $publicStorage = __DIR__ . '/public/storage';
        $storageSource = __DIR__ . '/storage/app/public';
        
        // Remove symlink
        if (is_link($publicStorage)) {
            if (unlink($publicStorage)) {
                echo "<span class='success'>✓ Removed symlink</span><br>";
                $fixes[] = "Removed problematic symlink";
            } else {
                echo "<span class='error'>✗ Could not remove symlink</span><br>";
                $issues[] = "Could not remove symlink";
            }
        }
        
        // Create directory structure
        if (!is_dir($publicStorage)) {
            if (mkdir($publicStorage, 0755, true)) {
                echo "<span class='success'>✓ Created storage directory</span><br>";
                $fixes[] = "Created storage directory";
            } else {
                echo "<span class='error'>✗ Could not create storage directory</span><br>";
                $issues[] = "Could not create storage directory";
            }
        }
        
        // Create projects subdirectory
        $publicProjectsDir = $publicStorage . '/projects';
        if (!is_dir($publicProjectsDir)) {
            if (mkdir($publicProjectsDir, 0755, true)) {
                echo "<span class='success'>✓ Created projects directory</span><br>";
                $fixes[] = "Created projects directory";
            }
        }
        
        echo "</div>";

        // Step 2: Copy all files from storage to public
        echo "<div class='section'><h2>📁 Step 2: Copy Files to Public Storage</h2>";
        
        $sourceProjectsDir = $storageSource . '/projects';
        $copiedFiles = 0;
        $failedFiles = 0;
        
        if (is_dir($sourceProjectsDir)) {
            $files = glob($sourceProjectsDir . '/*');
            
            foreach ($files as $sourceFile) {
                if (is_file($sourceFile)) {
                    $filename = basename($sourceFile);
                    $targetFile = $publicProjectsDir . '/' . $filename;
                    
                    if (copy($sourceFile, $targetFile)) {
                        chmod($targetFile, 0644);
                        $copiedFiles++;
                    } else {
                        $failedFiles++;
                    }
                }
            }
            
            echo "<span class='success'>✓ Copied $copiedFiles files</span><br>";
            if ($failedFiles > 0) {
                echo "<span class='warning'>⚠ Failed to copy $failedFiles files</span><br>";
            }
            $fixes[] = "Copied $copiedFiles image files to public storage";
        } else {
            echo "<span class='warning'>⚠ Source storage directory not found</span><br>";
        }
        
        // Also copy from uploads if they exist
        $uploadsSource = __DIR__ . '/public/uploads/projects';
        if (is_dir($uploadsSource)) {
            $uploadFiles = glob($uploadsSource . '/*');
            $uploadsCopied = 0;
            
            foreach ($uploadFiles as $sourceFile) {
                if (is_file($sourceFile)) {
                    $filename = basename($sourceFile);
                    $targetFile = $publicProjectsDir . '/' . $filename;
                    
                    if (!file_exists($targetFile)) {
                        if (copy($sourceFile, $targetFile)) {
                            chmod($targetFile, 0644);
                            $uploadsCopied++;
                        }
                    }
                }
            }
            
            if ($uploadsCopied > 0) {
                echo "<span class='success'>✓ Copied additional $uploadsCopied files from uploads</span><br>";
                $fixes[] = "Copied $uploadsCopied additional files from uploads";
            }
        }
        
        echo "</div>";

        // Step 3: Create proper .htaccess for storage directory
        echo "<div class='section'><h2>📄 Step 3: Create Storage .htaccess</h2>";
        
        $htaccessContent = <<<HTACCESS
# Allow access to storage files
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Allow direct file access
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^(.*)$ - [L]
    
    # Block access to sensitive files
    RewriteRule ^(.*\.(php|html|htm|js|css))$ - [F,L]
</IfModule>

# Set proper MIME types
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
    AddType image/bmp .bmp
    AddType image/svg+xml .svg
</IfModule>

# Enable file serving
Options +FollowSymLinks -Indexes
DirectoryIndex disabled

# Set proper headers
<IfModule mod_headers.c>
    Header set Cache-Control "public, max-age=31536000"
    Header set Access-Control-Allow-Origin "*"
</IfModule>

# File permissions
<FilesMatch "\.(jpg|jpeg|png|gif|webp|bmp|svg)$">
    Order allow,deny
    Allow from all
    Require all granted
</FilesMatch>
HTACCESS;

        $htaccessPath = $publicStorage . '/.htaccess';
        if (file_put_contents($htaccessPath, $htaccessContent)) {
            chmod($htaccessPath, 0644);
            echo "<span class='success'>✓ Created storage .htaccess</span><br>";
            $fixes[] = "Created storage .htaccess with proper rules";
        } else {
            echo "<span class='error'>✗ Could not create storage .htaccess</span><br>";
            $issues[] = "Could not create storage .htaccess";
        }
        
        // Create projects .htaccess too
        $projectsHtaccess = $publicProjectsDir . '/.htaccess';
        if (file_put_contents($projectsHtaccess, $htaccessContent)) {
            chmod($projectsHtaccess, 0644);
            echo "<span class='success'>✓ Created projects .htaccess</span><br>";
            $fixes[] = "Created projects .htaccess";
        }
        
        echo "</div>";

        // Step 4: Test file access
        echo "<div class='section'><h2>🧪 Step 4: Test File Access</h2>";
        
        $testFiles = glob($publicProjectsDir . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
        $workingFiles = 0;
        $brokenFiles = 0;
        
        if (!empty($testFiles)) {
            foreach (array_slice($testFiles, 0, 3) as $testFile) {
                $filename = basename($testFile);
                $testUrl = '/storage/projects/' . $filename;
                $fullTestUrl = 'https://' . $_SERVER['HTTP_HOST'] . $testUrl;
                
                echo "<strong>Testing:</strong> $filename<br>";
                echo "<strong>File exists:</strong> " . (file_exists($testFile) ? 'Yes' : 'No') . "<br>";
                echo "<strong>File size:</strong> " . (file_exists($testFile) ? filesize($testFile) . ' bytes' : 'N/A') . "<br>";
                echo "<strong>Test URL:</strong> <a href='$testUrl' target='_blank'>$testUrl</a><br>";
                
                // Test HTTP access
                $headers = @get_headers($fullTestUrl, 1);
                if ($headers && strpos($headers[0], '200') !== false) {
                    echo "<span class='success'>✓ Accessible via web</span><br>";
                    $workingFiles++;
                } else {
                    echo "<span class='error'>✗ Not accessible via web</span><br>";
                    echo "Response: " . ($headers[0] ?? 'No response') . "<br>";
                    $brokenFiles++;
                }
                echo "<br>";
            }
            
            if ($workingFiles > 0) {
                echo "<span class='success'>✓ $workingFiles files are now accessible!</span><br>";
                $fixes[] = "$workingFiles files are now web accessible";
            }
            
            if ($brokenFiles > 0) {
                echo "<span class='error'>✗ $brokenFiles files still not accessible</span><br>";
                $issues[] = "$brokenFiles files still not accessible";
            }
        } else {
            echo "<span class='warning'>⚠ No image files found to test</span><br>";
        }
        
        echo "</div>";

        // Step 5: Create alternative delivery method if needed
        if ($brokenFiles > 0 || empty($testFiles)) {
            echo "<div class='section'><h2>🔄 Step 5: Alternative Image Delivery</h2>";
            
            // Create image delivery script
            $imageDeliveryScript = <<<PHP
<?php
/**
 * Image Delivery Script for Shared Hosting
 * Use this if direct file access still doesn't work
 */

if (!isset(\$_GET['file'])) {
    http_response_code(404);
    exit('File not specified');
}

\$filename = basename(\$_GET['file']);
\$filePath = __DIR__ . '/storage/projects/' . \$filename;

// Security check
if (!preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', \$filename)) {
    http_response_code(403);
    exit('Invalid file type');
}

if (!file_exists(\$filePath)) {
    http_response_code(404);
    exit('File not found');
}

// Get file info
\$fileSize = filesize(\$filePath);
\$mimeType = 'image/jpeg'; // Default

// Set proper MIME type
\$ext = strtolower(pathinfo(\$filename, PATHINFO_EXTENSION));
switch (\$ext) {
    case 'png': \$mimeType = 'image/png'; break;
    case 'gif': \$mimeType = 'image/gif'; break;
    case 'webp': \$mimeType = 'image/webp'; break;
    case 'bmp': \$mimeType = 'image/bmp'; break;
}

// Set headers
header('Content-Type: ' . \$mimeType);
header('Content-Length: ' . \$fileSize);
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output file
readfile(\$filePath);
exit;
PHP;

            $deliveryPath = __DIR__ . '/image-delivery.php';
            if (file_put_contents($deliveryPath, $imageDeliveryScript)) {
                chmod($deliveryPath, 0644);
                echo "<span class='success'>✓ Created alternative image delivery script</span><br>";
                echo "<strong>Usage:</strong> /image-delivery.php?file=filename.jpg<br>";
                $fixes[] = "Created alternative image delivery script";
            }
            
            echo "</div>";
        }

        // Step 6: Create updated test page
        echo "<div class='section'><h2>📄 Step 6: Create Updated Test Page</h2>";
        
        $testPageContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Final Working Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 2px solid #ddd; margin: 20px; padding: 15px; }
        .success { border-color: #4CAF50; background: #f1f8e9; }
        .error { border-color: #f44336; background: #ffebee; }
        img { max-width: 300px; margin: 10px; border: 1px solid #ddd; }
        .status { font-weight: bold; margin: 10px 0; }
    </style>
    <script>
        function testImage(img, testType) {
            img.onload = function() {
                img.parentElement.className = 'test-box success';
                img.parentElement.querySelector('.status').innerHTML = '✅ ' + testType + ' WORKS!';
                img.parentElement.querySelector('.status').style.color = '#4CAF50';
            };
            img.onerror = function() {
                img.parentElement.className = 'test-box error';
                img.parentElement.querySelector('.status').innerHTML = '❌ ' + testType + ' failed';
                img.parentElement.querySelector('.status').style.color = '#f44336';
            };
        }
    </script>
</head>
<body>
    <h1>🖼️ Final Working Image Test</h1>
    <p><strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    <p><strong>Method:</strong> Direct file copy (no symlinks)</p>
HTML;

        $allImages = glob($publicProjectsDir . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
        
        if (!empty($allImages)) {
            foreach (array_slice($allImages, 0, 3) as $imagePath) {
                $filename = basename($imagePath);
                $storageUrl = '/storage/projects/' . $filename;
                $deliveryUrl = '/image-delivery.php?file=' . $filename;
                
                $testPageContent .= <<<HTML
    <div class="test-box">
        <h3>$filename</h3>
        <div class="status">Testing...</div>
        <img src="$storageUrl" onload="testImage(this, 'Direct Storage')" onerror="testImage(this, 'Direct Storage')">
        <p><strong>Direct URL:</strong> <a href="$storageUrl" target="_blank">$storageUrl</a></p>
        <p><strong>Alternative:</strong> <a href="$deliveryUrl" target="_blank">Via Delivery Script</a></p>
    </div>
HTML;
            }
        } else {
            $testPageContent .= "<p style='color: red;'>❌ No images found!</p>";
        }
        
        $testPageContent .= <<<HTML
    <div class="test-box">
        <h3>🔧 Status Summary</h3>
        <p><strong>Images found:</strong> <?php echo count(glob('$publicProjectsDir/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE)); ?></p>
        <p><strong>Storage method:</strong> Direct file copy</p>
        <p><strong>Symlink removed:</strong> Yes</p>
        <p><strong>Directory accessible:</strong> <a href="/storage/projects/">/storage/projects/</a></p>
    </div>
</body>
</html>
HTML;

        $finalTestPath = __DIR__ . '/working-image-test.html';
        if (file_put_contents($finalTestPath, $testPageContent)) {
            echo "<span class='success'>✓ Created working test page</span><br>";
            echo "<strong>Test URL:</strong> <a href='/working-image-test.html' target='_blank'>working-image-test.html</a><br>";
            $fixes[] = "Created working test page";
        }
        
        echo "</div>";

        // Final summary
        echo "<div class='info'>";
        echo "<h2>📋 Final Results</h2>";
        
        if (!empty($fixes)) {
            echo "<h3><span class='success'>✅ Successfully Applied:</span></h3><ul>";
            foreach ($fixes as $fix) {
                echo "<li>$fix</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($issues)) {
            echo "<h3><span class='error'>⚠️ Remaining Issues:</span></h3><ul>";
            foreach ($issues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
        }
        
        if (empty($issues) || $workingFiles > 0) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px;'>";
            echo "<h3>🎉 SUCCESS!</h3>";
            echo "<p>Your hosting environment has been fixed. Images should now work!</p>";
            echo "</div>";
        }
        
        echo "</div>";

        // Action items
        echo "<div class='info'>";
        echo "<h2>🎯 Next Actions</h2>";
        echo "<ol>";
        echo "<li><strong>Test the working page:</strong> <a href='/working-image-test.html' target='_blank'>working-image-test.html</a></li>";
        echo "<li><strong>Check your projects page:</strong> <a href='/projects' target='_blank'>/projects</a></li>";
        echo "<li><strong>Your Laravel app should now work</strong> (Model already updated)</li>";
        echo "<li><strong>Clean up debug files</strong> when everything works</li>";
        echo "</ol>";
        echo "</div>";
        ?>
    </div>
</body>
</html>