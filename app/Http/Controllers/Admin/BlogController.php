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
            if ($request->filled('date'))
                $data->whereDate('created_at', $request->date);
            $data = $data->paginate(config('constant.paginatePerPage'));

            $user = Auth::user();

            $html = "";
            foreach ($data as $val) {
                $created_at = \Carbon\Carbon::parse($val->created_at)->format('m-d-Y h:iA');
                $image = (isset($val->images[0]->item_name) && file_exists(public_path('uploads/blog/' . $val->images[0]->item_name))) ? assets('uploads/blog/' . $val->images[0]->item_name) : assets('assets/images/no-image.jpg');
                $html .= "<div class='col-md-4'>
                    <div class='blog-card'>
                        <div class='blog-card-image'>
                            <img src='" . $image . "'>
                            <div class='Views-text'> 19.k Views</div>
                        </div>
                        <div class='blog-card-content'>
                            <h2>$val->title</h2>
                            <div class='blog-card-point-text'>
                                <div class='blogby-text'>By <span>$user->name</span></div>
                                <div class='date-text'>$created_at</div>
                            </div 
                            <p>$val->description</p>
                            <a class='deletebtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void()'><img src='" . assets('assets/images/trash.svg') . "'> Delete</a>
                            <a class='editbtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void()'><img src='" . assets('assets/images/edit-2.svg') . "'> Edit</a>
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
            'blog_images' => 'required'
        ]);

        if ($validator->fails()) {
            return errorMsg($validator->errors()->first());
        } else {

            $blog = new Blog;

            $blog->title = $request->title ?? null;
            $blog->description = $request->description ?? null;
            $blog->image = 'image';
            $blog->status = 1;
            $blog->save();

            $blogImages = json_decode($request->blog_images, true);
            if (is_array($blogImages) && count($blogImages) > 0) {
                foreach ($blogImages as $value) {
                    $image = new Image;
                    $image->item_name = $value;
                    $image->item_id = $blog->id;
                    $image->item_type = 'blog';
                    $image->save();
                }
            }

            return redirect()->back()->with('success', 'blog created successfully');
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
                if (isset($blog->image)) {
                    fileRemove("/uploads/blog/$blog->image");
                }

                Blog::where('id', $id)->delete();

                return redirect()->back()->with('success', 'Blog deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    //function used to get the blog detail
    public function getBlogDetail($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $blog = Blog::with('images')->where('id', $id)->first();
            $blog->image = assets('uploads/blog/image/' . $blog->image);
            $response = []; 
            foreach($blog->images as $image)
            {
                $temp['id'] = $image->id;
                $temp['name'] = $image->item_name;
                $temp['path'] = assets('uploads/blog/' . $image->item_name);
                $temp['size'] = 10;
                $response[] = $temp;
            }
            $blog->images_arr = $response;

            // $blog->images = $blog->images->map(function ($image) {
            //     return [
            //         'id' => $image->id,
            //         'name' => $image->item_name,
            //         'path'=>asset('uploads/blog/' . $image->item_name),
            //     ];
            // });
            return response()->json(['status' => true, 'message' => 'Blog detail.', 'data' => $blog]);
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
                $blog = Blog::find($request->id);

                if ($request->hasFile("image")) {
                    fileRemove("uploads/blog/" . $blog->image);
                    $image = fileUpload($request->image, "/uploads/blog");
                    $blog->image = $image;
                }

                $blog->title = $request->title ?? null;
                $blog->description = $request->description ?? null;
                $blog->save();

                return redirect()->back()->with('success', 'Blog updated successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
