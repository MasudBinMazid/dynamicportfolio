<?php
/**
 * Simple Image Delivery Script
 * Use this if storage directory access is still blocked
 * Usage: /image.php?file=filename.jpg
 */

if (!isset($_GET['file'])) {
    http_response_code(404);
    exit('File not specified');
}

$filename = basename($_GET['file']);

// Security: only allow image files
if (!preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $filename)) {
    http_response_code(403);
    exit('Invalid file type');
}

// Try multiple locations
$possiblePaths = [
    __DIR__ . '/public/storage/projects/' . $filename,
    __DIR__ . '/storage/app/public/projects/' . $filename,
    __DIR__ . '/public/uploads/projects/' . $filename,
];

$filePath = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $filePath = $path;
        break;
    }
}

if (!$filePath) {
    http_response_code(404);
    exit('File not found');
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

// Set headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . $fileSize);
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output file
readfile($filePath);
exit;
?>