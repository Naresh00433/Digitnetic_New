<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $roleId = $_POST['role_id'];
        $roleName = trim($_POST['role_name']);
        $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

        // Validate inputs
        if (empty($roleId) || empty($roleName)) {
            throw new Exception('Role ID and name are required');
        }

        // Check if role exists
        $stmt = $conn->prepare("SELECT id FROM roles WHERE id = ?");
        $stmt->execute([$roleId]);
        if ($stmt->rowCount() === 0) {
            throw new Exception('Role not found');
        }

        // Begin transaction
        $conn->beginTransaction();

        // Update role name
        $stmt = $conn->prepare("UPDATE roles SET role_name = ? WHERE id = ?");
        $stmt->execute([$roleName, $roleId]);

        // Delete existing permissions
        $stmt = $conn->prepare("DELETE FROM role_permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);

        // Insert new permissions
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