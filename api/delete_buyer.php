<?php
require_once '../config/database.php';


header('Content-Type: application/json');
// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Buyer ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM buyers WHERE id = ?");
    $result = $stmt->execute([$data['id']]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Buyer deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete buyer']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
