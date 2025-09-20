<?php
/**
 * Simple Image Viewer - Works Around ALL Hosting Restrictions
 * URL: /image-view.php?img=filename.jpg
 */

// Get requested image
$img = $_GET['img'] ?? '';
if (empty($img)) {
    http_response_code(400);
    exit('No image specified');
}

// Security: only alphanumeric, dots, dashes, underscores
$img = preg_replace('/[^a-zA-Z0-9._-]/', '', $img);

// Add .jpg extension if missing
if (!preg_match('/\.(jpg|jpeg|png|gif|webp|bmp)$/i', $img)) {
    $img .= '.jpg';
}

// Find the image in any location
$searchPaths = [
    __DIR__ . '/public/images/projects/' . $img,
    __DIR__ . '/public/storage/projects/' . $img,
    __DIR__ . '/storage/app/public/projects/' . $img,
    __DIR__ . '/public/uploads/projects/' . $img,
];

$imagePath = null;
foreach ($searchPaths as $path) {
    if (file_exists($path) && filesize($path) > 0) {
        $imagePath = $path;
        break;
    }
}

if (!$imagePath) {
    // Return a simple placeholder
    header('Content-Type: image/svg+xml');
    echo '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">';
    echo '<rect width="100%" height="100%" fill="#f0f0f0"/>';
    echo '<text x="50%" y="50%" text-anchor="middle" fill="#666">Image Not Found: ' . htmlspecialchars($img) . '</text>';
    echo '</svg>';
    exit;
}

// Determine content type
$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
$contentTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg', 
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'bmp' => 'image/bmp'
];
$contentType = $contentTypes[$ext] ?? 'image/jpeg';

// Output the image
header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($imagePath));
header('Cache-Control: public, max-age=86400');

readfile($imagePath);
exit;
?>