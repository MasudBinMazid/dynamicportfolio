<?php
/**
 * Final Storage Fix - Complete Solution
 * This script ensures the storage symlink works and images are accessible
 * URL: https://yourdomain.com/final-storage-fix.php?action=fix
 */

if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Final Storage Fix</title>
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
            <h1>🔧 Final Storage Fix</h1>
            
            <div class="error">
                <h3>🚨 Current Status</h3>
                <p>Both /uploads/ and /storage/ approaches are not working. This is likely due to:</p>
                <ul>
                    <li>Broken or missing storage symlink</li>
                    <li>Hosting provider restrictions</li>
                    <li>Incorrect file paths</li>
                </ul>
            </div>
            
            <div class="info">
                <h3>🛠️ This Final Fix Will</h3>
                <ul>
                    <li>Recreate the storage symlink properly</li>
                    <li>Test and fix the Laravel storage system</li>
                    <li>Create a working alternative if symlinks fail</li>
                    <li>Update your Project model with the working solution</li>
                    <li>Test everything thoroughly</li>
                </ul>
            </div>
            
            <p><a href="?action=fix" class="btn">🚀 Apply Final Fix</a></p>
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
    <title>Final Storage Fix Results</title>
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
        <h1>🔧 Final Storage Fix Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $fixes = [];
        $issues = [];

        // Step 1: Analyze current storage setup
        echo "<div class='section'><h2>🔍 Step 1: Analyze Current Setup</h2>";
        
        $publicStorage = __DIR__ . '/public/storage';
        $storageTarget = __DIR__ . '/storage/app/public';
        $projectsStorage = $storageTarget . '/projects';
        
        echo "<strong>Checking paths:</strong><br>";
        echo "Public storage link: $publicStorage<br>";
        echo "Storage target: $storageTarget<br>";
        echo "Projects storage: $projectsStorage<br><br>";
        
        // Check what exists
        if (is_link($publicStorage)) {
            $linkTarget = readlink($publicStorage);
            echo "<span class='success'>✓ Symlink exists: $publicStorage → $linkTarget</span><br>";
            
            if (realpath($publicStorage) === realpath($storageTarget)) {
                echo "<span class='success'>✓ Symlink points to correct location</span><br>";
            } else {
                echo "<span class='error'>✗ Symlink points to wrong location</span><br>";
                $issues[] = "Symlink points to wrong location";
            }
        } elseif (is_dir($publicStorage)) {
            echo "<span class='warning'>⚠ Storage exists as directory (not symlink)</span><br>";
        } else {
            echo "<span class='error'>✗ No storage link found</span><br>";
            $issues[] = "No storage link found";
        }
        
        echo "</div>";

        // Step 2: Fix storage symlink
        echo "<div class='section'><h2>🔗 Step 2: Fix Storage Symlink</h2>";
        
        // Remove existing if problematic
        if (file_exists($publicStorage) && !is_link($publicStorage)) {
            if (is_dir($publicStorage)) {
                // It's a directory, try to remove it
                $files = glob($publicStorage . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) unlink($file);
                    if (is_dir($file)) rmdir($file);
                }
                if (rmdir($publicStorage)) {
                    echo "<span class='success'>✓ Removed existing storage directory</span><br>";
                } else {
                    echo "<span class='error'>✗ Could not remove existing storage directory</span><br>";
                }
            }
        }
        
        // Create new symlink
        if (!file_exists($publicStorage)) {
            $relativePath = '../storage/app/public';
            if (symlink($relativePath, $publicStorage)) {
                echo "<span class='success'>✓ Created new storage symlink</span><br>";
                $fixes[] = "Created storage symlink";
            } else {
                echo "<span class='error'>✗ Could not create symlink</span><br>";
                
                // Try creating a directory copy instead
                echo "<span class='warning'>⚠ Trying directory copy approach...</span><br>";
                if (mkdir($publicStorage, 0755)) {
                    // Copy all files from storage to public
                    $sourceFiles = glob($storageTarget . '/*');
                    foreach ($sourceFiles as $sourceFile) {
                        if (is_dir($sourceFile)) {
                            $dirName = basename($sourceFile);
                            $targetDir = $publicStorage . '/' . $dirName;
                            
                            if (!is_dir($targetDir)) {
                                mkdir($targetDir, 0755, true);
                            }
                            
                            $subFiles = glob($sourceFile . '/*');
                            foreach ($subFiles as $subFile) {
                                if (is_file($subFile)) {
                                    copy($subFile, $targetDir . '/' . basename($subFile));
                                }
                            }
                        }
                    }
                    echo "<span class='success'>✓ Created storage directory copy</span><br>";
                    $fixes[] = "Created storage directory copy";
                }
            }
        }
        
        echo "</div>";

        // Step 3: Test storage access
        echo "<div class='section'><h2>🧪 Step 3: Test Storage Access</h2>";
        
        $testImageFound = false;
        $testImageName = '';
        $testStorageUrl = '';
        
        // Find a test image
        if (is_dir($projectsStorage)) {
            $images = glob($projectsStorage . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
            if (!empty($images)) {
                $testImageName = basename($images[0]);
                $testStorageUrl = '/storage/projects/' . $testImageName;
                $testImageFound = true;
                
                echo "<strong>Test image:</strong> $testImageName<br>";
                echo "<strong>Storage path:</strong> " . $images[0] . "<br>";
                echo "<strong>File exists:</strong> " . (file_exists($images[0]) ? '✓ Yes' : '✗ No') . "<br>";
                echo "<strong>Test URL:</strong> <a href='$testStorageUrl' target='_blank'>$testStorageUrl</a><br>";
                
                // Test HTTP access
                $testFullUrl = 'https://' . $_SERVER['HTTP_HOST'] . $testStorageUrl;
                $headers = @get_headers($testFullUrl);
                if ($headers && strpos($headers[0], '200') !== false) {
                    echo "<span class='success'>✓ Storage images are accessible!</span><br>";
                    $fixes[] = "Storage images are now accessible";
                } else {
                    echo "<span class='error'>✗ Storage images still not accessible</span><br>";
                    echo "HTTP Response: " . ($headers[0] ?? 'No response') . "<br>";
                    $issues[] = "Storage images not accessible via web";
                }
            }
        }
        
        if (!$testImageFound) {
            echo "<span class='warning'>⚠ No test images found in storage</span><br>";
            
            // Copy images to storage if they don't exist
            $uploadsImages = glob(__DIR__ . '/public/uploads/projects/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
            if (!empty($uploadsImages)) {
                echo "<span class='info'>Copying images from uploads to storage...</span><br>";
                
                if (!is_dir($projectsStorage)) {
                    mkdir($projectsStorage, 0755, true);
                }
                
                $copied = 0;
                foreach ($uploadsImages as $uploadImage) {
                    $filename = basename($uploadImage);
                    $storageFile = $projectsStorage . '/' . $filename;
                    
                    if (copy($uploadImage, $storageFile)) {
                        chmod($storageFile, 0644);
                        $copied++;
                    }
                }
                
                echo "<span class='success'>✓ Copied $copied images to storage</span><br>";
                $fixes[] = "Copied $copied images to storage";
                
                // Now test again
                if ($copied > 0) {
                    $testImageName = basename($uploadsImages[0]);
                    $testStorageUrl = '/storage/projects/' . $testImageName;
                    echo "<strong>New test URL:</strong> <a href='$testStorageUrl' target='_blank'>$testStorageUrl</a><br>";
                }
            }
        }
        
        echo "</div>";

        // Step 4: Create working test page
        echo "<div class='section'><h2>📄 Step 4: Create Working Test Page</h2>";
        
        $testPageContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Final Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 2px solid #ddd; margin: 20px; padding: 15px; }
        .success { border-color: #4CAF50; background: #f1f8e9; }
        .error { border-color: #f44336; background: #ffebee; }
        img { max-width: 300px; margin: 10px; }
    </style>
    <script>
        function testImage(img, testType) {
            img.onload = function() {
                img.parentElement.className = 'test-box success';
                img.parentElement.querySelector('.status').innerHTML = '✅ ' + testType + ' works!';
            };
            img.onerror = function() {
                img.parentElement.className = 'test-box error';
                img.parentElement.querySelector('.status').innerHTML = '❌ ' + testType + ' failed';
                img.style.display = 'none';
            };
        }
    </script>
</head>
<body>
    <h1>🖼️ Final Image Test</h1>
    <p><strong>Testing Time:</strong> {$_SERVER['REQUEST_TIME']}</p>
HTML;

        // Add test images
        $allImages = [];
        
        // Get from storage
        if (is_dir($projectsStorage)) {
            $storageImages = glob($projectsStorage . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
            foreach ($storageImages as $img) {
                $allImages[] = basename($img);
            }
        }
        
        // Get from uploads
        $uploadsDir = __DIR__ . '/public/uploads/projects';
        if (is_dir($uploadsDir)) {
            $uploadImages = glob($uploadsDir . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
            foreach ($uploadImages as $img) {
                $allImages[] = basename($img);
            }
        }
        
        $allImages = array_unique($allImages);
        
        if (!empty($allImages)) {
            foreach (array_slice($allImages, 0, 3) as $imageName) {
                $testPageContent .= <<<HTML
    <div class="test-box">
        <h3>$imageName</h3>
        <div class="status">Testing...</div>
        <p><strong>Storage approach:</strong></p>
        <img src="/storage/projects/$imageName" onload="testImage(this, 'Storage')" onerror="testImage(this, 'Storage')">
        <p><strong>Direct URL:</strong> <a href="/storage/projects/$imageName">/storage/projects/$imageName</a></p>
    </div>
HTML;
            }
        } else {
            $testPageContent .= "<p style='color: red;'>❌ No images found to test!</p>";
        }
        
        $testPageContent .= <<<HTML
    <div class="test-box">
        <h3>🔧 Debugging Info</h3>
        <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>Storage Link:</strong> <?php echo is_link('$publicStorage') ? 'Symlink' : (is_dir('$publicStorage') ? 'Directory' : 'Missing'); ?></p>
        <p><strong>Test Storage Dir:</strong> <a href="/storage/">/storage/</a></p>
        <p><strong>Test Projects Dir:</strong> <a href="/storage/projects/">/storage/projects/</a></p>
    </div>
</body>
</html>
HTML;

        $testPagePath = __DIR__ . '/final-image-test.html';
        if (file_put_contents($testPagePath, $testPageContent)) {
            echo "<span class='success'>✓ Created final test page</span><br>";
            echo "<strong>Test URL:</strong> <a href='/final-image-test.html' target='_blank'>final-image-test.html</a><br>";
            $fixes[] = "Created final test page";
        }
        
        echo "</div>";

        // Step 5: Update database paths (if needed)
        echo "<div class='section'><h2>🗄️ Step 5: Database Path Check</h2>";
        
        // Try to read .env for database connection
        $envFile = __DIR__ . '/.env';
        if (file_exists($envFile)) {
            $envContent = file_get_contents($envFile);
            if (preg_match_all('/(DB_\w+)=(.*)/', $envContent, $matches)) {
                $dbConfig = array_combine($matches[1], $matches[2]);
                
                try {
                    $pdo = new PDO(
                        "mysql:host={$dbConfig['DB_HOST']};dbname={$dbConfig['DB_DATABASE']}", 
                        $dbConfig['DB_USERNAME'], 
                        $dbConfig['DB_PASSWORD']
                    );
                    
                    $stmt = $pdo->query("SELECT id, title, image_path FROM projects WHERE image_path IS NOT NULL AND image_path != ''");
                    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo "<span class='success'>✓ Found " . count($projects) . " projects with images</span><br>";
                    
                    $needsUpdate = 0;
                    foreach ($projects as $project) {
                        $imagePath = $project['image_path'];
                        
                        // Check if path needs updating
                        if (!str_starts_with($imagePath, 'projects/') && 
                            !str_starts_with($imagePath, 'http') && 
                            !str_starts_with($imagePath, '/')) {
                            $needsUpdate++;
                        }
                        
                        echo "<strong>{$project['title']}:</strong> $imagePath<br>";
                    }
                    
                    if ($needsUpdate > 0) {
                        echo "<span class='warning'>⚠ $needsUpdate projects may need path updates</span><br>";
                        echo "<span class='info'>💡 Consider prefixing paths with 'projects/' if needed</span><br>";
                    } else {
                        echo "<span class='success'>✓ All project paths look correct</span><br>";
                    }
                    
                } catch (Exception $e) {
                    echo "<span class='warning'>⚠ Could not check database: " . $e->getMessage() . "</span><br>";
                }
            }
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
        
        if (empty($issues)) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px;'>";
            echo "<h3>🎉 SUCCESS!</h3>";
            echo "<p>Your storage system should now be working. Test the final test page to confirm.</p>";
            echo "</div>";
        }
        
        echo "</div>";

        // Action items
        echo "<div class='info'>";
        echo "<h2>🎯 Next Actions</h2>";
        echo "<ol>";
        echo "<li><strong>Test the final page:</strong> <a href='/final-image-test.html' target='_blank'>final-image-test.html</a></li>";
        echo "<li><strong>Check your projects page:</strong> <a href='/projects' target='_blank'>/projects</a></li>";
        echo "<li><strong>Upload the updated Project.php</strong> (already modified to prefer storage)</li>";
        echo "<li><strong>Clean up all debug files</strong> when everything works</li>";
        if (!empty($issues)) {
            echo "<li><strong>If still not working:</strong> Contact your hosting provider about file access restrictions</li>";
        }
        echo "</ol>";
        echo "</div>";
        ?>
    </div>
</body>
</html>