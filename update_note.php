<?php
include 'session_starter.php';
require 'db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['content'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required data']);
    exit;
}

$id = $data['id'];
$uid = $_SESSION['uid'];

// ✅ Step 1: Check if the note belongs to the user
$stmt = $pdo->prepare("SELECT id FROM notes WHERE id = :id AND uid = :uid");
$stmt->execute([':id' => $id, ':uid' => $uid]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access forbidden']);
    exit;
}

// ✅ Step 2: Update the note content
$update = $pdo->prepare("UPDATE notes SET content = :content WHERE id = :id AND uid = :uid");
$update->execute([':content' => $data['content'], ':id' => $id, ':uid' => $uid]);

echo 'Note updated successfully';