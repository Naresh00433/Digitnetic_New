<!DOCTYPE html>
<html>
<?php

session_start();

// Add this line to include database configuration
require_once 'pre/db_config.php';

// Check if user is not logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: index.php");
    exit();
}

$pageTitle = "Dashboard";
require_once 'pre/head.php'; // Changed from @include to require_once

?>

<body>
	<div class="wrapper">

		<?php
		@include 'pre/header.php';
		@include 'pre/sidebar.php'
			?>

		<div class="main-panel">
			<div class="content">
				<div class="container-fluid">
					<h4 class="page-title">Dashboard</h4>
					<div class="row">
						 <!-- Total Posts Card -->
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-newspaper-o text-primary"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Total Posts</p>
												<?php
												$stmt = $conn->query("SELECT COUNT(*) FROM blog_posts");
												$totalPosts = $stmt->fetchColumn();
												?>
												<h4 class="card-title"><?php echo $totalPosts; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Published Posts Card -->
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-check-circle text-success"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Published</p>
												<?php
												$stmt = $conn->query("SELECT COUNT(*) FROM blog_posts WHERE status = 'published'");
												$publishedPosts = $stmt->fetchColumn();
												?>
												<h4 class="card-title"><?php echo $publishedPosts; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Draft Posts Card -->
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-edit text-warning"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Drafts</p>
												<?php
												$stmt = $conn->query("SELECT COUNT(*) FROM blog_posts WHERE status = 'draft'");
												$draftPosts = $stmt->fetchColumn();
												?>
												<h4 class="card-title"><?php echo $draftPosts; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Categories Card -->
						<div class="col-md-3">
							<div class="card card-stats">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="la la-folder text-info"></i>
											</div>
										</div>
										<div class="col-7 d-flex align-items-center">
											<div class="numbers">
												<p class="card-category">Categories</p>
												<?php
												$stmt = $conn->query("SELECT COUNT(*) FROM blog_category");
												$totalCategories = $stmt->fetchColumn();
												?>
												<h4 class="card-title"><?php echo $totalCategories; ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Recent Posts Table -->
					<div class="row">
						<div class="col-md-8">
							<div class="card">
								<div class="card-header d-flex justify-content-between align-items-center">
									<div class="card-title mb-0">Recent Posts</div>
									<a href="all-posts" class="btn btn-primary btn-sm"> View All <i class="la la-arrow-right"></i></a>

								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Title</th>
													<th>Category</th>
													<th>Status</th>
													<th>Date</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$stmt = $conn->query("
													SELECT p.title, c.cat_name, p.status, p.created_at 
													FROM blog_posts p 
													LEFT JOIN blog_category c ON p.cat_id = c.cat_id 
													ORDER BY p.created_at DESC 
													LIMIT 5
												");
												while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
													$statusClass = $row['status'] === 'published' ? 'success' : 'warning';
													echo "<tr>
														<td>" . htmlspecialchars($row['title']) . "</td>
														<td>" . htmlspecialchars($row['cat_name']) . "</td>
														<td><span class='badge bg-{$statusClass}'>" . ucfirst($row['status']) . "</span></td>
														<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>
													</tr>";
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<!-- Category Statistics -->
						<div class="col-md-4">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Posts by Category</div>
								</div>
								<div class="card-body">
									<?php
									$stmt = $conn->query("
										SELECT c.cat_name, COUNT(p.post_id) as post_count 
										FROM blog_category c 
										LEFT JOIN blog_posts p ON c.cat_id = p.cat_id 
										GROUP BY c.cat_id 
										ORDER BY post_count DESC 
										LIMIT 5
									");
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										$percentage = $totalPosts > 0 ? ($row['post_count'] / $totalPosts) * 100 : 0;
										?>
										<div class="progress-card">
											<div class="d-flex justify-content-between mb-1">
												<span class="text-muted"><?php echo htmlspecialchars($row['cat_name']); ?></span>
												<span class="text-muted fw-bold"><?php echo $row['post_count']; ?> posts</span>
											</div>
											<div class="progress mb-2" style="height: 7px;">
												<div class="progress-bar bg-primary" role="progressbar" 
													style="width: <?php echo $percentage; ?>%" 
													aria-valuenow="<?php echo $percentage; ?>" 
													aria-valuemin="0" 
													aria-valuemax="100">
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="my5"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></div>
			<?php
			@include 'pre/footer.php';
			?>
		</div>
	</div>
</body>
<?php

@include 'pre/footerScript.php';
?>

</html>