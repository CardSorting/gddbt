@extends('layouts.app')

@section('title', 'DBTPath - Learn Dialectical Behavior Therapy Skills')
@section('meta_description', 'Master DBT skills with our gamified learning platform. Build daily practice habits, track progress, and connect with others on your DBT journey.')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Your Journey to Emotional Wellness Starts Here</h1>
                    <p class="lead mb-4">Master Dialectical Behavior Therapy skills through a gamified learning experience. Build daily practice habits, track your progress, and connect with others on the same path.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-md-2">Get Started Free</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://via.placeholder.com/600x400" alt="DBT Learning Platform" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-light-custom py-5">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h2 class="fw-bold text-primary mb-0">{{ number_format($userCount) }}+</h2>
                        <p class="text-muted mb-0">Active Learners</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h2 class="fw-bold text-secondary mb-0">4</h2>
                        <p class="text-muted mb-0">Core DBT Modules</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h2 class="fw-bold text-accent mb-0">10K+</h2>
                        <p class="text-muted mb-0">Daily Goals Completed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DBT Module Features -->
    <section id="features" class="section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3">Core DBT Modules</span>
                    <h2 class="section-title">Master Essential DBT Skills</h2>
                    <p class="lead text-muted">Our platform covers all four modules of Dialectical Behavior Therapy, designed to help you build practical skills for emotional wellness.</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach($modules as $module)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 feature-card">
                        <div class="card-body p-4">
                            <div class="rounded-circle bg-{{ $loop->iteration == 1 ? 'primary' : ($loop->iteration == 2 ? 'secondary' : ($loop->iteration == 3 ? 'accent' : 'warning')) }} bg-opacity-10 p-3 d-inline-flex mb-3">
                                <i class="bi bi-{{ $module->icon ?? 'star' }} fs-3 text-{{ $loop->iteration == 1 ? 'primary' : ($loop->iteration == 2 ? 'secondary' : ($loop->iteration == 3 ? 'accent' : 'warning')) }}"></i>
                            </div>
                            <h4 class="card-title">{{ $module->name }}</h4>
                            <p class="card-text text-muted">{{ $module->description }}</p>
                            <a href="#" class="btn btn-sm btn-outline-{{ $loop->iteration == 1 ? 'primary' : ($loop->iteration == 2 ? 'secondary' : ($loop->iteration == 3 ? 'accent' : 'warning')) }} mt-2">Learn More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section-padding bg-light-custom">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3">How It Works</span>
                    <h2 class="section-title">A New Way to Learn DBT Skills</h2>
                    <p class="lead text-muted">Our platform combines proven DBT techniques with gamification elements to create an engaging and effective learning experience.</p>
                </div>
            </div>
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400" alt="How DBTPath works" class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-6">
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div>
                        </div>
                        <div>
                            <h4>Learn at Your Own Pace</h4>
                            <p class="text-muted">Progress through guided lessons and exercises that teach practical DBT skills for managing emotions and improving relationships.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">2</div>
                        </div>
                        <div>
                            <h4>Set Daily Goals</h4>
                            <p class="text-muted">Track your practice with daily goal setting, reflections, and gratitude journaling to build consistent habits.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">3</div>
                        </div>
                        <div>
                            <h4>Connect with Others</h4>
                            <p class="text-muted">Follow friends, share progress, and motivate each other on your wellness journeys.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-4">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">4</div>
                        </div>
                        <div>
                            <h4>Earn Achievements</h4>
                            <p class="text-muted">Stay motivated with rewards for your progress and consistent practice.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3">Benefits</span>
                    <h2 class="section-title">Why Choose DBT for Emotional Wellness?</h2>
                    <p class="lead text-muted">Dialectical Behavior Therapy provides research-backed techniques for managing emotions, improving relationships, and building a life worth living.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-emoji-smile text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Emotional Regulation</h4>
                            <p class="card-text text-muted">Learn to understand, accept, and manage intense emotions more effectively with proven DBT techniques.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-people text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Better Relationships</h4>
                            <p class="card-text text-muted">Develop skills for healthier communication, setting boundaries, and maintaining meaningful relationships.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-lightning text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Crisis Management</h4>
                            <p class="card-text text-muted">Build a toolkit of strategies to handle emotional crises and distressing situations effectively.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-peace text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Mindfulness Practice</h4>
                            <p class="card-text text-muted">Develop present-moment awareness and the ability to observe thoughts and feelings without judgment.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-hand-thumbs-up text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Enhanced Confidence</h4>
                            <p class="card-text text-muted">Build self-respect and confidence through effective skill development and practice.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <i class="bi bi-heart text-primary fs-3 mb-3"></i>
                            <h4 class="card-title">Self-Acceptance</h4>
                            <p class="card-text text-muted">Develop dialectical thinking to balance acceptance of yourself with motivation for positive change.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section-padding bg-light-custom">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3">Testimonials</span>
                    <h2 class="section-title">What Our Users Say</h2>
                    <p class="lead text-muted">Hear from people who have transformed their emotional wellness through our platform.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="card-text mb-3">"The daily goals feature has helped me consistently practice DBT skills. I've seen a noticeable improvement in how I handle difficult situations."</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">JD</div>
                                <div>
                                    <h6 class="mb-0">Jamie D.</h6>
                                    <small class="text-muted">Using DBTPath for 6 months</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="card-text mb-3">"As a therapist, I recommend this to my clients as a supplement to their therapy. The gamification makes skill practice fun and engaging."</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">SM</div>
                                <div>
                                    <h6 class="mb-0">Dr. Sarah M.</h6>
                                    <small class="text-muted">Clinical Psychologist</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                            <p class="card-text mb-3">"The social features allow me to connect with others who understand what I'm going through. We motivate each other to keep practicing."</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-accent text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">TW</div>
                                <div>
                                    <h6 class="mb-0">Taylor W.</h6>
                                    <small class="text-muted">Using DBTPath for 1 year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center text-center py-5">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Start Your DBT Journey Today</h2>
                    <p class="lead mb-4">Join thousands of others who are learning to master their emotions and improve their lives with DBT skills.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Sign Up Free</a>
                        <a href="#" class="btn btn-outline-light btn-lg px-5">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
