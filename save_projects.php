<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$raw = file_get_contents('php://input');
if (empty($raw)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit();
}

$data = json_decode($raw, true);
if ($data === null || !is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit();
}

foreach ($data as $project) {
    if (!isset($project['id'], $project['title'], $project['subtitle'], $project['image'], $project['tags'], $project['description'], $project['images'], $project['links'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields in project: ' . ($project['title'] ?? '?')]);
        exit();
    }
}

$file = __DIR__ . '/projects.json';
$result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

if ($result === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to write file']);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Projects saved successfully']);
