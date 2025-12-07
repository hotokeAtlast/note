<?php
include 'session_starter.php';
require 'db_conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access Forbidden']);
    exit;
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing note ID']);
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id AND uid = :uid");
$stmt->execute([':id' => $id, ':uid' => $_SESSION['uid']]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access forbidden']);
    exit;
}

echo json_encode([
    'id' => $note['id'],
    'title' => $note['title'],
    'content' => $note['content'],
    'date' => date("d/m/Y H:i", strtotime($note['date']))
]);

?>