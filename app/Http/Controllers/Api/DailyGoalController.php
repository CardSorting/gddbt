<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application\Commands\CommandBus;
use App\Application\Queries\QueryBus;
use App\Application\Commands\CreateDailyGoalCommand;
use App\Application\Queries\GetUserDailyGoalsQuery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DailyGoalController extends Controller
{
    /**
     * The command bus instance.
     *
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * The query bus instance.
     *
     * @var QueryBus
     */
    private QueryBus $queryBus;

    /**
     * Create a new controller instance.
     *
     * @param CommandBus $commandBus
     * @param QueryBus $queryBus
     */
    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    /**
     * Create or update a daily goal for today.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrUpdate(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'daily_goal' => 'required|string|max:1000',
                'skills_used' => 'nullable|array',
                'skills_used.*' => 'integer|exists:skills,id',
                'tomorrow_goal' => 'nullable|string|max:1000',
                'highlight' => 'nullable|string|max:1000',
                'gratitude' => 'nullable|string|max:1000',
                'is_public' => 'nullable|boolean',
            ]);

            // Ensure the authenticated user can only update their own daily goals
            // unless they have special privileges (like admin)
            if ($validated['user_id'] != Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only create or update your own daily goals.'
                ], 403);
            }

            $command = new CreateDailyGoalCommand(
                $validated['user_id'],
                $validated['daily_goal'],
                $validated['skills_used'] ?? null,
                $validated['tomorrow_goal'] ?? null,
                $validated['highlight'] ?? null,
                $validated['gratitude'] ?? null,
                $validated['is_public'] ?? true
            );

            $result = $this->commandBus->dispatch($command);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the daily goals for a user.
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function index(Request $request, int $userId): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'limit' => 'nullable|integer|min:1|max:100',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            ]);

            // Check authorization - users can only view their own goals or public goals
            if ($userId != Auth::id() && !Auth::user()->hasRole('admin')) {
                // Check if the target user allows viewing their goals
                $targetUser = \App\Domain\Models\User::find($userId);
                if (!$targetUser || $targetUser->private_profile || !$targetUser->share_daily_goals) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to view this user\'s goals.'
                    ], 403);
                }
            }

            $query = new GetUserDailyGoalsQuery(
                $userId,
                $validated['limit'] ?? null,
                $validated['start_date'] ?? null,
                $validated['end_date'] ?? null
            );

            $result = $this->queryBus->dispatch($query);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's daily goal for a user.
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getToday(int $userId): JsonResponse
    {
        try {
            // Check authorization - users can only view their own goals or public goals
            if ($userId != Auth::id() && !Auth::user()->hasRole('admin')) {
                // Check if the target user allows viewing their goals
                $targetUser = \App\Domain\Models\User::find($userId);
                if (!$targetUser || $targetUser->private_profile || !$targetUser->share_daily_goals) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to view this user\'s goals.'
                    ], 403);
                }
            }

            // We'll use the same query but with today's date
            $today = now()->toDateString();
            $query = new GetUserDailyGoalsQuery(
                $userId,
                1, // Limit to 1 result
                $today,
                $today
            );

            $result = $this->queryBus->dispatch($query);

            // Extract the goal if it exists
            $todayGoal = !empty($result['goals']) ? $result['goals'][0] : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'today' => $today,
                    'goal' => $todayGoal
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the visibility of a daily goal.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function toggleVisibility(Request $request, int $id): JsonResponse
    {
        try {
            // Find the goal
            $goal = \App\Domain\Models\DailyGoal::findOrFail($id);

            // Check authorization
            if ($goal->user_id != Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only toggle visibility of your own daily goals.'
                ], 403);
            }

            // Toggle visibility
            $isPublic = !$goal->is_public;
            $goal->is_public = $isPublic;
            $goal->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $goal->id,
                    'is_public' => $isPublic
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
