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
    if (empty($data['name']) || empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
        exit;
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    // Check if email already exists for other buyers
    $stmt = $pdo->prepare("SELECT id FROM buyers WHERE email = ? AND id != ?");
    $stmt->execute([$data['email'], $data['id']]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE buyers SET name = ?, email = ?, phone = ?, location = ? WHERE id = ?");
    $result = $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['location'],
        $data['id']
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Buyer updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update buyer']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
