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
        // Get the four core DBT modules for the homepage and ensure we always have a valid array
        $modules = [];
        
        if ($this->moduleRepository) {
            $allModules = $this->moduleRepository->all();
            
            // Make sure we have a valid array (could be null if repository method returns null)
            if (is_array($allModules)) {
                // Ensure there are no null values in the modules array
                $modules = array_filter($allModules, function($module) {
                    return $module !== null;
                });
                
                // Convert to indexed array
                $modules = array_values($modules);
            }
        }
        
        // Get some statistics for the landing page
        $userCount = User::count();
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        
        return view('home', compact('modules', 'userCount', 'activeUsers'));
    }
}
