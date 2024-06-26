@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush
@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>{{ translate('Manage Course Lessons') }}</h2>
        </div>
        <div class="plas-search-filter wd40">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="{{ route('admin.course.list') }}" class="btn-pi">{{ translate('Back') }}</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <a href="javascript:void(0)" id="save-lesson-section" class="btn-bl save-section-btn">{{ translate('Save') }}</a>
                        <button class="btn-bl d-none" id="wait" type="button">Please Wait <img style="padding: 0px;" width="30" src="{{ assets('assets/images/spinner4.svg') }}" alt=""></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course-section">
        <div class="row">
            <div class="col-md-3">
                <div class="course-sidebar">
                    <h2>{{ translate('Course Lessons') }}</h2>
                    <div class="course-sidebar-content">
                        <div class="chapter-list">
                            @forelse($lessons as $key => $item)
                            <div class="lesson-item">
                                <a href="{{ route('admin.course.lesson.all', ['courseId' => encrypt_decrypt('encrypt', $courseId), 'lessonId' => encrypt_decrypt('encrypt', $item->id)]) }}">
                                    <div class="lesson-item-inner">
                                        <div class="lesson-icon"><img src="{{ assets('assets/images/book-square1.svg') }}"></div>
                                        <div class="lesson-text1">{{$item->lesson ?? 'NA'}}</div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="deleteLessonBtn" data-id="{{ encrypt_decrypt('encrypt', $item->id) }}"><img src="{{ assets('assets/images/close-circle.svg') }}"></a>
                            </div>
                            @empty
                            <div class="d-flex justify-content-center align-items-center flex-column" style="height: 60vh">
                                <div>
                                    <img width="200" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                </div>
                                <div class="mt-3" style="font-size: 1rem;">
                                    No Lessons Found
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="lesson-action">
                        <a class="add-lesson-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addLesson">{{ translate('Add Lesson') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="plas-addcourse-card">
                    <div class="add-Course-form">

                        @forelse($datas as $key => $data)
                        @if($data->type == 'video')
                        <div class="add-course-form-item">
                            <div class="add-course-heading">
                                <div class="plas-course-text">
                                    <h3>Video</h3>
                                </div>
                                <div class="add-course-action">
                                    <a href="javascript:void(0)" class="btndelete delete-section" data-id="{{ $data->id }}"> Delete Section</a>
                                </div>
                            </div>
                            <div class="add-course-content-section add-course-form">
                                <form action="{{ route('admin.course.lesson.section.update') }}" method="post" enctype="multipart/form-data"> @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Video Title </h4>
                                                <input type="text" class="form-control" name="title" value="{{ $data->title ?? 'NA' }}" id="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Upload Video </h4>
                                                <input type="file" data-count="{{$data->id}}" id="video-file-{{$data->id}}" onchange="previewVideo(event)"  accept="video/mp4" class="form-control" name="file">
                                                <div class="Uploaded-group upload-file-item-{{$data->id}} @if(!isset($data->details)) d-none @endif">
                                                    <h4>Uploaded Video</h4>
                                                    <div class="upload-file-item0">
                                                        <div class="upload-file-media">
                                                            <video controls class="video-preview-{{$data->id}} @if(!isset($data->details)) d-none @endif">
                                                                <source src="{{ assets('uploads/course/lesson/video/'.$data->details) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Video Decription</h4>
                                                <textarea type="text" class="form-control" name="description" required placeholder="Enter description">{{ $data->description ?? 'NA' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button class="updatebtn" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @elseif($data->type == 'pdf')
                        <div class="add-course-form-item">
                            <div class="add-course-heading">
                                <div class="plas-course-text">
                                    <h3>PDF</h3>
                                </div>
                                <div class="add-course-action">
                                    <a href="javascript:void(0)" class="btndelete delete-section" data-id="{{ $data->id }}"> Delete Section</a>
                                </div>
                            </div>
                            <div class="add-course-content-section add-course-form">
                                <form action="{{ route('admin.course.lesson.section.update') }}" method="post" enctype="multipart/form-data"> @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>PDF Title </h4>
                                                <input type="text" class="form-control" name="title" value="{{ $data->title ?? 'NA' }}" id="PDFtitle" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>Upload PDF </h4>
                                                <input type="file" data-count="{{$data->id}}" id="pdf-file-{{$data->id}}" onchange="previewPdf(event)" class="form-control" name="file" accept="application/pdf">
                                                <div class="Uploaded-group upload-pdf-item-{{$data->id}} @if(!isset($data->details)) d-none @endif">
                                                    <h4>Uploaded PDF</h4>
                                                    <div class="upload-file-item0">
                                                        <div class="upload-file-media">
                                                            <a id="view-pdf-{{$data->id}}" href="{{ assets('uploads/course/lesson/pdf/'.$data->details) }}" target="_black" class="@if(!isset($data->details)) d-none @endif pdf-preview-{{$data->id}}">
                                                                <img src="{{ assets('assets/images/document-text.svg') }}" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h4>PDF Decription</h4>
                                                <textarea type="text" class="form-control" required name="description" placeholder="PDF Decription" data-gramm="false" wt-ignore-input="true">{{ $data->description ?? 'NA' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button class="updatebtn" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @elseif($data->type == 'quiz')
                        <div class="add-course-form-item">
                            <div class="add-course-heading">
                                <div class="plas-course-text">
                                    <h3>Quiz</h3>
                                </div>
                                <div class="plas-questionnaire-ans mb-0" style="padding: 5px;">
                                    <div class="plas-questionnaire-text">
                                        <input type="number" class="form-control" placeholder="Enter passing percentage" name="quiz_marks" value="{{ $data->passing ?? 33 }}" required>
                                    </div>
                                </div>
                                <div class="add-course-action">
                                    <a href="javascript:void(0)" class="btnAddQuestion"> Add Question</a>
                                    <a href="javascript:void(0)" class="btndelete delete-section" data-id="{{ $data->id }}"> Delete Section</a>
                                </div>
                            </div>

                            <div class="add-course-content-section">
                                @foreach ($data->quiz as $quiz)
                                <div class="plas-edit-questionnaire-box">
                                    <div class="plas-label">
                                        <div class="plas-badge">Q</div>
                                    </div>
                                    <div class="plas-questionnaire-content">
                                        <input type="text" class="form-control" placeholder="Enter question" name="question" value="{{ $quiz->title ?? 'NA' }}">
                                    </div>
                                    <div class="plas-questionnaire-ans mb-0" style="padding: 5px; width: 15%;">
                                        <div class="plas-questionnaire-text">
                                            <input type="number" class="form-control" placeholder="Enter marks" name="question_marks" value="{{ $quiz->marks ?? 0 }}" required style="padding: 6px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="lp-answer-option-list">
                                    <?php $s_no = 'A'; ?>
                                    @foreach ($quiz->quizOption as $option)
                                    <div class="plas-answer-box">
                                        <div class="plas-questionnaire-ans">
                                            <div class="plas-ans-label">
                                                <div class="a-badge">{{ $s_no }}</div>
                                            </div>
                                            <div class="plas-questionnaire-text">
                                                <input type="text" class="form-control" placeholder="Type Here..." name="" value="{{ $option->answer ?? 'NA' }}">
                                            </div>
                                            <div class="plas-answer-action-item">
                                                <div class="plas-btn-info">
                                                    <button class="update-btn">Update</button>
                                                    <button class="remove-btn">Remove</button>
                                                </div>
                                                <div class="plasradio1">
                                                    <input type="radio" @if($option->is_correct) checked @endif name="radio{{$quiz->id}}" id="quiz{{$option->id}}">
                                                    <label for="quiz{{$option->id}}">&nbsp;</label>
                                                </div>
                                                <div class="plas-add-questionnaire-tooltip">
                                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Select Correct Answer">
                                                        <img src="{{ assets('assets/images/info-icon.svg') }}">
                                                    </div>
                                                    <script>
                                                        $(function() {
                                                            $('[data-bs-toggle="tooltip"]').tooltip();
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $s_no++; ?>
                                    @endforeach
                                    <a class="add-answer mt-3" href="javascript:void(0)">Add option</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @elseif($data->type == 'assignment')
                        <div class="add-course-form-item">
                            <div class="add-course-heading">
                                <div class="plas-course-text">
                                    <h3>Assignment</h3>
                                </div>
                                <div class="add-course-action">
                                    <a href="javascript:void(0)" class="btndelete delete-section" data-id="{{ $data->id }}"> Delete Section</a>
                                </div>
                            </div>
                            @foreach($data->quiz as $val)
                            <div class="add-course-content-section add-course-form">
                                <div class="assignment-section">
                                    <div class="plas-questionnaire-ans d-block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <h4>Assignment Title </h4>
                                                    <input type="text" class="form-control" name="assignment_title" value="{{ $val->title ?? 'NA' }}" placeholder="Enter title" id="assignment-title" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <h4>Assignment Decription</h4>
                                                    <textarea type="text" class="form-control" required id="assignment-description" name="assignment_description" placeholder="Enter decription">{{ $val->description ?? 'NA' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="add-answer assignment-add-optin mt-2" href="javascript:void(0)" id="assignment-addOption">Add Option</a>
                            </div>
                            @endforeach
                            
                        </div>
                        @elseif($data->type == 'survey')
                        <div class="add-course-form-item">
                            <div class="add-course-heading">
                                <div class="plas-course-text">
                                    <h3>Survey</h3>
                                    <!-- <div class="heading-checkbox-list">
                                        <ul>
                                            <li>
                                                <div class="plascheckbox">
                                                    <input type="checkbox" id="Optional" name="">
                                                    <label for="Optional">
                                                        Optional
                                                    </label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="plascheckbox">
                                                    <input type="checkbox" id="Mandatory" name="">
                                                    <label for="Mandatory">
                                                        Mandatory
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div>
                                <div class="add-course-action">
                                    <a href="javascript:void(0)" class="btnAddQuestion"> Add Question</a>
                                    <a href="javascript:void(0)" class="btndelete delete-section" data-id="{{ $data->id }}"> Delete Section</a>
                                </div>
                            </div>
                            <div class="add-course-content-section">
                                @foreach ($data->quiz as $quiz)
                                <div class="plas-edit-questionnaire-box">
                                    <div class="plas-label">
                                        <div class="plas-badge">Q</div>
                                    </div>
                                    <div class="plas-questionnaire-content">
                                        <input type="text" class="form-control" placeholder="Enter question" name="" value="{{ $quiz->title ?? 'NA' }}">
                                    </div>
                                </div>

                                <div class="plas-answer-option-list">
                                    <?php $s_no = 'A'; ?>
                                    @foreach ($quiz->quizOption as $option)
                                    <div class="plas-questionnaire-ans">
                                        <div class="plas-ans-label">
                                            <div class="a-badge">{{ $s_no }}</div>
                                        </div>
                                        <div class="plas-questionnaire-text">
                                            <input type="text" class="form-control" placeholder="Type Here..." name="" value="{{ $option->answer ?? 'NA' }}">
                                        </div>
                                        <div class="plas-answer-action-item">
                                            <div class="plas-btn-info">
                                                <button class="update-btn">Update</button>
                                                <button class="remove-btn">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $s_no++; ?>
                                    @endforeach
                                    <a class="add-answer mt-3" href="javascript:void(0)">Add option</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @empty
                        @endforelse

                        @if(count($lessons)>0)
                        <form action="{{ route('admin.course.lesson.section.create') }}" method="POST" class="my-3" id="lesson-section-form"> @csrf
                            <div id="add-lesson-section">

                            </div>
                            <input type="hidden" name="courseId" value="{{ $courseId }}" />
                            <input type="hidden" name="lessonId" value="{{ $lessonId }}" />
                        </form>

                        <div class="addcourse-questionnairetype-item">
                            <div class="addcourse-questionnairetype-list">
                                <ul>
                                    <li>
                                        <div class="questionnairetype-radio">
                                            <input type="radio" id="Video" checked name="lessonsSections" value="video">
                                            <label for="Video">
                                                Video
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="questionnairetype-radio">
                                            <input type="radio" id="PDF" name="lessonsSections" value="pdf">
                                            <label for="PDF">
                                                PDF
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="questionnairetype-radio">
                                            <input type="radio" id="Quiz" name="lessonsSections" value="quiz">
                                            <label for="Quiz">
                                                Quiz
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="questionnairetype-radio">
                                            <input type="radio" id="Assignment" name="lessonsSections" value="assignment">
                                            <label for="Assignment">
                                                Assignment
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="questionnairetype-radio">
                                            <input type="radio" id="Survey" name="lessonsSections" value="survey">
                                            <label for="Survey">
                                                Survey
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                                <button class="add-answer" id="addLessonSection">Add Section</button>
                            </div>
                        </div>
                        @else
                        <div class="d-flex justify-content-center align-items-center flex-column" style="height: 60vh">
                            <div>
                                <img width="200" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                            </div>
                            <div class="mt-3" style="font-size: 1rem;">
                                No Lessons Found
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lesson -->
<div class="modal lm-modal fade" id="addLesson" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Add Lesson') }}</h2>
                    <form action="{{ route('admin.course.lesson.save') }}" method="POST" id="addLessonForm">@csrf
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="lesson" class="form-control" placeholder="Enter lesson name">
                                    <input type="hidden" name="courseId" value="{{ $courseId }}">
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="save-btn">{{ translate('Add') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Lesson -->
<div class="modal lm-modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Are You Sure?') }}</h2>
                    <p>{{ translate('You want to delete this lesson!') }}</p>
                    <form action="{{ route('admin.course.lesson.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="hidden" name="id" id="deleteLessonId" value="">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="save-btn">{{ translate('Yes! Delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Lesson -->
@if(isset($lessonId))
<div class="modal lm-modal fade" id="deleteSectionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Are You Sure?') }}</h2>
                    <p>{{ translate('You want to delete this section!') }}</p>
                    <form action="{{ route('admin.course.lesson.section.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="hidden" name="lesson_id" id="sectionLessonId" value="{{ $lessonId }}">
                                <input type="hidden" name="section_id" id="sectionId" value="">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="save-btn">{{ translate('Yes! Delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('js')
<script src="{{ assets('assets/js/lessons.js') }}" type="text/javascript"></script>
<script>
    $(document).on("click", "#save-lesson-section", function() {
        $('#lesson-section-form').submit();
    });

    $(document).on("click", ".deleteLessonBtn", function() {
        $("#deleteLessonId").val($(this).data('id'));
        $("#deleteLessonModal").modal("show");
    });

    $(document).on("click", ".delete-section", function() {
        $("#sectionId").val($(this).data('id'));
        $("#deleteSectionModal").modal("show");
    });

    $('#addLessonForm').validate({
        rules: {
            lesson: {
                required: true,
            },
        },
        messages: {
            lesson: {
                required: 'Enter lesson name',
            },
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            form.submit();
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
</script>
@endpush