<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session('errorMessage')): ?>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 10000
            });
            Toast.fire({
                icon: 'error',
                title: '<?php echo e(session('errorMessage')); ?>'
            });
        });
    </script>
<?php endif; ?>

<?php if(session('successMessage')): ?>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000
            });
            Toast.fire({
                icon: 'success',
                title: '<?php echo e(session('successMessage')); ?>'
            });
        });
    </script>
<?php endif; ?>

<?php if(session('infoMessage')): ?>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'info',
                title: '<?php echo e(session('infoMessage')); ?>'
            });
        });
    </script>
<?php endif; ?>

<?php if(session('warningMessage')): ?>
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 10000
            });
            Toast.fire({
                icon: 'warning',
                title: '<?php echo e(session('warningMessage')); ?>'
            });
        });
    </script>
<?php endif; ?><?php /**PATH C:\laragon\www\pest_control\resources\views/layouts/partials/alerts.blade.php ENDPATH**/ ?>