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
            <h1 class="mb-3">{{ $moduleData->name }} Skills</h1>
            <p class="lead text-muted mb-4">{{ $moduleData->description }}</p>
            <div class="mb-4">
                @if($progress['status'] == 'completed')
                    <span class="badge bg-success px-3 py-2 me-2">Completed</span>
                    <span class="text-muted">Completed on {{ $progress['last_activity_at'] ? $progress['last_activity_at']->format('F j, Y') : 'Unknown date' }}</span>
                @elseif($progress['status'] == 'in_progress')
                    <span class="badge bg-warning px-3 py-2 me-2">In Progress</span>
                    <span class="text-muted">{{ $progress['completion_percentage'] }}% complete</span>
                @else
                    <span class="badge bg-secondary px-3 py-2 me-2">Not Started</span>
                    <span class="text-muted">Begin your learning journey</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Module Progress</h5>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar 
                            @if($progress['status'] == 'completed') bg-success 
                            @elseif($progress['status'] == 'in_progress') bg-warning 
                            @else bg-secondary @endif" 
                            role="progressbar" 
                            style="width: {{ $progress['completion_percentage'] }}%;" 
                            aria-valuenow="{{ $progress['completion_percentage'] }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ count($progress['completed_lessons']) }}/{{ count($lessons) }} lessons completed</span>
                        <span>{{ $progress['completion_percentage'] }}%</span>
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
            @if($progress['status'] == 'not_started' && $progress['completion_percentage'] == 0)
                <!-- Module not started -->
                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle me-2"></i>
                    Start this module to begin your learning journey.
                </div>
            @endif
            
            <div class="list-group">
                @foreach($lessons as $index => $lesson)
                    @php
                        $isCompleted = $lesson['is_completed'];
                        $isActive = ($activeLesson !== null && $index == $activeLesson);
                        $isLocked = !$isCompleted && !$isActive;
                    @endphp
                    
                    <a href="#" class="list-group-item list-group-item-action p-4 d-flex justify-content-between align-items-center
                        {{ $isActive ? 'active' : '' }} 
                        {{ $isLocked ? 'disabled' : '' }}">
                        <div>
                            <h5 class="mb-1">Lesson {{ $index + 1 }}: {{ $lesson['title'] }}</h5>
                            <p class="mb-1 {{ !$isActive ? 'text-muted' : '' }}">{{ $lesson['description'] }}</p>
                            
                            @if($isCompleted)
                                <span class="badge bg-success">Completed</span>
                            @elseif($isActive)
                                <span class="badge bg-primary">In Progress</span>
                            @else
                                <span class="badge bg-secondary">Locked</span>
                            @endif
                        </div>
                        
                        @if($isCompleted)
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        @elseif($isActive)
                            <i class="bi bi-lightning-fill fs-4"></i>
                        @else
                            <i class="bi bi-lock-fill text-secondary fs-4"></i>
                        @endif
                    </a>
                @endforeach
                
                @if(count($lessons) == 0)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No lessons available for this module yet.
                    </div>
                @endif
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
@endsection
