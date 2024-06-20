<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $category;
    public $course;
    public $product;
    public function __construct(CourseCategory $category, Course $course, Product $product){
        $this->category = $category;
        $this->course = $course;
        $this->product = $product;
    }
    
    public function home(Request $request){
        try{
            $course = $this->course->allCourses($request, 5);
            $product = $this->product->allProducts($request, 5);
            return successMsg('Home', ['course' => $course, 'product' => $product]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
