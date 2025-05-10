<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application\Commands\CompleteLessonCommand;
use App\Application\Queries\GetUserProgressQuery;
use App\Application\Commands\CommandBus;
use App\Application\Queries\QueryBus;

class UserProgressController extends Controller
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
     * Get user's progress overview.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgress(Request $request, int $userId)
    {
        try {
            $moduleId = $request->input('module_id');
            $query = new GetUserProgressQuery($userId, $moduleId);
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
     * Mark a lesson as completed.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeLesson(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'lesson_id' => 'required|integer',
                'score' => 'required|integer|min:0|max:100',
            ]);

            $command = new CompleteLessonCommand(
                $validated['user_id'],
                $validated['lesson_id'],
                $validated['score']
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
}
