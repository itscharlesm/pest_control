

<?php $__env->startSection('title', 'Home - Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="<?php echo e(action('App\Http\Controllers\AdminController@home')); ?>">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <section class="content">

        <?php echo $__env->make('layouts.partials.onclick', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php
            // Define roles per tab (EASY TO UPDATE)
            $dashboardRoles = ['SUPERADMIN', 'ADMIN']; // can see dashboard
            $announcementRoles = [
                'SUPERADMIN',
                'ADMIN'
            ]; // can see announcements

            // Check access
            $canViewDashboard = false;
            foreach ($dashboardRoles as $role) {
                if (session($role) == '1') {
                    $canViewDashboard = true;
                    break;
                }
            }

            $canViewAnnouncements = false;
            foreach ($announcementRoles as $role) {
                if (session($role) == '1') {
                    $canViewAnnouncements = true;
                    break;
                }
            }

            // Determine default active tab
            if ($canViewDashboard) {
                $activeTab = 'dashboard';
            } else {
                $activeTab = 'announcements';
            }
        ?>

        <ul class="nav nav-tabs" role="tablist">

            <?php if($canViewAnnouncements): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e($activeTab == 'announcements' ? 'active' : ''); ?>" id="announcements-tab"
                        data-toggle="tab" href="#announcements-content" role="tab">
                        Announcements
                    </a>
                </li>
            <?php endif; ?>

            <?php if($canViewDashboard): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e($activeTab == 'dashboard' ? 'active' : ''); ?>" id="dashboard-tab" data-toggle="tab"
                        href="#dashboard-content" role="tab">
                        Dashboard
                    </a>
                </li>
            <?php endif; ?>

        </ul>

        <div class="tab-content">

            <!-- Announcements -->
            <?php if($canViewAnnouncements): ?>
                <div class="tab-pane fade <?php echo e($activeTab == 'announcements' ? 'show active' : ''); ?>"
                    id="announcements-content" role="tabpanel">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span class="bg-info"><i class="fa fa-bullhorn"></i> Announcements</span>
                                            <?php if(session('SUPERADMIN') == '1' || session('ADMIN') == '1'): ?>
                                                <a class="btn btn-primary float-right" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#newAnnouncementModal"><i
                                                        class="fa fa-comment"></i> Compose</a>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($announcements->count() == 0): ?>
                                            <div>
                                                <i class="fas fa-newspaper bg-blue"></i>
                                                <div class="timeline-item">
                                                    <h3 class="timeline-header">Announcement</h3>
                                                    <div class="timeline-body">
                                                        <p>No announcement yet!</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div>
                                                    <i class="fas fa-newspaper bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i>
                                                            <?php echo e(\Carbon\Carbon::parse($announcement->ann_date_created)->diffForHumans()); ?></span>
                                                        <h3 class="timeline-header"><?php echo e($announcement->ann_title); ?></h3>
                                                        <div class="timeline-body">
                                                            <?php if($announcement->ann_image != ''): ?>
                                                                <div class="thumbnail">
                                                                    <a
                                                                        href="<?php echo e(asset('images/announcements/' . $announcement->ann_image)); ?>">
                                                                        <img src="<?php echo e(asset('images/announcements/' . $announcement->ann_image)); ?>"
                                                                            alt="" style="width:100%">
                                                                    </a>
                                                                    <div class="caption">
                                                                        <p><?php echo e($announcement->ann_content); ?></p>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <p><?php echo e($announcement->ann_content); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="timeline-footer">
                                                            <?php if(session('SUPERADMIN') == '1' || session('ADMIN') == '1'): ?>
                                                                <a class="btn btn-danger btn-sm" href="javascript:void(0)"
                                                                    data-toggle="modal"
                                                                    data-target="#deleteModal-<?php echo e($announcement->ann_uuid); ?>">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>

                                                        <!-- Delete Confirmation Modal -->
                                                        <div class="modal fade"
                                                            id="deleteModal-<?php echo e($announcement->ann_uuid); ?>" tabindex="-1"
                                                            role="dialog"
                                                            aria-labelledby="deleteModalLabel-<?php echo e($announcement->ann_uuid); ?>"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel-<?php echo e($announcement->ann_uuid); ?>">
                                                                            <i class="fa fa-exclamation-triangle"></i>
                                                                            Confirm Delete
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete the announcement:
                                                                        <strong><?php echo e($announcement->ann_title); ?></strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>

                                                                        <!-- Proper form for deletion -->
                                                                        <form
                                                                            action="<?php echo e(action('App\Http\Controllers\AnnouncementController@delete', [$announcement->ann_uuid])); ?>"
                                                                            method="POST" style="display:inline;">
                                                                            <?php echo csrf_field(); ?>
                                                                            <button type="submit" class="btn btn-danger">
                                                                                <span class="fa fa-trash"></span> Yes,
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card card-info">
                                        <div class="card-header border-bottom-0 bg-navy">
                                            <h3 class="card-title" style="color:white"><i class="fas fa-history"></i> Recent
                                                Users</h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                                <?php $__currentLoopData = $logins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $login): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="item">
                                                        <div class="product-img">
                                                            <img class="img-size-50 img-circle"
                                                                src="<?php echo e(asset(getAvatar($login->usr_id))); ?>"
                                                                alt="user image">
                                                        </div>
                                                        <div class="product-info">
                                                            <a href="#" class="product-title">
                                                                <?php echo e($login->usr_last_name); ?>, <?php echo e($login->usr_first_name); ?>

                                                                <span class="badge badge-info float-right"></span>
                                                            </a>
                                                            <span class="product-description">
                                                                <?php echo e(\Carbon\Carbon::parse($login->log_date_max)->diffForHumans()); ?>

                                                            </span>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            <?php endif; ?>

            
            <?php if($canViewDashboard): ?>
                <div class="tab-pane fade <?php echo e($activeTab == 'dashboard' ? 'show active' : ''); ?>" id="dashboard-content"
                    role="tabpanel">
                    <?php echo $__env->make('home.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- New Announcement Modal -->
    <div class="modal fade" id="newAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Annoucement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(action('App\Http\Controllers\AnnouncementController@save')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="ann_title">Title <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="ann_title" name="ann_title"
                                placeholder="Title" required />
                        </div>
                        <div class="form-group">
                            <label for="ann_content">Message Content <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="ann_content" name="ann_content" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <label for="ann_image">Image</label>
                                <input type="file" class="custom-file-input" id="customFile" name="ann_image"
                                    accept=".jpeg, .jpg, .png" />
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            <small id="fileHelp" class="form-text text-muted">Please upload a valid image file in jpg or
                                png
                                format. Size of image should not be more than 3MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.themes.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pest_control\resources\views/home/main.blade.php ENDPATH**/ ?>