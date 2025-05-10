@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>{{ __('Welcome to your DBT Learning Journey!') }}</h4>
                    <p>{{ __('You are logged in and ready to continue learning DBT skills.') }}</p>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <div class="text-center">
                            <h5>{{ __('Level') }}</h5>
                            <div class="display-4">{{ Auth::user()->level }}</div>
                        </div>
                        <div class="text-center">
                            <h5>{{ __('XP Points') }}</h5>
                            <div class="display-4">{{ Auth::user()->xp_points ?? 0 }}</div>
                        </div>
                        <div class="text-center">
                            <h5>{{ __('Streak') }}</h5>
                            <div class="display-4">{{ Auth::user()->streak ? Auth::user()->streak->current_streak : 0 }}</div>
                            <small>days</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">{{ __('Continue Learning') }}</div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('modules.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ __('Browse DBT Modules') }}
                            <span class="badge bg-primary rounded-pill">4</span>
                        </a>
                        <a href="{{ route('daily-goals.create') }}" class="list-group-item list-group-item-action">
                            {{ __('Set Today\'s Goal') }}
                        </a>
                        <a href="{{ route('daily-goals.index') }}" class="list-group-item list-group-item-action">
                            {{ __('View Your Progress') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
