<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
                    $image = (isset($val->image) && file_exists(public_path('uploads/course/image/' . $val->image))) ? assets('uploads/course/image/' . $val->image) : assets('assets/images/no-image.jpg');
                    $html .= "<div class='col-md-3'>
                        <div class='product-card'>
                            <div class='product-card-image'>
                                <img src='" . $image . "'>
                                <div class='published-text'><img src='" . assets('assets/images/tick-circle.svg') . "'>" . config('constant.courseStatus')[$val->status] . "</div>
                            </div>
                            <div class='product-card-content'>
                                <h2>$val->title</h2>
                                <div class='product-card-point-text'>
                                    <div class='productfee-text'>$$val->price</div>
                                    <div class='Purchases-text'>456 Purchases</div>
                                </div>
                            </div>
                            <div class='product-card-action-text'>
                                <a class='deletebtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void(0)'>Delete</a>
                                <a class='Editbtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void(0)'>Edit</a>
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
            return view('pages.product.list');
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
                'image' => 'required'
            ]);

            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $product = new Product;

                if ($request->hasFile("image")) {
                    $image = fileUpload($request->image, "/uploads/product");
                    $product->image = $image;
                }


                $product->title = $request->title ?? null;
                $product->price = $request->price ?? null;
                $product->description = $request->description ?? null;
                $product->status = 1;
                $product->save();

                return redirect()->back()->with('success', 'Product created successfully');
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
                if (isset($product->image)) {
                    fileRemove("/uploads/product/$product->image");
                }

                Product::where('id', $id)->delete();

                return redirect()->back()->with('success', 'Product deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //function used to get the product detail
    public function getProductDetail($id)
    {
        try {
            $id = encrypt_decrypt('decrypt', $id);
            $product = Product::where('id', $id)->first();
            $product->image = assets('uploads/course/image/' . $product->image);
            return response()->json(['status' => true, 'message' => 'Product detail.', 'data' => $product]);
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
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $product = Product::find($request->id);

                if ($request->hasFile("image")) {
                    fileRemove("uploads/product/" . $product->image);
                    $image = fileUpload($request->image, "/uploads/product");
                    $product->image = $image;
                }

                $product->title = $request->title ?? null;
                $product->price = $request->price ?? null;
                $product->description = $request->description ?? null;
                $product->save();

                return redirect()->back()->with('success', 'Product updated successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
