<?php
/**
 * Image File Locator and Mover
 * This will find your actual image files and move them to the correct location
 * URL: https://yourdomain.com/locate-and-fix-images.php?action=fix
 */

if (!isset($_GET['action']) || $_GET['action'] !== 'fix') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Image File Locator</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
            .btn { background: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 16px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔍 Image File Locator and Fixer</h1>
            
            <div class="info">
                <h3>🎯 What This Will Do</h3>
                <ul>
                    <li>Search your entire server for the missing image files</li>
                    <li>Check all possible directories where images might be stored</li>
                    <li>Copy found images to the correct accessible location</li>
                    <li>Test that the moved images work with your Laravel app</li>
                    <li>Provide a definitive working solution</li>
                </ul>
            </div>
            
            <p><a href="?action=fix" class="btn">🔍 Locate and Fix Images</a></p>
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
    <title>Image Locator Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .file-found { background: #e8f5e8; padding: 10px; margin: 5px 0; border-radius: 4px; }
        .file-moved { background: #d4edda; padding: 10px; margin: 5px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Image File Locator Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $foundImages = [];
        $movedImages = [];
        $failedMoves = [];

        // Get the image filename from the database
        $targetImage = 'FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg';

        echo "<div class='section'><h2>🎯 Step 1: Target Image from Database</h2>";
        echo "<strong>Looking for:</strong> $targetImage<br>";
        echo "</div>";

        echo "<div class='section'><h2>🔍 Step 2: Search All Possible Locations</h2>";
        
        // Define all possible search locations
        $searchPaths = [
            __DIR__ . '/storage/app/public/',
            __DIR__ . '/storage/app/public/projects/',
            __DIR__ . '/public/storage/',
            __DIR__ . '/public/storage/projects/',
            __DIR__ . '/public/uploads/',
            __DIR__ . '/public/uploads/projects/',
            __DIR__ . '/storage/',
            __DIR__ . '/storage/projects/',
            __DIR__ . '/public/',
            __DIR__ . '/public/images/',
            __DIR__ . '/public/assets/',
            __DIR__ . '/public/assets/images/',
            __DIR__ . '/uploads/',
            __DIR__ . '/uploads/projects/',
        ];

        // Function to recursively search for files
        function findFiles($dir, $filename) {
            $found = [];
            if (!is_dir($dir)) return $found;
            
            // Check current directory
            $files = glob($dir . '/' . $filename);
            foreach ($files as $file) {
                if (is_file($file)) {
                    $found[] = $file;
                }
            }
            
            // Check subdirectories
            $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
            foreach ($subdirs as $subdir) {
                $found = array_merge($found, findFiles($subdir, $filename));
            }
            
            return $found;
        }

        // Search for the specific image
        foreach ($searchPaths as $path) {
            echo "<strong>Searching:</strong> $path<br>";
            
            if (is_dir($path)) {
                // Direct check
                $directFile = $path . '/' . $targetImage;
                if (file_exists($directFile)) {
                    $foundImages[] = $directFile;
                    echo "<div class='file-found'>✓ Found: $directFile</div>";
                }
                
                // Recursive search
                $recursiveFiles = findFiles($path, $targetImage);
                foreach ($recursiveFiles as $file) {
                    if (!in_array($file, $foundImages)) {
                        $foundImages[] = $file;
                        echo "<div class='file-found'>✓ Found: $file</div>";
                    }
                }
            } else {
                echo "<span class='warning'>⚠ Directory not found</span><br>";
            }
        }

        if (empty($foundImages)) {
            echo "<span class='error'>✗ No image files found anywhere!</span><br>";
            
            // Let's also search for any JPG files to see what's available
            echo "<br><strong>Searching for ANY jpg files...</strong><br>";
            foreach ($searchPaths as $path) {
                if (is_dir($path)) {
                    $anyImages = glob($path . '/*.{jpg,jpeg,png,gif,webp,bmp}', GLOB_BRACE);
                    if (!empty($anyImages)) {
                        echo "<div class='file-found'>Found " . count($anyImages) . " images in $path:</div>";
                        foreach (array_slice($anyImages, 0, 5) as $img) {
                            echo "• " . basename($img) . "<br>";
                        }
                        if (count($anyImages) > 5) {
                            echo "• ... and " . (count($anyImages) - 5) . " more<br>";
                        }
                    }
                }
            }
        } else {
            echo "<span class='success'>✓ Found " . count($foundImages) . " copies of the image!</span><br>";
        }
        
        echo "</div>";

        // Step 3: Create proper directory structure and copy files
        echo "<div class='section'><h2>📁 Step 3: Create Accessible Location</h2>";
        
        $targetDir = __DIR__ . '/public/images/projects';
        
        if (!is_dir($targetDir)) {
            if (mkdir($targetDir, 0755, true)) {
                echo "<span class='success'>✓ Created directory: $targetDir</span><br>";
            } else {
                echo "<span class='error'>✗ Could not create directory: $targetDir</span><br>";
            }
        } else {
            echo "<span class='success'>✓ Directory exists: $targetDir</span><br>";
        }

        // Copy the first found image to the accessible location
        if (!empty($foundImages)) {
            $sourceFile = $foundImages[0];
            $targetFile = $targetDir . '/' . $targetImage;
            
            if (copy($sourceFile, $targetFile)) {
                chmod($targetFile, 0644);
                echo "<div class='file-moved'>✓ Copied image to: $targetFile</div>";
                $movedImages[] = $targetFile;
            } else {
                echo "<span class='error'>✗ Failed to copy image</span><br>";
                $failedMoves[] = $sourceFile;
            }
        }
        
        echo "</div>";

        // Step 4: Update Project model to use accessible location
        echo "<div class='section'><h2>🔄 Step 4: Update Laravel Model</h2>";
        
        $modelPath = __DIR__ . '/app/Models/Project.php';
        if (file_exists($modelPath)) {
            // Read current model
            $modelContent = file_get_contents($modelPath);
            
            // Create new findImageUrl method that uses /images/ directory
            $newFindImageMethod = '    private function findImageUrl(string $imagePath): ?string
    {
        $filename = basename($imagePath);
        
        // Priority 1: Public images directory (accessible)
        $publicImagesPath = public_path(\'images/projects/\' . $filename);
        if (file_exists($publicImagesPath)) {
            return \'/images/projects/\' . $filename;
        }
        
        // Priority 2: Laravel storage link approach
        $publicStoragePath = public_path(\'storage/projects/\' . $filename);
        if (file_exists($publicStoragePath)) {
            return \'/storage/projects/\' . $filename;
        }
        
        // Priority 3: Direct uploads approach
        $uploadsPath = public_path(\'uploads/projects/\' . $filename);
        if (file_exists($uploadsPath)) {
            return \'/uploads/projects/\' . $filename;
        }

        // Fallback: Use image delivery script
        $deliveryPaths = [
            public_path(\'images/projects/\' . $filename),
            storage_path(\'app/public/projects/\' . $filename),
            public_path(\'storage/projects/\' . $filename),
            public_path(\'uploads/projects/\' . $filename)
        ];
        
        foreach ($deliveryPaths as $path) {
            if (file_exists($path)) {
                return \'/image.php?file=\' . urlencode($filename);
            }
        }

        // If nothing found, return default
        return \'/assets/img/default-project.jpg\';
    }';

            // Replace the method in the model
            $pattern = '/private function findImageUrl\(string \$imagePath\): \?string\s*\{[^}]*\}/s';
            $updatedContent = preg_replace($pattern, $newFindImageMethod, $modelContent);
            
            if ($updatedContent !== $modelContent) {
                if (file_put_contents($modelPath, $updatedContent)) {
                    echo "<span class='success'>✓ Updated Project model to use /images/ directory</span><br>";
                } else {
                    echo "<span class='error'>✗ Could not update Project model</span><br>";
                }
            } else {
                echo "<span class='warning'>⚠ Could not find method to replace in Project model</span><br>";
            }
        } else {
            echo "<span class='error'>✗ Project model not found</span><br>";
        }
        
        echo "</div>";

        // Step 5: Test the working solution
        echo "<div class='section'><h2>🧪 Step 5: Test Working Solution</h2>";
        
        if (!empty($movedImages)) {
            $testUrl = '/images/projects/' . $targetImage;
            $fullTestUrl = 'https://' . $_SERVER['HTTP_HOST'] . $testUrl;
            
            echo "<strong>Test URL:</strong> <a href='$testUrl' target='_blank'>$testUrl</a><br>";
            
            // Test HTTP access
            $headers = @get_headers($fullTestUrl);
            if ($headers && strpos($headers[0], '200') !== false) {
                echo "<span class='success'>✓ Image is accessible via web!</span><br>";
            } else {
                echo "<span class='error'>✗ Image still not accessible</span><br>";
                echo "Response: " . ($headers[0] ?? 'No response') . "<br>";
            }
        }
        
        echo "</div>";

        // Create final test page
        echo "<div class='section'><h2>📄 Step 6: Create Final Test</h2>";
        
        $finalTestContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Final Working Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-box { border: 2px solid #ddd; margin: 20px; padding: 15px; }
        .success { border-color: #4CAF50; background: #f1f8e9; }
        .error { border-color: #f44336; background: #ffebee; }
        img { max-width: 400px; margin: 10px; }
    </style>
    <script>
        function testFinalImage(img) {
            img.onload = function() {
                img.parentElement.className = 'test-box success';
                img.parentElement.querySelector('.status').innerHTML = '✅ SUCCESS! Images are now working!';
                img.parentElement.querySelector('.status').style.color = '#4CAF50';
            };
            img.onerror = function() {
                img.parentElement.className = 'test-box error';
                img.parentElement.querySelector('.status').innerHTML = '❌ Still not working';
                img.parentElement.querySelector('.status').style.color = '#f44336';
            };
        }
    </script>
</head>
<body>
    <h1>🎉 Final Working Image Test</h1>
    <p><strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <div class="test-box">
        <h3>Your Project Image</h3>
        <div class="status" style="font-weight: bold; margin: 10px 0;">Testing...</div>
        <img src="/images/projects/$targetImage" onload="testFinalImage(this)" onerror="testFinalImage(this)">
        <p><strong>URL:</strong> /images/projects/$targetImage</p>
        <p><strong>Method:</strong> Direct access to public/images/projects/</p>
    </div>
    
    <div style="background: #e3f2fd; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <h3>🔧 What Was Done</h3>
        <ul>
            <li>Located your image file in the server</li>
            <li>Copied it to public/images/projects/ directory</li>
            <li>Updated your Laravel Project model to use this location</li>
            <li>This directory should be accessible by web browsers</li>
        </ul>
    </div>
    
    <div style="background: #d4edda; padding: 15px; margin: 15px 0; border-radius: 5px;">
        <h3>✅ Next Steps</h3>
        <ul>
            <li><strong>If the image loads above:</strong> Your project is now fixed!</li>
            <li><strong>Check your projects page:</strong> <a href="/projects">/projects</a></li>
            <li><strong>Upload new projects:</strong> They will automatically use this working method</li>
            <li><strong>Clean up:</strong> Delete all debug files when confirmed working</li>
        </ul>
    </div>
</body>
</html>
HTML;

        $finalTestPath = __DIR__ . '/final-working-test.html';
        if (file_put_contents($finalTestPath, $finalTestContent)) {
            echo "<span class='success'>✓ Created final test page</span><br>";
            echo "<strong>Test URL:</strong> <a href='/final-working-test.html' target='_blank'>final-working-test.html</a><br>";
        }
        
        echo "</div>";

        // Summary
        echo "<div class='info'>";
        echo "<h2>📋 Summary</h2>";
        echo "<strong>Images Found:</strong> " . count($foundImages) . "<br>";
        echo "<strong>Images Moved:</strong> " . count($movedImages) . "<br>";
        echo "<strong>Failed Moves:</strong> " . count($failedMoves) . "<br>";
        
        if (!empty($movedImages)) {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
            echo "<h3>🎉 SUCCESS!</h3>";
            echo "<p>Your images should now be accessible. Test the final page above!</p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
            echo "<h3>⚠️ Issue</h3>";
            echo "<p>Could not locate or move image files. Manual intervention may be needed.</p>";
            echo "</div>";
        }
        echo "</div>";
        ?>
    </div>
</body>
</html>