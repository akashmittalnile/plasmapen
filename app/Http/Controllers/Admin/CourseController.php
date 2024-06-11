<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseLessonQuiz;
use App\Models\CourseLessonQuizOption;
use App\Models\CourseLessonStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function list(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Course::orderByDesc('id');
                if ($request->filled('search'))
                    $data->whereRaw("(`title` LIKE '%" . $request->search . "%' or `course_fee` LIKE '%" . $request->search . "%')");
                if ($request->filled('status'))
                    $data->where('status', $request->status);
                $data = $data->paginate(config('constant.paginatePerPage'));

                $html = "";
                foreach ($data as $val) {
                    $image = (isset($val->image) && file_exists(public_path('uploads/course/image/' . $val->image))) ? assets('uploads/course/image/' . $val->image) : assets('assets/images/no-image.jpg');
                    $html .= "<div class='col-md-3'>
                        <div class='course-card'>
                            <div class='course-card-image'>
                                <img src='" . $image . "'>
                                <div class='published-text'><img src='" . assets('assets/images/tick-circle.svg') . "'>" . config('constant.courseStatus')[$val->status] . "</div>
                            </div>
                            <div class='course-card-content'>
                                <h2>About : $val->title</h2>
                                <div class='course-card-point-text'>
                                    <div class='coursefee-text'>$$val->course_fee</div>
                                    <div class='lesson-text'>".$val->lessonCount()." Lessons</div>
                                </div>
                                <div class='course-card-action-text'>
                                    <a class='deletebtn' data-id='" . encrypt_decrypt('encrypt', $val->id) . "' href='javascript:void(0)'>Delete</a>
                                    <a class='Editbtn' href='javascript:void(0)'>Edit</a>
                                    <a class='Addbtn' href='" . route('admin.course.lesson.empty', encrypt_decrypt('encrypt', $val->id)) . "'>Add Lessons</a>
                                </div>
                            </div>
                        </div>
                    </div>";
                }

                if ($data->total() < 1) return errorMsg("No courses found");
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
    public function createCourse(Request $request)
    {
        try {
            $course = Course::where('status', 1)->orderByDesc('id')->get();
            return view('pages.course.create')->with(compact('course'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // Dev Name :- Dishant Gupta
    public function courseCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'fees' => 'required',
                'video' => 'required',
                'image' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $course = new Course;

                if ($request->hasFile("image")) {
                    $image = fileUpload($request->image, "/uploads/course/image");
                    $course->image = $image;
                }
                if ($request->hasFile("video")) {
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

                return response()->json(['status' => true, 'message' => 'Course created successfully.', 'route' => route("admin.course.list")]);
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function courseDelete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $course = Course::where('id', $id)->first();
                if (isset($course->image)) {
                    fileRemove("/uploads/course/image/$course->image");
                }
                if (isset($course->video)) {
                    fileRemove("/uploads/course/video/$course->video");
                }
                Course::where('id', $id)->delete();

                return redirect()->back()->with('success', 'Course deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function courseLessonEmpty($courseId)
    {
        try {
            $courseId = encrypt_decrypt('decrypt', $courseId);
            $lessons = CourseLesson::where('course_id', $courseId)->get();
            if (count($lessons) > 0) {
                $lesson = CourseLesson::where('course_id', $courseId)->first();
                return redirect()->route('admin.course.lesson.all', ['courseId' => encrypt_decrypt('encrypt', $courseId), 'lessonId' => encrypt_decrypt('encrypt', $lesson->id)]);
            } else {
                $lesson = null;
                $datas = CourseLessonStep::where('course_lesson_id', $lesson)->get();
                return view('pages.course.lessons', compact('lessons', 'lesson', 'courseId', 'datas'));
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function courseLessonAll($courseId, $lessonId)
    {
        try {
            $courseId = encrypt_decrypt('decrypt', $courseId);
            $lessonId = encrypt_decrypt('decrypt', $lessonId);

            $lessons = CourseLesson::where('course_id', $courseId)->get();
            $selectedLesson = CourseLesson::where('id', $lessonId)->first();
            $datas = CourseLessonStep::where('course_lesson_id', $lessonId)->get();

            return view('pages.course.lessons', compact('courseId', 'lessonId', 'lessons', 'selectedLesson', 'datas'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function saveCourseLesson(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lesson' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $course_lesson = new CourseLesson;
                $course_lesson->course_id = $request->courseId;
                $course_lesson->lesson = $request->lesson;
                $course_lesson->save();

                $encrypt = encrypt_decrypt('encrypt', $request->courseId);
                $encryptModule = encrypt_decrypt('encrypt', $course_lesson->id);

                return redirect()->route('admin.course.lesson.all', ['courseId' => $encrypt, 'lessonId' => $encryptModule])->with('message', 'Lesson created successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function deleteLesson(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            } else {
                $id = encrypt_decrypt('decrypt', $request->id);
                $course = CourseLesson::where('id', $id)->first();
                CourseLesson::where('id', $id)->delete();

                return redirect()->back()->with('success', 'Lesson deleted successfully');
            }
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function lessonSectionCreate(Request $request)
    {
        try {
            $type = array_unique($request->type);

            if (array_has_dupes($request->queue)) {
                return errorMsg("Two sections cannot have the same serial order please check and change the serial order.");
            }

            // dd($request->all());

            if (isset($type) && count($type) > 0) {
                foreach ($type as $key => $value) {
                    if ($type[$key] == 'video') {
                        if (count($request->video_file) > 0) {
                            foreach ($request->video_file as $keyVideo => $valueVideo) {
                                $videoName = fileUpload($request->video_file[$keyVideo], 'uploads/course/lesson/video');

                                $lessonVideo = new CourseLessonStep;
                                $lessonVideo->type = 'video';
                                $lessonVideo->sort_order = $request->queue[$keyVideo] ?? -1;
                                $lessonVideo->title = $request->video_title[$keyVideo] ?? null;
                                $lessonVideo->description = $request->video_description[$keyVideo] ?? null;
                                $lessonVideo->details = $videoName;
                                $lessonVideo->prerequisite = 0;
                                $lessonVideo->course_lesson_id = $request->lessonId;
                                $lessonVideo->save();
                            }
                        }
                    } else if ($type[$key] == 'pdf') {
                        if (count($request->pdf_file) > 0) {
                            foreach ($request->pdf_file as $keyPdf => $valuePdf) {
                                $pdfName = fileUpload($request->pdf_file[$keyPdf], 'uploads/course/lesson/pdf');

                                $lessonPdf = new CourseLessonStep;
                                $lessonPdf->type = 'pdf';
                                $lessonPdf->sort_order = $request->queue[$keyPdf] ?? -1;
                                $lessonPdf->title = $request->pdf_title[$keyPdf] ?? null;
                                $lessonPdf->description = $request->pdf_description[$keyPdf] ?? null;
                                $lessonPdf->details = $pdfName;
                                $lessonPdf->prerequisite = 0;
                                $lessonPdf->course_lesson_id = $request->lessonId;
                                $lessonPdf->save();
                            }
                        }
                    } else if ($type[$key] == 'assignment') {
                        if (count($request->assignment) > 0) {
                            foreach ($request->assignment as $keyAssignment => $valueAssignment) {
                                $ChapterQuiz = new CourseLessonStep;
                                $ChapterQuiz->type = 'assignment';
                                $ChapterQuiz->sort_order = $request->queue[$keyAssignment] ?? -1;
                                $ChapterQuiz->title = $request->assignment_description[$keyAssignment] ?? null;
                                $ChapterQuiz->description = null;
                                $ChapterQuiz->details = null;
                                $ChapterQuiz->prerequisite = $request->prerequisite[$keyAssignment] ?? 0;
                                $ChapterQuiz->course_chapter_id = $request->chapter_id;
                                $ChapterQuiz->save();
                            }
                        }
                    } else if ($type[$key] == 'quiz') {
                        if (count($request->questions) > 0) {
                            foreach ($request->questions as $keyQ => $valueQ) {
                                $Step = new CourseLessonStep;
                                $Step->title = $request->quiz_description[$keyQ] ?? null;
                                $Step->sort_order = $request->queue[$keyQ] ?? -1;
                                $Step->type = 'quiz';
                                $Step->description = null;
                                $Step->passing = $request->quiz_passing_per_[$keyQ] ?? null;
                                $Step->prerequisite = $request->prerequisite[$keyQ] ?? 0;
                                $Step->course_chapter_id = $request->chapter_id;
                                $Step->save();
                                foreach ($valueQ as $keyQVal => $valueQVal) {
                                    $ChapterQuiz = new CourseLessonQuiz;
                                    $ChapterQuiz->title = $valueQVal['text'];
                                    $ChapterQuiz->type = 'quiz';
                                    $ChapterQuiz->chapter_id = $request->chapter_id;
                                    $ChapterQuiz->course_id = $request->courseID;
                                    $ChapterQuiz->step_id = $Step['id '];
                                    $ChapterQuiz->marks = $valueQVal['marks'] ?? 0;
                                    $ChapterQuiz->save();
                                    $quiz_id = CourseLessonQuiz::orderBy('id', 'DESC')->first();
                                    foreach ($valueQVal['options'] as $keyOp => $optionText) {
                                        $isCorrect = '0';
                                        if (isset($valueQVal['correct'])) {
                                            $isCorrect = ($valueQVal['correct'] == $keyOp) ? '1' : '0';
                                        }
                                        $option = new CourseLessonQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = $isCorrect;
                                        $option->created_date = date('Y-m-d H:i:s');
                                        $option->status = 1;
                                        $option->save();
                                    }
                                }
                            }
                        }
                    } else if ($type[$key] == 'survey') {
                        if (count($request->survey_question) > 0) {
                            foreach ($request->survey_question as $keyS => $valueQ) {
                                $Step = new CourseLessonStep;
                                $Step->title = $request->survey_description[$keyS] ?? null;
                                $Step->sort_order = $request->queue[$keyS] ?? -1;
                                $Step->type = 'survey';
                                $Step->description = null;
                                $Step->prerequisite = $request->prerequisite[$keyS] ?? 0;
                                $Step->course_chapter_id = $request->chapter_id;
                                $Step->save();
                                foreach ($valueQ as $keyQVal => $valueQVal) {
                                    $ChapterQuiz = new CourseLessonQuiz;
                                    $ChapterQuiz->title = $valueQVal['text'];
                                    $ChapterQuiz->type = 'survey';
                                    $ChapterQuiz->chapter_id = $request->chapter_id;
                                    $ChapterQuiz->course_id = $request->courseID;
                                    $ChapterQuiz->step_id = $Step['id '];
                                    $ChapterQuiz->save();
                                    $quiz_id = CourseLessonQuiz::orderBy('id', 'DESC')->first();
                                    foreach ($valueQVal['options'] as $keyOp => $optionText) {
                                        // dd($optionText);
                                        $option = new CourseLessonQuizOption;
                                        $option->quiz_id = $quiz_id->id;
                                        $option->answer_option_key = $optionText;
                                        $option->is_correct = '0';
                                        $option->created_date = date('Y-m-d H:i:s');
                                        $option->status = 1;
                                        $option->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return successMsg('Lesson saved successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}
