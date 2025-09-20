<?php
/**
 * Complete Setup Script for Portfolio Image System
 * This script sets up the image system to work without fileinfo extension
 * Run this once: https://yourdomain.com/setup-images.php?action=setup
 */

// Security check
if (!isset($_GET['action']) || $_GET['action'] !== 'setup') {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Portfolio Image Setup</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
            .warning { background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 10px 0; }
            .btn { background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
            .btn:hover { background: #45a049; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🖼️ Portfolio Image System Setup</h1>
            
            <div class="info">
                <h3>What this script will do:</h3>
                <ul>
                    <li>Create the public/uploads/projects directory</li>
                    <li>Copy existing images from storage to public uploads</li>
                    <li>Set proper permissions</li>
                    <li>Test the image system</li>
                    <li>Generate a summary report</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>⚠️ Important:</strong> This script will copy your project images to a new location. 
                Make sure you have a backup of your website before proceeding.
            </div>
            
            <p>
                <a href="?action=setup" class="btn">🚀 Setup Image System</a>
            </p>
            
            <div class="info">
                <strong>Why this is needed:</strong><br>
                Your server doesn't have the PHP fileinfo extension enabled, which Laravel's storage system requires. 
                This setup creates an alternative image system that works without fileinfo.
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
    <title>Image Setup Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Image System Setup Results</h1>
        <p><strong>Executed:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        $results = [];
        $errors = [];
        $warnings = [];

        // Step 1: Create uploads directory structure
        echo "<div class='section'><h2>📁 Creating Directory Structure</h2>";
        
        $uploadsDir = __DIR__ . '/public/uploads';
        $projectsDir = __DIR__ . '/public/uploads/projects';
        
        if (!is_dir($uploadsDir)) {
            if (mkdir($uploadsDir, 0755, true)) {
                echo "<span class='success'>✓ Created uploads directory</span><br>";
                $results[] = "Created uploads directory";
            } else {
                echo "<span class='error'>✗ Failed to create uploads directory</span><br>";
                $errors[] = "Failed to create uploads directory";
            }
        } else {
            echo "<span class='success'>✓ Uploads directory already exists</span><br>";
        }
        
        if (!is_dir($projectsDir)) {
            if (mkdir($projectsDir, 0755, true)) {
                echo "<span class='success'>✓ Created projects directory</span><br>";
                $results[] = "Created projects directory";
            } else {
                echo "<span class='error'>✗ Failed to create projects directory</span><br>";
                $errors[] = "Failed to create projects directory";
            }
        } else {
            echo "<span class='success'>✓ Projects directory already exists</span><br>";
        }
        echo "</div>";

        // Step 2: Copy existing images
        echo "<div class='section'><h2>🔄 Migrating Existing Images</h2>";
        
        $sourceDir = __DIR__ . '/storage/app/public/projects/';
        $migrated = 0;
        $skipped = 0;
        
        if (is_dir($sourceDir)) {
            $files = glob($sourceDir . '*');
            echo "<p>Found " . count($files) . " files in storage directory</p>";
            
            foreach ($files as $sourceFile) {
                if (!is_file($sourceFile)) continue;
                
                $filename = basename($sourceFile);
                $targetFile = $projectsDir . '/' . $filename;
                
                echo "<strong>Processing:</strong> $filename<br>";
                
                if (file_exists($targetFile)) {
                    echo "<span class='warning'>⚠ Already exists, skipping</span><br>";
                    $skipped++;
                } else {
                    if (copy($sourceFile, $targetFile)) {
                        echo "<span class='success'>✓ Copied successfully</span><br>";
                        $migrated++;
                        
                        // Set permissions
                        chmod($targetFile, 0644);
                    } else {
                        echo "<span class='error'>✗ Copy failed</span><br>";
                        $errors[] = "Failed to copy $filename";
                    }
                }
            }
            
            echo "<p><strong>Migration Summary:</strong> $migrated copied, $skipped skipped</p>";
            $results[] = "Migrated $migrated images";
        } else {
            echo "<span class='warning'>⚠ No storage/app/public/projects directory found</span><br>";
            $warnings[] = "No existing images to migrate";
        }
        echo "</div>";

        // Step 3: Test image URL generation
        echo "<div class='section'><h2>🧪 Testing Image URL Generation</h2>";
        
        function testImageUrl($imagePath) {
            // Replicate the Project model logic
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
        
        // Test with actual images
        $actualImages = glob($projectsDir . '/*');
        if (!empty($actualImages)) {
            echo "<strong>Testing with actual images:</strong><br>";
            foreach (array_slice($actualImages, 0, 3) as $imagePath) {
                $filename = basename($imagePath);
                $url = testImageUrl($filename);
                if ($url) {
                    echo "<span class='success'>✓ $filename → $url</span><br>";
                } else {
                    echo "<span class='error'>✗ $filename → No URL generated</span><br>";
                }
            }
        } else {
            echo "<span class='warning'>⚠ No images found to test</span><br>";
        }
        echo "</div>";

        // Step 4: Verify permissions
        echo "<div class='section'><h2>🔐 Verifying Permissions</h2>";
        
        $checkDirs = [
            'public/uploads' => $uploadsDir,
            'public/uploads/projects' => $projectsDir,
            'storage/app/public' => __DIR__ . '/storage/app/public',
        ];
        
        foreach ($checkDirs as $name => $path) {
            if (is_dir($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path);
                if ($writable) {
                    echo "<span class='success'>✓ $name: $perms (Writable)</span><br>";
                } else {
                    echo "<span class='error'>✗ $name: $perms (Not Writable)</span><br>";
                    $errors[] = "$name is not writable";
                }
            } else {
                echo "<span class='error'>✗ $name: Does not exist</span><br>";
            }
        }
        echo "</div>";

        // Step 5: Create .htaccess for uploads directory
        echo "<div class='section'><h2>🛡️ Setting up Security</h2>";
        
        $htaccessContent = "# Prevent execution of PHP files in uploads directory\n";
        $htaccessContent .= "<Files *.php>\n";
        $htaccessContent .= "    Require all denied\n";
        $htaccessContent .= "</Files>\n\n";
        $htaccessContent .= "# Allow image files\n";
        $htaccessContent .= "<FilesMatch \"\.(jpg|jpeg|png|gif|webp|bmp)$\">\n";
        $htaccessContent .= "    Require all granted\n";
        $htaccessContent .= "</FilesMatch>\n";
        
        $htaccessPath = $uploadsDir . '/.htaccess';
        if (file_put_contents($htaccessPath, $htaccessContent)) {
            echo "<span class='success'>✓ Created security .htaccess file</span><br>";
            $results[] = "Created security configuration";
        } else {
            echo "<span class='warning'>⚠ Could not create .htaccess file</span><br>";
            $warnings[] = "Could not create security configuration";
        }
        echo "</div>";

        // Summary
        echo "<div class='info'>";
        echo "<h2>📋 Setup Summary</h2>";
        
        if (!empty($results)) {
            echo "<h3><span class='success'>✅ Completed Successfully:</span></h3><ul>";
            foreach ($results as $result) {
                echo "<li>$result</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($warnings)) {
            echo "<h3><span class='warning'>⚠️ Warnings:</span></h3><ul>";
            foreach ($warnings as $warning) {
                echo "<li>$warning</li>";
            }
            echo "</ul>";
        }
        
        if (!empty($errors)) {
            echo "<h3><span class='error'>❌ Errors:</span></h3><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
        
        echo "</div>";

        // Next steps
        echo "<div class='info'>";
        echo "<h2>🎯 Next Steps</h2>";
        echo "<ol>";
        echo "<li><strong>Test your website:</strong> Check if project images are now loading correctly</li>";
        echo "<li><strong>Upload new images:</strong> New uploads will go to public/uploads/projects/</li>";
        echo "<li><strong>Update admin panel:</strong> Ensure the updated controller code is deployed</li>";
        echo "<li><strong>Clean up:</strong> Delete this setup script for security</li>";
        if ($migrated > 0) {
            echo "<li><strong>Optional:</strong> After confirming everything works, you can delete the old images from storage/app/public/projects/</li>";
        }
        echo "</ol>";
        echo "</div>";

        // Configuration summary
        echo "<div class='section'>";
        echo "<h2>⚙️ Configuration Summary</h2>";
        echo "<div class='code'>";
        echo "<strong>Image Storage Locations:</strong><br>";
        echo "• New uploads: public/uploads/projects/<br>";
        echo "• Legacy storage: storage/app/public/projects/ (via /storage/ URL)<br>";
        echo "• Project model: Updated to check both locations<br>";
        echo "• Admin controller: Updated to upload without fileinfo<br>";
        echo "</div>";
        echo "</div>";
        ?>
        
        <div class="info">
            <strong>🔒 Security Reminder:</strong> Delete this setup script (setup-images.php) after successful setup!
        </div>
    </div>
</body>
</html>