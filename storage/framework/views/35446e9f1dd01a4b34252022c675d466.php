

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
                        <li class="breadcrumb-item">Users</li>
                        <li class="breadcrumb-item active">Deleted</li>
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
                                <a class="btn btn-success btn-md mb-3" href="<?php echo e(url('profiling/users/active')); ?>">
                                    <span class="fa fa-users"></span> Users
                                </a>
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
                                                <a class="btn btn-success btn-sm" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#restoreModal-<?php echo e($user->usr_id); ?>">
                                                    <span class="fa fa-refresh"></span>
                                                </a>
                                            </td>

                                            
                                            <div class="modal fade" id="restoreModal-<?php echo e($user->usr_id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="<?php echo e(action('App\Http\Controllers\ProfilingController@users_restore', [$user->usr_id])); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                    Please Confirm
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to <strong>RESTORE</strong> user
                                                                    <strong><?php echo e($user->usr_first_name); ?>

                                                                        <?php echo e($user->usr_last_name); ?></strong>
                                                                    ?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <span class="fa fa-refresh"></span> Confirm Restore
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.themes.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pest_control\resources\views/profiling/users/deleted.blade.php ENDPATH**/ ?>