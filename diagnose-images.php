<?php
/**
 * Project Images Diagnostic Script
 * This script diagnoses exactly why images aren't showing
 * URL: https://yourdomain.com/diagnose-images.php
 */

// Try to load Laravel environment
$envFile = __DIR__ . '/.env';
$dbConfig = [];

if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (preg_match('/DB_HOST=(.*)/', $envContent, $matches)) {
        $dbConfig['host'] = trim($matches[1]);
    }
    if (preg_match('/DB_DATABASE=(.*)/', $envContent, $matches)) {
        $dbConfig['database'] = trim($matches[1]);
    }
    if (preg_match('/DB_USERNAME=(.*)/', $envContent, $matches)) {
        $dbConfig['username'] = trim($matches[1]);
    }
    if (preg_match('/DB_PASSWORD=(.*)/', $envContent, $matches)) {
        $dbConfig['password'] = trim($matches[1]);
    }
    if (preg_match('/APP_URL=(.*)/', $envContent, $matches)) {
        $dbConfig['app_url'] = trim($matches[1]);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Project Images Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: #4CAF50; font-weight: bold; }
        .error { color: #f44336; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0; }
        .section { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; word-break: break-all; }
        th { background-color: #4CAF50; color: white; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; white-space: pre-wrap; }
        .fix-btn { background: #4CAF50; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Project Images Diagnostic Report</h1>
        <p><strong>Generated:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- Database Connection Test -->
        <div class="section">
            <h2>🗄️ Database Connection & Projects</h2>
            <?php
            $projects = [];
            $dbConnected = false;
            
            if (!empty($dbConfig['host']) && !empty($dbConfig['database'])) {
                try {
                    $pdo = new PDO(
                        "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}", 
                        $dbConfig['username'], 
                        $dbConfig['password']
                    );
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "<span class='success'>✓ Database connected successfully</span><br>";
                    $dbConnected = true;
                    
                    // Get projects
                    $stmt = $pdo->query("SELECT id, title, slug, image_path, featured FROM projects ORDER BY id");
                    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo "<span class='success'>✓ Found " . count($projects) . " projects in database</span><br>";
                    
                } catch (Exception $e) {
                    echo "<span class='error'>✗ Database connection failed: " . $e->getMessage() . "</span><br>";
                }
            } else {
                echo "<span class='error'>✗ Database configuration not found in .env file</span><br>";
            }
            ?>
        </div>

        <!-- Projects Analysis -->
        <?php if (!empty($projects)): ?>
        <div class="section">
            <h2>📊 Projects & Image Paths Analysis</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image Path (DB)</th>
                    <th>Expected URL</th>
                    <th>File Status</th>
                    <th>Generated URL Test</th>
                </tr>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo $project['id']; ?></td>
                    <td><?php echo htmlspecialchars($project['title']); ?></td>
                    <td><small><?php echo htmlspecialchars($project['image_path'] ?: 'NULL'); ?></small></td>
                    <td>
                        <?php
                        if ($project['image_path']) {
                            // Test our Project model logic
                            $imagePath = $project['image_path'];
                            $testUrl = null;
                            
                            // Check if it's already a URL
                            if (strpos($imagePath, 'http') === 0 || strpos($imagePath, '/') === 0) {
                                $testUrl = $imagePath;
                                echo "<small>Direct URL/Path</small>";
                            } else {
                                // Check public uploads
                                $publicPath = __DIR__ . '/public/uploads/projects/' . $imagePath;
                                if (file_exists($publicPath)) {
                                    $testUrl = '/uploads/projects/' . $imagePath;
                                    echo "<small class='success'>→ /uploads/projects/$imagePath</small>";
                                } else {
                                    // Check storage
                                    $storagePath = __DIR__ . '/storage/app/public/' . $imagePath;
                                    if (file_exists($storagePath)) {
                                        $testUrl = '/storage/' . $imagePath;
                                        echo "<small class='warning'>→ /storage/$imagePath</small>";
                                    } else {
                                        echo "<small class='error'>No valid path found</small>";
                                    }
                                }
                            }
                        } else {
                            echo "<small class='error'>No image path</small>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($project['image_path']) {
                            $imagePath = $project['image_path'];
                            $found = false;
                            
                            // Check various locations
                            $checkPaths = [
                                'public/uploads/projects/' . $imagePath => __DIR__ . '/public/uploads/projects/' . $imagePath,
                                'storage/app/public/' . $imagePath => __DIR__ . '/storage/app/public/' . $imagePath,
                                'storage/app/public/projects/' . $imagePath => __DIR__ . '/storage/app/public/projects/' . $imagePath,
                                'public/' . $imagePath => __DIR__ . '/public/' . $imagePath,
                            ];
                            
                            foreach ($checkPaths as $label => $fullPath) {
                                if (file_exists($fullPath)) {
                                    echo "<span class='success'>✓ Found in $label</span><br>";
                                    $found = true;
                                }
                            }
                            
                            if (!$found) {
                                echo "<span class='error'>✗ File not found anywhere</span>";
                            }
                        } else {
                            echo "<span class='error'>No path to check</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if (isset($testUrl) && $testUrl): ?>
                            <a href="<?php echo $testUrl; ?>" target="_blank" style="color: #4CAF50;">Test URL</a>
                        <?php else: ?>
                            <span class="error">No URL</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <!-- File System Analysis -->
        <div class="section">
            <h2>📁 File System Analysis</h2>
            <?php
            $directories = [
                'public/uploads' => __DIR__ . '/public/uploads',
                'public/uploads/projects' => __DIR__ . '/public/uploads/projects',
                'storage/app/public' => __DIR__ . '/storage/app/public',
                'storage/app/public/projects' => __DIR__ . '/storage/app/public/projects',
                'public/storage' => __DIR__ . '/public/storage',
            ];
            
            foreach ($directories as $label => $path) {
                echo "<strong>$label:</strong> ";
                if (is_dir($path)) {
                    $files = glob($path . '/*');
                    $imageFiles = array_filter($files, function($file) {
                        return preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $file);
                    });
                    echo "<span class='success'>✓ Exists (" . count($imageFiles) . " images)</span>";
                    
                    if (!empty($imageFiles)) {
                        echo "<ul>";
                        foreach (array_slice($imageFiles, 0, 5) as $file) {
                            echo "<li>" . basename($file) . "</li>";
                        }
                        if (count($imageFiles) > 5) {
                            echo "<li>... and " . (count($imageFiles) - 5) . " more</li>";
                        }
                        echo "</ul>";
                    }
                } else {
                    echo "<span class='error'>✗ Does not exist</span>";
                }
                echo "<br>";
            }
            ?>
        </div>

        <!-- Project Model Simulation -->
        <div class="section">
            <h2>🧪 Project Model URL Generation Test</h2>
            <?php
            function simulateProjectModel($imagePath) {
                if (!$imagePath) return null;

                // If admin saved an absolute URL or absolute public path, return as-is
                if (strpos($imagePath, 'http://') === 0 || strpos($imagePath, 'https://') === 0 || strpos($imagePath, '/') === 0) {
                    return $imagePath;
                }

                // Option 1: Check if using direct public uploads
                $publicPath = __DIR__ . '/public/uploads/projects/' . $imagePath;
                if (file_exists($publicPath)) {
                    return '/uploads/projects/' . $imagePath;
                }

                // Option 2: Check if file exists in storage/app/public
                $storagePath = __DIR__ . '/storage/app/public/' . $imagePath;
                if (file_exists($storagePath)) {
                    return '/storage/' . $imagePath;
                }

                // Option 3: Check if the image_path already includes the projects directory
                if (strpos($imagePath, 'projects/') !== 0) {
                    return simulateProjectModel('projects/' . $imagePath);
                }

                // Option 4: Check if file exists in public directory directly
                $directPublicPath = __DIR__ . '/public/' . $imagePath;
                if (file_exists($directPublicPath)) {
                    return '/' . $imagePath;
                }

                return null;
            }

            if (!empty($projects)) {
                echo "<table>";
                echo "<tr><th>Project</th><th>DB Image Path</th><th>Generated URL</th><th>File Exists</th><th>Test Link</th></tr>";
                foreach ($projects as $project) {
                    $url = simulateProjectModel($project['image_path']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($project['title']) . "</td>";
                    echo "<td><small>" . htmlspecialchars($project['image_path'] ?: 'NULL') . "</small></td>";
                    echo "<td>" . ($url ? "<code>$url</code>" : "<span class='error'>NULL</span>") . "</td>";
                    
                    if ($url) {
                        $fullPath = __DIR__ . '/public' . $url;
                        if (strpos($url, '/uploads/') === 0) {
                            $fullPath = __DIR__ . '/public' . $url;
                        } elseif (strpos($url, '/storage/') === 0) {
                            $fullPath = __DIR__ . '/storage/app/public' . substr($url, 8);
                        }
                        
                        echo "<td>" . (file_exists($fullPath) ? "<span class='success'>✓ Yes</span>" : "<span class='error'>✗ No</span>") . "</td>";
                        echo "<td><a href='$url' target='_blank'>Test</a></td>";
                    } else {
                        echo "<td><span class='error'>N/A</span></td>";
                        echo "<td><span class='error'>N/A</span></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </div>

        <!-- Quick Fixes -->
        <div class="section">
            <h2>🔧 Quick Fix Actions</h2>
            <div class="info">
                <p>Based on the analysis above, here are potential fixes:</p>
                
                <a href="?action=copy_storage_to_uploads" class="fix-btn">📁 Copy All Storage Images to Uploads</a>
                <a href="?action=create_uploads_dir" class="fix-btn">📂 Create Uploads Directory</a>
                <a href="?action=fix_db_paths" class="fix-btn">🗄️ Update Database Image Paths</a>
                <a href="?action=test_all" class="fix-btn">🧪 Test All Image URLs</a>
            </div>
            
            <?php
            if (isset($_GET['action'])) {
                echo "<div class='info'>";
                echo "<h3>Action: " . $_GET['action'] . "</h3>";
                
                switch ($_GET['action']) {
                    case 'copy_storage_to_uploads':
                        $sourceDir = __DIR__ . '/storage/app/public/projects/';
                        $targetDir = __DIR__ . '/public/uploads/projects/';
                        
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                            echo "✓ Created uploads directory<br>";
                        }
                        
                        if (is_dir($sourceDir)) {
                            $files = glob($sourceDir . '*');
                            foreach ($files as $file) {
                                if (is_file($file)) {
                                    $filename = basename($file);
                                    $target = $targetDir . $filename;
                                    if (copy($file, $target)) {
                                        echo "✓ Copied $filename<br>";
                                    }
                                }
                            }
                        }
                        break;
                        
                    case 'create_uploads_dir':
                        $dirs = [
                            __DIR__ . '/public/uploads',
                            __DIR__ . '/public/uploads/projects'
                        ];
                        foreach ($dirs as $dir) {
                            if (!is_dir($dir)) {
                                if (mkdir($dir, 0755, true)) {
                                    echo "✓ Created $dir<br>";
                                }
                            } else {
                                echo "✓ $dir already exists<br>";
                            }
                        }
                        break;
                        
                    case 'test_all':
                        echo "<h4>Testing all image URLs:</h4>";
                        foreach ($projects as $project) {
                            $url = simulateProjectModel($project['image_path']);
                            if ($url) {
                                echo "<a href='$url' target='_blank'>{$project['title']}: $url</a><br>";
                            } else {
                                echo "<span class='error'>{$project['title']}: No URL generated</span><br>";
                            }
                        }
                        break;
                }
                echo "</div>";
            }
            ?>
        </div>

        <!-- Debug Information -->
        <div class="section">
            <h2>🐛 Debug Information</h2>
            <div class="code">
Current Directory: <?php echo __DIR__; ?>
Server Document Root: <?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Not set'; ?>
HTTP Host: <?php echo $_SERVER['HTTP_HOST'] ?? 'Not set'; ?>
Request URI: <?php echo $_SERVER['REQUEST_URI'] ?? 'Not set'; ?>
App URL (from .env): <?php echo $dbConfig['app_url'] ?? 'Not found'; ?>
            </div>
        </div>

        <div class="info">
            <strong>🔒 Security:</strong> Delete this diagnostic script after use!
        </div>
    </div>
</body>
</html>