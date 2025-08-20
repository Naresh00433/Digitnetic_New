$(document).ready(function() {
    // Generate slug from title if slug is empty
    $('#postTitle').on('keyup', function() {
        if ($('#postPermalink').val().trim() === '') {
            let slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            $('#postPermalink').val(slug);
        }
    });

    // Handle form submission
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
        formData.append('postId', $('#postId').val());
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

        // Show loading state
        const submitButton = status === 'published' ? $('.btn-primary') : $('.btn-warning');
        const originalText = submitButton.html();
        submitButton.html('<i class="la la-spinner la-spin"></i> Processing...').prop('disabled', true);

        $.ajax({
            url: 'process/edit-post-process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.status === 'success') {
                        toastr.success('Post updated successfully!');
                        window.location.href = data.redirect;
                    } else {
                        toastr.error(data.message || 'An error occurred');
                        submitButton.html(originalText).prop('disabled', false);
                    }
                } catch (e) {
                    toastr.error('Invalid server response');
                    submitButton.html(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while saving the post: ' + error);
                submitButton.html(originalText).prop('disabled', false);
            }
        });
    }

    // Bind submit buttons
    $('.btn-warning').click(function(e) {
        e.preventDefault();
        submitPost('draft');
    });

    $('.btn-primary').click(function(e) {
        e.preventDefault();
        submitPost('published');
    });
});