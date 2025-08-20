<!DOCTYPE html>
<html>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}

// Check if user has permission to view categories
if (!in_array('view_category', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to view categories.';
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "All Categories";
@include 'pre/head.php';
@include 'pre/db_config.php';

?>

<body>
    <div class="wrapper">
        <?php
        @include 'pre/header.php';
        @include 'pre/sidebar.php';
        ?>

        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">All Categories</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between">
                                    <h4 class="card-title">Categories List</h4>
                                    <?php if (in_array('create_category', $_SESSION['permissions'])): ?>
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                                            <i class="la la-plus-circle"></i> Add New Category
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Category Name</th>
                                                    <th>Created Date</th>
                                                    <th>Last Modified</th>
                                                    <th>Post Count</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $conn->query("SELECT cat_id, cat_name, cat_date, timestamp, (SELECT COUNT(*) FROM blog_posts WHERE cat_id = blog_category.cat_id) AS post_count FROM blog_category ORDER BY cat_id DESC");
                                                $index = 0;
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $index++;
                                                    echo '<tr>';
                                                    echo '<td>' . $index . '</td>';
                                                    echo '<td>' . $row['cat_name'] . '</td>';
                                                    echo '<td>' . $row['cat_date'] . '</td>';
                                                    echo '<td>' . $row['timestamp'] . '</td>';
                                                    echo '<td>' . $row['post_count'] . '</td>';
                                                    echo '<td>';
                                                    if (in_array('create_category', $_SESSION['permissions'])) {
                                                        echo '<button class="btn btn-sm btn-primary edit-category" data-id="' . $row['cat_id'] . '" data-name="' . $row['cat_name'] . '">
                                                        <i class="la la-edit"></i>
                                                    </button>';
                                                    }
                                                    echo '</td>';
                                                    echo '</tr>';
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
            <?php @include 'pre/footer.php'; ?>
        </div>
    </div>

    <?php if (in_array('create_category', $_SESSION['permissions'])): ?>
        <!-- Add Category Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCategoryForm">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" id="categoryName" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Edit Category Modal -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCategoryForm">
                            <input type="hidden" id="editCategoryId">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" id="editCategoryName" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateCategoryBtn">Update Category</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php @include 'pre/footerScript.php'; ?>
    <script src="assets/js/category.js"></script>

    <?php if (isset($successMessage)): ?>
        <script>
            $(document).ready(function () {
                $.notify({
                    icon: 'la la-bell',
                    message: "<?php echo $successMessage; ?>"
                }, {
                    type: 'success',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        </script>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <script>
            $(document).ready(function () {
                $.notify({
                    icon: 'la la-bell',
                    message: "<?php echo $errorMessage; ?>"
                }, {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        </script>
    <?php endif; ?>
</body>

</html>