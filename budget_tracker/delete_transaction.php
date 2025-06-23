<?php
require 'includes/config.php';
require 'includes/auth_functions.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
$success = $stmt->execute([$data['id'], $_SESSION['user_id']]);

header('Content-Type: application/json');
echo json_encode(['success' => $success]);