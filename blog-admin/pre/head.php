<head>
	<?php

	global $base_url;
	// $base_url = "http://localhost/MetricVibes(Tools)/Analyst%20AI/analyticsai.in%20v-1.2/blog-admin/";
	// $base_url = "https://www.analyticsai.in/blog-admin/";
	$base_url = $_SERVER['HTTP_HOST'];
	?>
	<base href="<?php echo $base_url; ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo $pageTitle; ?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
		name='viewport' />
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="assets/css/ready.css">
	<link rel="stylesheet" href="assets/css/demo.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<!-- Toastr CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


</head>

<?php
// Enable error logging
ini_set('log_errors', 1);
ini_set('display_errors', 0); // Hide errors from users
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/error.log'); // Log file path

// Optional: Catch all errors and log them
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno]: $errstr in $errfile on line $errline");
    return true; // Prevent PHP default error handling
});
?>