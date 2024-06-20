<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
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
                $temp['lesson_count'] = $data->lessonCount();
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
                $temp['lessons'][] = $lessons;
                return successMsg('Course details', $temp);
            } else errorMsg('Course not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
