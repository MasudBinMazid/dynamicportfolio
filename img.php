<?php
/**
 * Bulletproof Image Server for Restrictive Hosting
 * This serves images directly from PHP, bypassing all directory restrictions
 * URL: https://yourdomain.com/img.php?f=filename.jpg
 */

// Enable error reporting for debugging
error_reporting(E_ALL);

// Security check
if (!isset($_GET['f'])) {
    http_response_code(400);
    header('Content-Type: text/plain');
    exit('Error: No file specified. Usage: img.php?f=filename.jpg');
}

$filename = basename($_GET['f']);

// Security: only allow image files
if (!preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $filename)) {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit('Error: Invalid file type. Only image files allowed.');
}

// List of possible file locations (in order of preference)
$possiblePaths = [
    __DIR__ . '/public/images/projects/' . $filename,
    __DIR__ . '/public/storage/projects/' . $filename,
    __DIR__ . '/storage/app/public/projects/' . $filename,
    __DIR__ . '/public/uploads/projects/' . $filename,
    __DIR__ . '/storage/app/public/' . $filename,
    __DIR__ . '/public/storage/' . $filename,
];

$filePath = null;
$debugInfo = [];

foreach ($possiblePaths as $path) {
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $size = $exists ? filesize($path) : 0;
    
    $debugInfo[] = [
        'path' => $path,
        'exists' => $exists,
        'readable' => $readable,
        'size' => $size
    ];
    
    if ($exists && $readable && $size > 0) {
        $filePath = $path;
        break;
    }
}

// If no file found, return debug information
if (!$filePath) {
    http_response_code(404);
    header('Content-Type: text/html');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Image Debug Info</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .debug { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0; }
            .found { color: #4CAF50; }
            .notfound { color: #f44336; }
        </style>
    </head>
    <body>
        <h1>🔍 Image Debug Information</h1>
        <p><strong>Requested file:</strong> <?php echo htmlspecialchars($filename); ?></p>
        <p><strong>Search results:</strong></p>
        
        <div class="debug">
            <?php foreach ($debugInfo as $info): ?>
                <div class="<?php echo $info['exists'] ? 'found' : 'notfound'; ?>">
                    <strong>Path:</strong> <?php echo htmlspecialchars($info['path']); ?><br>
                    <strong>Exists:</strong> <?php echo $info['exists'] ? 'YES' : 'NO'; ?><br>
                    <strong>Readable:</strong> <?php echo $info['readable'] ? 'YES' : 'NO'; ?><br>
                    <strong>Size:</strong> <?php echo $info['size']; ?> bytes<br>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
        
        <div style="background: #fff3e0; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3>💡 Possible Solutions</h3>
            <ul>
                <li>Run the locate-and-fix-images.php script to move images to correct location</li>
                <li>Check if images were uploaded correctly</li>
                <li>Verify file permissions (should be 644 for files, 755 for directories)</li>
                <li>Contact hosting provider if files exist but aren't readable</li>
            </ul>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Get file info
$fileSize = filesize($filePath);
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

// Set proper MIME type
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'bmp' => 'image/bmp'
];

$mimeType = $mimeTypes[$ext] ?? 'image/jpeg';

// Set headers for proper image serving
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . $fileSize);
header('Cache-Control: public, max-age=2592000'); // 30 days
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');

// Prevent any output before image
ob_clean();
flush();

// Output the image file
readfile($filePath);
exit;
?>