@extends('layouts.app')

@section('title', 'DBT Modules - DBTPath')
@section('meta_description', 'Explore the core DBT modules and track your learning progress')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">DBT Skill Modules</h1>
            <p class="lead text-muted">Master the four core skill sets of Dialectical Behavior Therapy through interactive lessons and exercises.</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Your Progress</h5>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $overall_progress['completion_percentage'] }}%;" 
                             aria-valuenow="{{ $overall_progress['completion_percentage'] }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">{{ $overall_progress['completed_count'] }}/{{ $overall_progress['total_count'] }} modules completed</small>
                        <small class="text-muted">{{ $overall_progress['completion_percentage'] }}% complete</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($modules as $module)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if($module['status'] == 'completed')
                                <span class="badge bg-success px-3 py-2">Completed</span>
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            @elseif($module['status'] == 'in_progress')
                                <span class="badge bg-warning px-3 py-2">In Progress</span>
                                <i class="bi bi-lightning-fill text-warning fs-4"></i>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Locked</span>
                                <i class="bi bi-lock-fill text-secondary fs-4"></i>
                            @endif
                        </div>
                        <h4 class="card-title">{{ $module['name'] }}</h4>
                        <p class="card-text text-muted">{{ $module['description'] }}</p>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar 
                                @if($module['status'] == 'completed') bg-success 
                                @elseif($module['status'] == 'in_progress') bg-warning 
                                @else bg-secondary @endif" 
                                role="progressbar" 
                                style="width: {{ $module['completion_percentage'] }}%;" 
                                aria-valuenow="{{ $module['completion_percentage'] }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">{{ $module['completed_lessons_count'] }}/{{ $module['total_lessons_count'] }} lessons completed</small>
                        <div class="mt-4">
                            @if($module['status'] == 'completed')
                                <a href="{{ route('modules.show', $module['slug']) }}" class="btn btn-outline-primary w-100">Review Module</a>
                            @elseif($module['status'] == 'in_progress')
                                <a href="{{ route('modules.show', $module['slug']) }}" class="btn btn-primary w-100">Continue Module</a>
                            @else
                                <button class="btn btn-secondary w-100" disabled>Complete Previous Module</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light-custom border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div>
                            <h5 class="mb-1">Learning Path</h5>
                            <p class="mb-0">Modules are structured in a sequential learning path. Complete each module to unlock the next one in the sequence.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
