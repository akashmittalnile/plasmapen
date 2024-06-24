<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BlogController extends Controller
{
    //function used to show the blog list
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::with('images')->orderByDesc('id');
            if ($request->filled('search'))
                $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
            if ($request->filled('date')) {
                $request->date = \Carbon\Carbon::createFromFormat('m-d-Y', $request->date)->format('Y-m-d');
                $data->whereDate('created_at', $request->date);
            };
            $data = $data->paginate(config('constant.paginatePerPage'));
            $html = "";
            foreach ($data as $val) {

                $image_html = "";
                foreach($val->images as $name){
                    $image_html .= "<div class='item'>
                    <div class='community-media'>
                            <a data-fancybox='' href='".assets("uploads/blog/$name->item_name")."'>
                                <img src='".assets("uploads/blog/$name->item_name")."'>
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

                $html .= "<div class='col-md-4'>
                        <div class='blog-card'>
                            <div class='blog-card-image'>
                                $image_html
                                <div class='Views-text'>0 Views</div>
                            </div>
                            <div class='blog-card-content'>
                                <h2>$val->title</h2>
                                <div class='blog-card-point-text'>
                                    <div class='blogby-text'>By <span>Plasm Pen </span></div>
                                    <div class='date-text'>".date('m-d-Y h:iA', strtotime($val->created_at))."</div>
                                </div>
                                <p>$val->description</p>
                            </div>
                            <div class='blog-card-action-text'>
                                <!-- <a class='deletebtn' href='javascript:void(0)'><img src='". assets('assets/images/trash.svg') ."'> Delete</a>
                                <a class='Editbtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' data-title='$val->title' data-description='$val->description' data-price='$val->price' href='javascript:void(0)'>Edit</a> -->
                                <a class='Addbtn' style='padding: 5px 46.5%;' href='" . route('admin.blog.info', encrypt_decrypt('encrypt', $val->id)) . "'>Info</a>
                            </div>
                        </div>
                    </div>";
            }

            if ($data->total() < 1) return errorMsg("No blog found");
            $response = array(
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'total' => $data->total(),
                'html' => $html,
            );
            return successMsg('blog list', $response);
        }
        
        return view('pages.blog.list');
    }


    //function used to add a new blog post
    public function blogCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'array_of_image' => 'required'
        ]);

        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {
            $blog = new Blog;
            $blog->title = $request->title ?? null;
            $blog->description = $request->description ?? null;
            $blog->status = 1;
            $blog->save();

            $array_of_image = json_decode($request->array_of_image);
            if(is_array($array_of_image) && count($array_of_image)>0){
                foreach($array_of_image as $val){
                    $image = new Image;
                    $image->item_name = $val;
                    $image->item_id = $blog->id;
                    $image->item_type = 'blog';
                    $image->save();
                }
            }

            return successMsg('Blog created successfully');
        }
    }

    //function used to delete blog
    public function blogDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $blog = Blog::where('id', $id)->first();
                foreach($blog->images as $val){
                    fileRemove("/uploads/blog/$val->item_name");
                }
                Image::where('item_id', $id)->where('item_type', 'blog')->delete();
                Blog::where('id', $id)->delete();

                return redirect()->route('admin.blog.list')->with('success', 'Blog deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    //function used to get the blog detail
    public function getBlogInfo($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $blog = Blog::where('id', $id)->first();
            $latestBlog = Blog::where('id', '!=', $id)->orderByDesc('id')->where('status', 1)->limit(3)->get();
            $imgs = $blog->images;
            return view('pages.blog.details')->with(compact('blog', 'imgs', 'latestBlog'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    //function used to update the blog
    public function blogUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $blog = Blog::where('id', $id)->first();
                $blog->title = $request->title ?? null;
                $blog->description = $request->description ?? null;
                $blog->save();

                return response()->json(['status' => true, 'message' => 'Blog updated successfully', 'route' => route('admin.blog.list')]);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
