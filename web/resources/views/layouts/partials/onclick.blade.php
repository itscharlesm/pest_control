<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('button[type="submit"]:not(.no-auto-submit)')
            .forEach(function (btn) {

                btn.addEventListener('click', function (e) {
                    if (btn.disabled) return;

                    btn.disabled = true;

                    const icon = btn.querySelector('span.fa');
                    if (icon) {
                        icon.classList.add('fa-spin');
                    }

                    btn.form.submit();
                });
            });
    });
</script>

{{-- <button type="submit" data-no-disable> --}}