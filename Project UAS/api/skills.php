<?php
header('Content-Type: application/json');
require_once '../includes/auth.php';
require_once '../includes/db.php';

$skills = getSkills();
echo json_encode($skills);
try {
    $stmt = $pdo->query("SELECT * FROM skill");
    $skills = $stmt->fetchAll();
    echo json_encode($skills);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch skills']);
}
?>