$(document).ready(function() {
    // Toastr options
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // Function to handle AJAX submission
    function submitForm(formId, action, successCallback) {
        var formData = {};
        if (action === 'add') {
            formData = {
                action: action,
                categoryName: $('#categoryName').val()
            };
        } else if (action === 'update') {
            formData = {
                action: action,
                categoryId: $('#editCategoryId').val(),
                categoryName: $('#editCategoryName').val()
            };
        }

        $.ajax({
            url: 'process/category-process.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                window.location.reload(); // Reload the page to show toaster message
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('An error occurred. Please try again.');
            }
        });
    }

    // Add Category
    $('#saveCategoryBtn').click(function() {
        submitForm('addCategoryForm', 'add');
    });

    // Edit Category
    $('.edit-category').click(function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
        $('#editCategoryModal').modal('show');
    });

    // Update Category
    $('#updateCategoryBtn').click(function() {
        submitForm('editCategoryForm', 'update');
    });

    // Handle Enter key press in Add Category Modal
    $('#addCategoryForm').keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            submitForm('addCategoryForm', 'add');
        }
    });

    // Handle Enter key press in Edit Category Modal
    $('#editCategoryForm').keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            submitForm('editCategoryForm', 'update');
        }
    });

    // Delete Category
    $('.delete-category').click(function() {
        var id = $(this).data('id');
        
        if(confirm('Are you sure you want to delete this category?')) {
            var form = $('<form method="post">')
                .append('<input type="hidden" name="action" value="delete">')
                .append('<input type="hidden" name="categoryId" value="' + id + '">');
            $('body').append(form);
            form.submit();
        }
    });

    // Clear modal forms when closed
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});