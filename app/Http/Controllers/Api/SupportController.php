<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to create a inquiry/query of user
    public function createQuery(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'contact'=>'required',
                'message'=>'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $support = new HelpSupport;
                $support->name = $request->name ?? null;
                $support->email = $request->email ?? null;
                $support->contact = $request->contact ?? null;
                $support->message = $request->message ?? null;
                $support->status = 3;
                $support->user_id = auth()->user()->id;
                $support->save();

                return successMsg('Thank you submitting you query.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of user query
    public function queryList(Request $request) {
        try{
            $response = array();
            $list = HelpSupport::where('user_id', auth()->user()->id);
            if($request->filled('date')) $list->whereDate('created_at', $request->date);
            $list = $list->orderByDesc('id')->get();
            $admin = User::where('role', 1)->first();
            foreach($list as $key => $val){
                $temp['id'] = $val->id;
                $temp['name'] = $val->name;
                $temp['profile'] = isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : null;
                $temp['email'] = $val->email;
                $temp['contact'] = $val->contact;
                $temp['message'] = $val->message;
                $temp['admin_reply'] = $val->past_response;
                $temp['admin_name'] = $admin->name;
                $temp['admin_profile'] = isset($admin->profile) ? assets('uploads/profile/'.$admin->profile) : null;
                $temp['status'] = $val->status;
                $temp['status_name'] = ($val->status == 1) ? 'Closed' : (($val->status == 2) ? 'In-Progress' : 'Pending');
                $temp['query_date'] = date('m-d-Y h:iA', strtotime($val->created_at));
                $temp['admin_reply_date'] = isset($val->past_response) ? date('m-d-Y h:iA', strtotime($val->updated_at)) : null;
                $response[] = $temp;
            }
            return successMsg('Query list', $response);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
