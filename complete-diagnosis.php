<?php
/**
 * 🔍 COMPLETE IMAGE DIAGNOSIS - Find Why Images Aren't Showing
 * This script will tell you exactly what's wrong and how to fix it
 */

echo "<!DOCTYPE html><html><head><title>Complete Image Diagnosis</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:15px 0;border-radius:8px;border-left:5px solid #007cba;} .success{border-left-color:#4CAF50;background:#f1f8e9;} .error{border-left-color:#f44336;background:#ffebee;} .warning{border-left-color:#ff9800;background:#fff8e1;} .info{border-left-color:#2196F3;background:#e3f2fd;} img{max-width:200px;margin:10px;border:1px solid #ddd;} code{background:#f8f8f8;padding:2px 5px;border-radius:3px;} .test-result{padding:10px;margin:5px 0;border-radius:5px;} h1,h2,h3{color:#333;}</style>";
echo "</head><body>";

echo "<h1>🔍 Complete Image Diagnosis</h1>";
echo "<p><strong>Diagnosis Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

// Step 1: Check if Laravel is working
echo "<div class='box info'><h2>📋 Step 1: Laravel Connection</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "<div class='test-result success'>✅ Laravel connected successfully</div>";
    
    // Get projects
    $projects = \App\Models\Project::whereNotNull('image_path')->where('image_path', '!=', '')->get();
    echo "<div class='test-result success'>✅ Found " . count($projects) . " projects with images</div>";
    
} catch (Exception $e) {
    echo "<div class='test-result error'>❌ Laravel Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "</div></body></html>";
    exit;
}
echo "</div>";

// Step 2: Check image files exist
echo "<div class='box info'><h2>📁 Step 2: File Existence Check</h2>";
$imageDirectories = [
    'public/images/projects' => __DIR__ . '/public/images/projects',
    'public/storage/projects' => __DIR__ . '/public/storage/projects',
    'public/uploads/projects' => __DIR__ . '/public/uploads/projects',
    'storage/app/public/projects' => __DIR__ . '/storage/app/public/projects'
];

