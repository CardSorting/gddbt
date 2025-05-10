@extends('layouts.app')

@section('title', 'Daily Goals - DBTPath')
@section('meta_description', 'Set and track your daily goals for DBT skill practice')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-3">Daily Goals</h1>
            <p class="lead text-muted">Track your daily practice goals and build consistency in applying DBT skills.</p>
        </div>
        <div class="col-md-4 text-md-end d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <a href="{{ route('daily-goals.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Create New Goal
            </a>
        </div>
    </div>

    <!-- Current Goals Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4">Today's Goals</h4>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-primary px-3 py-2">In Progress</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="goalCheck1">
                                            <label class="form-check-label" for="goalCheck1"></label>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Practice Mindfulness</h5>
                                    <p class="card-text text-muted">10 minutes of mindful breathing each morning.</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Mindfulness</small>
                                        <small class="text-muted">May 9, 2025</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-success px-3 py-2">Completed</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="goalCheck2" checked>
                                            <label class="form-check-label" for="goalCheck2"></label>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Identify Emotions</h5>
                                    <p class="card-text text-muted">Track and name three emotions experienced today.</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Emotion Regulation</small>
                                        <small class="text-muted">May 9, 2025</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-warning px-3 py-2">Not Started</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="goalCheck3">
                                            <label class="form-check-label" for="goalCheck3"></label>
                                        </div>
                                    </div>
                                    <h5 class="card-title">Use PLEASE Skills</h5>
                                    <p class="card-text text-muted">Exercise, get enough sleep, and eat a balanced meal.</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Emotion Regulation</small>
                                        <small class="text-muted">May 9, 2025</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History & Stats Section -->
    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h4 class="mb-4">Goal History</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Goal</th>
                                    <th>Module</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>May 8, 2025</td>
                                    <td>Practice Mindfulness</td>
                                    <td>Mindfulness</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>May 8, 2025</td>
                                    <td>Identify Emotions</td>
                                    <td>Emotion Regulation</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>May 7, 2025</td>
                                    <td>Practice Mindfulness</td>
                                    <td>Mindfulness</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>May 7, 2025</td>
                                    <td>Use PLEASE Skills</td>
                                    <td>Emotion Regulation</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>May 6, 2025</td>
                                    <td>Practice Mindfulness</td>
                                    <td>Mindfulness</td>
                                    <td><span class="badge bg-danger">Missed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-end">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All History</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Completion Rate</h5>
                    <div class="d-flex align-items-center">
                        <div class="display-4 fw-bold text-primary me-3">86%</div>
                        <div>
                            <p class="mb-0">12 of 14 goals completed in the last 7 days</p>
                            <small class="text-success"><i class="bi bi-arrow-up-short"></i> 15% improvement from last week</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Current Streak</h5>
                    <div class="d-flex align-items-center">
                        <div class="display-4 fw-bold text-primary me-3">3</div>
                        <div>
                            <p class="mb-0">days with completed goals</p>
                            <small class="text-muted">Best streak: 5 days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suggested Goals Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4">Suggested Goals</h4>
                    <p class="text-muted mb-4">Based on your current modules and progress, here are some suggested goals:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border border-primary border-2 bg-light">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-stars text-primary fs-4 me-2"></i>
                                        <h5 class="mb-0">Practice STOP Skill</h5>
                                    </div>
                                    <p class="card-text">When feeling overwhelmed, practice the STOP skill (Stop, Take a step back, Observe, Proceed mindfully).</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Distress Tolerance</small>
                                        <button class="btn btn-sm btn-outline-primary">Add Goal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border border-primary border-2 bg-light">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-stars text-primary fs-4 me-2"></i>
                                        <h5 class="mb-0">Opposite Action</h5>
                                    </div>
                                    <p class="card-text">Identify one emotion and practice opposite action skill to change the emotion.</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Emotion Regulation</small>
                                        <button class="btn btn-sm btn-outline-primary">Add Goal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border border-primary border-2 bg-light">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-stars text-primary fs-4 me-2"></i>
                                        <h5 class="mb-0">Three-Minute Breathing Space</h5>
                                    </div>
                                    <p class="card-text">Practice the three-minute breathing space meditation three times throughout the day.</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">Module: Mindfulness</small>
                                        <button class="btn btn-sm btn-outline-primary">Add Goal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
