<?php
// At the top of the file, add database connection if not already included
require_once 'db_config.php';
?>
<div class="sidebar">
    <div class="scrollbar-inner sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="assets/img/profile.jpg" alt="Profile">
            </div>
            <div class="info">
                <a class="" data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                    <span>
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        <span class="user-level">
                            <?php
                            $role_name = "User"; // Default role name
                            if (isset($_SESSION['user_role'])) {
                                try {
                                    $stmt = $conn->prepare("SELECT role_name FROM roles WHERE id = ?");
                                    $stmt->execute([$_SESSION['user_role']]);
                                    $role = $stmt->fetch(PDO::FETCH_ASSOC);
                                    if ($role) {
                                        $role_name = ucwords($role['role_name']);
                                    }
                                } catch (PDOException $e) {
                                    error_log("Error fetching role name: " . $e->getMessage());
                                }
                            }
                            echo htmlspecialchars($role_name);
                            ?>
                        </span>
                        <span class="caret"></span>
                    </span>
                </a>
                <div class="clearfix"></div>
            </div>
        </div>
        <ul class="nav">
            <!-- Dashboard - Always visible -->
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'dashboard') ? 'active' : ''; ?>">
                <a href="dashboard">
                    <i class="la la-dashboard"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Posts Management -->
            <?php 
            $isPostsPage = in_array(basename($_SERVER['PHP_SELF'], '.php'), ['all-posts', 'add-post']);
            if (in_array('view_posts', $_SESSION['permissions']) || in_array('create_post', $_SESSION['permissions'])): 
            ?>
            <li class="nav-item <?php echo $isPostsPage ? 'active' : ''; ?>">
                <a data-toggle="collapse" href="#posts" aria-expanded="<?php echo $isPostsPage ? 'true' : 'false'; ?>" 
                   class="<?php echo $isPostsPage ? 'active' : ''; ?>">
                    <i class="la la-newspaper-o"></i>
                    <p>Posts</p>
                    <span class="caret"></span>
                </a>
                <div class="collapse <?php echo $isPostsPage ? 'show' : ''; ?>" id="posts">
                    <ul class="nav nav-collapse">
                        <?php if (in_array('view_posts', $_SESSION['permissions'])): ?>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'all-posts') ? 'active' : ''; ?>">
                            <a href="all-posts">
                                <span class="sub-item">All Posts</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (in_array('create_post', $_SESSION['permissions'])): ?>
                        <li class="<?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'add-post') ? 'active' : ''; ?>">
                            <a href="add-post">
                                <span class="sub-item">Add New Post</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- Categories Management -->
            <?php if (in_array('view_category', $_SESSION['permissions']) || in_array('create_category', $_SESSION['permissions'])): ?>
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'all-category') ? 'active' : ''; ?>">
                <a href="all-category">
                    <i class="la la-list"></i>
                    <p>Categories</p>
                </a>
            </li>
            <?php endif; ?>

            <!-- User Management -->
            <?php if (in_array('manage_users', $_SESSION['permissions'])): ?>
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'manage-users') ? 'active' : ''; ?>">
                <a href="manage-users">
                    <i class="la la-users"></i>
                    <p>Manage Users</p>
                </a>
            </li>
            <?php endif; ?>

            <!-- Role Management -->
            <?php if (in_array('manage_roles', $_SESSION['permissions'])): ?>
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'manage-roles') ? 'active' : ''; ?>">
                <a href="manage-roles">
                    <i class="la la-key"></i>
                    <p>Manage Roles</p>
                </a>
            </li>
            <?php endif; ?>

            <!-- Logout Button -->
            <li class="nav-item update-pro">
                <a class="btn btn-primary btn-block text-white" href="auth-process/logout.php">
                    <i class="la la-sign-out text-white"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </div>
</div>