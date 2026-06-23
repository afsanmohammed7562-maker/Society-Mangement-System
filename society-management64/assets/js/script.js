document.addEventListener('DOMContentLoaded', function () {
    console.log('Society Management System Loaded');

    // Confirm Delete
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alerts after 3 seconds
    const alerts = document.querySelectorAll('p[style*="green"], p[style*="red"]');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(el => el.style.display = 'none');
        }, 5000);
    }
});
