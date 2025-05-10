<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Domain\Models\User;
use App\Domain\Repositories\DailyGoalRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;

class SocialController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * The daily goal repository instance.
     *
     * @var DailyGoalRepositoryInterface
     */
    private DailyGoalRepositoryInterface $dailyGoalRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $userRepository
     * @param DailyGoalRepositoryInterface $dailyGoalRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        DailyGoalRepositoryInterface $dailyGoalRepository
    ) {
        $this->userRepository = $userRepository;
        $this->dailyGoalRepository = $dailyGoalRepository;
    }

    /**
     * Follow a user.
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function follow(Request $request, int $userId): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            $userToFollow = $this->userRepository->find($userId);
            
            if (!$userToFollow) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            
            // Can't follow yourself
            if ($currentUser->id === $userToFollow->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot follow yourself.'
                ], 400);
            }
            
            // Check if the user is already following
            if ($currentUser->isFollowing($userToFollow)) {
                return response()->json([
                    'success' => true,
                    'message' => 'You are already following this user.',
                    'data' => [
                        'following_id' => $userId
                    ]
                ]);
            }
            
            // Follow the user
            $result = $currentUser->follow($userToFollow);
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'User followed successfully.' : 'Failed to follow user.',
                'data' => [
                    'following_id' => $userId
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
     * Unfollow a user.
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function unfollow(Request $request, int $userId): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            $userToUnfollow = $this->userRepository->find($userId);
            
            if (!$userToUnfollow) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            
            // Check if the user is actually following
            if (!$currentUser->isFollowing($userToUnfollow)) {
                return response()->json([
                    'success' => true,
                    'message' => 'You are not following this user.',
                    'data' => [
                        'unfollowed_id' => $userId
                    ]
                ]);
            }
            
            // Unfollow the user
            $result = $currentUser->unfollow($userToUnfollow);
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'User unfollowed successfully.' : 'Failed to unfollow user.',
                'data' => [
                    'unfollowed_id' => $userId
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
     * Get followers of a user.
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function getFollowers(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->userRepository->find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            
            // Check privacy settings
            if ($userId !== Auth::id() && $user->private_profile && !Auth::user()->isFollowing($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This user\'s profile is private.'
                ], 403);
            }
            
            // Get followers with pagination
            $perPage = $request->input('per_page', 20);
            $followers = $user->followers()
                ->paginate($perPage);
            
            // Transform to simple array format
            $formattedFollowers = $followers->map(function ($follower) {
                return [
                    'id' => $follower->id,
                    'name' => $follower->name,
                    'level' => $follower->level,
                    'private_profile' => $follower->private_profile,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'total' => $followers->total(),
                    'per_page' => $followers->perPage(),
                    'current_page' => $followers->currentPage(),
                    'last_page' => $followers->lastPage(),
                    'followers' => $formattedFollowers
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
     * Get users that a user is following.
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function getFollowing(Request $request, int $userId): JsonResponse
    {
        try {
            $user = $this->userRepository->find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }
            
            // Check privacy settings
            if ($userId !== Auth::id() && $user->private_profile && !Auth::user()->isFollowing($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This user\'s profile is private.'
                ], 403);
            }
            
            // Get following with pagination
            $perPage = $request->input('per_page', 20);
            $following = $user->following()
                ->paginate($perPage);
            
            // Transform to simple array format
            $formattedFollowing = $following->map(function ($followedUser) {
                return [
                    'id' => $followedUser->id,
                    'name' => $followedUser->name,
                    'level' => $followedUser->level,
                    'private_profile' => $followedUser->private_profile,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $userId,
                    'total' => $following->total(),
                    'per_page' => $following->perPage(),
                    'current_page' => $following->currentPage(),
                    'last_page' => $following->lastPage(),
                    'following' => $formattedFollowing
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
     * Get the social feed (daily goals from followed users).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getFeed(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $limit = $request->input('limit', 20);
            
            // Get daily goals from users the current user is following
            $goals = $this->dailyGoalRepository->getFollowingGoals($user->id, $limit);
            
            // Transform goals for display
            $formattedGoals = $goals->map(function ($goal) {
                return [
                    'id' => $goal->id,
                    'user' => [
                        'id' => $goal->user->id,
                        'name' => $goal->user->name,
                    ],
                    'date' => $goal->date->toDateString(),
                    'daily_goal' => $goal->daily_goal,
                    'tomorrow_goal' => $goal->tomorrow_goal,
                    'highlight' => $goal->highlight,
                    'gratitude' => $goal->gratitude,
                    'skills_used' => $goal->skills_used,
                    'created_at' => $goal->created_at->toDateTimeString()
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => count($formattedGoals),
                    'goals' => $formattedGoals
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
     * Update user privacy settings.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePrivacySettings(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'private_profile' => 'nullable|boolean',
                'share_streaks' => 'nullable|boolean',
                'share_progress' => 'nullable|boolean',
                'share_daily_goals' => 'nullable|boolean',
            ]);
            
            // Update user privacy settings
            if (isset($validated['private_profile'])) {
                $user->private_profile = $validated['private_profile'];
            }
            
            if (isset($validated['share_streaks'])) {
                $user->share_streaks = $validated['share_streaks'];
            }
            
            if (isset($validated['share_progress'])) {
                $user->share_progress = $validated['share_progress'];
            }
            
            if (isset($validated['share_daily_goals'])) {
                $user->share_daily_goals = $validated['share_daily_goals'];
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Privacy settings updated successfully.',
                'data' => [
                    'private_profile' => $user->private_profile,
                    'share_streaks' => $user->share_streaks,
                    'share_progress' => $user->share_progress,
                    'share_daily_goals' => $user->share_daily_goals,
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
