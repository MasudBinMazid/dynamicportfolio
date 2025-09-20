<?php
/**
 * Test Laravel Project Model Image URLs
 * This tests your actual Laravel Project model to see what URLs it generates
 * URL: https://yourdomain.com/test-laravel-images.php
 */

// Bootstrap Laravel (simplified)
require_once __DIR__ . '/vendor/autoload.php';

// Try to load Laravel
try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    // Test database connection and get projects
    $projects = \App\Models\Project::whereNotNull('image_path')
                                  ->where('image_path', '!=', '')
                                  ->limit(5)
                                  ->get();
    
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Laravel Project Model Test</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .project-box { border: 1px solid #ddd; margin: 15px; padding: 15px; border-radius: 5px; background: #f9f9f9; }
            .success { border-color: #4CAF50; background: #f1f8e9; }
            .error { border-color: #f44336; background: #ffebee; }
            img { max-width: 300px; margin: 10px; border: 1px solid #ddd; }
            .status { font-weight: bold; margin: 10px 0; }
            .debug-info { background: #e3f2fd; padding: 10px; margin: 10px 0; border-radius: 4px; font-family: monospace; font-size: 12px; }
        </style>
        <script>
            function testProjectImage(img, projectBox, projectTitle) {
                img.onload = function() {
                    projectBox.className = projectBox.className.replace('project-box', 'project-box success');
                    projectBox.querySelector('.status').innerHTML = '✅ ' + projectTitle + ' image loads successfully!';
                    projectBox.querySelector('.status').style.color = '#4CAF50';
                };
                img.onerror = function() {
                    projectBox.className = projectBox.className.replace('project-box', 'project-box error');
                    projectBox.querySelector('.status').innerHTML = '❌ ' + projectTitle + ' image failed to load';
                    projectBox.querySelector('.status').style.color = '#f44336';
                    img.style.display = 'none';
                };
            }
        </script>
    </head>
    <body>
        <div class="container">
            <h1>🧪 Laravel Project Model Test</h1>
            <p><strong>Test Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Purpose:</strong> Test your actual Laravel Project model and database</p>
            
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h3>✅ Laravel Connection Successful!</h3>
                <p>Successfully connected to Laravel and found <?php echo count($projects); ?> projects with images.</p>
            </div>

            <?php foreach ($projects as $project): ?>
                <div class="project-box" id="project-<?php echo $project->id; ?>">
                    <h3><?php echo htmlspecialchars($project->title); ?></h3>
                    <div class="status">Testing image...</div>
                    
                    <div class="debug-info">
                        <strong>Project ID:</strong> <?php echo $project->id; ?><br>
                        <strong>Database image_path:</strong> <?php echo htmlspecialchars($project->image_path); ?><br>
                        <strong>Generated URL:</strong> <?php echo htmlspecialchars($project->image_url ?? 'NULL'); ?><br>
                        <strong>Has Valid Image:</strong> <?php echo $project->hasValidImage() ? 'Yes' : 'No'; ?><br>
                    </div>
                    
                    <?php if ($project->image_url): ?>
                        <img src="<?php echo htmlspecialchars($project->image_url); ?>" 
                             onload="testProjectImage(this, document.getElementById('project-<?php echo $project->id; ?>'), '<?php echo addslashes($project->title); ?>')"
                             onerror="testProjectImage(this, document.getElementById('project-<?php echo $project->id; ?>'), '<?php echo addslashes($project->title); ?>')">
                        
                        <div style="margin-top: 10px;">
                            <strong>Direct Link:</strong> 
                            <a href="<?php echo htmlspecialchars($project->image_url); ?>" target="_blank">
                                <?php echo htmlspecialchars($project->image_url); ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="color: #f44336;">No image URL generated</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if (count($projects) === 0): ?>
                <div style="background: #fff3e0; color: #e65100; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <h3>⚠️ No Projects Found</h3>
                    <p>No projects with image_path found in the database.</p>
                </div>
            <?php endif; ?>

            <div class="project-box" style="background: #e3f2fd;">
                <h3>🔧 Debug Information</h3>
                <div class="debug-info">
                    <strong>Laravel App:</strong> Successfully loaded<br>
                    <strong>Database:</strong> Connected<br>
                    <strong>Project Model:</strong> Working<br>
                    <strong>Total Projects:</strong> <?php echo \App\Models\Project::count(); ?><br>
                    <strong>Projects with Images:</strong> <?php echo \App\Models\Project::whereNotNull('image_path')->where('image_path', '!=', '')->count(); ?><br>
                </div>
            </div>
        </div>
    </body>
    </html>
    
    <?php
    
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Laravel Test - Error</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            .error { background: #ffebee; color: #c62828; padding: 20px; border-radius: 5px; border: 2px solid #f44336; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🚨 Laravel Connection Error</h1>
            
            <div class="error">
                <h3>Could not connect to Laravel</h3>
                <p><strong>Error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?></p>
                <p><strong>File:</strong> <?php echo htmlspecialchars($e->getFile()); ?></p>
                <p><strong>Line:</strong> <?php echo $e->getLine(); ?></p>
            </div>
            
            <div style="background: #e3f2fd; padding: 15px; margin: 15px 0; border-radius: 5px;">
                <h3>💡 Possible Solutions</h3>
                <ul>
                    <li>Make sure your .env file is configured correctly</li>
                    <li>Check database connection settings</li>
                    <li>Ensure Laravel dependencies are installed (composer install)</li>
                    <li>Verify file permissions on Laravel directories</li>
                </ul>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>