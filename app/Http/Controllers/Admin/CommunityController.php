<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\FollowCommunity;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    public function list(Request $request){
        try{
            if ($request->ajax()) {
                $data = Community::with('images', 'communityFollower')->orderByDesc('id');
                if ($request->filled('search'))
                    $data->whereRaw("(`name` LIKE '%" . $request->search . "%')");
                $data = $data->paginate(config('constant.paginatePerPage'));
                $html = "";
                foreach ($data as $val) {
                    $image_html = "";
                    foreach($val->images as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-media'>
                                <a data-fancybox='' href='".assets("uploads/community/$name->item_name")."'>
                                    <img src='".assets("uploads/community/$name->item_name")."'>
                                </a>
                            </div>
                        </div>";
                    }
                    if($image_html == "") {
                        $image_html = "<div class='item'>
                        <div class='community-media'>
                                <img src='".assets('assets/images/no-image.jpg')."'>
                            </div>
                        </div>";
                    }

                    $postcount = count($val->communityPost);
                    $followcount = count($val->communityFollower);
                    $follow = FollowCommunity::where('community_id', $val->id)->orderByDesc('id')->limit(3)->get();
                    if(count($follow) > 0){
                        $mem_html = "";
                        $count = 1;
                        foreach($follow as $items){
                            $followedUser = $items->user;
                            $asset = (isset($followedUser->profile) && File::exists(public_path("uploads/profile/$followedUser->profile"))) ? assets("uploads/profile/$followedUser->profile") : assets('assets/images/no-image.jpg');
                            $mem_html .= "<span class='community-detail-member-image image$count'>
                                <img src='".$asset."'>
                            </span>";
                            $count++;
                        }
                    } else {
                        $mem_html = "";
                    }

                    $html .= "<div class='col-md-4'>
                        <div class='community-card'>
                            <div class='community-card-image owl-carousel owl-theme'>
                                $image_html
                            </div>
                            <div class='community-card-content'>
                                <h2>$val->name</h2>
                                <p>$val->description</p>

                                <div class='community-card-point-text'>
                                    <div class='blogby-text'>Total Posts <span>$postcount</span></div>
                                    <div class='date-text'>Created on <span>".date('m-d-Y h:iA', strtotime($val->created_at))."</span></div>
                                </div>
                                <div class='community-member-item'>
                                    <div class='community-member-info'>
                                        $mem_html
                                    </div>
                                    <p>$followcount Member Follows</p>
                                </div>
                                <a class='managebtn' href='".route('admin.community.info', encrypt_decrypt('encrypt', $val->id))."'> Manage Community</a>
                            </div>
                        </div>
                    </div>";
                }
                if ($data->total() < 1) return errorMsg("No community found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('community list', $response);
            }
            return view('pages.community.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function communityCreate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'description' => 'required|string',
                'array_of_image' => 'required'
            ]);
    
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = new Community;
                $community->name = $request->name ?? null;
                $community->description = $request->description ?? null;
                $community->status = 1;
                $community->save();
    
                $array_of_image = json_decode($request->array_of_image);
                if(is_array($array_of_image) && count($array_of_image)>0){
                    foreach($array_of_image as $val){
                        $image = new Image;
                        $image->item_name = $val;
                        $image->item_id = $community->id;
                        $image->item_type = 'community';
                        $image->save();
                    }
                }
    
                return successMsg('Community created successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function getCommunityInfo($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $community = Community::where('id', $id)->first();
            $imgs = $community->images;
            $follow = FollowCommunity::where('community_id', $id)->orderByDesc('id')->limit(3)->get();
            return view('pages.community.details')->with(compact('community', 'imgs', 'follow'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
