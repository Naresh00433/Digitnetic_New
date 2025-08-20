<?php
session_start();
require_once 'pre/db_config.php';

// Check authentication and permissions
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}

if (!in_array('manage_roles', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to manage roles.';
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Manage Roles";
require_once 'pre/head.php';

// Fetch all roles and their permissions
$roles = $conn->query("SELECT r.id, r.role_name, GROUP_CONCAT(p.permission_name SEPARATOR ', ') AS permissions
                       FROM roles r
                       LEFT JOIN role_permissions rp ON r.id = rp.role_id
                       LEFT JOIN permissions p ON rp.permission_id = p.id
                       GROUP BY r.id, r.role_name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all available permissions
$allPermissions = $conn->query("SELECT * FROM permissions ORDER BY permission_name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<body>
    <div class="wrapper">
        <?php
        require_once 'pre/header.php';
        require_once 'pre/sidebar.php';
        ?>

        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="page-title">Manage Roles</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
                                <i class="la la-plus"></i> Add New Role
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($roles as $role): ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title"><?php echo htmlspecialchars(ucwords($role['role_name'])); ?>
                                        </h4>
                                        <button class="btn btn-sm btn-light" onclick="editRole(<?php echo $role['id']; ?>)">
                                            <i class="la la-edit"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Permissions:</strong></p>
                                        <ul class="list-unstyled permission-list">
                                            <?php 
                                            $permissions = explode(', ', $role['permissions']);
                                            foreach($permissions as $permission): 
                                                if(!empty($permission)):
                                                    // Define icon based on permission type
                                                    $icon = match (true) {
                                                        str_contains($permission, 'create_post') => 'la-plus-circle',
                                                        str_contains($permission, 'edit_post') => 'la-edit',
                                                        str_contains($permission, 'delete_post') => 'la-trash',
                                                        str_contains($permission, 'manage_users') => 'la-users',
                                                        str_contains($permission, 'manage_roles') => 'la-user-shield',
                                                        str_contains($permission, 'view_category') => 'la-folder-open',
                                                        str_contains($permission, 'create_category') => 'la-folder-plus',
                                                        str_contains($permission, 'view_posts') => 'la-file-alt',
                                                        default => 'la-check-circle'
                                                    };
                                            ?>
                                                <li>
                                                    <i class="la <?php echo $icon; ?> permission-icon"></i> 
                                                    <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $permission))); ?>
                                                </li>
                                            <?php 
                                                endif;
                                            endforeach; 
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php require_once 'pre/footer.php'; ?>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="addRoleForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" class="form-control" name="role_name" required>
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="permissions-list">
                                <?php foreach ($allPermissions as $permission): ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="perm_<?php echo $permission['id']; ?>" name="permissions[]"
                                            value="<?php echo $permission['id']; ?>">
                                        <label class="custom-control-label" for="perm_<?php echo $permission['id']; ?>">
                                            <?php echo ucwords(str_replace('_', ' ', $permission['permission_name'])); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editRoleForm">
                    <input type="hidden" name="role_id" id="edit_role_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Role Name</label>
                            <input type="text" class="form-control" name="role_name" id="edit_role_name" required>
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="permissions-list" id="edit_permissions_list">
                                <?php foreach ($allPermissions as $permission): ?>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="edit_perm_<?php echo $permission['id']; ?>" name="permissions[]"
                                            value="<?php echo $permission['id']; ?>">
                                        <label class="custom-control-label"
                                            for="edit_perm_<?php echo $permission['id']; ?>">
                                            <?php echo ucwords(str_replace('_', ' ', $permission['permission_name'])); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once 'pre/footerScript.php'; ?>

    <script>
        $(document).ready(function () {
            // Add Role Form Submit
            $('#addRoleForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'process/add-role.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                toastr.success('Role added successfully');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(data.message || 'Failed to add role');
                            }
                        } catch (e) {
                            toastr.error('An error occurred');
                        }
                    }
                });
            });

            // Edit Role Form Submit
            $('#editRoleForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'process/edit-role.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                toastr.success('Role updated successfully');
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(data.message || 'Failed to update role');
                            }
                        } catch (e) {
                            toastr.error('An error occurred');
                        }
                    }
                });
            });
        });

        function editRole(roleId) {
            $.ajax({
                url: 'process/get-role.php',
                type: 'GET',
                data: { role_id: roleId },
                success: function (response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#edit_role_id').val(data.role.id);
                            $('#edit_role_name').val(data.role.role_name);

                            // Reset all checkboxes
                            $('#edit_permissions_list input[type="checkbox"]').prop('checked', false);

                            // Check the permissions that the role has
                            data.role.permissions.forEach(permissionId => {
                                $(`#edit_perm_${permissionId}`).prop('checked', true);
                            });

                            $('#editRoleModal').modal('show');
                        } else {
                            toastr.error(data.message || 'Failed to load role data');
                        }
                    } catch (e) {
                        toastr.error('An error occurred');
                    }
                }
            });
        }
    </script>

    <style>
        /* Permission list styling */
        .permission-list {
            margin: 0;
            padding: 0;
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .permission-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .permission-item .la {
            font-size: 16px;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .permission-item span {
            font-size: 13px;
            color: #495057;
        }

        /* Icon colors */
        .text-success {
            color: #28a745 !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        /* Card styling */
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            /* background: linear-gradient(45deg, #007bff, #0056b3); */
            color: white;
        }

        .card-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .permission-list li {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .permission-icon {
            font-size: 1.5em;
            margin-right: 10px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</body>

</html>