<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Dev name : Dishant Gupta
    // This function is used to getting the list of all courses
    public function courses(Request $request)
    {
        try {
            $data = Course::where('status', 1);
            if($request->filled('search')) 
                $data->whereRaw("(`title` LIKE '%" . $request->search . "%')");
            $data = $data->orderByDesc('id')->get();
            $response = array();
            if (isset($data)) {
                foreach ($data as $key => $item) {
                    $temp['id'] = $item->id;
                    $temp['title'] = $item->title;
                    $temp['description'] = $item->description;
                    $temp['course_fee'] = $item->course_fee;
                    $temp['category_id'] = $item->category_id;
                    $temp['category_name'] = $item->category->title;
                    $temp['currency'] = $item->currency;
                    $temp['video'] = isset($item->video) ? assets('uploads/course/video/'.$item->video) : null;
                    $temp['image'] = isset($item->image) ? assets('uploads/course/image/'.$item->image) : null;
                    $temp['lesson_count'] = $item->lessonCount();
                    $temp['status'] = $item->status;
                    $temp['created_date'] = date('m-d-Y h:iA', strtotime($item->created_at));
                    $response[] = $temp;
                }
                return successMsg('Course list', $response);
            } else errorMsg('Course not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev name : Dishant Gupta
    // This function is used to getting the all the details of course
    public function courseDetails($id)
    {
        try {
            $data = Course::where('id', $id)->first();
            $response = array();
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
                $temp['created_date'] = date('m-d-Y h:iA', strtotime($data->created_at));
                $lessons = array();
                foreach($data->lessons() as $lesson){
                    $les['lesson_id'] = $lesson->id;
                    $les['lesson_name'] = $lesson->lesson;
                    $lessonStep = array();
                    foreach($lesson->steps() as $steps){
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
                return successMsg('Course list', $temp);
            } else errorMsg('Course not found');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
