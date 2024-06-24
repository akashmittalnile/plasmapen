<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $blog;
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all blog
    public function blogs(Request $request)
    {
        try {
            $data = $this->blog->allBlogs($request);
            $response = array();
            if (isset($data)) {
                return successMsg('Blog list', $data);
            } else errorMsg('No blogs found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of particular blog
    public function blogDetails($id)
    {
        try {
            $data = $this->blog->details($id);
            if (isset($data->id)) {
                $temp['id'] = $data->id;
                $temp['title'] = $data->title;
                $temp['description'] = $data->description;
                $temp['price'] = $data->price;
                $images = array();
                foreach ($data->images as $val) {
                    $img['id'] = $val->id;
                    $img['image'] = isset($val->item_name) ? assets('uploads/blog/' . $val->item_name) : assets('assets/images/no-image.jpg');
                    $images[] = $img;
                }
                $temp['images'] = $images;
                $temp['status'] = $data->status;
                $temp['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $temp['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                return successMsg('Blog details', $temp);
            } else errorMsg('Blog not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
