

<?php $__env->startSection('content'); ?>
    
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(action('App\Http\Controllers\AdminController@home')); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item">Profiling</li>
                        <li class="breadcrumb-item active">Users</li>
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
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(session('SUPERADMIN') == '1' || session('ADMIN') == '1'): ?>
                                <a class="btn btn-danger btn-md mb-3" href="<?php echo e(url('profiling/users/deleted')); ?>">
                                    <span class="fa fa-archive"></span> Deleted Users
                                </a>
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#addUserModal">
                                    <span class="fa fa-plus"></span> Add User
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Table Column -->
                        <div class="col-lg-9 col-md-7">
                            <form method="GET" action="<?php echo e(url('profiling/users/active')); ?>" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search users..." value="<?php echo e(request('search')); ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fa fa-search"></span> Search
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <table id="profilingTable" class="table table-hover table-bordered table-sm responsive">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; text-align: center">Name</th>
                                        <th style="vertical-align: middle; text-align: center" width="130px">Role(s)</th>
                                        <th style="vertical-align: middle; text-align: center" width="110px">Action</th>
                                        <?php if(session('rol_admin') == '1' || session('rol_manager') == '1'): ?>
                                            <th style="vertical-align: middle; text-align: center" width="70px">Active
                                            </th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="vertical-align: middle; text-align: left">
                                                <?php echo e($user->usr_last_name); ?>, <?php echo e($user->usr_first_name); ?>

                                                <?php echo e($user->usr_middle_name); ?>

                                                <br />
                                                <small><?php echo e($user->usr_email); ?></small>
                                                <br />
                                                <em><small>Last login: <?php echo e(getLastLogin($user->usr_id)); ?></small></em>
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                <?php if(!empty($user->roles)): ?>
                                                    <?php $__currentLoopData = explode(', ', $user->roles); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge bg-success"><?php echo e($role); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">No Role Assigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                <a class="btn btn-warning btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#updateRoleModal-<?php echo e($user->usr_id); ?>">
                                                    <span class="fa fa-edit"></span>
                                                </a>
                                                <a class="btn btn-info btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#resetModal-<?php echo e($user->usr_id); ?>">
                                                    <span class="fa fa-key"></span>
                                                </a>
                                                <a class="btn btn-danger btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#deleteModal-<?php echo e($user->usr_id); ?>">
                                                    <span class="fa fa-trash"></span>
                                                </a>
                                            </td>

                                            
                                            <div class="modal fade" id="updateRoleModal-<?php echo e($user->usr_id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="<?php echo e(url('user/update/role', $user->usr_id)); ?>"
                                                        method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title text-black" id="exampleModalLabel">
                                                                    Update Role
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="rol_id">Name: <span
                                                                            style="color:black;"><?php echo e($user->usr_first_name); ?>

                                                                            <?php echo e($user->usr_last_name); ?></span></label>

                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="rol_id">Roles: <span
                                                                            style="color:red;">*</span></label>
                                                                    <select class="select2" multiple="multiple"
                                                                        data-placeholder="Select Role(s)"
                                                                        style="width:100%;" name="roles[]">
                                                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($role->rol_id); ?>"
                                                                                <?php if(isset($user->roles) && in_array($role->rol_name, explode(', ', $user->roles))): ?> selected <?php endif; ?>>
                                                                                <?php echo e($role->rol_name); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-warning">
                                                                    <span class="fa fa-save"></span> Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            
                                            <div class="modal fade" id="resetModal-<?php echo e($user->usr_id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="<?php echo e(action('App\Http\Controllers\ProfilingController@users_reset_password', [$user->usr_id])); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-header bg-info text-white">
                                                                <h5 class="modal-title text-black" id="exampleModalLabel">
                                                                    Please Confirm
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to RESET this user's password to
                                                                    <strong>123456</strong>?
                                                                </p>
                                                                <small><?php echo e($user->usr_first_name); ?>

                                                                    <?php echo e($user->usr_last_name); ?><br><?php echo e($user->usr_email); ?></small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-info">
                                                                    <span class="fa fa-refresh"></span> Confirm Reset
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="modal fade" id="deleteModal-<?php echo e($user->usr_id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="<?php echo e(action('App\Http\Controllers\ProfilingController@users_delete', [$user->usr_id])); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                    Please Confirm
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Are you sure you want to DELETE user
                                                                        <?php echo e($user->usr_first_name); ?>

                                                                        <?php echo e($user->usr_last_name); ?>

                                                                        ?</strong>
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <span class="fa fa-trash"></span> Confirm Delete
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Role Info Column -->
                        <div class="col-lg-3 col-md-5">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong><i class="fa fa-info-circle"></i> Role Information</strong>
                                </div>
                                <div class="card-body" style="overflow-y: auto;">
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-3">
                                            <h6 class="text-dark mb-1">
                                                <i class="fa fa-user-tag"></i> <?php echo e($role->rol_name); ?>

                                            </h6>
                                            <p class="text-muted small mb-0">
                                                <?php echo e($role->rol_description ?? 'No description available'); ?>

                                            </p>
                                            <hr>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(url('profiling/users/add')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="usr_password" value="<?php echo e(md5('123456')); ?>">
                <input type="hidden" name="usr_code" value="123456">
                <input type="hidden" name="region" id="add_hidden_region" value="">
                <input type="hidden" name="province" id="add_hidden_province" value="">
                <input type="hidden" name="municipality" id="add_hidden_municipality" value="">
                <input type="hidden" name="barangay" id="add_hidden_barangay" value="">

                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="addUserModalLabel">
                            <span class="fa fa-plus text-white"></span> Add User
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            
                            <div class="col-md-4 mb-3">
                                <label for="add_first_name">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_first_name" name="usr_first_name"
                                    placeholder="First Name" required>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label for="add_middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="add_middle_name" name="usr_middle_name"
                                    placeholder="Middle Name">
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label for="add_last_name">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_last_name" name="usr_last_name"
                                    placeholder="Last Name" required>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label for="add_email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="add_email" name="usr_email"
                                    placeholder="Email" required>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <label for="add_mobile">
                                    Mobile Number
                                    <small class="text-muted">(9123456789)</small>
                                </label>
                                <input type="number" class="form-control" id="add_mobile" name="usr_mobile"
                                    placeholder="Mobile Number" oninput="this.value=this.value.slice(0,10)">
                            </div>

                            
                            <div class="col-md-12 mb-3">
                                <label for="add_street">Street</label>
                                <input type="text" class="form-control" id="add_street" name="street"
                                    placeholder="Street / House No. / Bldg.">
                            </div>

                            
                            <div class="col-md-3 mb-3">
                                <label for="add_region">Region</label>
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
                                <label for="add_province">Province</label>
                                <select id="add_province" class="form-control" disabled>
                                    <option value="">-- SELECT PROVINCE --</option>
                                </select>
                            </div>

                            
                            <div class="col-md-3 mb-3">
                                <label for="add_municipality">City/Municipality</label>
                                <select id="add_municipality" class="form-control" disabled>
                                    <option value="">-- SELECT MUNICIPALITY --</option>
                                </select>
                            </div>

                            
                            <div class="col-md-3 mb-3">
                                <label for="add_barangay">Barangay</label>
                                <select id="add_barangay" class="form-control" disabled>
                                    <option value="">-- SELECT BARANGAY --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="fa fa-close"></span> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="fa fa-save"></span> Save User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function() {
            // Initialize Select2 Elements with classic theme
            $('.select2').select2({
                theme: "classic"
            });
        });
    </script>

    
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let value = this.value.toLowerCase();

            // Select all tables with ID containing "Table"
            let tables = document.querySelectorAll('table[id*="Table"]');

            tables.forEach(function(table) {
                let rows = table.querySelectorAll("tbody tr");

                rows.forEach(function(row) {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? "" : "none";
                });
            });
        });
    </script>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.themes.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pest_control\resources\views/profiling/users/active.blade.php ENDPATH**/ ?>