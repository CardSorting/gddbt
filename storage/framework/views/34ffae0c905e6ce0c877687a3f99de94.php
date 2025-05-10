<?php $__env->startSection('title', 'Module Details - DBTPath'); ?>
<?php $__env->startSection('meta_description', 'Learn and practice DBT skills through interactive lessons and exercises'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('modules.index')); ?>">Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e(ucfirst(str_replace('-', ' ', $module))); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Module Header -->
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="mb-3"><?php echo e($moduleData->name); ?> Skills</h1>
            <p class="lead text-muted mb-4"><?php echo e($moduleData->description); ?></p>
            <div class="mb-4">
                <?php if($progress['status'] == 'completed'): ?>
                    <span class="badge bg-success px-3 py-2 me-2">Completed</span>
                    <span class="text-muted">Completed on <?php echo e($progress['last_activity_at'] ? $progress['last_activity_at']->format('F j, Y') : 'Unknown date'); ?></span>
                <?php elseif($progress['status'] == 'in_progress'): ?>
                    <span class="badge bg-warning px-3 py-2 me-2">In Progress</span>
                    <span class="text-muted"><?php echo e($progress['completion_percentage']); ?>% complete</span>
                <?php else: ?>
                    <span class="badge bg-secondary px-3 py-2 me-2">Not Started</span>
                    <span class="text-muted">Begin your learning journey</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Module Progress</h5>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar 
                            <?php if($progress['status'] == 'completed'): ?> bg-success 
                            <?php elseif($progress['status'] == 'in_progress'): ?> bg-warning 
                            <?php else: ?> bg-secondary <?php endif; ?>" 
                            role="progressbar" 
                            style="width: <?php echo e($progress['completion_percentage']); ?>%;" 
                            aria-valuenow="<?php echo e($progress['completion_percentage']); ?>" 
                            aria-valuemin="0" 
                            aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><?php echo e(count($progress['completed_lessons'])); ?>/<?php echo e(count($lessons)); ?> lessons completed</span>
                        <span><?php echo e($progress['completion_percentage']); ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lessons Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">Lessons</h2>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <?php if($progress['status'] == 'not_started' && $progress['completion_percentage'] == 0): ?>
                <!-- Module not started -->
                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle me-2"></i>
                    Start this module to begin your learning journey.
                </div>
            <?php endif; ?>
            
            <?php
                // Group lessons by skill
                $lessonsBySkill = [];
                foreach($lessons as $lesson) {
                    $skillName = $lesson['skill_name'] ?? 'General';
                    if (!isset($lessonsBySkill[$skillName])) {
                        $lessonsBySkill[$skillName] = [];
                    }
                    $lessonsBySkill[$skillName][] = $lesson;
                }
            ?>
            
            <div class="accordion" id="skillsAccordion">
                <?php $__currentLoopData = $lessonsBySkill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skillName => $skillLessons): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="heading-<?php echo e(\Illuminate\Support\Str::slug($skillName)); ?>">
                            <button class="accordion-button <?php echo e($loop->first ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse-<?php echo e(\Illuminate\Support\Str::slug($skillName)); ?>" 
                                aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>" 
                                aria-controls="collapse-<?php echo e(\Illuminate\Support\Str::slug($skillName)); ?>">
                                <strong><?php echo e($skillName); ?> Skill</strong>
                                <span class="badge bg-primary ms-2"><?php echo e(count($skillLessons)); ?> lesson(s)</span>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo e(\Illuminate\Support\Str::slug($skillName)); ?>" class="accordion-collapse collapse <?php echo e($loop->first ? 'show' : ''); ?>" 
                            aria-labelledby="heading-<?php echo e(\Illuminate\Support\Str::slug($skillName)); ?>" 
                            data-bs-parent="#skillsAccordion">
                            <div class="accordion-body p-0">
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $skillLessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isCompleted = $lesson['is_completed'];
                                            $isDefault = isset($lesson['is_default']) && $lesson['is_default'];
                                            $lessonNumber = array_search($lesson, $lessons) + 1;
                                            
                                            // Only first lesson is active for new users, or specifically marked active ones
                                            $isActive = ($activeLesson !== null && $lessons[$activeLesson]['id'] == $lesson['id']);
                                            
                                            // First lesson in first skill is always available
                                            if ($loop->parent->first && $loop->first) {
                                                $isLocked = false;
                                            } else {
                                                $isLocked = !$isCompleted && !$isActive;
                                            }
                                        ?>
                                        
                                        <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center
                                            <?php echo e($isActive ? 'active' : ''); ?> 
                                            <?php echo e($isLocked ? 'disabled' : ''); ?>">
                                            <div>
                                                <h5 class="mb-1">Lesson <?php echo e($lessonNumber); ?>: <?php echo e($lesson['title']); ?></h5>
                                                <p class="mb-1 <?php echo e(!$isActive ? 'text-muted' : ''); ?>"><?php echo e($lesson['description']); ?></p>
                                                
                                                <?php if($isCompleted): ?>
                                                    <span class="badge bg-success">Completed</span>
                                                <?php elseif($isActive): ?>
                                                    <span class="badge bg-primary">In Progress</span>
                                                <?php elseif($isLocked): ?>
                                                    <span class="badge bg-secondary">Locked</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Available</span>
                                                <?php endif; ?>
                                                
                                                <?php if($isDefault): ?>
                                                    <span class="badge bg-info ms-1">Default Content</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if($isCompleted): ?>
                                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                            <?php elseif($isActive): ?>
                                                <i class="bi bi-lightning-fill text-warning fs-4"></i>
                                            <?php elseif($isLocked): ?>
                                                <i class="bi bi-lock-fill text-secondary fs-4"></i>
                                            <?php else: ?>
                                                <i class="bi bi-play-circle text-primary fs-4"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
                
                <?php if(count($lessons) == 0): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No lessons available for this module yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Module Resources -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Resources</h2>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-file-earmark-text text-primary fs-3 mb-3"></i>
                    <h5 class="card-title">Worksheets</h5>
                    <p class="card-text text-muted">Downloadable worksheets to practice skills from this module.</p>
                    <a href="#" class="btn btn-outline-primary">Download PDF</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-journal-text text-primary fs-3 mb-3"></i>
                    <h5 class="card-title">Practice Journal</h5>
                    <p class="card-text text-muted">Templates for journaling your skill practice and progress.</p>
                    <a href="#" class="btn btn-outline-primary">View Templates</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <i class="bi bi-play-circle text-primary fs-3 mb-3"></i>
                    <h5 class="card-title">Video Guides</h5>
                    <p class="card-text text-muted">Instructional videos demonstrating key skills from this module.</p>
                    <a href="#" class="btn btn-outline-primary">Watch Videos</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/bozoegg/Desktop/gddbt/resources/views/modules/show.blade.php ENDPATH**/ ?>