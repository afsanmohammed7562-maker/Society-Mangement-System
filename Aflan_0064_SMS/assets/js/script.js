$(document).ready(function() {
    // Initialize DataTable if exists
    if ($('#membersTable').length) {
        $('#membersTable').DataTable({
            language: {
                search: "Search members:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ members",
                infoEmpty: "Showing 0 to 0 of 0 members",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            pageLength: 10,
            order: [[0, 'desc']]
        });
    }

    // Initialize users table
    if ($('#usersTable').length) {
        $('#usersTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }

    // Initialize accounts table
    if ($('#accountsTable').length) {
        $('#accountsTable').DataTable({
            pageLength: 10,
            order: [[1, 'asc']],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var intVal = function(i) { return typeof i === 'string' ? i.replace(/[LKR,\s]/g, '') * 1 : typeof i === 'number' ? i : 0; };
                var total = api.column(2).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                $(api.column(2).footer()).html('LKR ' + total.toFixed(2));
            }
        });
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Preview uploaded image
    $('#profilePhoto').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Edit balance modal
    $('#editBalanceModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var memberId = button.data('member-id');
        var memberName = button.data('member-name');
        var balance = button.data('balance');
        var modal = $(this);
        modal.find('#editMemberId').val(memberId);
        modal.find('#editMemberName').text(memberName);
        modal.find('#editBalance').val(balance);
    });

    // Sidebar toggle for mobile
    $('.navbar-toggler').on('click', function() {
        $('#sidebarMenu').toggleClass('show');
    });

    // Close sidebar on link click in mobile
    $('.sidebar .nav-link').on('click', function() {
        if ($(window).width() < 992) {
            $('#sidebarMenu').removeClass('show');
        }
    });

    // Confirm delete
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this member? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});
