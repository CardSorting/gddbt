<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\Repositories\ModuleRepositoryInterface;
use App\Domain\Models\User;

class HomeController extends Controller
{
    /**
     * The module repository instance.
     *
     * @var ModuleRepositoryInterface
     */
    protected $moduleRepository;

    /**
     * Create a new controller instance.
     *
     * @param ModuleRepositoryInterface $moduleRepository
     * @return void
     */
    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * Show the application landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the four core DBT modules for the homepage
        $modules = $this->moduleRepository->all();
        
        // Get some statistics for the landing page
        $userCount = User::count();
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        
        return view('home', compact('modules', 'userCount', 'activeUsers'));
    }
}
