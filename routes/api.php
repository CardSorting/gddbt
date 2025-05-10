<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserProgressController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// DBT Learning Platform API Routes
Route::prefix('v1')->group(function () {
    
    // User Progress Routes
    Route::get('/users/{userId}/progress', [UserProgressController::class, 'getProgress']);
    Route::post('/lessons/complete', [UserProgressController::class, 'completeLesson']);
    
    // These routes would be implemented with additional controllers
    /*
    // Module Routes
    Route::get('/modules', 'ModuleController@index');
    Route::get('/modules/{moduleId}', 'ModuleController@show');
    
    // Skill Routes  
    Route::get('/skills', 'SkillController@index');
    Route::get('/skills/{skillId}', 'SkillController@show');
    Route::get('/modules/{moduleId}/skills', 'SkillController@byModule');
    
    // Lesson Routes
    Route::get('/lessons', 'LessonController@index');
    Route::get('/lessons/{lessonId}', 'LessonController@show');
    Route::get('/skills/{skillId}/lessons', 'LessonController@bySkill');
    
    // Exercise Routes
    Route::get('/exercises/{exerciseId}', 'ExerciseController@show');
    Route::get('/lessons/{lessonId}/exercises', 'ExerciseController@byLesson');
    Route::post('/exercises/{exerciseId}/submit', 'ExerciseController@submitAnswer');
    
    // Achievement Routes
    Route::get('/achievements', 'AchievementController@index');
    Route::get('/users/{userId}/achievements', 'AchievementController@userAchievements');
    
    // Streak Routes
    Route::get('/users/{userId}/streak', 'StreakController@show');
    */
});
