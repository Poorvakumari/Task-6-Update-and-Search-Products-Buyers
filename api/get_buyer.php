<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Buyer ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM buyers WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $buyer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($buyer) {
        echo json_encode($buyer);
    } else {
        echo json_encode(['success' => false, 'message' => 'Buyer not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
