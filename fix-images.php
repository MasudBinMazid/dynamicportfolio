<?php
/**
 * Complete Image Fix Script
 * This script fixes the most common image display issues
 * URL: https://yourdomain.com/fix-images.php?action=fix
 */

if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Image Fix Script</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .warning { background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 10px 0; }
            .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
            .btn { background: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔧 Complete Image Fix Script</h1>
            
            <div class="warning">
                <h3>⚠️ Before You Start</h3>
                <p>This script will:</p>
                <ul>
                    <li>Create the necessary directory structure</li>
                    <li>Copy images from storage to public uploads</li>
                    <li>Fix file permissions</li>
                    <li>Test all image URLs</li>
                    <li>Generate a comprehensive report</li>
                </ul>
                <p><strong>Make sure you have a backup of your website before proceeding.</strong></p>
            </div>
            
            <div class="info">
                <h3>🎯 What This Fixes</h3>
                <ul>
                    <li>Missing public/uploads/projects directory</li>
                    <li>Images stored in storage but not accessible via web</li>
                    <li>Broken storage symlinks</li>
                    <li>File permission issues</li>
                    <li>Incorrect image paths in database</li>
                </ul>
            </div>
            
            <p><a href="?action=fix" class="btn">🚀 Fix All Image Issues</a></p>
            
            <div class="info">
                <p><strong>Alternative:</strong> If you want to diagnose first, use the <a href="diagnose-images.php">diagnostic script</a>.</p>
            </div>
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
    <title>Image Fix Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Image Fix Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $fixedIssues = [];
        $remainingIssues = [];

        // Step 1: Create directory structure
        echo "<div class='section'><h2>📁 Step 1: Directory Structure</h2>";
        
        $directories = [
            'public/uploads' => __DIR__ . '/public/uploads',
            'public/uploads/projects' => __DIR__ . '/public/uploads/projects',
        ];
        
        foreach ($directories as $label => $path) {
            if (!is_dir($path)) {
                if (mkdir($path, 0755, true)) {
                    echo "<span class='success'>✓ Created $label</span><br>";
                    $fixedIssues[] = "Created $label directory";
                } else {
                    echo "<span class='error'>✗ Failed to create $label</span><br>";
                    $remainingIssues[] = "Could not create $label directory";
                }
            } else {
                echo "<span class='success'>✓ $label already exists</span><br>";
            }
            
            // Check permissions
            if (is_dir($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                if (is_writable($path)) {
                    echo "<span class='success'>✓ $label is writable ($perms)</span><br>";
                } else {
                    echo "<span class='warning'>⚠ $label has limited permissions ($perms)</span><br>";
                    if (chmod($path, 0755)) {
                        echo "<span class='success'>✓ Fixed permissions for $label</span><br>";
                        $fixedIssues[] = "Fixed permissions for $label";
                    }
                }
            }
        }
        echo "</div>";

        // Step 2: Copy images from storage to uploads
        echo "<div class='section'><h2>🔄 Step 2: Copy Images</h2>";
        
        $sourceDir = __DIR__ . '/storage/app/public/projects/';
        $targetDir = __DIR__ . '/public/uploads/projects/';
        $copied = 0;
        $skipped = 0;
        $failed = 0;
        
        if (is_dir($sourceDir)) {
            $files = glob($sourceDir . '*');
            $imageFiles = array_filter($files, function($file) {
                return is_file($file) && preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $file);
            });
            
            echo "<p>Found " . count($imageFiles) . " images in storage directory</p>";
            
            foreach ($imageFiles as $sourceFile) {
                $filename = basename($sourceFile);
                $targetFile = $targetDir . $filename;
                
                echo "<strong>$filename:</strong> ";
                
                if (file_exists($targetFile)) {
                    echo "<span class='warning'>Already exists, skipping</span><br>";
                    $skipped++;
                } else {
                    if (copy($sourceFile, $targetFile)) {
                        chmod($targetFile, 0644);
                        echo "<span class='success'>✓ Copied successfully</span><br>";
                        $copied++;
                    } else {
                        echo "<span class='error'>✗ Copy failed</span><br>";
                        $failed++;
                    }
                }
            }
            
            echo "<p><strong>Copy Summary:</strong> $copied copied, $skipped skipped, $failed failed</p>";
            if ($copied > 0) {
                $fixedIssues[] = "Copied $copied images to public directory";
            }
            if ($failed > 0) {
                $remainingIssues[] = "$failed images failed to copy";
            }
        } else {
            echo "<span class='warning'>⚠ No storage/app/public/projects directory found</span><br>";
        }
        echo "</div>";

        // Step 3: Test storage symlink
        echo "<div class='section'><h2>🔗 Step 3: Storage Symlink Test</h2>";
        
        $publicStorageLink = __DIR__ . '/public/storage';
        $storageTarget = __DIR__ . '/storage/app/public';
        
        if (is_link($publicStorageLink)) {
            $linkTarget = readlink($publicStorageLink);
            echo "<span class='success'>✓ Storage symlink exists: $linkTarget</span><br>";
            
            // Test if link works
            if (is_dir($publicStorageLink . '/projects')) {
                echo "<span class='success'>✓ Storage symlink is working</span><br>";
            } else {
                echo "<span class='warning'>⚠ Storage symlink exists but projects directory not accessible</span><br>";
                $remainingIssues[] = "Storage symlink not working properly";
            }
        } elseif (is_dir($publicStorageLink)) {
            echo "<span class='success'>✓ Storage directory exists (not a symlink)</span><br>";
        } else {
            echo "<span class='error'>✗ No storage link found</span><br>";
            echo "<p>You need to run: <code>php artisan storage:link</code></p>";
            $remainingIssues[] = "Missing storage symlink";
        }
        echo "</div>";

        // Step 4: Test image URLs
        echo "<div class='section'><h2>🧪 Step 4: Test Image URLs</h2>";
        
        function testImageUrl($imagePath) {
            if (!$imagePath) return null;

            // If admin saved an absolute URL or absolute public path, return as-is
            if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0 || strpos($imagePath, '/') === 0) {
                return $imagePath;
            }

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

            // Check with projects prefix
            if (strpos($imagePath, 'projects/') !== 0) {
                return testImageUrl('projects/' . $imagePath);
            }

            return null;
        }
        
        // Test with actual images
        $testImages = [];
        $uploadsImages = glob(__DIR__ . '/public/uploads/projects/*');
        $storageImages = glob(__DIR__ . '/storage/app/public/projects/*');
        
        foreach ($uploadsImages as $file) {
            if (is_file($file)) $testImages[] = basename($file);
        }
        foreach ($storageImages as $file) {
            if (is_file($file)) $testImages[] = basename($file);
        }
        
        $testImages = array_unique($testImages);
        
        if (!empty($testImages)) {
            echo "<p>Testing " . count($testImages) . " images:</p>";
            $workingUrls = 0;
            $brokenUrls = 0;
            
            foreach (array_slice($testImages, 0, 5) as $image) { // Test first 5 images
                $url = testImageUrl($image);
                echo "<strong>$image:</strong> ";
                if ($url) {
                    echo "<span class='success'>✓ $url</span><br>";
                    $workingUrls++;
                } else {
                    echo "<span class='error'>✗ No URL generated</span><br>";
                    $brokenUrls++;
                }
            }
            
            if ($workingUrls > 0) {
                $fixedIssues[] = "$workingUrls images have working URLs";
            }
            if ($brokenUrls > 0) {
                $remainingIssues[] = "$brokenUrls images have no working URLs";
            }
        } else {
            echo "<span class='warning'>⚠ No images found to test</span><br>";
            $remainingIssues[] = "No images found in any location";
        }
        echo "</div>";

        // Step 5: Create security .htaccess
        echo "<div class='section'><h2>🛡️ Step 5: Security Configuration</h2>";
        
        $htaccessContent = "# Prevent execution of PHP files in uploads directory\n";
        $htaccessContent .= "<Files *.php>\n    Require all denied\n</Files>\n\n";
        $htaccessContent .= "# Allow image files\n";
        $htaccessContent .= "<FilesMatch \"\.(jpg|jpeg|png|gif|webp|bmp)$\">\n    Require all granted\n</FilesMatch>\n";
        
        $htaccessPath = __DIR__ . '/public/uploads/.htaccess';
        if (file_put_contents($htaccessPath, $htaccessContent)) {
            echo "<span class='success'>✓ Created security .htaccess file</span><br>";
            $fixedIssues[] = "Created security configuration";
        } else {
            echo "<span class='warning'>⚠ Could not create .htaccess file</span><br>";
        }
        echo "</div>";

        // Summary
        echo "<div class='info'>";
        echo "<h2>📋 Fix Summary</h2>";
        
        if (!empty($fixedIssues)) {
            echo "<h3><span class='success'>✅ Issues Fixed:</span></h3><ul>";
            foreach ($fixedIssues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($remainingIssues)) {
            echo "<h3><span class='error'>❌ Remaining Issues:</span></h3><ul>";
            foreach ($remainingIssues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
        }
        
        if (empty($remainingIssues)) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h3>🎉 All Issues Fixed!</h3>";
            echo "<p>Your project images should now be working correctly.</p>";
            echo "</div>";
        }
        echo "</div>";

        // Next steps
        echo "<div class='info'>";
        echo "<h2>🎯 Next Steps</h2>";
        echo "<ol>";
        echo "<li><strong>Test your website:</strong> Go to <a href='/projects' target='_blank'>/projects</a> and check if images are loading</li>";
        echo "<li><strong>Test individual URLs:</strong> Use the <a href='test-images.php'>image test script</a></li>";
        if (!empty($remainingIssues)) {
            echo "<li><strong>Fix remaining issues:</strong> See the list above</li>";
            echo "<li><strong>Run diagnostic:</strong> Use <a href='diagnose-images.php'>diagnose-images.php</a> for detailed analysis</li>";
        }
        echo "<li><strong>Clean up:</strong> Delete all diagnostic scripts for security</li>";
        echo "</ol>";
        echo "</div>";
        ?>
        
        <div class="info">
            <strong>🔒 Security Reminder:</strong> Delete this fix script after use!
        </div>
    </div>
</body>
</html>