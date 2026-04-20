<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentStep = 1;
        var totalSteps = 4;

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(function(stepElement) {
                stepElement.style.display = 'none';
            });
            document.getElementById('step-' + step).style.display = 'block';
            document.getElementById('prev-step').style.display = step === 1 ? 'none' : 'inline';
            document.getElementById('next-step').style.display = step === totalSteps ? 'none' : 'inline';
            document.getElementById('submit-form').style.display = step === totalSteps ? 'inline' : 'none';
        }

        document.getElementById('next-step').addEventListener('click', function() {
            currentStep = Math.min(currentStep + 1, totalSteps);
            showStep(currentStep);
        });

        document.getElementById('prev-step').addEventListener('click', function() {
            currentStep = Math.max(currentStep - 1, 1);
            showStep(currentStep);
        });

        showStep(currentStep);
    });
</script>