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

// Check if user has permission to create posts
if (!in_array('create_post', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to create posts.';
    header("Location: dashboard.php");
    exit();
}

$pageTitle = "Add New Post";
@include 'pre/head.php';
?>

<style>
    
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

<head>
    <!-- CKEditor CSS -->
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.3.0/ckeditor5.css" crossorigin>
    <link rel="stylesheet" href="assets/css/post-editor/style.css">

    <style>
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 10px 0;
        }

        .post-actions {
            display: flex;
            gap: 10px;
        }

        .post-title-card {
            margin-bottom: 20px;
        }

        .post-title-card .form-control {
            font-size: 24px;
            font-weight: 500;
            padding: 15px;
            border: none;
            box-shadow: none;
        }

        .post-title-card .form-control:focus {
            border: none;
            box-shadow: none;
        }

        .sidebar-card {
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar-card .card-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .sidebar-card .card-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .sidebar-card .card-body {
            padding: 20px;
        }

        .editor-container {
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .editor-container__toolbar {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .editor-container__editor {
            min-height: 800px;
            padding: 20px;
        }

        select.form-control {
            height: 45px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .ck-editor__editable_inline {
            min-height: 700px;
        }

        #imagePreview {
            position: relative;
        }

        #imagePreview img {
            max-height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }

        #removeImage {
            opacity: 0.8;
        }

        #removeImage:hover {
            opacity: 1;
        }
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
                        <h4 class="page-title">Create New Post</h4>
                        <div class="post-actions">
                            <button class="btn btn-warning"><i class="la la-save"></i> Save Draft</button>
                            <button class="btn btn-primary"><i class="la la-check-circle"></i> Publish</button>
                        </div>
                    </div>

                    <form id="postForm" enctype="multipart/form-data">
                        <!-- Title Card -->
                        <div class="card post-title-card">
                            <div class="card-body">
                                <input type="text" class="form-control" id="postTitle" placeholder="Enter post title">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Main Content Column -->
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">

                                        <!-- <div class="editor-container editor-container_classic-editor"
                                            id="editor-container"> -->
                                        <div class="" id="editor-container">
                                            <div class="editor-container__editor">
                                                <div id="editor"></div>
                                            </div>
                                        </div>
2

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
                                        <!-- Meta Title -->
                                        <div class="mb-3">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="metaTitle"
                                                placeholder="Enter meta title" maxlength="60">
                                        </div>

                                        <!--  Description -->
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" id="description" rows="3"
                                                placeholder="Enter description" maxlength="160"></textarea>
                                        </div>
                                        <!-- Meta Description -->
                                        <div class="mb-3">
                                            <label class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="metaDescription" rows="3"
                                                placeholder="Enter meta description" maxlength="160"></textarea>
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
                                            // Database connection
                                            include 'pre/db_config.php';  // Adjust path as needed
                                            
                                            // Fetch categories from database
                                            $sql = "SELECT cat_id, cat_name FROM blog_category ORDER BY cat_name ASC";
                                            $result = $conn->query($sql);

                                            if ($result->rowCount() > 0) {
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option value='" . $row['cat_id'] . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
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
                                        <div id="imagePreview" class="mt-3"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- <input type="submit" name="" id=""> -->
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
    <script src="assets/js/add-post-process.js"></script>
    <?php @include 'pre/footerScript.php'; ?>
</body>

</html>