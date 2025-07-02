<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

$user = authenticateUser();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$file = $_FILES['file'];

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'File type not allowed']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large (max 5MB)']);
    exit;
}

$filename = uniqid() . '_' . preg_replace('/[^a-z0-9\.]/i', '_', $file['name']);
$uploadPath = '../../public/uploads/projects/' . $filename;

if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo json_encode([
        'success' => true,
        'path' => '/uploads/projects/' . $filename
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to upload file']);
}
?>