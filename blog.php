<?php
include 'pre/db_config.php';

// Fetch post details based on slug only
// $post_slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Prepare and execute query for blog post
// $stmt = $conn->prepare("
//     SELECT p.*, c.cat_name, COALESCE(u.first_name, 'Admin') as author
//     FROM blog_posts p
//     LEFT JOIN blog_category c ON p.cat_id = c.cat_id 
//     LEFT JOIN blog_users u ON p.published_by = u.id
//     WHERE p.slug = ?
// ");

// $stmt->execute([$post_slug]);
// $post = $stmt->fetch(PDO::FETCH_ASSOC);

// If post not found, redirect to blogs page
if (!$post) {
    header('Location: blogs');
    exit();
}

// Format the post date
$post_date = date('F d, Y', strtotime($post['created_at']));

// Update recent posts query
$recent_stmt = $conn->prepare("
    SELECT 
        post_id,
        title,
        slug,
        created_at,
        featured_image,
        meta_description as excerpt
    FROM blog_posts 
    WHERE status = 'published' 
    AND slug != ?
    ORDER BY created_at DESC 
    LIMIT 3
");
$recent_stmt->execute([$post_slug]);
$recent_posts = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get categories with post count as
$cat_stmt = $conn->prepare("
    SELECT 
        c.cat_id,
        c.cat_name,
        COUNT(p.post_id) as post_count 
    FROM blog_category c
    LEFT JOIN blog_posts p ON c.cat_id = p.cat_id 
    WHERE p.status = 'published'
    GROUP BY c.cat_id
    HAVING post_count > 0
    ORDER BY c.cat_name ASC
");
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<?php
include '_pre/header.php';
head($post['title']);
?>
<link rel="stylesheet" href="css/blog.css">

<style>
    .blog-post-content img {
        max-width: 100%;
        /* Make images responsive */
        height: auto;
        /* Maintain aspect ratio */
        display: block;
        /* Remove extra space below image */
        margin: 0 auto;
        /* Center the image horizontally */
    }

    .share-button {
        padding: 0.4rem 1.2rem !important;
        /* Reduce padding */
        font-size: 0.75rem !important;
        /* Reduce font size */
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

<meta name="description" content="<?php echo htmlspecialchars($post['description']); ?>">
<meta property="og:title" content="<?php echo htmlspecialchars($post['meta_title'] ?? $post['title']); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($post['meta_description']); ?>">


<body>
    <?php include '_pre/navbar.php';
    navbar('Blog');
    ?>

    <!-- Post Header -->
    <header class="post-header text-center">
        <div class="container">
            <span class="badge badge-primary mb-3"><?php echo htmlspecialchars($post['cat_name']); ?></span>
            <h1 class="display-4"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="mt-3">
                <span><i class="far fa-calendar-alt mr-2"></i><?php echo $post_date; ?></span>
                <span class="mx-3">•</span>
                <span><i class="far fa-user mr-2"></i><?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?></span>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="sidebar-card card mb-4">
                        <?php if ($post['featured_image']): ?>
                            <img src="blog-upload/images/<?php echo htmlspecialchars($post['featured_image']); ?>"
                                alt="<?php echo htmlspecialchars($post['title']); ?>" class="card-img-top">
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="post-meta">
                                <div class="mt-2">
                                    <span class="blog-category mb-3 d-inline-block"><i class="fas fa-tag mr-2"></i>
                                        <?php echo htmlspecialchars($post['cat_name']); ?></span>

                                    <span class="mx-3">•</span>
                                    <span><i class="far fa-calendar-alt mr-2"></i><?php echo $post_date; ?></span>
                                    <span class="mx-3">•</span>
                                    <span><i
                                            class="far fa-user mr-2"></i><?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?></span>
                                </div>
                            </div>
                            <div class="post-content blog-post-content">
                                <?php echo $post['content']; ?>
                            </div>

                            <!-- Share Buttons -->
                            <div class="mt-5">
                                <h5 class="mb-3">Share this post</h5>
                                <div class="d-flex gap-2 flex-wrap">
                                    <?php
                                    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                    $current_title = htmlspecialchars($post['title']);
                                    $summary = htmlspecialchars($post['meta_description']); // Use meta description as summary
                                    $image = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/blog-upload/images/' . htmlspecialchars($post['featured_image']);
                                    ?>

                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($current_url); ?>&title=<?php echo urlencode($current_title); ?>&summary=<?php echo urlencode($summary); ?>&source=<?php echo urlencode($_SERVER['HTTP_HOST']); ?>"
                                        class="btn btn-sm mr-2 share-button" style="background: #0077B5; color: white;"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-linkedin fa-sm"></i> LinkedIn
                                    </a>

                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($current_url); ?>&text=<?php echo urlencode($current_title); ?>"
                                        class="btn btn-sm mr-2 share-button" style="background: #1DA1F2; color: white;"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-twitter fa-sm"></i> Twitter
                                    </a>

                                    <a href="https://www.instagram.com/?url=<?php echo urlencode($current_url); ?>"
                                        class="btn btn-sm mr-2 share-button" style="background: #E4405F; color: white;"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-instagram fa-sm"></i> Instagram
                                    </a>

                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($current_title . ' ' . $current_url); ?>"
                                        class="btn btn-sm mr-2 share-button" style="background: #25D366; color: white;"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-whatsapp fa-sm"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Recent Posts with Thumbnails -->
                    <div class="sidebar-card card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Recent Posts</h5>
                            <?php foreach ($recent_posts as $recent): ?>
                                <div class="recent-post d-flex align-items-center">
                                    <img src="blog-upload/images/<?php echo htmlspecialchars($recent['featured_image']); ?>"
                                        alt="<?php echo htmlspecialchars($recent['title']); ?>" class="rounded"
                                        style="width: 70px; height: 70px; object-fit: cover;">
                                    <a href="blog/<?php echo $recent['slug']; ?>" class="text-dark">
                                        <div class="ml-3">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($recent['title']); ?></h6>
                                            <small
                                                class="text-muted"><?php echo date('F d, Y', strtotime($recent['created_at'])); ?></small>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Categories with Count -->
                    <div class="sidebar-card card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Categories</h5>
                            <?php foreach ($categories as $category): ?>
                                <a href="blogs?category=<?php echo htmlspecialchars($category['cat_name']); ?>"
                                    class="category-link d-flex justify-content-between align-items-center">
                                    <span><?php echo htmlspecialchars($category['cat_name']); ?></span>
                                    <span
                                        class="badge badge-primary rounded-pill"><?php echo $category['post_count']; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'pre/footer.php';

    ?>

</body>

</html>