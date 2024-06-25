<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\goal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    // to function for create goal 
    public function createGoal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_statement' => 'required|string|max:255',
            'achieve_date' => 'required|date',
            'goal_for_me' => 'required|string|max:255',
            'goal_type' => 'required|string|max:255',
            // 'one_month_milestones' => 'required|string|max:255',
            // 'six_month_milestones' => 'required|string|max:255',
            // 'one_year_goal' => 'required|string|max:255',
            'accountability_partner' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $goal = new Goal;
        $goal->goal_statement = $request->goal_statement;
        $goal->achieve_date = $request->achieve_date;
        $goal->goal_for_me = $request->goal_for_me;
        $goal->goal_type = $request->goal_type;
        $goal->one_week_milestones = $request->one_week_milestones;
        $goal->one_month_milestones = $request->one_month_milestones;
        $goal->six_month_milestones = $request->six_month_milestones;
        $goal->one_year_goal = $request->one_year_goal;
        $goal->accountability_partner = $request->accountability_partner;
        $goal->user_id = Auth::id();
        $goal->save();

        return response()->json([
            'status' => true,
            'message' => 'Goal created successfully',
            'goal' => $goal,
        ]);
    }

    // to function for get goal
    public function getGoal(Request $request)
    {
        // Fetch all goals sorted by their created_at timestamps in descending order
        $goals = Goal::orderBy('created_at', 'desc')->get();

        if ($goals->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Record Not Found',
                'data' => []
            ]);
        }

        // If goals are found, return them
        return response()->json([
            'status' => true,
            'message' => 'Records Found',
            'data' => $goals
        ]);
    }

    // to function for get goal details
    public function goalDetails($id)
    {
        try {
            $goal = Goal::find($id);

            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Goal not found',
                    'data' => (object) []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Goal details found',
                'data' => $goal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching user details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // to function for update goal
    public function updateGoal(Request $request, $id)
    {

        try {
            // Find the user by ID
            $goal = Goal::find($id);

            // Check if user exists
            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Goal not found',
                    'data' => (object) []
                ], 404);
            }
            $validator = Validator::make($request->all(), [
                'goal_statement' => 'required|string|max:255',
                'achieve_date' => 'required|date',
                'goal_for_me' => 'required|string|max:255',
                'goal_type' => 'required|string|max:255',
                'one_month_milestones' => 'required|string|max:255',
                'one_year_goal' => 'required|string|max:255',
                'accountability_partner' => 'required|string|max:255',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ]);
            }

            // Update user data
            $goal->goal_statement = $request->goal_statement;
            $goal->achieve_date = $request->achieve_date;
            $goal->goal_for_me = $request->goal_for_me;
            $goal->goal_type = $request->goal_type;
            $goal->one_month_milestones = $request->one_month_milestones;
            $goal->one_year_goal = $request->one_year_goal;
            $goal->accountability_partner = $request->accountability_partner;
            $goal->save();

            return response()->json([
                'status' => true,
                'message' => 'Goal updated successfully',
                'data' => $goal
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating user details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // to function for delete goal
    public function deleteGoal(Request $request, $id)
    {
        try {
            // Find the user by ID
            $goal = Goal::find($id);

            // Check if user exists
            if (!$goal) {
                return response()->json([
                    'status' => false,
                    'message' => 'Goal not found',
                    'data' => (object) []
                ], 404);
            }

            // Delete the user
            $goal->delete();

            return response()->json([
                'status' => true,
                'message' => 'Goal deleted successfully',
                'data' => (object) []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
