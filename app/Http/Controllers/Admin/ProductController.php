<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductRecommendationLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //function used to show the product list view page
    public function list(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Product::orderByDesc('id');
                if ($request->filled('search'))
                    $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
                if ($request->filled('status'))
                    $data->where('status', $request->status);
                $data = $data->paginate(config('constant.paginatePerPage'));
                $html = "";
                foreach ($data as $val) {

                    $image_html = "";
                    foreach($val->images as $name){
                        $image_html .= "<div class='item'>
                        <div class='community-media'>
                                <a data-fancybox='' href='".assets("uploads/product/$name->item_name")."'>
                                    <img src='".assets("uploads/product/$name->item_name")."'>
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

                    $html .= "<div class='col-md-3'>
                        <div class='product-card'>
                            <div class='product-card-image'>
                                $image_html
                                <div class='published-text'><img src='" . assets('assets/images/tick-circle.svg') . "'>" . config('constant.courseStatus')[$val->status] . "</div>
                            </div>
                            <div class='product-card-content'>
                                <h2>$val->title</h2>
                                <div class='product-card-point-text'>
                                    <div class='productfee-text'>$$val->price</div>
                                    <div class='Purchases-text'>0 Purchases</div>
                                </div>
                            </div>
                            <div class='product-card-action-text'>
                                <!-- <a class='deletebtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void(0)'>Delete</a>
                                <a class='Editbtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' data-title='$val->title' data-description='$val->description' data-price='$val->price' href='javascript:void(0)'>Edit</a> -->
                                <a class='Addbtn' style='padding: 5px 123px;' href='" . route('admin.product.info', encrypt_decrypt('encrypt', $val->id)) . "'>Info</a>
                            </div>
                        </div>
                    </div>";
                }

                if ($data->total() < 1) return errorMsg("No product found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Product list', $response);
            }
            $lesson = CourseLesson::orderByDesc('id')->get();
            return view('pages.product.list')->with(compact('lesson'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //function used to store the new product
    public function productCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'price' => 'required',
                'description' => 'required|string',
                'array_of_image' => 'required',
                'lesson' => 'required|array'
            ]);

            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $product = new Product;
                $product->title = $request->title ?? null;
                $product->price = $request->price ?? null;
                $product->description = $request->description ?? null;
                $product->status = 1;
                $product->save();

                $array_of_image = json_decode($request->array_of_image);
                if(is_array($array_of_image) && count($array_of_image)>0){
                    foreach($array_of_image as $val){
                        $image = new Image;
                        $image->item_name = $val;
                        $image->item_id = $product->id;
                        $image->item_type = 'product';
                        $image->save();
                    }
                }

                if(is_array($request->lesson) && count($request->lesson)>0){
                    foreach($request->lesson as $val){
                        $recommed = new ProductRecommendationLesson();
                        $recommed->lesson_id = $val;
                        $recommed->product_id = $product->id;
                        $recommed->status = 1;
                        $recommed->save();
                    }
                }

                return successMsg('Product created successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //function used to delete product
    public function productDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $product = Product::where('id', $id)->first();
                foreach($product->images as $val){
                    fileRemove("/uploads/product/$val->item_name");
                }
                Image::where('item_id', $id)->where('item_type', 'product')->delete();
                Product::where('id', $id)->delete();

                return redirect()->route('admin.product.list')->with('success', 'Product deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //function used to get the product detail
    public function getProductInfo($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $product = Product::where('id', $id)->first();
            $imgs = $product->images;
            $lesson = CourseLesson::orderByDesc('id')->get();
            $combined = array();
            foreach ($lesson as $arr) {
                $comb = array('id' => $arr['id'], 'name' => $arr['lesson'], 'selected' => false);
                foreach ($product->lessons as $arr2) {
                    if ($arr2->lesson_id == $arr['id']) {
                        $comb['selected'] = true;
                        break;
                    }
                }
                $combined[] = $comb;
            }
            return view('pages.product.details')->with(compact('product', 'imgs', 'combined'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    //function used to update the product
    public function productUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'price' => 'required',
                'description' => 'required|string',
                'lesson' => 'required|array'
            ]);

            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $product = Product::where('id', $id)->first();
                $product->title = $request->title ?? null;
                $product->price = $request->price ?? null;
                $product->description = $request->description ?? null;
                $product->save();
                
                ProductRecommendationLesson::where('product_id', $product->id)->delete();
                if(is_array($request->lesson) && count($request->lesson)>0){
                    foreach($request->lesson as $val){
                        $recommed = new ProductRecommendationLesson();
                        $recommed->lesson_id = $val;
                        $recommed->product_id = $product->id;
                        $recommed->status = 1;
                        $recommed->save();
                    }
                }

                return response()->json(['status' => true, 'message' => 'Product updated successfully', 'route' => route('admin.product.list')]);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
