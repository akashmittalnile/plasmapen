<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpSupport;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to show the list of all the inquiries for users
    public function supportCommunication(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = HelpSupport::select('help_and_supports.*');
                if ($request->filled('search')) {
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `contact` LIKE '%" . $request->search . "%')");
                }
                if ($request->filled('date')) {
                    $request->date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->date)->format('Y-m-d');
                    $data->whereDate('created_at', $request->date);
                };
                $data = $data->orderByDesc('id')->paginate(config('constant.communityPerPage'));
                if ($data->total() < 1) return errorMsg("No record found");

                $html = '';
                foreach ($data as $key => $val) {
                    $userProfileImage = (isset($val->user->profile) && File::exists(public_path("uploads/profile/" . $val->user->profile))) ? assets("uploads/profile/" . $val->user->profile) : assets("assets/images/no-image.jpg");

                    $adminReply = '';
                    if (isset($val->past_response) && $val->past_response != '') {
                        $adminReply = "<div class='col-md-4'>
                                <a class='Sendreply-btn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' data-name='$val->name' data-img='" . $userProfileImage . "' data-msg='$val->message' data-time='" . date('m-d-Y h:iA', strtotime($val->created_at)) . "' data-updatetime='" . date('m-d-Y h:iA', strtotime($val->updated_at)) . "' data-past='$val->past_response' id='seeRply' href='javascript:void(0)'>Your Answer</a>
                        </div>";
                    }

                    $sendReply = '';
                    if (isset($val->status) && $val->status != 1) {
                        $sendReply = "<div class='col-md-4'>
                            <a class='Sendreply-btn send-rply' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' data-name='$val->name' data-img='" . $userProfileImage . "' data-msg='$val->message' data-time='" . date('m-d-Y h:iA', strtotime($val->created_at)) . "' data-status='$val->status' id='sendRply' href='javascript:void(0)'>Send reply</a>
                        </div>";
                    }

                    $status = ($val->status == 1) ? 'Closed' : (($val->status == 2) ? 'In-Progress' : 'Pending');

                    $html .= "<div class='col-md-6'>
                            <div class='support-card'>
                                <div class='card-support-head'>
                                    <div class='card-user-card'>
                                        <div class='card-user-avtar'>
                                            <img src='" . $userProfileImage . "'>
                                        </div>
                                        <div class='card-user-text'>
                                            <h4>" . ucwords($val->name) . "</h4>
                                        </div>
                                    </div>
                                    <div class='lpcard-user-action'>
                                        <a class='phone-btn' href='tel:" . $val->contact . "'><img src='" . assets('assets/images/call2.svg') . "'></a>
                                        <a class='email-btn' href='mailto:" . $val->email . "'><img src='" . assets('assets/images/sms2.svg') . "'></a>
                                    </div>
                                </div>
                                <div class='card-support-body'>
                                    <div class='support-desc'>
                                        <p>$val->message</p>
                                    </div>

                                    <div class='support-action-info'>
                                        <div class='row'>
                                            <div class='row'>
                                                $sendReply
                                                $adminReply
                                                <div class='col-md-4'>
                                                    <div class='support-option-info'>
                                                        <h2 style='border-color: #5cabe6; background: white; padding: 13px 32px;' >" . $status . "</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='card-support-foot'>
                                    <div class='support-date-info'>
                                        <img src='" . assets('assets/images/calendar.svg') . "'> Submitted On : " . date('m-d-Y h:iA', strtotime($val->created_at)) . "
                                    </div>
                                </div>
                            </div>
                        </div>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Help & Support', $response);
            }
            return view('pages.support.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to add a reply of administrator
    public function sendReply(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'id' => 'required',
                'message' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $support = HelpSupport::where('id', $id)->first();
                $support->status = $request->status ?? null;
                $support->past_response = $request->message ?? null;
                $support->updated_at = date('Y-m-d H:i:s');
                $support->save();
                return successMsg('Message sent successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of notification
    public function notification(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Notification::orderByDesc('id');
                if ($request->filled('search')) {
                    $data->whereRaw("(`text` LIKE '%" . $request->search . "%')");
                }
                if ($request->filled('date')) {
                    $request->date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->date)->format('Y-m-d');
                    $data->whereDate('created_at', $request->date);
                };
                if ($request->filled('type')) {
                    $data->where('type', $request->type);
                };
                $data = $data->paginate(config('constant.paginatePerPage'));
                if ($data->total() < 1) return errorMsg("No notifications found");

                $html = '';
                foreach ($data as $key => $val) {
                    $html .= "<div class='col-md-12'>
                        <div class='manage-notification-item'>
                            <div class='manage-notification-icon'>
                                <img src='". assets('assets/images/notification.svg') ."'>
                            </div>
                            <div class='manage-notification-content'>
                                <div class='notification-tags'>".ucwords($val->type)."</div>
                                <div class='notification-descr'>
                                    <h6 class='p-0'>$val->text</h6>
                                    <p>$val->message</p>
                                </div>
                                <div class='notification-date'>Pushed On : " . date('m-d-Y h:iA', strtotime($val->created_at)) . "</div>
                            </div>
                        </div>
                    </div>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Notification', $response);
            }
            return view('pages.notification.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

        // Dev name : Dishant Gupta
    // This function is used to create a notification for admin
    public function createNotification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'type' => 'required',
                'description'=>'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $notification = new Notification;
                $notification->text = $request->title;
                $notification->message = $request->description;
                $notification->type = $request->type;
                $notification->user_id = auth()->user()->id;
                $notification->status = 1;
                $notification->save();
                $users = User::where('status', 1)->where('role', 2)->get();
                foreach($users as $val){
                    $notify = new Notify;
                    $notify->sender_id = auth()->user()->id;
                    $notify->receiver_id = $val->id;
                    $notify->type = "PUSH_NOTIFICATION";
                    $notify->title = $request->title;
                    $notify->message = $request->description;
                    $notify->save();
                    if(isset($val->fcm_token) && $val->fcm_token != ''){
                        $data = array(
                            'msg' => $request->description,
                            'title' => $request->title
                        );
                        sendNotification($val->fcm_token, $data);
                    }
                }
                return successMsg('Notification sent successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
