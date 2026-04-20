<?php
session_start();
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No file received or upload error']);
    exit();
}

$projectName = isset($_POST['project_name']) ? trim($_POST['project_name']) : '';
if ($projectName === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Project name is required']);
    exit();
}

$safeFolder = preg_replace('/[^a-zA-Z0-9_\- ]/', '', $projectName);
$safeFolder = trim($safeFolder);
if ($safeFolder === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid project name']);
    exit();
}

$allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['image']['tmp_name']);

if (!in_array($mime, $allowedMimes, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid file type: ' . $mime]);
    exit();
}

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$safeExt = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $ext));
$allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
if (!in_array($safeExt, $allowedExts, true)) {
    $safeExt = 'jpg';
}

$root = __DIR__ . '/../../';
$destDir = $root . 'Portfolio/' . $safeFolder . '/';

if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
}

$baseName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
$safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $baseName);
$fileName = $safeBase . '.' . $safeExt;
$destPath = $destDir . $fileName;

$counter = 1;
while (file_exists($destPath)) {
    $fileName = $safeBase . '_' . $counter . '.' . $safeExt;
    $destPath = $destDir . $fileName;
    $counter++;
}

if (!move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    exit();
}

$publicPath = 'Portfolio/' . $safeFolder . '/' . $fileName;
echo json_encode(['success' => true, 'path' => $publicPath]);
