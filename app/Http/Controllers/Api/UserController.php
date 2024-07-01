<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Community;
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
    public $community;
    public function __construct(CourseCategory $category, Course $course, Product $product, Blog $blog, Community $community){
        $this->category = $category;
        $this->course = $course;
        $this->product = $product;
        $this->blog = $blog;
        $this->community = $community;
    }
    
    public function home(Request $request){
        try{
            $course = $this->course->allCourses($request, 5);
            $courseCategory = $this->category->allCategory($request);
            $product = $this->product->allProducts($request, 5);
            $blog = $this->blog->allBlogs($request, 5);
            $community = $this->community->allCommunities($request, 5);
            return successMsg('Home', ['course' => $course, 'course_category' => $courseCategory, 'product' => $product, 'blog' => $blog, 'community' => $community]);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
