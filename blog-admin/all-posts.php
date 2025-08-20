<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}

// Check if user has permission to view posts
if (!in_array('view_posts', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to view posts.';
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<?php

$pageTitle = "All Posts";
@include 'pre/head.php';

?>

<style>
    /* Add this to your CSS file */
    .badge {
        padding: 4px 8px;
        font-size: 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    .table td {
        vertical-align: middle;
        padding: 0.5rem !important;
    }

    .action-buttons {
        white-space: nowrap;
        display: flex;
        gap: 2px;
    }

    .action-buttons .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.875rem;
    }

    .action-buttons .la {
        font-size: 14px;
    }

    h1 {
        font-size: 32px;
    }

    h2 { 
        font-size: 28px;
    }

    h3 {
        font-size: 24px;
    }
    
</style>

<body>
    <div class="wrapper">

        <?php
        @include 'pre/header.php';
        @include 'pre/sidebar.php'
            ?>

        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">All Posts</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between">
                                    <h4 class="card-title">Posts List</h4>
                                    <a href="add-post" class="btn btn-primary"><i class="la la-plus-circle"></i> Add
                                        New Post
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No</th>
                                                    <th>Title</th>
                                                    <th>Slug</th>
                                                    <th>Category</th>
                                                    <th>Created On</th>
                                                    <th>Last Modified</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Database connection
                                                require_once 'pre/db_config.php';

                                                // Fetch posts with category names
                                                $sql = "SELECT p.*, c.cat_name 
                                                       FROM blog_posts p 
                                                       LEFT JOIN blog_category c ON p.cat_id = c.cat_id 
                                                       ORDER BY p.created_at DESC";

                                                $result = $conn->query($sql);

                                                if ($result->rowCount() > 0) {
                                                    $index = 0;
                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        // Define status badge colors
                                                        $statusBadge = '';
                                                        switch (strtolower($row['status'])) {
                                                            case 'published':
                                                                $statusBadge = 'success';
                                                                break;
                                                            case 'draft':
                                                                $statusBadge = 'warning';
                                                                break;
                                                            case 'pending':
                                                                $statusBadge = 'info';
                                                                break;
                                                            default:
                                                                $statusBadge = 'secondary';
                                                        }
                                                        $index++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['slug']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['cat_name'] ?? 'Uncategorized'); ?>
                                                            </td>
                                                            <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?>
                                                            </td>
                                                            <td><?php echo date('Y-m-d H:i', strtotime($row['updated_at'])); ?>
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-<?php echo $statusBadge; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <?php if (in_array('edit_post', $_SESSION['permissions'])): ?>
                                                                        <a href="edit-post.php?id=<?php echo $row['post_id']; ?>"
                                                                            class="btn btn-sm btn-primary">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
                                                                    <?php endif; ?>

                                                                    <?php if (in_array('delete_post', $_SESSION['permissions'])): ?>
                                                                        <a href="process/post-delete.php?id=<?php echo $row['post_id']; ?>"
                                                                            class="btn btn-sm btn-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">
                                                                            <i class="la la-trash"></i>
                                                                        </a>
                                                                    <?php endif; ?>

                                                                    <a href="../blog/<?php echo $row['slug']; ?>"
                                                                        target="_blank" class="btn btn-sm btn-info">
                                                                        <i class="la la-eye"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No posts found</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            @include 'pre/footer.php';
            ?>
        </div>
    </div>
</body>
<?php

@include 'pre/footerScript.php';
?>

</html>