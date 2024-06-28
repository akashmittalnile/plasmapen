<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class GoalController extends Controller
{
    // public function goal(Request $request)
    // {
    //     try {
    //         if ($request->ajax()) {
    //             // Start building the query to fetch goals
    //             $query = Goal::with('user');

    //             // Apply search filter if present
    //             if ($request->filled('search')) {
    //                 $query->whereHas('user', function ($q) use ($request) {
    //                     $q->where('first_name', 'like', '%' . $request->search . '%')
    //                       ->orWhere('last_name', 'like', '%' . $request->search . '%');
    //                 });
    //             }

    //             // Paginate the results
    //             $data = $query->paginate(config('constant.paginatePerPage', 10)); // default to 10 if constant not set

    //             // Check if there are any goals found
    //             if ($data->total() < 1) {
    //                 return response()->json(['status' => 'error', 'message' => 'No goals found']);
    //             }

    //             // Build the HTML response
    //             $html = '<table class="table table-striped">';
    //             $html .= '<thead><tr><th>S.no</th><th>Name</th><th>Email</th><th>Type</th><th>Date</th><th>What will it take</th><th>Action</th></tr></thead><tbody>';
    //             $i = ($data->currentPage() - 1) * $data->perPage() + 1;
    //             foreach ($data as $goal) {
    //                 $email = $goal->user->email ? $goal->user->email : 'No email';
    //                 $html .= "
    //                     <tr>
    //                         <td>{$i}</td>
    //                         <td>{$goal->user->first_name} {$goal->user->last_name}</td>
    //                         <td>{$email}</td>
    //                         <td>{$goal->goal_type}</td>
    //                         <td>" . date('d M Y', strtotime($goal->achieve_date)) . "</td>
    //                         <td>{$goal->goal_statement}</td>
    //                         <td>
    //                             <a href='#' class='Viewiconbtn' data-bs-toggle='modal' data-bs-target='#viewGoalModal' data-goal-id='{$goal->id}'>
    //                                 <img src='" . assets('assets/images/view.svg') . "' alt='View'>
    //                             </a>
    //                         </td>
    //                     </tr>";
    //                 $i++;
    //             }
    //             $html .= '</tbody></table>';

    //             // Prepare the response
    //             $response = [
    //                 'currentPage' => $data->currentPage(),
    //                 'lastPage' => $data->lastPage(),
    //                 'total' => $data->total(),
    //                 'html' => $html,
    //             ];

    //             return response()->json(['status' => 'success', 'data' => $response]);
    //         }

    //         // Render the goal list page if the request is not AJAX
    //         return view('pages.goal.list');
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    //     }
    // }


    public function goal(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Goal::with('user');

                // Apply search filter if present
                if ($request->filled('search')) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    });
                }

                // Apply type filter if present
                if ($request->filled('type')) {
                    $query->where('goal_type', $request->type);
                }

                // Paginate the results
                $data = $query->paginate(config('constant.paginatePerPage', 10)); // default to 10 if constant not set

                $html = '';
                foreach ($data as $goal) {
                    $email = $goal->user->email ? $goal->user->email : 'No email';
                    $status = ($goal->status == 1) ? 'Active' : 'Inactive';
                    $statusName = ($goal->status == 1) ? 'status-active' : 'status-inactive';
                    $profileImage = $goal->profile ? assets('uploads/profile/' . $goal->profile) : assets('assets/images/no-image.jpg');
                    $html .= "
                    <div class='user-table-item'>
                        <div class='row g-1 align-items-center'>
                            <div class='col-md-3'>
                                <div class='user-profile-item'>
                                    <div class='user-profile-media'><img src='$profileImage'></div>
                                    <div class='user-profile-text'>
                                        <h2>{$goal->user->first_name} {$goal->user->last_name}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-9'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <div class='user-contact-info'>
                                            <div class='user-contact-info-icon'>
                                                <img src='" . assets('assets/images/sms1.svg') . "'>
                                            </div>
                                            <div class='user-contact-info-content'>
                                                <h2>Email Address</h2>
                                                <p>$email</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='user-contact-info'>
                                       
                                            <div class='user-contact-info-content'>
                                                <h2>Type</h2>
                                                <p>$goal->goal_type</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class='user-contact-info'>
                                            <div class='user-contact-info-content'>
                                                <h2>Achieve Date</h2>
                                                <p>$goal->achieve_date</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class='user-contact-info'>
                                            <div class='user-contact-info-content'>
                                                <h2>Goal Statement</h2>
                                                <p>$goal->goal_statement</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-1'>
                                        <div class='action-item-text'>
                                            <a href='#' class='Viewiconbtn' data-bs-toggle='modal' data-bs-target='#viewGoalModal' data-goal-id='{$goal->id}'>
                                    <img src='" . assets('assets/images/view.svg') . "' alt='View'>
                             </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                if ($data->total() < 1) {
                    return response()->json(['status' => 'error', 'message' => 'No goals found']);
                }

                $response = [
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                ];
                return response()->json(['status' => 'success', 'message' => 'Goals list', 'data' => $response]);
            }

            return view('pages.goal.list');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
        }
    }
    // public function detailCourse($id){
    //     try{
    //         $id = encrypt_decrypt('decrypt', $id);
    //         $goal = Goal::with('user')->findOrFail($id);

    //         return view('pages.goal.detail-goal')->with(compact('goal'));
    //     } catch (\Exception $e) {
    //         return errorMsg('Exception => ' . $e->getMessage());
    //     }
    // }
    public function showGoal($id)
    {
        try {
            $goal = Goal::with('user')->findOrFail($id);
            // $userProfileImage = isset($goal->user->profile) && File::exists(public_path("uploads/profile/" . $goal->user->profile))
            // ? assets("uploads/profile/" . $goal->user->profile)
            // : assets("assets/images/no-image.jpg");
            // $goal->user->profile = $userProfileImage;
            return response()->json(['status' => 'success', 'data' => $goal]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Goal not found.']);
        }
    }
}
