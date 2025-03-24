<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Log the received data
error_log("Received data: " . print_r($data, true));

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
    exit;
}

try {
    // Validate required fields
    if (empty($data['name']) || empty($data['category']) || !isset($data['price'])) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit;
    }

    // Validate price is numeric and positive
    if (!is_numeric($data['price']) || $data['price'] < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid price value']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, price = ?, description = ? WHERE id = ?");
    $result = $stmt->execute([
        $data['name'],
        $data['category'],
        $data['price'],
        $data['description'],
        $data['id']
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
