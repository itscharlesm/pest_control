<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('button[type="submit"]:not(.no-auto-submit')")
        .forEach(function (btn) {

            btn.addEventListener('click', function (e) {
                if (btn.disabled) return;

                const form = btn.closest('form');

                // Let browser validation run first
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                btn.disabled = true;

                const icon = btn.querySelector('span.fa');
                if (icon) {
                    icon.classList.add('fa-spin');
                }

                // IMPORTANT: keep validation
                form.requestSubmit();
            });
        });
});
</script>

{{-- <button type="submit" data-no-disable> --}}