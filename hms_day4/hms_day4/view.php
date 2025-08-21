<?php
include 'config.php';

// --- Search Handling ---
$search = isset($_GET['search']) ? $_GET['search'] : '';

// --- Pagination Setup ---
$limit = 5; // entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// --- Get Total Records for Pagination ---
$total_sql = "SELECT COUNT(*) as total FROM entries 
              WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// --- Fetch Records with Search & Pagination ---
$sql = "SELECT * FROM entries 
        WHERE name LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY id DESC 
        LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Entries - Your Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>All Entries</h2>

    <!-- Search Form -->
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by Name or Email" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Entries Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <a href='update.php?id={$row['id']}' class='btn btn-primary btn-sm'>Edit</a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this entry?');\">Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No entries found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="mt-3">
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $page-1; ?>">Previous</a>
            </li>

            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?php echo ($i==$page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $page+1; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
</body>
</html>
