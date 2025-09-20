<?php
/**
 * Simple Image Test Script
 * Tests exactly what's happening with your project images
 * URL: https://yourdomain.com/test-images.php
 */

// Try to simulate Laravel's asset() and other functions
function asset($path) {
    $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
    return $baseUrl . '/' . ltrim($path, '/');
}

function public_path($path = '') {
    return __DIR__ . '/public/' . ltrim($path, '/');
}

function storage_path($path = '') {
    return __DIR__ . '/storage/' . ltrim($path, '/');
}

// Test the Project model logic
function getImageUrlAttribute($imagePath) {
    if (!$imagePath) return null;

    // If admin saved an absolute URL or absolute public path, return as-is
    if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0 || strpos($imagePath, '/') === 0) {
        return $imagePath;
    }

    // For cPanel hosting - check if using direct public uploads first
    if (file_exists(public_path('uploads/projects/' . $imagePath))) {
        return asset('uploads/projects/' . $imagePath);
    }

    // Check if file exists in storage/app/public and generate URL manually
    $storagePath = storage_path('app/public/' . $imagePath);
    if (file_exists($storagePath)) {
        $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
        return $baseUrl . '/storage/' . $imagePath;
    }

    return null;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-image { border: 2px solid #ddd; margin: 10px; padding: 10px; display: inline-block; }
        .success { border-color: #4CAF50; }
        .error { border-color: #f44336; }
        img { max-width: 200px; max-height: 150px; }
        .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🖼️ Image Test Page</h1>
    
    <div class="info">
        <strong>This page tests your project images directly.</strong><br>
        It simulates the exact logic used in your Project model.
    </div>

    <?php
    // Test with known image paths from your debug report
    $testPaths = [];
    
    // Scan for actual images in storage
    $storageDir = __DIR__ . '/storage/app/public/projects/';
    if (is_dir($storageDir)) {
        $files = glob($storageDir . '*');
        foreach ($files as $file) {
            if (is_file($file) && preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $file)) {
                $testPaths[] = basename($file);
            }
        }
    }
    
    // Scan for images in uploads
    $uploadsDir = __DIR__ . '/public/uploads/projects/';
    if (is_dir($uploadsDir)) {
        $files = glob($uploadsDir . '*');
        foreach ($files as $file) {
            if (is_file($file) && preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $file)) {
                $testPaths[] = basename($file);
            }
        }
    }
    
    // Remove duplicates
    $testPaths = array_unique($testPaths);
    
    echo "<h2>Found " . count($testPaths) . " images to test:</h2>";
    
    if (empty($testPaths)) {
        echo "<p style='color: red;'>❌ No images found in either storage/app/public/projects/ or public/uploads/projects/</p>";
        echo "<p>Let's check what files exist:</p>";
        
        echo "<h3>Files in storage/app/public/projects/:</h3>";
        if (is_dir($storageDir)) {
            $allFiles = scandir($storageDir);
            foreach ($allFiles as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "- $file<br>";
                }
            }
        } else {
            echo "Directory doesn't exist<br>";
        }
        
        echo "<h3>Files in public/uploads/projects/:</h3>";
        if (is_dir($uploadsDir)) {
            $allFiles = scandir($uploadsDir);
            foreach ($allFiles as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "- $file<br>";
                }
            }
        } else {
            echo "Directory doesn't exist<br>";
        }
    } else {
        foreach ($testPaths as $imagePath) {
            $url = getImageUrlAttribute($imagePath);
            
            echo "<div class='test-image " . ($url ? 'success' : 'error') . "'>";
            echo "<h3>Testing: $imagePath</h3>";
            echo "<p><strong>Generated URL:</strong> " . ($url ?: 'NULL') . "</p>";
            
            if ($url) {
                echo "<p><strong>Test Image:</strong></p>";
                echo "<img src='$url' alt='$imagePath' onload=\"this.parentElement.parentElement.style.borderColor='#4CAF50'\" onerror=\"this.parentElement.parentElement.style.borderColor='#f44336'; this.style.display='none'; this.nextElementSibling.style.display='block';\">";
                echo "<p style='display:none; color:red;'>❌ Failed to load image</p>";
                
                echo "<p><strong>Direct Link:</strong> <a href='$url' target='_blank'>$url</a></p>";
                
                // Check file existence
                $filePath = '';
                if (strpos($url, '/uploads/') !== false) {
                    $filePath = __DIR__ . '/public' . parse_url($url, PHP_URL_PATH);
                } elseif (strpos($url, '/storage/') !== false) {
                    $relativePath = substr(parse_url($url, PHP_URL_PATH), 9); // Remove '/storage/'
                    $filePath = __DIR__ . '/storage/app/public/' . $relativePath;
                }
                
                if ($filePath && file_exists($filePath)) {
                    echo "<p style='color:green;'>✅ File exists: $filePath</p>";
                    echo "<p><strong>File size:</strong> " . filesize($filePath) . " bytes</p>";
                } else {
                    echo "<p style='color:red;'>❌ File not found: $filePath</p>";
                }
            } else {
                echo "<p style='color:red;'>❌ No URL generated - file not found in any location</p>";
                
                // Check where the file might be
                $locations = [
                    'public/uploads/projects/' . $imagePath => __DIR__ . '/public/uploads/projects/' . $imagePath,
                    'storage/app/public/' . $imagePath => __DIR__ . '/storage/app/public/' . $imagePath,
                    'storage/app/public/projects/' . $imagePath => __DIR__ . '/storage/app/public/projects/' . $imagePath,
                ];
                
                echo "<p><strong>Checked locations:</strong></p><ul>";
                foreach ($locations as $label => $path) {
                    $exists = file_exists($path);
                    echo "<li>$label: " . ($exists ? '✅ EXISTS' : '❌ NOT FOUND') . "</li>";
                }
                echo "</ul>";
            }
            
            echo "</div>";
        }
    }
    ?>
    
    <div class="info">
        <h3>🔧 Quick Actions:</h3>
        <p>If images aren't showing:</p>
        <ol>
            <li>Run the diagnostic script: <a href="diagnose-images.php">diagnose-images.php</a></li>
            <li>Check the setup script: <a href="setup-images.php">setup-images.php</a></li>
            <li>Verify your storage link exists: <code>/public/storage</code> → <code>../storage/app/public</code></li>
        </ol>
    </div>

    <div class="info">
        <h3>🔍 Manual Test:</h3>
        <p>Try these direct URLs in your browser:</p>
        <ul>
            <li><a href="/storage/projects/" target="_blank">/storage/projects/</a> (if using storage)</li>
            <li><a href="/uploads/projects/" target="_blank">/uploads/projects/</a> (if using uploads)</li>
        </ul>
    </div>
</body>
</html>