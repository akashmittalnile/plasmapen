<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

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
            $response = array();
            if (isset($data)) {
                return successMsg('Community list', $data);
            } else errorMsg('No communities found');
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
                $temp['id'] = $data->id;
                $temp['title'] = $data->title;
                $temp['description'] = $data->description;
                $temp['price'] = $data->price;
                $images = array();
                foreach ($data->images as $val) {
                    $img['id'] = $val->id;
                    $img['image'] = isset($val->item_name) ? assets('uploads/community/' . $val->item_name) : assets('assets/images/no-image.jpg');
                    $images[] = $img;
                }
                $temp['images'] = $images;
                $temp['status'] = $data->status;
                $temp['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $temp['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                return successMsg('Community details', $temp);
            } else errorMsg('Community not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
