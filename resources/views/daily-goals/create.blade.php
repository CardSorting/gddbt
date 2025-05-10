@extends('layouts.app')

@section('title', 'Create Daily Goal - DBTPath')
@section('meta_description', 'Create a new daily goal for your DBT skills practice')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('daily-goals.index') }}">Daily Goals</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Goal</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="card-title mb-4">Create a New Daily Goal</h1>
                    <p class="text-muted mb-4">Set a specific, measurable goal to practice DBT skills in your daily life.</p>

                    <form method="POST" action="#">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="goalTitle" class="form-label">Goal Title</label>
                            <input type="text" class="form-control" id="goalTitle" name="title" placeholder="e.g., Practice Mindfulness" required>
                            <div class="form-text">A brief, clear title for your goal.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="goalDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="goalDescription" name="description" rows="3" placeholder="e.g., 10 minutes of mindful breathing each morning" required></textarea>
                            <div class="form-text">Describe exactly what you will do to accomplish this goal.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="moduleSelect" class="form-label">Related Module</label>
                            <select class="form-select" id="moduleSelect" name="module_id" required>
                                <option value="" selected disabled>Select a module</option>
                                <option value="1">Mindfulness</option>
                                <option value="2">Emotion Regulation</option>
                                <option value="3">Distress Tolerance</option>
                                <option value="4">Interpersonal Effectiveness</option>
                            </select>
                            <div class="form-text">Which DBT skill module does this goal relate to?</div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="goalDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="goalDate" name="date" value="{{ date('Y-m-d') }}" required>
                                <div class="form-text">When will you complete this goal?</div>
                            </div>
                            <div class="col-md-6">
                                <label for="reminderTime" class="form-label">Reminder Time (Optional)</label>
                                <input type="time" class="form-control" id="reminderTime" name="reminder_time">
                                <div class="form-text">Set a reminder to complete your goal.</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label d-block">Difficulty Level</label>
                            <div class="btn-group" role="group" aria-label="Difficulty level">
                                <input type="radio" class="btn-check" name="difficulty" id="difficultyEasy" value="easy" checked>
                                <label class="btn btn-outline-primary" for="difficultyEasy">Easy</label>
                                
                                <input type="radio" class="btn-check" name="difficulty" id="difficultyMedium" value="medium">
                                <label class="btn btn-outline-primary" for="difficultyMedium">Medium</label>
                                
                                <input type="radio" class="btn-check" name="difficulty" id="difficultyHard" value="hard">
                                <label class="btn btn-outline-primary" for="difficultyHard">Hard</label>
                            </div>
                            <div class="form-text mt-2">How challenging will this goal be for you?</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="repeatDaily" name="repeat_daily">
                                <label class="form-check-label" for="repeatDaily">Repeat this goal daily</label>
                            </div>
                            <div class="form-text">Set this as an ongoing daily goal.</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="shareGoal" name="share_goal">
                                <label class="form-check-label" for="shareGoal">Share with followers</label>
                            </div>
                            <div class="form-text">Allow your followers to see and cheer you on with this goal.</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('daily-goals.index') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Goal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light-custom border-0">
                <div class="card-body p-4">
                    <h4 class="mb-3">Tips for Effective Goals</h4>
                    <ul class="mb-0">
                        <li class="mb-2"><strong>Be specific:</strong> Define exactly what you will do.</li>
                        <li class="mb-2"><strong>Make it measurable:</strong> How will you know you've accomplished it?</li>
                        <li class="mb-2"><strong>Set realistic goals:</strong> Challenge yourself, but stay achievable.</li>
                        <li class="mb-2"><strong>Connect to skills:</strong> Tie your goal to specific DBT skills you're learning.</li>
                        <li><strong>Start small:</strong> It's better to succeed at a small goal than to fail at a big one.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
