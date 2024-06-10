<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function list(Request $request){
        try{
            if($request->ajax()){
                $data = Course::orderByDesc('id');
                if($request->filled('search'))
                    $data->whereRaw("(`title` LIKE '%" . $request->search . "%' or `course_fee` LIKE '%" . $request->search . "%')");
                if($request->filled('status'))
                    $data->where('status', $request->status);
                $data = $data->paginate(config('constant.paginatePerPage'));

                $html = "";
                foreach($data as $val){
                    $image = (isset($val->image) && file_exists(public_path('uploads/course/image/'.$val->image))) ? assets('uploads/course/image/'.$val->image) : assets('assets/images/no-image.jpg');
                    $html .= "<div class='col-md-3'>
                        <div class='course-card'>
                            <div class='course-card-image'>
                                <img src='" . $image . "'>
                                <div class='published-text'><img src='". assets('assets/images/tick-circle.svg') ."'>".config('constant.courseStatus')[$val->status]."</div>
                            </div>
                            <div class='course-card-content'>
                                <h2>About : $val->title</h2>
                                <div class='course-card-point-text'>
                                    <div class='coursefee-text'>$$val->course_fee</div>
                                    <div class='lesson-text'>0 Lessons</div>
                                </div>
                                <div class='course-card-action-text'>
                                    <a class='deletebtn' href=''>Delete</a>
                                    <a class='Editbtn' href=''>Edit</a>
                                    <a class='Addbtn' href=''>Add Lessons</a>
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                if($data->total() < 1) return errorMsg("No courses found");
                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Community list', $response);
            }
            return view('pages.course.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function createCourse(Request $request){
        try{
            $course = Course::where('status', 1)->orderByDesc('id')->get();
            return view('pages.course.create')->with(compact('course'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function courseCreate(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'fees' => 'required',
                'video' => 'required',
                'image' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            } else {
                $course = new Course;

                if($request->hasFile("image")) {
                    $image = fileUpload($request->image, "/uploads/course/image");
                    $course->image = $image;
                }
                if($request->hasFile("video")) {
                    $video = fileUpload($request->video, "/uploads/course/video");
                    $course->video = $video;
                }

                $course->title = $request->title ?? null;
                $course->description = $request->description ?? null;
                $course->course_fee = $request->fees ?? null;
                $course->currency = 'usd';
                $course->prerequisite = $request->prerequisite ?? null;
                $course->status = 1;
                $course->save();

                return response()->json(['status' => true, 'message' => 'Course Created Successfully.', 'route' => route("admin.course.list")]);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
