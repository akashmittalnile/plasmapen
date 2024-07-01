<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $product;
    public function __construct(Product $product){
        $this->product = $product;
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all product
    public function products(Request $request)
    {
        try {
            $data = $this->product->allProducts($request);
            $response = array();
            if (isset($data)) {
                return successMsg('Product list', $data);
            } else return errorMsg('No products found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the details of particular product
    public function productDetails($id)
    {
        try {
            $data = $this->product->details($id);
            if (isset($data->id)) {
                $temp['title'] = $data->title;
                $temp['description'] = $data->description;
                $temp['price'] = $data->price;
                $images = array();
                foreach($data->images as $val){
                    $img['id'] = $val->id;
                    $img['image'] = isset($val->item_name) ? assets('uploads/product/'.$val->item_name) : assets('assets/images/no-image.jpg');
                    $images[] = $img;
                }
                $temp['images'] = $images;
                $temp['status'] = $data->status;
                $review = array();
                foreach($data->reviewList as $val){
                    $rev['id'] = $val->id;
                    $rev['rating'] = isset($val->rating) ? number_format((float)$val->rating, 1, '.', '') : 0;
                    $rev['review'] = $val->review;
                    $rev['review_by_name'] = $val->user->name;
                    $rev['review_by_profile'] = isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg');
                    $rev['review_on'] = date('m-d-Y h:iA', strtotime($val->created_at));
                    $review[] = $rev;
                }
                $temp['review_list'] = $review;
                $temp['wishlist'] = count($data->wishlist) ? true : false;
                $temp['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $temp['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                return successMsg('Product details', $temp);
            } else return errorMsg('Product not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
