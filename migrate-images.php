<?php
/**
 * Image Migration Helper Script
 * This script helps move images from storage/app/public to public/uploads for better compatibility
 * Upload this to your server root and run it once: https://yourdomain.com/migrate-images.php?run=true
 */

// Security check
if (!isset($_GET['run']) || $_GET['run'] !== 'true') {
    die('Add ?run=true to the URL to execute this migration script');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Image Migration Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; }
        .error { color: #f44336; }
        .warning { color: #ff9800; }
        .info { background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Image Migration Results</h1>
        
        <?php
        $sourceDir = __DIR__ . '/storage/app/public/projects/';
        $targetDir = __DIR__ . '/public/uploads/projects/';
        $backupDir = __DIR__ . '/storage/app/backup/projects/';
        
        echo "<div class='info'>";
        echo "<strong>Migration Plan:</strong><br>";
        echo "Source: $sourceDir<br>";
        echo "Target: $targetDir<br>";
        echo "Backup: $backupDir<br>";
        echo "</div>";
        
        $migrated = 0;
        $errors = 0;
        $skipped = 0;
        
        // Create target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            if (mkdir($targetDir, 0755, true)) {
                echo "<span class='success'>✓ Created target directory: $targetDir</span><br>";
            } else {
                echo "<span class='error'>✗ Failed to create target directory: $targetDir</span><br>";
                exit;
            }
        }
        
        // Create backup directory if it doesn't exist
        if (!is_dir($backupDir)) {
            if (mkdir($backupDir, 0755, true)) {
                echo "<span class='success'>✓ Created backup directory: $backupDir</span><br>";
            } else {
                echo "<span class='warning'>⚠ Could not create backup directory: $backupDir</span><br>";
            }
        }
        
        if (is_dir($sourceDir)) {
            $files = scandir($sourceDir);
            
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                
                $sourcePath = $sourceDir . $file;
                $targetPath = $targetDir . $file;
                $backupPath = $backupDir . $file;
                
                if (!is_file($sourcePath)) continue;
                
                echo "<br><strong>Processing: $file</strong><br>";
                
                // Check if target already exists
                if (file_exists($targetPath)) {
                    echo "<span class='warning'>⚠ File already exists in target, skipping</span><br>";
                    $skipped++;
                    continue;
                }
                
                // Copy to target
                if (copy($sourcePath, $targetPath)) {
                    echo "<span class='success'>✓ Copied to public/uploads/projects/</span><br>";
                    
                    // Create backup if backup directory exists
                    if (is_dir($backupDir) && copy($sourcePath, $backupPath)) {
                        echo "<span class='success'>✓ Backup created</span><br>";
                    }
                    
                    // Verify the copy worked
                    if (file_exists($targetPath) && filesize($targetPath) === filesize($sourcePath)) {
                        echo "<span class='success'>✓ Copy verified</span><br>";
                        $migrated++;
                    } else {
                        echo "<span class='error'>✗ Copy verification failed</span><br>";
                        $errors++;
                    }
                } else {
                    echo "<span class='error'>✗ Failed to copy file</span><br>";
                    $errors++;
                }
            }
        } else {
            echo "<span class='warning'>⚠ Source directory does not exist: $sourceDir</span><br>";
        }
        
        echo "<div class='info'>";
        echo "<h3>Migration Summary:</h3>";
        echo "<span class='success'>✓ Migrated: $migrated files</span><br>";
        echo "<span class='warning'>⚠ Skipped: $skipped files</span><br>";
        echo "<span class='error'>✗ Errors: $errors files</span><br>";
        echo "</div>";
        
        if ($migrated > 0) {
            echo "<div class='info'>";
            echo "<h3>⚠️ Important Next Steps:</h3>";
            echo "1. Test your website to ensure images are loading correctly<br>";
            echo "2. If everything works, you can safely delete files from storage/app/public/projects/<br>";
            echo "3. Update your admin panel to upload to public/uploads/projects/ going forward<br>";
            echo "4. Delete this migration script for security<br>";
            echo "</div>";
        }
        ?>
        
        <div class="info">
            <strong>🔒 Security Warning:</strong> Delete this script after use!
        </div>
    </div>
</body>
</html>