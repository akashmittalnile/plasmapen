<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function list(Request $request){
        try{
            if($request->ajax()){
                $data = User::where('role', 2);
                if($request->filled('search')) $data->whereRaw("(`name` LIKE '%" . $request->search . "%' or `email` LIKE '%" . $request->search . "%' or `mobile` LIKE '%" . $request->search . "%')");
                if($request->filled('status')) $data->where('status', $request->status);
                else $data->where('status', 1);
                $data = $data->orderByDesc('id')->paginate(config('constant.paginatePerPage'));

                $html = '';
                foreach($data as $val)
                {
                    $status = ($val->status==1) ? 'Active' : 'Inactive';
                    $statusName = ($val->status==1) ? 'status-active' : 'status-inactive';
                    $html .= "<div class='user-table-item'>
                        <div class='row g-1 align-items-center'>
                            <div class='col-md-3'>
                                <div class='user-profile-item'>
                                    <div class='user-profile-media'><img src='". assets('uploads/profile/'.$val->profile) ."'></div>
                                    <div class='user-profile-text'>
                                        <h2>$val->name</h2>
                                        <div class='user-Module-text'><img src='". assets('assets/images/tick-circle.svg') ."'> Completed Module 0</div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-9'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <div class='user-contact-info'>
                                            <div class='user-contact-info-icon'>
                                                <img src='". assets('assets/images/sms1.svg') ."'>
                                            </div>
                                            <div class='user-contact-info-content'>
                                                <h2>Email Address</h2>
                                                <p>$val->email</p>
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class='col-md-4'>
                                        <div class='user-contact-info'>
                                            <div class='user-contact-info-icon'>
                                                <img src='". assets('assets/images/call1.svg') ."'>
                                            </div>
                                            <div class='user-contact-info-content'>
                                                <h2>Phone Number</h2>
                                                <p>$val->country_code $val->mobile</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-md-3'>
                                        <div class='user-status-content'>
                                            <h2>Account Status</h2>
                                            <div class='status-text $statusName'>$status</div>
                                        </div>
                                    </div>
        
                                    <div class='col-md-1'>
                                        <div class='action-item-text'>
                                            <a class='action-btn1' href='". route('admin.student.details.course', encrypt_decrypt('encrypt', $val->id)) ."'><img src='". assets('assets/images/arrow-right.svg') ."'></a>
                                        </div>
                                    </div>
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
                return successMsg('Users list', $response);
            }
            return view('pages.student.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function detailCourse($id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $user = User::where('id', $id)->first();
            return view('pages.student.detail-course')->with(compact('user'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    // Dev Name :- Dishant Gupta
    public function detailProduct($id){
        try{
            $id = encrypt_decrypt('decrypt', $id);
            $user = User::where('id', $id)->first();
            return view('pages.student.detail-product')->with(compact('user'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
