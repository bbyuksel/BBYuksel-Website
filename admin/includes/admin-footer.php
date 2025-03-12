    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="text-center text-muted">
                &copy; <?php echo date('Y'); ?> BBYuksel Admin Panel. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Enable popovers
        $('[data-toggle="popover"]').popover();
        
        // Auto-hide alerts after 5 seconds
        $('.alert').delay(5000).fadeOut(500);
        
        // Confirm delete actions
        $('form[data-confirm]').submit(function(e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
            }
        });
    });
    </script>
</body>
</html> 