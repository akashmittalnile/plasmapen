<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public $category;
    public $course;
    public function __construct(CourseCategory $category, Course $course){
        $this->category = $category;
        $this->course = $course;
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the list of all courses
    public function courses(Request $request)
    {
        try {
            $data = $this->course->allCourses($request);
            $response = array();
            if (isset($data)) {
                return successMsg('Course list', $data);
            } else errorMsg('No courses found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function courseCategory(Request $request){
        try{
            $data = $this->category->allCategory($request);
            if (isset($data)) {
                return successMsg('Course category list', $data);
            } else errorMsg('No category found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the all the details of course
    public function courseDetails($id)
    {
        try {
            $data = $this->course->details($id);
            if (isset($data->id)) {
                $temp['id'] = $data->id;
                $temp['title'] = $data->title;
                $temp['description'] = $data->description;
                $temp['course_fee'] = $data->course_fee;
                $temp['currency'] = $data->currency;
                $temp['category_id'] = $data->category_id;
                $temp['category_name'] = $data->category->title;
                $temp['video'] = isset($data->video) ? assets('uploads/course/video/'.$data->video) : null;
                $temp['image'] = isset($data->image) ? assets('uploads/course/image/'.$data->image) : null;
                $temp['lesson_count'] = count($data->lessons);
                $temp['rating'] = isset($data->rating) ? number_format((float)$data->rating, 1, '.', '') : 0;
                $temp['status'] = $data->status;
                $temp['created_at'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $temp['updated_at'] = date('m-d-Y h:iA', strtotime($data->updated_at));
                $lessons = array();
                foreach($data->lessons as $lesson){
                    $les['lesson_id'] = $lesson->id;
                    $les['lesson_name'] = $lesson->lesson;
                    $lessonStep = array();
                    foreach($lesson->steps as $steps){
                        $step['step_id'] = $steps->id;
                        $step['title'] = $steps->title;
                        $step['description'] = $steps->description;
                        $step['file'] = isset($steps->details) ? assets('uploads/course/lesson/'.$steps->type.'/'.$steps->details) : null;
                        $step['type'] = $steps->type;
                        $lessonQuiz = array();
                        foreach($steps->quiz as $quiz){
                            $question['id'] = $quiz->id; 
                            $question['type'] = $quiz->type; 
                            $question['title'] = $quiz->title; 
                            $question['description'] = $quiz->description; 
                            $question['marks'] = $quiz->marks; 
                            $quizOption = array();
                            foreach($quiz->quizOption as $options){
                                $option['id'] = $options->id;
                                $option['answer'] = $options->answer;
                                $option['is_correct'] = $options->is_correct;
                                $quizOption[] = $option;
                            }
                            $question['options'] = $quizOption; 
                            $lessonQuiz[] = $question;
                        }
                        $step['quiz'] = $lessonQuiz;
                        $lessonStep[] = $step;
                    }
                    $les['chapter_steps'] = $lessonStep;
                    $lessons[] = $les;
                }
                $temp['lessons'] = $lessons;
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
                return successMsg('Course details', $temp);
            } else errorMsg('Course not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function submitRating(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'course_id' => 'required',
                'rating' => 'required',
                'review' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $isExist = UserReview::where('user_id', auth()->user()->id)->where('object_id', $request->course_id)->where('object_type', 'course')->first();
                if(isset($isExist->id)) return errorMsg('You already submitted the rating.');
                $rating = new UserReview;
                $rating->user_id = auth()->user()->id;
                $rating->object_id = $request->course_id;
                $rating->object_type = 'course';
                $rating->rating = $request->rating;
                $rating->review = $request->review;
                $rating->status = 1;
                $rating->save();
                return successMsg('Your feedback is successfully submitted.');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
