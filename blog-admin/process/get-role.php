<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['role_id'])) {
    try {
        $roleId = $_GET['role_id'];

        // Get role details
        $stmt = $conn->prepare("
            SELECT r.*, GROUP_CONCAT(rp.permission_id) as permission_ids
            FROM roles r
            LEFT JOIN role_permissions rp ON r.id = rp.role_id
            WHERE r.id = ?
            GROUP BY r.id
        ");
        $stmt->execute([$roleId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$role) {
            throw new Exception('Role not found');
        }

        // Format permissions
        $role['permissions'] = $role['permission_ids'] ? explode(',', $role['permission_ids']) : [];
        unset($role['permission_ids']);

        echo json_encode(['status' => 'success', 'role' => $role]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}