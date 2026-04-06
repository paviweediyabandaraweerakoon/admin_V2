document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer actions JavaScript loaded');

    // Use event delegation on the document for better performance
    document.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.customer-edit-btn');
        const deleteBtn = e.target.closest('.customer-delete-btn');

        if (editBtn) {
            console.log('Edit button clicked', editBtn.dataset);
            e.preventDefault();
            handleEditCustomer(editBtn);
        }

        if (deleteBtn) {
            console.log('Delete button clicked', deleteBtn.dataset);
            e.preventDefault();
            handleDeleteCustomer(deleteBtn);
        }
    });
});

/**
 * Handle Edit Customer Action
 * Extracts customer data from button attributes and populates edit modal
 */
function handleEditCustomer(button) {
    const customerId = button.dataset.id;
    const customerName = button.dataset.name;
    const customerPhone = button.dataset.phone;
    const customerCountry = button.dataset.country;
    const customerStatus = button.dataset.status;

    // Populate the edit form
    const editForm = document.getElementById('customerEditForm');
    if (editForm) {
        // Set form action
        editForm.action = `/customers/${customerId}`;

        // Populate form fields
        const idField = editForm.querySelector('input[name="id"]');
        const nameField = editForm.querySelector('input[name="company_name"]');
        const phoneField = editForm.querySelector('input[name="phone"]');
        const countryField = editForm.querySelector('input[name="country"]');
        const statusField = editForm.querySelector('select[name="status"]');

        if (idField) idField.value = customerId;
        if (nameField) nameField.value = customerName || '';
        if (phoneField) phoneField.value = customerPhone || '';
        if (countryField) countryField.value = customerCountry || '';
        if (statusField) statusField.value = customerStatus;

        // Show the edit modal
        $('#editModal').modal('show');
    } else {
        console.error('Edit form not found');
    }
}

/**
 * Handle Delete Customer Action
 * Sends AJAX DELETE request with CSRF token
 */
function handleDeleteCustomer(button) {
    const customerId = button.dataset.id;
    const customerName = button.dataset.name;
    const deleteUrl = `/customers/${customerId}`;

    // Confirm before deletion
    if (!confirm(`Are you sure you want to delete "${customerName}"?`)) {
        return;
    }

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        console.error('CSRF token not found in meta tag');
        alert('Security error: CSRF token missing');
        return;
    }

    // Show loading state
    button.disabled = true;
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    // Send DELETE request via AJAX
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        
        // Success - reload table
        alert(data.message || 'Customer deleted successfully');

        // Reload the DataTable
        if ($('#dataTable').length && $('#dataTable').DataTable) {
            $('#dataTable').DataTable().ajax.reload();
        } else {
            location.reload(); // Fallback to full page reload
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Error deleting customer. Please try again.');

        // Restore button state
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}