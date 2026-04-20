<?php
session_start();
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

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

$repoRoot = realpath(__DIR__ . '/../../');
$file = $repoRoot . '/projects.json';
$result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

if ($result === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to write file']);
    exit();
}

$gitOutput = [];
$gitStatus = 0;

chdir($repoRoot);
exec('git add -A 2>&1', $gitOutput, $gitStatus);
if ($gitStatus !== 0) {
    echo json_encode(['success' => false, 'message' => 'git add failed: ' . implode("\n", $gitOutput)]);
    exit();
}

exec('git diff --cached --quiet 2>&1', $diffOut, $diffStatus);
if ($diffStatus === 0) {
    echo json_encode(['success' => true, 'message' => 'Saved (nothing new to commit)']);
    exit();
}

exec('git commit -m "Admin: update projects" 2>&1', $gitOutput, $gitStatus);
if ($gitStatus !== 0) {
    echo json_encode(['success' => false, 'message' => 'git commit failed: ' . implode("\n", $gitOutput)]);
    exit();
}

exec('git push 2>&1', $gitOutput, $gitStatus);
if ($gitStatus !== 0) {
    echo json_encode(['success' => false, 'message' => 'git push failed: ' . implode("\n", $gitOutput)]);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Projects saved and pushed to repository']);
