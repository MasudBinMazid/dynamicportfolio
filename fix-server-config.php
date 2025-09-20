<?php
/**
 * Server Configuration Fix for Image Loading
 * This script fixes server configuration issues preventing image access
 * URL: https://yourdomain.com/fix-server-config.php?action=fix
 */

if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Server Configuration Fix</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .error { background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 10px 0; }
            .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
            .btn { background: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔧 Server Configuration Fix</h1>
            
            <div class="error">
                <h3>🚨 Issue Identified</h3>
                <p>Your images exist on the server but can't be accessed via web browser. This is a server configuration issue.</p>
                <p><strong>Problem:</strong> Web server is not serving files from the uploads directory.</p>
            </div>
            
            <div class="info">
                <h3>🛠️ What This Fix Does</h3>
                <ul>
                    <li>Creates proper .htaccess files for uploads directory</li>
                    <li>Sets correct MIME types for images</li>
                    <li>Fixes directory permissions</li>
                    <li>Tests server configuration</li>
                    <li>Creates fallback solutions</li>
                </ul>
            </div>
            
            <p><a href="?action=fix" class="btn">🚀 Fix Server Configuration</a></p>
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
    <title>Server Configuration Fix Results</title>
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
        <h1>🔧 Server Configuration Fix Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $fixes = [];
        $issues = [];

        // Step 1: Create comprehensive .htaccess for uploads
        echo "<div class='section'><h2>📝 Step 1: Create Upload Directory .htaccess</h2>";
        
        $uploadsDir = __DIR__ . '/public/uploads';
        $projectsDir = __DIR__ . '/public/uploads/projects';
        
        // Main uploads .htaccess
        $mainHtaccess = <<<HTACCESS
# Allow access to upload directories
Options +Indexes +FollowSymLinks

# Set MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
    AddType image/bmp .bmp
</IfModule>

# Cache images for better performance
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/webp "access plus 1 month"
    ExpiresByType image/bmp "access plus 1 month"
</IfModule>

# Prevent execution of PHP files
<Files "*.php">
    Require all denied
</Files>

# Allow image files
<FilesMatch "\.(jpg|jpeg|png|gif|webp|bmp)$">
    Require all granted
</FilesMatch>

# Set headers for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|webp|bmp)$">
        Header set Cache-Control "public, max-age=2592000"
        Header set Access-Control-Allow-Origin "*"
    </FilesMatch>
</IfModule>
HTACCESS;

        $mainHtaccessPath = $uploadsDir . '/.htaccess';
        if (file_put_contents($mainHtaccessPath, $mainHtaccess)) {
            echo "<span class='success'>✓ Created main uploads .htaccess</span><br>";
            $fixes[] = "Created uploads directory .htaccess";
        } else {
            echo "<span class='error'>✗ Failed to create uploads .htaccess</span><br>";
            $issues[] = "Could not create uploads .htaccess";
        }

        // Projects specific .htaccess
        $projectsHtaccess = <<<HTACCESS
# Allow direct access to image files
Options +Indexes

# Force correct MIME types
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
    AddType image/bmp .bmp
</IfModule>

# Set proper headers
<IfModule mod_headers.c>
    Header always set Access-Control-Allow-Origin "*"
    Header always set Cache-Control "public, max-age=31536000"
</IfModule>

# Allow all image access
<FilesMatch "\.(jpg|jpeg|png|gif|webp|bmp)$">
    Require all granted
    SetEnvIf Request_URI "\.(jpg|jpeg|png|gif|webp|bmp)$" image_file
</FilesMatch>
HTACCESS;

        $projectsHtaccessPath = $projectsDir . '/.htaccess';
        if (file_put_contents($projectsHtaccessPath, $projectsHtaccess)) {
            echo "<span class='success'>✓ Created projects .htaccess</span><br>";
            $fixes[] = "Created projects directory .htaccess";
        } else {
            echo "<span class='error'>✗ Failed to create projects .htaccess</span><br>";
            $issues[] = "Could not create projects .htaccess";
        }
        echo "</div>";

        // Step 2: Fix permissions
        echo "<div class='section'><h2>🔐 Step 2: Fix Directory Permissions</h2>";
        
        $directories = [
            $uploadsDir,
            $projectsDir
        ];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                $currentPerms = substr(sprintf('%o', fileperms($dir)), -4);
                echo "<strong>" . basename($dir) . ":</strong> Current permissions: $currentPerms ";
                
                if (chmod($dir, 0755)) {
                    echo "<span class='success'>✓ Set to 755</span><br>";
                } else {
                    echo "<span class='warning'>⚠ Could not change permissions</span><br>";
                }
                
                // Fix file permissions
                $files = glob($dir . '/*');
                $fixedFiles = 0;
                foreach ($files as $file) {
                    if (is_file($file) && chmod($file, 0644)) {
                        $fixedFiles++;
                    }
                }
                if ($fixedFiles > 0) {
                    echo "✓ Fixed permissions on $fixedFiles files<br>";
                }
            }
        }
        echo "</div>";

        // Step 3: Test direct file access
        echo "<div class='section'><h2>🧪 Step 3: Test File Access</h2>";
        
        // Get a test image
        $testImages = glob($projectsDir . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
        
        if (!empty($testImages)) {
            $testImage = basename($testImages[0]);
            $testUrl = '/uploads/projects/' . $testImage;
            $fullPath = $projectsDir . '/' . $testImage;
            
            echo "<strong>Testing image:</strong> $testImage<br>";
            echo "<strong>File path:</strong> $fullPath<br>";
            echo "<strong>File exists:</strong> " . (file_exists($fullPath) ? '✓ Yes' : '✗ No') . "<br>";
            echo "<strong>File size:</strong> " . filesize($fullPath) . " bytes<br>";
            echo "<strong>File permissions:</strong> " . substr(sprintf('%o', fileperms($fullPath)), -4) . "<br>";
            echo "<strong>Test URL:</strong> <a href='$testUrl' target='_blank'>$testUrl</a><br>";
            
            // Test with HTTP headers
            echo "<br><strong>HTTP Test:</strong><br>";
            $headers = @get_headers('https://' . $_SERVER['HTTP_HOST'] . $testUrl);
            if ($headers) {
                $status = $headers[0];
                echo "Response: $status<br>";
                if (strpos($status, '200') !== false) {
                    echo "<span class='success'>✓ Image accessible via web</span><br>";
                    $fixes[] = "Images are now accessible via web";
                } else {
                    echo "<span class='error'>✗ Image not accessible via web</span><br>";
                    $issues[] = "Images still not accessible via web";
                }
            } else {
                echo "<span class='warning'>⚠ Could not test HTTP access</span><br>";
            }
        } else {
            echo "<span class='warning'>⚠ No test images found</span><br>";
        }
        echo "</div>";

        // Step 4: Create alternative solution - move to storage
        echo "<div class='section'><h2>🔄 Step 4: Alternative Solution - Use Storage Link</h2>";
        
        $storageDir = __DIR__ . '/storage/app/public/projects';
        $publicStorageLink = __DIR__ . '/public/storage';
        
        // Ensure all images are in storage too
        if (is_dir($projectsDir) && is_dir($storageDir)) {
            $uploadsImages = glob($projectsDir . '/*');
            $copiedToStorage = 0;
            
            foreach ($uploadsImages as $uploadFile) {
                if (is_file($uploadFile)) {
                    $filename = basename($uploadFile);
                    $storageFile = $storageDir . '/' . $filename;
                    
                    if (!file_exists($storageFile)) {
                        if (copy($uploadFile, $storageFile)) {
                            $copiedToStorage++;
                        }
                    }
                }
            }
            
            if ($copiedToStorage > 0) {
                echo "<span class='success'>✓ Copied $copiedToStorage images to storage</span><br>";
                $fixes[] = "Copied images to storage as backup";
            }
        }

        // Test storage link
        if (is_link($publicStorageLink) || is_dir($publicStorageLink)) {
            echo "<span class='success'>✓ Storage link exists</span><br>";
            
            // Test storage access
            $storageTestUrl = '/storage/projects/';
            echo "<strong>Storage test URL:</strong> <a href='$storageTestUrl' target='_blank'>$storageTestUrl</a><br>";
            
            // Update Project model to prefer storage
            echo "<span class='info'>💡 Consider updating Project model to use /storage/ URLs as primary</span><br>";
        } else {
            echo "<span class='error'>✗ No storage link found</span><br>";
            echo "<span class='warning'>⚠ Run: php artisan storage:link</span><br>";
            $issues[] = "Storage link missing";
        }
        echo "</div>";

        // Step 5: Create manual test file
        echo "<div class='section'><h2>📄 Step 5: Create Manual Test</h2>";
        
        $testHtml = <<<HTML
<!DOCTYPE html>
<html>
<head><title>Image Test</title></head>
<body>
<h1>Manual Image Test</h1>
HTML;

        if (!empty($testImages)) {
            foreach (array_slice($testImages, 0, 3) as $imagePath) {
                $filename = basename($imagePath);
                $testHtml .= "<h3>$filename</h3>\n";
                $testHtml .= "<p>Uploads: <img src='/uploads/projects/$filename' style='max-width:200px;' onerror='this.style.border=\"2px solid red\";'></p>\n";
                $testHtml .= "<p>Storage: <img src='/storage/projects/$filename' style='max-width:200px;' onerror='this.style.border=\"2px solid red\";'></p>\n";
            }
        }

        $testHtml .= "</body></html>";
        
        $testFilePath = __DIR__ . '/image-manual-test.html';
        if (file_put_contents($testFilePath, $testHtml)) {
            echo "<span class='success'>✓ Created manual test file</span><br>";
            echo "<strong>Test URL:</strong> <a href='/image-manual-test.html' target='_blank'>image-manual-test.html</a><br>";
            $fixes[] = "Created manual test file";
        }
        echo "</div>";

        // Summary
        echo "<div class='info'>";
        echo "<h2>📋 Fix Summary</h2>";
        
        if (!empty($fixes)) {
            echo "<h3><span class='success'>✅ Applied Fixes:</span></h3><ul>";
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
        echo "</div>";

        // Final recommendations
        echo "<div class='info'>";
        echo "<h2>🎯 Final Recommendations</h2>";
        echo "<ol>";
        echo "<li><strong>Test the manual test file:</strong> <a href='/image-manual-test.html' target='_blank'>image-manual-test.html</a></li>";
        echo "<li><strong>If uploads still don't work:</strong> Use the storage approach (/storage/ URLs)</li>";
        echo "<li><strong>Update your Project model</strong> to prefer storage URLs if uploads fail</li>";
        echo "<li><strong>Contact your hosting provider</strong> if images still don't load</li>";
        echo "<li><strong>Clean up:</strong> Delete all debug scripts after testing</li>";
        echo "</ol>";
        echo "</div>";
        ?>
    </div>
</body>
</html>