$foundImages = [];
foreach ($imageDirectories as $label => $path) {
    if (is_dir($path)) {
        $files = glob($path . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        if (count($files) > 0) {
            echo "<div class='test-result success'>✅ {$label}: " . count($files) . " images found</div>";
            $foundImages = array_merge($foundImages, $files);
        } else {
            echo "<div class='test-result warning'>⚠️ {$label}: Directory exists but no images</div>";
        }
    } else {
        echo "<div class='test-result error'>❌ {$label}: Directory doesn't exist</div>";
    }
}

if (empty($foundImages)) {
    echo "<div class='test-result error'>🚨 CRITICAL: No image files found in any directory!</div>";
    echo "<p><strong>Solution:</strong> Upload your image files to one of these directories:</p>";
    echo "<ul>";
    foreach ($imageDirectories as $label => $path) {
        echo "<li>{$label}</li>";
    }
    echo "</ul>";
    echo "</div></body></html>";
    exit;
}
echo "</div>";

// Step 3: Check image serving scripts
echo "<div class='box info'><h2>🔧 Step 3: Image Serving Scripts</h2>";
$servingScripts = [
    'image-view.php' => __DIR__ . '/image-view.php',
    'img.php' => __DIR__ . '/img.php',
    'image.php' => __DIR__ . '/image.php'
];

$workingScripts = [];
foreach ($servingScripts as $name => $path) {
    if (file_exists($path)) {
        echo "<div class='test-result success'>✅ {$name}: Script exists</div>";
        $workingScripts[] = $name;
    } else {
        echo "<div class='test-result error'>❌ {$name}: Script missing</div>";
    }
}

if (empty($workingScripts)) {
    echo "<div class='test-result error'>🚨 CRITICAL: No image serving scripts found!</div>";
    echo "<p><strong>Solution:</strong> Upload image-view.php to your server root</p>";
}
echo "</div>";

// Step 4: Test Project Model URL Generation
echo "<div class='box info'><h2>🎯 Step 4: Project Model URL Generation</h2>";
if (count($projects) > 0) {
    foreach ($projects->take(3) as $project) {
        echo "<div style='background:#f8f9fa;padding:15px;margin:10px 0;border-radius:5px;'>";
        echo "<h3>Project: " . htmlspecialchars($project->title) . "</h3>";
        echo "<p><strong>Database image_path:</strong> <code>" . htmlspecialchars($project->image_path) . "</code></p>";
        
        // Test the model's URL generation
        $generatedUrl = $project->image_url;
        if ($generatedUrl) {
            echo "<p><strong>Generated URL:</strong> <code>" . htmlspecialchars($generatedUrl) . "</code></p>";
            echo "<p><strong>Test Link:</strong> <a href='{$generatedUrl}' target='_blank'>{$generatedUrl}</a></p>";
            
            // Test if the URL actually works
            echo "<div style='margin:10px 0;'>";
            echo "<strong>Live Test:</strong><br>";
            echo "<img src='{$generatedUrl}' onload='this.nextElementSibling.innerHTML=\"✅ Image loads successfully!\"' onerror='this.nextElementSibling.innerHTML=\"❌ Image failed to load\"' style='max-width:150px;'>";
            echo "<div class='test-result'>Testing...</div>";
            echo "</div>";
        } else {
            echo "<div class='test-result error'>❌ No URL generated - Model returned NULL</div>";
        }
        echo "</div>";
    }
} else {
    echo "<div class='test-result warning'>⚠️ No projects with images found in database</div>";
}
echo "</div>";

// Step 5: Direct URL Tests
echo "<div class='box info'><h2>🌐 Step 5: Direct URL Access Tests</h2>";
$sampleImage = basename($foundImages[0] ?? 'FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg');

$testUrls = [
    'Image Viewer' => "/image-view.php?img={$sampleImage}",
    'Bulletproof Server' => "/img.php?f={$sampleImage}", 
    'Direct Storage' => "/storage/projects/{$sampleImage}",
    'Direct Images' => "/images/projects/{$sampleImage}",
    'Direct Uploads' => "/uploads/projects/{$sampleImage}"
];

echo "<p><strong>Testing with sample image:</strong> {$sampleImage}</p>";
foreach ($testUrls as $method => $url) {
    echo "<div style='margin:10px 0;padding:10px;background:#f8f9fa;border-radius:5px;'>";
    echo "<strong>{$method}:</strong> <a href='{$url}' target='_blank'>{$url}</a><br>";
    echo "<img src='{$url}' style='max-width:100px;margin-top:5px;' onload='this.nextElementSibling.innerHTML=\"✅ Works!\"' onerror='this.nextElementSibling.innerHTML=\"❌ Failed\"'>";
    echo "<span class='test-result'>Testing...</span>";
    echo "</div>";
}
echo "</div>";

// Step 6: Check Laravel Cache
echo "<div class='box info'><h2>🔄 Step 6: Laravel Cache Status</h2>";
echo "<p>Laravel caching can sometimes interfere with model changes.</p>";
echo "<p><strong>Recommended Action:</strong> Clear Laravel cache</p>";
echo "<p><code>php artisan config:clear</code></p>";
echo "<p><code>php artisan view:clear</code></p>";
echo "<p><code>php artisan cache:clear</code></p>";
echo "</div>";

// Step 7: Solutions Summary
echo "<div class='box warning'><h2>🎯 Step 7: Solutions Based on Results</h2>";
echo "<h3>If Image Viewer URLs work but Laravel doesn't show images:</h3>";
echo "<ul>";
echo "<li>✅ Upload the updated Project.php model to app/Models/</li>";
echo "<li>✅ Clear Laravel cache (commands above)</li>";
echo "<li>✅ Check if image_path values in database are correct</li>";
echo "</ul>";

echo "<h3>If NO URLs work:</h3>";
echo "<ul>";
echo "<li>❌ Upload image-view.php to your server root</li>";
echo "<li>❌ Check file permissions (644 for PHP files)</li>";
echo "<li>❌ Verify images are in the correct directories</li>";
echo "</ul>";

echo "<h3>If Direct URLs work but PHP scripts don't:</h3>";
echo "<ul>";
echo "<li>⚠️ Use direct URLs in Project model instead</li>";
echo "<li>⚠️ Check PHP execution permissions</li>";
echo "</ul>";
echo "</div>";

// Final recommendations
echo "<div class='box success'><h2>🚀 Final Recommendations</h2>";
echo "<ol>";
echo "<li><strong>Upload Files:</strong> Make sure image-view.php and updated Project.php are on the server</li>";
echo "<li><strong>Clear Cache:</strong> Run the Laravel cache clear commands</li>";
echo "<li><strong>Test Again:</strong> Visit /projects page to see if images show</li>";
echo "<li><strong>Check Console:</strong> Use browser developer tools to see any JavaScript errors</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>