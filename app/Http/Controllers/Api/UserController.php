<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $category;
    public $course;
    public $product;
    public $blog;
    public function __construct(CourseCategory $category, Course $course, Product $product, Blog $blog){
        $this->category = $category;
        $this->course = $course;
        $this->product = $product;
        $this->blog = $blog;
    }
    
    public function home(Request $request){
        try{
            $course = $this->course->allCourses($request, 5);
            $product = $this->product->allProducts($request, 5);
            $blog = $this->blog->allBlogs($request, 5);
            return successMsg('Home', ['course' => $course, 'product' => $product, 'blog' => $blog]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
