<!DOCTYPE html>
<html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}


// Check if user has permission to edit posts
if (!in_array('edit_post', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to edit posts.';
    header("Location: dashboard.php");
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'Invalid post ID';
    header("Location: all-posts.php");
    exit();
}

// Get post data
require_once 'pre/db_config.php'; // Change include to require_once
try {
    $postId = intval($_GET['id']); // Sanitize the input
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE post_id = ?"); // Changed id to post_id
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        $_SESSION['error'] = 'Post not found';
        header("Location: all-posts.php");
        exit();
    }

    // Add null checks for form values
    $post = array_map(function ($value) {
        return $value === null ? '' : $value;
    }, $post);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $_SESSION['error'] = 'Error loading post: ' . $e->getMessage();
    header("Location: all-posts.php");
    exit();
}

$pageTitle = "Edit Post";
require_once 'pre/head.php'; // Change include to require_once
?>

<head>
    <!-- CKEditor CSS -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.css" crossorigin>
    <link rel="stylesheet" href="assets/css/post-editor/style.css">

    <style>
        /* Copy the same styles from add-post.php */
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 10px 0;
        }

        /* ... (include all other styles from add-post.php) ... */
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        @include 'pre/header.php';
        @include 'pre/sidebar.php';
        ?>

        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <!-- Post Header -->
                    <div class="post-header">
                        <h4 class="page-title">Edit Post</h4>
                        <div class="post-actions">
                            <button class="btn btn-warning"><i class="la la-save"></i> Save Draft</button>
                            <button class="btn btn-primary"><i class="la la-check-circle"></i> Update</button>
                        </div>
                    </div>

                    <form id="postForm" enctype="multipart/form-data">
                        <input type="hidden" id="postId" value="<?php echo htmlspecialchars($post['post_id'] ?? ''); ?>">

                        <!-- Title Card -->
                        <div class="card post-title-card">
                            <div class="card-body">
                                <input type="text" class="form-control" id="postTitle"
                                    value="<?php echo htmlspecialchars($post['title']); ?>"
                                    placeholder="Enter post title">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Main Content Column -->
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="" id="editor-container">
                                            <div class="editor-container__editor">
                                                <div id="editor">
                                                    <?php 
                                                        // Properly decode HTML content from database
                                                        $content = isset($post['content']) ? html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
                                                        echo $content;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Column -->
                            <div class="col-md-3">
                                <!-- SEO Meta Card -->
                                <div class="card sidebar-card">
                                    <div class="card-header">
                                        <h4><i class="la la-search"></i> SEO Meta</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="metaTitle"
                                                value="<?php echo htmlspecialchars($post['meta_title']); ?>"
                                                placeholder="Enter meta title" maxlength="60">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" id="description" rows="3"
                                                placeholder="Enter description"
                                                maxlength="160"><?php echo htmlspecialchars($post['description']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="metaDescription" rows="3"
                                                placeholder="Enter meta description"
                                                maxlength="160"><?php echo htmlspecialchars($post['meta_description']); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Card -->
                                <div class="card sidebar-card">
                                    <div class="card-header">
                                        <h4><i class="la la-folder"></i> Category</h4>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">Select Category</label>
                                        <select class="form-control" id="postCategory">
                                            <option value="">Select Category</option>
                                            <?php
                                            $sql = "SELECT cat_id, cat_name FROM blog_category ORDER BY cat_name ASC";
                                            $result = $conn->query($sql);

                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = ($row['cat_id'] == $post['cat_id']) ? 'selected' : '';
                                                    echo "<option value='" . $row['cat_id'] . "' {$selected}>" .
                                                        htmlspecialchars($row['cat_name']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Permalink Card -->
                                <div class="card sidebar-card">
                                    <div class="card-header">
                                        <h4><i class="la la-link"></i> Permalink</h4>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">URL Slug</label>
                                        <input type="text" class="form-control" id="postPermalink"
                                            value="<?php echo htmlspecialchars($post['slug']); ?>"
                                            placeholder="post-url-slug">
                                    </div>
                                </div>

                                <!-- Featured Image Card -->
                                <div class="card sidebar-card">
                                    <div class="card-header">
                                        <h4><i class="la la-image"></i> Featured Image</h4>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">Select Image</label>
                                        <input type="file" class="form-control" id="postFeaturedImage">
                                        <div id="imagePreview" class="mt-3">
                                            <?php if (!empty($post['featured_image'])): ?>
                                                <div class="position-relative">
                                                    <img src="../blog-upload/images/<?php echo htmlspecialchars($post['featured_image']); ?>"
                                                        class="img-fluid">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                        id="removeImage">
                                                        <i class="la la-times"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php @include 'pre/footer.php'; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.umd.js" crossorigin></script>
    <script src="assets/js/post-editor/main.js"></script>
    <script src="assets/js/edit-post-process.js"></script>
    <?php @include 'pre/footerScript.php'; ?>
</body>

</html>