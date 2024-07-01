<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\FollowCommunity;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    public $community;
    public function __construct(Community $community)
    {
        $this->community = $community;
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all community
    public function communities(Request $request)
    {
        try {
            $data = $this->community->allCommunities($request);
            if (isset($data)) {
                return successMsg('Community list', $data);
            } else return errorMsg('No communities found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of particular community
    public function communityDetails($id)
    {
        try {
            $data = $this->community->details($id);
            if (isset($data->id)) {
                $temp['name'] = $data->name;
                $temp['description'] = $data->description;
                $images = array();
                foreach ($data->images as $val) {
                    $img['id'] = $val->id;
                    $img['image'] = isset($val->item_name) ? assets('uploads/community/' . $val->item_name) : assets('assets/images/no-image.jpg');
                    $images[] = $img;
                }
                $temp['images'] = $images;
                $temp['community_post'] = count($data->communityPost);
                $posts = array();
                foreach($data->communityPost as $val){
                    $post['id'] = $val->id;
                    $post['title'] = $val->title;
                    $post['description'] = $val->description;
                    $imgs = array();
                    foreach($val->images as $image){
                        $imgTemp['id'] = $image->id;
                        $imgTemp['image'] = isset($image->item_name) ? assets('uploads/post/' . $image->item_name) : assets('assets/images/no-image.jpg');
                        $imgs[] = $imgTemp;
                    }
                    $post['images'] = $imgs;
                    $post['like'] = 0;
                    $post['comment'] = 0;
                    $post['created_at'] = date('m-d-Y h:iA', strtotime($val->created_at));
                    $post['updated_at'] = date('m-d-Y h:iA', strtotime($val->updated_at));
                    $posts[] = $post;
                }
                $temp['posts'] = $posts;
                $temp['status'] = $data->status;
                $temp['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $temp['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                return successMsg('Community details', $temp);
            } else return errorMsg('Community not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of community
    public function postDetails($id)
    {
        try {
            $data = Post::where('id', $id)->with('images')->first();
            if (isset($data->id)) {
                $post['title'] = $data->title;
                $post['description'] = $data->description;
                $imgs = array();
                foreach($data->images as $image){
                    $imgTemp['id'] = $image->id;
                    $imgTemp['image'] = isset($image->item_name) ? assets('uploads/post/' . $image->item_name) : assets('assets/images/no-image.jpg');
                    $imgs[] = $imgTemp;
                }
                $post['images'] = $imgs;
                $post['like'] = 0;
                $post['comment'] = 0;
                $post['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $post['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                return successMsg('Post details', $post);
            } else return errorMsg('Post not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function followUnfollow(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'community_id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $community = Community::where('id', $request->community_id)->first();
                if(isset($community->id)){
                    if($request->status == 1){
                        $follow = new FollowCommunity;
                        $follow->user_id = auth()->user()->id;
                        $follow->community_id = $request->community_id;
                        $follow->save();
                        return successMsg('You are now following '.$community->name);
                    } else {
                        $isFollow = FollowCommunity::where('user_id', auth()->user()->id)->where('community_id', $request->community_id)->first();
                        if(isset($isFollow->id)){
                            FollowCommunity::where('user_id', auth()->user()->id)->where('community_id', $request->community_id)->delete();
                            return successMsg('You unfollowed '.$community->name);
                        }else{
                            return errorMsg("Community not found.");
                        }
                    }
                } else return errorMsg("Community not found.");
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function followedCommunityList(Request $request) {
        try{
            $data = $this->community->followedCommunities($request);
            if (isset($data)) {
                return successMsg('Community list', $data);
            } else return errorMsg('No followed communities found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function postLikeUnlike(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->with('community')->first();
                if(isset($post->id)){
                    $ufc = FollowCommunity::where('community_id', $post->community_id)->where('user_id', auth()->user()->id)->first();
                    $data = $post->community;
                    if(isset($ufc) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                        $like = Like::where('user_id', auth()->user()->id)->where('object_id', $request->id)->where('object_type', $request->type)->first();
                        if(isset($like->id)){
                            $like->status = ($like->status == 0) ? 1 : 0;
                            $like->updated_at = date('Y-m-d H:i:s');
                            $like->save();
                            $msg = ($like->status == 1) ? "You have liked the post" : "You have unliked the post";
                        } else {
                            $like = new Like;
                            $like->object_id = $request->id;
                            $like->object_type = $request->type;
                            $like->user_id = auth()->user()->id;
                            $like->status = 1;
                            $like->save();
                            $msg = "You have liked the post";
                        }
                        
                        return successMsg($msg);
                    } else return errorMsg('Please follow community first.');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function postComment(Request $request) {
        try{
            if(isset($request->is_reply) && $request->is_reply == 1)
                $valid = ['id' => 'required', 'comment' => 'required', 'is_reply' => 'required', 'reply_id' => 'required', 'type' => 'required'];
            else
                $valid = ['id' => 'required', 'comment' => 'required', 'is_reply' => 'required', 'type' => 'required'];
            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $post = Post::where('id', $request->id)->with('community')->first();
                if(isset($post->id)){
                    $ufc = FollowCommunity::where('community_id', $post->community_id)->where('user_id', auth()->user()->id)->first();
                    $data = $post->community;
                    if(isset($ufc) || (isset($data->id) && ($data->created_by == auth()->user()->id))){
                        $comment = new Comment;
                        $comment->user_id = auth()->user()->id;
                        $comment->object_id = $request->id;
                        $comment->object_type = $request->type;
                        $comment->parent_id = $request->reply_id ?? null;
                        $comment->comment = $request->comment ?? null;
                        $comment->status = 1;
                        $comment->save();

                        if(isset($request->is_reply) && $request->is_reply == 1)
                            return successMsg('Replied successfully.');
                        else return successMsg('Comment posted successfully.');
                    } else return errorMsg('Please follow community first.');
                } else return errorMsg('Post not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function postEditComment(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required', 
                'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $data = Comment::where('id', $request->id)->first();
                if(isset($data->id)){
                    if($data->user_id == auth()->user()->id){
                        $data->comment = $request->comment ?? null;
                        $data->save();
                        return successMsg('Comment updated successfully.');
                    } else return errorMsg("This comment is not posted by you.");
                } else return errorMsg('Comment not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function postDeleteComment(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required', 
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $data = Comment::where('id', $request->id)->first();
                if(isset($data->id)){
                    if($data->user_id == auth()->user()->id){
                        Comment::where('id', $request->id)->delete();
                        return successMsg('Comment deleted successfully.');
                    } else return errorMsg("This comment is not posted by you.");
                } else return errorMsg('Comment not found');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
