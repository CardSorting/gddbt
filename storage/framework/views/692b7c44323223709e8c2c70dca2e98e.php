<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white"><?php echo e(__('Dashboard')); ?></div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <h4><?php echo e(__('Welcome to your DBT Learning Journey!')); ?></h4>
                    <p><?php echo e(__('You are logged in and ready to continue learning DBT skills.')); ?></p>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <div class="text-center">
                            <h5><?php echo e(__('Level')); ?></h5>
                            <div class="display-4"><?php echo e(Auth::user()->level); ?></div>
                        </div>
                        <div class="text-center">
                            <h5><?php echo e(__('XP Points')); ?></h5>
                            <div class="display-4"><?php echo e(Auth::user()->xp_points ?? 0); ?></div>
                        </div>
                        <div class="text-center">
                            <h5><?php echo e(__('Streak')); ?></h5>
                            <div class="display-4"><?php echo e(Auth::user()->streak ? Auth::user()->streak->current_streak : 0); ?></div>
                            <small>days</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white"><?php echo e(__('Continue Learning')); ?></div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?php echo e(route('modules.index')); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <?php echo e(__('Browse DBT Modules')); ?>

                            <span class="badge bg-primary rounded-pill">4</span>
                        </a>
                        <a href="<?php echo e(route('daily-goals.create')); ?>" class="list-group-item list-group-item-action">
                            <?php echo e(__('Set Today\'s Goal')); ?>

                        </a>
                        <a href="<?php echo e(route('daily-goals.index')); ?>" class="list-group-item list-group-item-action">
                            <?php echo e(__('View Your Progress')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bozoegg/Desktop/gddbt/resources/views/dashboard.blade.php ENDPATH**/ ?>