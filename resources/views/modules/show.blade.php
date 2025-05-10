@extends('layouts.app')

@section('title', 'Module Details - DBTPath')
@section('meta_description', 'Learn and practice DBT skills through interactive lessons and exercises')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('modules.index') }}">Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ ucfirst(str_replace('-', ' ', $module)) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Module Header -->
    <div class="row mb-5">
        <div class="col-md-8">
            @if($module == 'mindfulness')
                <h1 class="mb-3">Mindfulness Skills</h1>
                <p class="lead text-muted mb-4">Learn to be fully aware and present in this moment, observing experiences with acceptance and without judgment.</p>
                <div class="mb-4">
                    <span class="badge bg-success px-3 py-2 me-2">Completed</span>
                    <span class="text-muted">Completed on May 2, 2025</span>
                </div>
            @elseif($module == 'emotion-regulation')
                <h1 class="mb-3">Emotion Regulation Skills</h1>
                <p class="lead text-muted mb-4">Understand emotions, reduce emotional vulnerability, and decrease emotional suffering.</p>
                <div class="mb-4">
                    <span class="badge bg-warning px-3 py-2 me-2">In Progress</span>
                    <span class="text-muted">60% complete</span>
                </div>
            @elseif($module == 'distress-tolerance')
                <h1 class="mb-3">Distress Tolerance Skills</h1>
                <p class="lead text-muted mb-4">Survive crisis situations without making them worse, and accept reality as it is in the moment.</p>
                <div class="mb-4">
                    <span class="badge bg-secondary px-3 py-2 me-2">Locked</span>
                    <span class="text-muted">Complete Emotion Regulation to unlock</span>
                </div>
            @elseif($module == 'interpersonal-effectiveness')
                <h1 class="mb-3">Interpersonal Effectiveness Skills</h1>
                <p class="lead text-muted mb-4">Navigate relationships effectively, balance priorities against demands, and build mastery of social skills.</p>
                <div class="mb-4">
                    <span class="badge bg-secondary px-3 py-2 me-2">Locked</span>
                    <span class="text-muted">Complete Distress Tolerance to unlock</span>
                </div>
            @else
                <h1 class="mb-3">Module Details</h1>
                <p class="lead text-muted mb-4">Detailed information about this DBT skill module.</p>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Module Progress</h5>
                    @if($module == 'mindfulness')
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>5/5 lessons completed</span>
                            <span>100%</span>
                        </div>
                    @elseif($module == 'emotion-regulation')
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>3/5 lessons completed</span>
                            <span>60%</span>
                        </div>
                    @else
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>0/5 lessons completed</span>
                            <span>0%</span>
                        </div>
                    @endif
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

    @if($module == 'mindfulness')
        <!-- Mindfulness Lessons -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 1: Introduction to Mindfulness</h5>
                            <p class="mb-1 text-muted">Learn the fundamentals of mindful awareness and its benefits.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 2: Wise Mind</h5>
                            <p class="mb-1 text-muted">Discover the balance between emotional mind and reasonable mind.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 3: What Skills</h5>
                            <p class="mb-1 text-muted">Learn to observe, describe, and participate in the present moment.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 4: How Skills</h5>
                            <p class="mb-1 text-muted">Practice non-judgmentally, one-mindfully, and effectively.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 5: Mindfulness in Daily Life</h5>
                            <p class="mb-1 text-muted">Apply mindfulness skills to everyday activities and challenges.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                </div>
            </div>
        </div>
    @elseif($module == 'emotion-regulation')
        <!-- Emotion Regulation Lessons -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 1: Understanding Emotions</h5>
                            <p class="mb-1 text-muted">Learn about the function and components of emotions.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 2: Identifying Emotions</h5>
                            <p class="mb-1 text-muted">Learn to recognize and name your emotions.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Lesson 3: Reducing Vulnerability</h5>
                            <p class="mb-1 text-muted">Build emotional resilience through physical and mental self-care.</p>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center active">
                        <div>
                            <h5 class="mb-1">Lesson 4: Changing Emotional Responses</h5>
                            <p class="mb-1">Strategies for modifying emotional responses.</p>
                            <span class="badge bg-primary">In Progress</span>
                        </div>
                        <i class="bi bi-lightning-fill fs-4"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center disabled">
                        <div>
                            <h5 class="mb-1">Lesson 5: Managing Difficult Emotions</h5>
                            <p class="mb-1 text-muted">Advanced techniques for working with challenging emotions.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                        <i class="bi bi-lock-fill text-secondary fs-4"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Other Modules (Locked) -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="alert alert-secondary">
                    <i class="bi bi-lock-fill me-2"></i>
                    Complete previous modules to unlock this content.
                </div>
                <div class="list-group opacity-75">
                    <div class="list-group-item p-4">
                        <div>
                            <h5 class="mb-1">Lesson 1</h5>
                            <p class="mb-1 text-muted">This lesson is locked.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                    </div>
                    <div class="list-group-item p-4">
                        <div>
                            <h5 class="mb-1">Lesson 2</h5>
                            <p class="mb-1 text-muted">This lesson is locked.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                    </div>
                    <div class="list-group-item p-4">
                        <div>
                            <h5 class="mb-1">Lesson 3</h5>
                            <p class="mb-1 text-muted">This lesson is locked.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                    </div>
                    <div class="list-group-item p-4">
                        <div>
                            <h5 class="mb-1">Lesson 4</h5>
                            <p class="mb-1 text-muted">This lesson is locked.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                    </div>
                    <div class="list-group-item p-4">
                        <div>
                            <h5 class="mb-1">Lesson 5</h5>
                            <p class="mb-1 text-muted">This lesson is locked.</p>
                            <span class="badge bg-secondary">Locked</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
@endsection
