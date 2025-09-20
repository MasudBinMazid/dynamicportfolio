<?php
/**
 * Debug Project Image URLs 
 * This script shows exactly what URLs your Project model is generating
 */
require_once __DIR__ . '/vendor/autoload.php';

echo "<h1>🐛 Project Image URL Debug</h1>";
echo "<p><strong>Debug Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // Get projects with images
    $projects = \App\Models\Project::whereNotNull('image_path')
                                  ->where('image_path', '!=', '')
                                  ->limit(5)
                                  ->get();
    
    echo "<div style='background: #d4edda; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "✅ Found " . count($projects) . " projects with image_path<br>";
    echo "</div>";
    
    if (count($projects) === 0) {
        echo "<div style='background: #fff3e0; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h3>⚠️ No Projects Found</h3>";
        echo "<p>No projects with image_path found in database.</p>";
        echo "</div>";
        
        // Show all projects
        $allProjects = \App\Models\Project::all();
        echo "<h2>📊 All Projects in Database:</h2>";
        foreach ($allProjects as $proj) {
            echo "<div style='background: #f8f9fa; padding: 10px; margin: 5px 0; border-radius: 3px;'>";
            echo "<strong>ID:</strong> {$proj->id} | ";
            echo "<strong>Title:</strong> {$proj->title} | ";
            echo "<strong>image_path:</strong> " . ($proj->image_path ?: 'NULL') . "<br>";
            echo "</div>";
        }
        exit;
    }
    
    foreach ($projects as $project) {
        echo "<div style='border: 2px solid #ddd; margin: 20px 0; padding: 20px; border-radius: 8px;'>";
        echo "<h2>🔍 Project: " . htmlspecialchars($project->title) . " (ID: {$project->id})</h2>";
        
        // Show database values
        echo "<div style='background: #e3f2fd; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>📊 Database Values</h3>";
        echo "<strong>image_path:</strong> " . htmlspecialchars($project->image_path) . "<br>";
        echo "<strong>Raw image_path:</strong> <code>" . var_export($project->image_path, true) . "</code><br>";
        echo "</div>";
        
        // Show generated URL
        echo "<div style='background: #f1f8e9; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>🔗 Generated URL</h3>";
        $imageUrl = $project->image_url;
        echo "<strong>Generated image_url:</strong> " . htmlspecialchars($imageUrl ?: 'NULL') . "<br>";
        if ($imageUrl) {
            echo "<strong>Full URL:</strong> <a href='{$imageUrl}' target='_blank'>{$imageUrl}</a><br>";
        }
        echo "</div>";
        
        // Test file existence
        echo "<div style='background: #fff3e0; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>📁 File Existence Check</h3>";
        
        $filename = basename($project->image_path);
        $testPaths = [
            'public/images/projects/' . $filename => public_path('images/projects/' . $filename),
            'public/storage/projects/' . $filename => public_path('storage/projects/' . $filename),
            'public/uploads/projects/' . $filename => public_path('uploads/projects/' . $filename),
            'storage/app/public/projects/' . $filename => storage_path('app/public/projects/' . $filename)
        ];
        
        $foundFiles = [];
        foreach ($testPaths as $label => $path) {
            if (file_exists($path)) {
                echo "✅ <strong>{$label}:</strong> EXISTS ({$path})<br>";
                $foundFiles[] = $label;
            } else {
                echo "❌ <strong>{$label}:</strong> Not found ({$path})<br>";
            }
        }
        echo "</div>";
        
        // Test the actual image
        echo "<div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>🖼️ Image Test</h3>";
        if ($imageUrl) {
            echo "<p><strong>Testing URL:</strong> {$imageUrl}</p>";
            echo "<img src='{$imageUrl}' style='max-width: 200px; border: 1px solid #ddd;' ";
            echo "onload='this.nextSibling.innerHTML=\"✅ Image loads successfully!\"' ";
            echo "onerror='this.nextSibling.innerHTML=\"❌ Image failed to load - URL not accessible\"'>";
            echo "<div style='margin: 10px 0; font-weight: bold;'>Loading...</div>";
        } else {
            echo "<p style='color: #f44336;'>❌ No URL generated</p>";
        }
        echo "</div>";
        
        // Show hasValidImage result
        echo "<div style='background: #e8f5e8; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>✅ hasValidImage() Result</h3>";
        $hasValid = $project->hasValidImage();
        echo "<strong>hasValidImage():</strong> " . ($hasValid ? '✅ TRUE' : '❌ FALSE') . "<br>";
        echo "</div>";
        
        echo "</div>";
    }
    
    // Test URLs manually
    echo "<hr style='margin: 30px 0;'>";
    echo "<h2>🔗 Manual Test Links</h2>";
    echo "<div style='background: #f9f9f9; padding: 15px; border-radius: 5px;'>";
    echo "<p>Test these image serving methods manually:</p>";
    echo "<ul>";
    echo "<li><a href='/image-view.php?img=FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg' target='_blank'>Image Viewer Test</a></li>";
    echo "<li><a href='/img.php?f=FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg' target='_blank'>Bulletproof Server Test</a></li>";
    echo "<li><a href='/storage/projects/FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg' target='_blank'>Direct Storage Test</a></li>";
    echo "<li><a href='/images/projects/FeOTN1JlmuSBSzvRPi2CYE36QXNgZ82O3IZLkNEO.jpg' target='_blank'>Direct Images Test</a></li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #ffebee; color: #c62828; padding: 20px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>❌ Error</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
h1, h2, h3 { color: #333; }
code { background: #f8f8f8; padding: 2px 5px; border-radius: 3px; }
a { color: #007cba; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>