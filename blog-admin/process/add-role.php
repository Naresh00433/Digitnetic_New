<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $roleName = trim($_POST['role_name']);
        $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

        // Validate role name
        if (empty($roleName)) {
            throw new Exception('Role name is required');
        }

        // Check if role name already exists
        $stmt = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
        $stmt->execute([$roleName]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('Role name already exists');
        }

        // Begin transaction
        $conn->beginTransaction();

        // Insert role
        $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
        $stmt->execute([$roleName]);
        $roleId = $conn->lastInsertId();

        // Insert permissions
        if (!empty($permissions)) {
            $stmt = $conn->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($permissions as $permissionId) {
                $stmt->execute([$roleId, $permissionId]);
            }
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}