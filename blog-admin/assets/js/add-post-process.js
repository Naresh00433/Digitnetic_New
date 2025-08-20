// Handle form submission
$(document).ready(function() {
    // Generate slug from title
    $('#postTitle').on('keyup', function() {
        let slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        $('#postPermalink').val(slug);
    });

    // Handle image preview
    $('#postFeaturedImage').change(function() {
        const file = this.files[0];
        if (file) {
            // Check file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                toastr.error('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.');
                this.value = '';
                $('#imagePreview').empty();
                return;
            }

            // Check file size (max 5MB)
            const maxSize = 10 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                toastr.error('File is too large. Maximum size is 10MB.');
                this.value = '';
                $('#imagePreview').empty();
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-fluid">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" id="removeImage">
                            <i class="la la-times"></i>
                        </button>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle remove image
    $(document).on('click', '#removeImage', function() {
        $('#postFeaturedImage').val('');
        $('#imagePreview').empty();
    });

    // Save draft
    $('.btn-warning').click(function(e) {
        e.preventDefault();
        submitPost('draft');
    });

    // Publish post
    $('.btn-primary').click(function(e) {
        e.preventDefault();
        submitPost('published');
    });

    function submitPost(status) {
        if (!editorInstance) {
            toastr.error('Editor is not initialized properly.');
            return;
        }

        // Validate required fields
        if (!$('#postTitle').val().trim()) {
            toastr.error('Post title is required.');
            return;
        }

        const formData = new FormData();
        formData.append('title', $('#postTitle').val().trim());
        formData.append('content', editorInstance.getData());
        formData.append('metaTitle', $('#metaTitle').val());
        formData.append('description', $('#description').val());
        formData.append('metaDescription', $('#metaDescription').val());
        formData.append('category', $('#postCategory').val());
        formData.append('slug', $('#postPermalink').val());
        formData.append('status', status);

        const featuredImage = $('#postFeaturedImage')[0].files[0];
        if (featuredImage) {
            formData.append('featuredImage', featuredImage);
        }

        // Show loading indicator
        const submitButton = status === 'published' ? $('.btn-primary') : $('.btn-warning');
        const originalText = submitButton.html();
        submitButton.html('<i class="la la-spinner la-spin"></i> Processing...').prop('disabled', true);

        $.ajax({
            url: 'process/add-post-process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        // Redirect to all posts page
                        window.location.href = data.redirect;
                    } else {
                        // Error will be shown by footerScript.php
                        submitButton.html(originalText).prop('disabled', false);
                    }
                } catch (e) {
                    submitButton.html(originalText).prop('disabled', false);
                    $_SESSION['error'] = 'Invalid server response';
                }
            },
            error: function(xhr, status, error) {
                submitButton.html(originalText).prop('disabled', false);
                $_SESSION['error'] = 'An error occurred while saving the post: ' + error;
            }
        });
    }
});