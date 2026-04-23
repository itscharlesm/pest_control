

<?php $__env->startSection('content'); ?>
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Account Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(action('App\Http\Controllers\AdminController@home')); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <section class="content">
        <?php echo $__env->make('layouts.partials.onclick', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.partials.modal_style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="<?php echo e(url('account/update')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        
                        <div class="text-center mb-4">
                            <a href="<?php echo e(asset(getAvatar(session('usr_id')))); ?>" id="avatarLink" download>
                                <img src="<?php echo e(asset(getAvatar(session('usr_id')))); ?>" id="avatarPreview"
                                    class="img-circle elevation-2"
                                    style="width:120px;height:120px;object-fit:cover;cursor:pointer;">
                            </a>

                            <input type="file" id="avatarInput" name="avatar" hidden accept=".jpg,.jpeg,.png,.webp">
                        </div>

                        <div class="row">

                            
                            <div class="col-md-4 mb-3">
                                <label>First Name</label>
                                <input type="text" name="usr_first_name" class="form-control editable"
                                    value="<?php echo e($user->usr_first_name); ?>" readonly>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label>Middle Name</label>
                                <input type="text" name="usr_middle_name" class="form-control editable"
                                    value="<?php echo e($user->usr_middle_name); ?>" readonly>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label>Last Name</label>
                                <input type="text" name="usr_last_name" class="form-control editable"
                                    value="<?php echo e($user->usr_last_name); ?>" readonly>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label>Birthdate</label>
                                <input type="date" name="usr_birth_date" class="form-control editable"
                                    value="<?php echo e($user->usr_birth_date); ?>" readonly>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label>Age</label>
                                <input type="text" id="ageField" class="form-control"
                                    value="<?php echo e(\Carbon\Carbon::parse($user->usr_birth_date)->age); ?> years old" readonly>
                            </div>

                            
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <input type="text" class="form-control" value="<?php echo e($user->usr_address); ?>" readonly>
                            </div>

                        </div>

                        
                        <div id="editAddressSection" style="display:none;">
                            <div class="row">

                                <div class="col-md-12 mb-3">
                                    <label>Street</label>
                                    <input type="text" name="street" class="form-control">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Region</label>
                                    <select id="add_region" class="form-control">
                                        <option value="">-- SELECT REGION --</option>
                                        <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($r->reg_id); ?>" data-name="<?php echo e($r->reg_name); ?>">
                                                <?php echo e($r->reg_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Province</label>
                                    <select id="add_province" class="form-control" disabled></select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Municipality</label>
                                    <select id="add_municipality" class="form-control" disabled></select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label>Barangay</label>
                                    <select id="add_barangay" class="form-control" disabled></select>
                                </div>

                                
                                <input type="hidden" name="region" id="add_hidden_region">
                                <input type="hidden" name="province" id="add_hidden_province">
                                <input type="hidden" name="municipality" id="add_hidden_municipality">
                                <input type="hidden" name="barangay" id="add_hidden_barangay">

                            </div>
                        </div>

                        
                        <div class="text-right">
                            <button type="button" id="editBtn" class="btn btn-warning">
                                <span class="fa fa-edit"></span> Edit
                            </button>

                            <button type="button" id="cancelBtn" class="btn btn-secondary" style="display:none;">
                                <span class="fa fa-close"></span> Cancel
                            </button>

                            <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">
                                <span class="fa fa-save"></span> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addRegionSel = document.getElementById('add_region');
            const addProvSel = document.getElementById('add_province');
            const addMuniSel = document.getElementById('add_municipality');
            const addBrgySel = document.getElementById('add_barangay');

            const addHiddenRegion = document.getElementById('add_hidden_region');
            const addHiddenProv = document.getElementById('add_hidden_province');
            const addHiddenMuni = document.getElementById('add_hidden_municipality');
            const addHiddenBrgy = document.getElementById('add_hidden_barangay');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const baseUrl = window.location.origin;

            function addResetSelect(el, placeholder) {
                el.innerHTML = `<option value="">${placeholder}</option>`;
                el.disabled = true;
            }

            async function addLoadOptions(el, endpoint, textKey, valueKey, placeholder) {
                el.innerHTML = '<option value="">Loading...</option>';
                try {
                    const response = await fetch(`${baseUrl}${endpoint}`, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    el.innerHTML = `<option value="">${placeholder}</option>`;
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            el.insertAdjacentHTML('beforeend',
                                `<option value="${item[valueKey]}" data-name="${item[textKey]}">${item[textKey]}</option>`
                            );
                        });
                        el.disabled = false;
                    } else {
                        el.innerHTML = `<option value="">No options available</option>`;
                    }
                } catch (err) {
                    console.error(`Error loading ${el.id}:`, err);
                    el.innerHTML = `<option value="">Error loading data</option>`;
                }
            }

            addRegionSel.addEventListener('change', function() {
                const regionId = this.value;
                const regionName = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
                addHiddenRegion.value = regionName;

                addResetSelect(addProvSel, '-- SELECT PROVINCE --');
                addResetSelect(addMuniSel, '-- SELECT MUNICIPALITY --');
                addResetSelect(addBrgySel, '-- SELECT BARANGAY --');
                addHiddenProv.value = '';
                addHiddenMuni.value = '';
                addHiddenBrgy.value = '';

                if (regionId) {
                    addLoadOptions(addProvSel, `/locations/${regionId}/provinces`, 'prov_name', 'prov_id',
                        '-- SELECT PROVINCE --');
                }
            });

            addProvSel.addEventListener('change', function() {
                const provId = this.value;
                const provName = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
                addHiddenProv.value = provName;

                addResetSelect(addMuniSel, '-- SELECT MUNICIPALITY --');
                addResetSelect(addBrgySel, '-- SELECT BARANGAY --');
                addHiddenMuni.value = '';
                addHiddenBrgy.value = '';

                if (provId) {
                    addLoadOptions(addMuniSel, `/locations/${provId}/municipalities`, 'mun_name', 'mun_id',
                        '-- SELECT MUNICIPALITY --');
                }
            });

            addMuniSel.addEventListener('change', function() {
                const muniId = this.value;
                const muniName = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
                addHiddenMuni.value = muniName;

                addResetSelect(addBrgySel, '-- SELECT BARANGAY --');
                addHiddenBrgy.value = '';

                if (muniId) {
                    addLoadOptions(addBrgySel, `/locations/${muniId}/barangays`, 'brg_name', 'brg_id',
                        '-- SELECT BARANGAY --');
                }
            });

            addBrgySel.addEventListener('change', function() {
                addHiddenBrgy.value = this.options[this.selectedIndex]?.getAttribute('data-name') || '';
            });

            // Reset modal fields on close
            document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function() {
                this.querySelector('form').reset();
                addResetSelect(addProvSel, '-- SELECT PROVINCE --');
                addResetSelect(addMuniSel, '-- SELECT MUNICIPALITY --');
                addResetSelect(addBrgySel, '-- SELECT BARANGAY --');
                addHiddenRegion.value = '';
                addHiddenProv.value = '';
                addHiddenMuni.value = '';
                addHiddenBrgy.value = '';
            });
        });
    </script>

    <script>
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const editableFields = document.querySelectorAll('.editable');
        const addressSection = document.getElementById('editAddressSection');

        editBtn.addEventListener('click', () => {
            editableFields.forEach(el => el.removeAttribute('readonly'));

            addressSection.style.display = 'block';

            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
            cancelBtn.style.display = 'inline-block';
        });

        cancelBtn.addEventListener('click', () => {
            location.reload(); // simplest way to reset everything
        });
    </script>

    <script>
        const birthdateInput = document.querySelector('input[name="usr_birth_date"]');
        const ageField = document.getElementById('ageField');

        birthdateInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageField.value = age + " years old";
        });
    </script>

    <script>
        const avatarLink = document.getElementById('avatarLink');
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');

        let editMode = false;

        editBtn.addEventListener('click', () => {
            editMode = true;

            // remove download behavior
            avatarLink.removeAttribute('href');
            avatarLink.removeAttribute('download');
        });

        avatarPreview.addEventListener('click', function(e) {
            if (editMode) {
                e.preventDefault();
                avatarInput.click();
            }
        });

        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                avatarPreview.src = URL.createObjectURL(file);
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.themes.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pest_control\resources\views/account.blade.php ENDPATH**/ ?>