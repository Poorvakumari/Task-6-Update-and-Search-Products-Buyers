<?php
session_start();
require_once 'config/database.php';
require_once 'includes/pagination.php';

// Handle search and pagination
$search = isset($_GET['search']) ? $_GET['search'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Build query for total count
$count_query = "SELECT COUNT(*) as total FROM buyers WHERE 1=1";
$params = [];

if ($search) {
    $count_query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($location) {
    $count_query .= " AND location = ?";
    $params[] = $location;
}

$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Build query for paginated results
$query = "SELECT * FROM buyers WHERE 1=1";
$params = []; // Reset params array for the main query

if ($search) {
    $query .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($location) {
    $query .= " AND location = ?";
    $params[] = $location;
}

$query .= " LIMIT " . (int)$records_per_page . " OFFSET " . (int)$offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$buyers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get unique locations for filter
$locations = $pdo->query("SELECT DISTINCT location FROM buyers WHERE location IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

// Generate pagination
$pagination = getPagination($total_records, $records_per_page, $page);
$search_params = getSearchParams($_GET);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Management</title>
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
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Buyer Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="buyers.php">Buyers</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Search and Filter Section -->
        <div class="search-container">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="search" placeholder="Search buyers..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="location">
                        <option value="">All Locations</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo $location === $loc ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($loc); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
                <div class="col-md-1">
                    <a href="buyers.php" class="btn btn-secondary w-100" title="Clear Search">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Buyers Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($buyers as $buyer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buyer['id']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['name']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['email']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['phone']); ?></td>
                            <td><?php echo htmlspecialchars($buyer['location']); ?></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-primary edit-buyer" data-id="<?php echo $buyer['id']; ?>" title="Edit Buyer">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-buyer" data-id="<?php echo $buyer['id']; ?>" title="Delete Buyer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php echo $pagination; ?>
    </div>

    <!-- Edit Buyer Modal -->
    <div class="modal fade" id="editBuyerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Buyer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editBuyerForm">
                        <input type="hidden" id="buyerId">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="buyerName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="buyerEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="buyerPhone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" id="buyerLocation">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBuyerChanges">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Highlight search terms
            function highlightText(text, searchTerm) {
                if (!searchTerm) return text;
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                return text.replace(regex, '<span class="highlight">$1</span>');
            }

            // Apply highlighting to table cells
            const searchTerm = '<?php echo htmlspecialchars($search); ?>';
            if (searchTerm) {
                $('.table td').each(function() {
                    const text = $(this).text();
                    $(this).html(highlightText(text, searchTerm));
                });
            }

            // Handle edit button click
            $('.edit-buyer').click(function() {
                const id = $(this).data('id');
                // Fetch buyer details and populate modal
                $.get('api/get_buyer.php', { id: id }, function(buyer) {
                    $('#buyerId').val(buyer.id);
                    $('#buyerName').val(buyer.name);
                    $('#buyerEmail').val(buyer.email);
                    $('#buyerPhone').val(buyer.phone);
                    $('#buyerLocation').val(buyer.location);
                    $('#editBuyerModal').modal('show');
                });
            });

            // Handle save changes
            $('#saveBuyerChanges').click(function() {
                const buyerData = {
                    id: $('#buyerId').val(),
                    name: $('#buyerName').val(),
                    email: $('#buyerEmail').val(),
                    phone: $('#buyerPhone').val(),
                    location: $('#buyerLocation').val()
                };

                $.ajax({
                    url: 'api/update_buyer.php',
                    type: 'POST',
                    data: JSON.stringify(buyerData),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            alert('Buyer updated successfully');
                            location.reload();
                        } else {
                            alert('Error updating buyer: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating buyer: ' + error);
                    }
                });
            });

            // Handle delete button click
            $('.delete-buyer').click(function() {
                if (confirm('Are you sure you want to delete this buyer?')) {
                    const id = $(this).data('id');
                    $.post('api/delete_buyer.php', { id: id }, function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error deleting buyer: ' + response.message);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
