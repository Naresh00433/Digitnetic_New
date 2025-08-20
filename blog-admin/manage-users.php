<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}

// Check if user has permission to manage users
if (!in_array('manage_users', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to manage users.';
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Manage Users";
@include 'pre/head.php';
@include 'pre/db_config.php';

// Fetch all roles for dropdown
$roles = $conn->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<body>
    <div class="wrapper">
        <?php
        @include 'pre/header.php';
        @include 'pre/sidebar.php';
        ?>

        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Manage Users</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="d-flex card-header justify-content-between">
                                    <h4 class="card-title">Users List</h4>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                                        <i class="la la-plus-circle"></i> Add New User
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Status</th>  <!-- Add this line -->
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $stmt = $conn->query("
                                                SELECT u.*, r.role_name 
                                                FROM blog_users u 
                                                LEFT JOIN roles r ON u.role_id = r.id 
                                                ORDER BY u.id DESC
                                            ");
                                            $index=0;
                                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $index++;
                                                echo '<tr>';
                                                echo '<td>'.$index.'</td>';
                                                echo '<td>'.htmlspecialchars($row['first_name'] . ' ' . $row['last_name']).'</td>';
                                                echo '<td>'.htmlspecialchars($row['email']).'</td>';
                                                echo '<td>'.htmlspecialchars($row['role_name']).'</td>';
                                                echo '<td>
                                                    <span class="badge badge-'.($row['status'] == 'Active' ? 'success' : ($row['status'] == 'Inactive' ? 'warning' : 'danger')).'">
                                                        '.htmlspecialchars($row['status']).'
                                                    </span>
                                                </td>';
                                                echo '<td>'.date('Y-m-d H:i', strtotime($row['created_at'])).'</td>';
                                                echo '<td>'.date('Y-m-d H:i', strtotime($row['updated_at'])).'</td>';
                                                echo '<td>
                                                    <div class="action-buttons">';
                                                    if (in_array('manage_users', $_SESSION['permissions'])) {
                                                        echo '<button class="btn btn-sm btn-primary edit-user" 
                                                            data-id="'.$row['id'].'" 
                                                            data-firstname="'.htmlspecialchars($row['first_name']).'"
                                                            data-lastname="'.htmlspecialchars($row['last_name']).'"
                                                            data-email="'.htmlspecialchars($row['email']).'"
                                                            data-role="'.$row['role_id'].'"
                                                            data-status="'.$row['status'].'">
                                                            <i class="la la-edit"></i>
                                                        </button>';
                                                    }
                                                    echo '
                                                    </div>
                                                </td>';
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="addUserForm" action="auth-process/add-user.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role_id" required>
                                <?php foreach($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Exited">Exited</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="editUserForm" action="auth-process/edit-user.php" method="POST">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role_id" id="edit_role_id" required>
                                <?php foreach($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>">
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Exited">Exited</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php @include 'pre/footerScript.php'; ?>

    <style>
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
    </style>

    <script>
        $(document).ready(function() {
            // Edit User
            $('.edit-user').click(function() {
                var id = $(this).data('id');
                var firstname = $(this).data('firstname');
                var lastname = $(this).data('lastname');
                var email = $(this).data('email');
                var role = $(this).data('role');
                var status = $(this).data('status');

                console.log('Status:', status);

                $('#edit_user_id').val(id);
                $('#edit_first_name').val(firstname);
                $('#edit_last_name').val(lastname);
                $('#edit_email').val(email);
                $('#edit_role_id').val(role);
                $('#edit_status').val(status);

                // Verify status is set correctly
                console.log('Selected status:', $('#edit_status').val());

                $('#editUserModal').modal('show');
            });

            // Delete User
            $('.delete-user').click(function() {
                if(confirm('Are you sure you want to delete this user?')) {
                    var id = $(this).data('id');
                    window.location.href = 'user-process/delete-user.php?id=' + id;
                }
            });
        });
    </script>
</body>
</html>