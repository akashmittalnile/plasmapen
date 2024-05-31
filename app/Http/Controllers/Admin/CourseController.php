<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function list(Request $request){
        try{
            return view('pages.course.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function createCourse(Request $request){
        try{
            return view('pages.course.create');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
