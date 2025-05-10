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

    <!-- Current Lesson Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Current Lesson</h2>
                <div>
                    <span class="badge bg-primary px-3 py-2"><?php echo e(count($lessons)); ?> total lessons</span>
                </div>
            </div>
            <p class="text-muted mt-2">Complete this lesson to unlock the next one</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <?php
                // Determine current lesson (first incomplete or active lesson)
                $currentLesson = null;
                $currentLessonIndex = 0;
                $currentSkill = 'General';
                $progress = 0;
                
                if ($activeLesson !== null) {
                    $currentLesson = $lessons[$activeLesson];
                    $currentLessonIndex = $activeLesson;
                    $currentSkill = $currentLesson['skill_name'] ?? 'General';
                    $progress = ($currentLessonIndex / count($lessons)) * 100;
                } else {
                    // If no active lesson defined, use first lesson
                    $currentLesson = $lessons[0] ?? null;
                    $currentSkill = $currentLesson['skill_name'] ?? 'General';
                }
                
                // Determine if there's a next lesson
                $hasNextLesson = $currentLessonIndex < count($lessons) - 1;
                $nextLessonIndex = $currentLessonIndex + 1;
                $nextLesson = $hasNextLesson ? $lessons[$nextLessonIndex] : null;
                
                // Determine if there are completed lessons
                $completedLessons = array_filter($lessons, function($lesson) {
                    return $lesson['is_completed'] ?? false;
                });
                $completedCount = count($completedLessons);
                
                // Calculate total progress
                $moduleProgress = $completedCount > 0 ? ($completedCount / count($lessons)) * 100 : 0;
                
                if ($currentLesson) {
                    $isCompleted = $currentLesson['is_completed'] ?? false;
                    $isDefault = isset($currentLesson['is_default']) && $currentLesson['is_default'];
                    $lessonNumber = $currentLessonIndex + 1;
                }
            ?>
            
            <?php if(count($lessons) == 0): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No lessons available for this module yet.
                </div>
            <?php elseif($currentLesson): ?>
                <div class="card shadow-sm border-primary" style="border-width: 2px;">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-primary me-2">Lesson <?php echo e($lessonNumber); ?> of <?php echo e(count($lessons)); ?></span>
                            <span class="badge bg-info"><?php echo e($currentSkill); ?> Skill</span>
                        </div>
                        <?php if($isCompleted): ?>
                            <span class="badge bg-success px-3 py-2">Completed</span>
                        <?php else: ?>
                            <span class="badge bg-warning px-3 py-2">In Progress</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="card-title"><?php echo e($currentLesson['title']); ?></h3>
                        <p class="lead"><?php echo e($currentLesson['description']); ?></p>
                        
                        <div class="my-4">
                            <h5>Lesson Content</h5>
                            <p>This is where detailed lesson content would appear. This could include text, images, videos, and interactive exercises to help you learn and practice this DBT skill.</p>
                            
                            <?php if($isDefault): ?>
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    This is placeholder content. Real lesson content will be available soon.
                                </div>
                            <?php endif; ?>
                            
                            <hr class="my-4">
                            
                            <h5>Practice Exercise</h5>
                            <p>Complete this exercise to practice what you've learned:</p>
                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <p>Write down a situation where you could apply this skill in your daily life.</p>
                                    <textarea class="form-control" rows="4" placeholder="Enter your response here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between py-3">
                        <button class="btn btn-outline-secondary" <?php if($currentLessonIndex == 0): ?> disabled <?php endif; ?>>
                            <i class="bi bi-arrow-left me-1"></i> Previous Lesson
                        </button>
                        
                        <?php if($isCompleted): ?>
                            <?php if($hasNextLesson): ?>
                                <a href="#" class="btn btn-primary">
                                    <i class="bi bi-arrow-right me-1"></i> Next Lesson
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('modules.index')); ?>" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Module Complete
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i> Mark as Complete
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Lesson Navigation -->
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Your Progress</h5>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: <?php echo e($moduleProgress); ?>%;" 
                                aria-valuenow="<?php echo e($moduleProgress); ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted"><?php echo e($completedCount); ?>/<?php echo e(count($lessons)); ?> lessons completed</small>
                            <small class="text-muted"><?php echo e(round($moduleProgress)); ?>% complete</small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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