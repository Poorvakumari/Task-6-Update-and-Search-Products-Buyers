<?php
session_start();
require_once 'config/database.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';

// Build query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

if ($price_min) {
    $query .= " AND price >= ?";
    $params[] = $price_min;
}

if ($price_max) {
    $query .= " AND price <= ?";
    $params[] = $price_max;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique categories for filter
$categories = $pdo->query("SELECT DISTINCT category FROM products")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        body {
            padding-top: 60px;
        }
        .search-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Product Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php">Products</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Search and Filter Section -->
        <div class="search-container">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="price_min" placeholder="Min Price" value="<?php echo htmlspecialchars($price_min); ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="price_max" placeholder="Max Price" value="<?php echo htmlspecialchars($price_max); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-primary edit-product" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-product" data-id="<?php echo $product['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="productName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" id="productCategory" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProductChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle edit button click
            $('.edit-product').click(function() {
                const id = $(this).data('id');
                // Fetch product details and populate modal
                $.get('api/get_product.php', { id: id }, function(product) {
                    $('#productId').val(product.id);
                    $('#productName').val(product.name);
                    $('#productCategory').val(product.category);
                    $('#productPrice').val(product.price);
                    $('#productDescription').val(product.description);
                    $('#editProductModal').modal('show');
                });
            });

            // Handle save changes
            $('#saveProductChanges').click(function() {
                const productData = {
                    id: $('#productId').val(),
                    name: $('#productName').val(),
                    category: $('#productCategory').val(),
                    price: $('#productPrice').val(),
                    description: $('#productDescription').val()
                };

                $.ajax({
                    url: 'api/update_product.php',
                    type: 'POST',
                    data: JSON.stringify(productData),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            alert('Product updated successfully');
                            location.reload();
                        } else {
                            alert('Error updating product: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating product: ' + error);
                    }
                });
            });

            // Handle delete button click
            $('.delete-product').click(function() {
                if (confirm('Are you sure you want to delete this product?')) {
                    const id = $(this).data('id');
                    $.post('api/delete_product.php', { id: id }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error deleting product: ' + response.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
