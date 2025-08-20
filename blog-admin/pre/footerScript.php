<script src="assets/js/core/jquery.3.2.1.min.js"></script>
<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/chartist/chartist.min.js"></script>
<script src="assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/ready.min.js"></script>
<script src="assets/js/demo.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Configure toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000",
        "showDuration": "300",
        "hideDuration": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Show error messages
    <?php if (isset($_SESSION['error'])): ?>
        toastr.error('<?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES); ?>', 'Error');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    // Show success messages
    <?php if (isset($_SESSION['success'])): ?>
        toastr.success('<?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES); ?>', 'Success');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    // Show warning messages
    <?php if (isset($_SESSION['warning'])): ?>
        toastr.warning('<?php echo htmlspecialchars($_SESSION['warning'], ENT_QUOTES); ?>', 'Warning');
        <?php unset($_SESSION['warning']); ?>
    <?php endif; ?>

    // Show info messages
    <?php if (isset($_SESSION['info'])): ?>
        toastr.info('<?php echo htmlspecialchars($_SESSION['info'], ENT_QUOTES); ?>', 'Information');
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>
</script>