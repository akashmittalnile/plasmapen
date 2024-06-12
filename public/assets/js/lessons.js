let type_arr = [];
let htmlForm = ``;
let countForm = 0;
let questionCounter = 0;

$(document).on("click", "#addLessonSection", function () {
    let div_type = $('input[name="lessonsSections"]:checked').val();
    type_arr.push(div_type);
    if (div_type == "" || div_type == null) {
        $(".toastdesc")
            .text("Please select any section")
            .addClass("text-danger");
        launch_toast();
        return;
    }
    type_arr.push(div_type);
    $(".save-section-btn").removeClass("d-none");

    if (div_type == "video") {
        htmlForm = `<div class="add-course-form-item section-item">
                <div class="add-course-heading">
                    <div class="plas-course-text">
                        <h3>Video</h3>
                    </div>
                    <div class="add-course-action">
                        <a href="javascript:void(0)" class="btndelete dlt-div" data-type="video"> Delete Section</a>
                    </div>
                </div>
                <div class="add-course-content-section add-course-form">
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Video Title </h4>
                                <input type="text" class="form-control" name="video_title[${countForm}]" id="video-title-${countForm}" required placeholder="Enter title">
                            </div>
                        </div>
                        <input type="hidden" name="type[${countForm}]" id="type${countForm}" value="video" />
                        <input type="hidden" name="queue[${countForm}]" id="type${countForm}" value="${countForm}" />
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Upload Video </h4>
                                <input type="file" data-count="${countForm}" id="video-file-${countForm}" onchange="previewVideo(event)" required accept="video/mp4" class="form-control" name="video_file[${countForm}]">
                                <div class="Uploaded-group upload-file-item-${countForm} d-none">
                                    <h4>Uploaded Video</h4>
                                    <div class="upload-file-item0">
                                        <div class="upload-file-media">
                                            <video controls class="video-preview-${countForm} d-none">
                                                <source src="" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Video Decription</h4>
                                <textarea type="text" class="form-control" required id="video-description-${countForm}" name="video_description[${countForm}]" placeholder="Enter decription"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        countForm += 1;
    } else if (div_type == "pdf") {
        htmlForm = `<div class="add-course-form-item section-item">
                <div class="add-course-heading">
                    <div class="plas-course-text">
                        <h3>PDF</h3>
                    </div>
                    <div class="add-course-action">
                        <a href="javascript:void(0)" class="btndelete dlt-div" data-type="pdf"> Delete Section</a>
                    </div>
                </div>
                <div class="add-course-content-section add-course-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>PDF Title </h4>
                                <input type="text" class="form-control" name="pdf_title[${countForm}]" value="" placeholder="Enter title" id="pdf-title-${countForm}" required>
                            </div>
                        </div>
                        <input type="hidden" name="type[${countForm}]" id="type${countForm}" value="pdf" />
                        <input type="hidden" name="queue[${countForm}]" id="type${countForm}" value="${countForm}" />
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Upload PDF </h4>
                                <input type="file" data-count="${countForm}" id="pdf-file-${countForm}" onchange="previewPdf(event)" required class="form-control" name="pdf_file[${countForm}]" accept="application/pdf">
                                <div class="Uploaded-group upload-pdf-item-${countForm} d-none">
                                    <h4>Uploaded PDF</h4>
                                    <div class="upload-file-item0">
                                        <div class="upload-file-media">
                                            <a id="view-pdf-${countForm}" target="_black" class="d-none pdf-preview-${countForm}">
                                                <img src="${baseUrl}/public/assets/images/document-text.svg"/>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>PDF Decription</h4>
                                <textarea type="text" class="form-control" required id="pdf-description-${countForm}" name="pdf_description[${countForm}]" placeholder="Enter decription"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        countForm += 1;
    } else if (div_type == "quiz") {
        let oplength = $('.options .plas-answer-box .hidden'+countForm+questionCounter).length;
        htmlForm = `<div class="add-course-form-item section-item">
                <div class="add-course-heading">
                    <div class="plas-course-text">
                        <h3>Quiz</h3>
                    </div>
                    <div class="plas-questionnaire-ans mb-0" style="padding: 5px;">
                        <div class="plas-questionnaire-text">
                            <input type="number" class="form-control" placeholder="Enter passing percentage" name="quiz_passing[${countForm}]" value="" required>
                        </div>
                    </div>
                    <div class="add-course-action">
                        <a href="javascript:void(0)" class="btnAddQuestion add-question-create" id="addQuestion-${countForm}"> Add Question</a>
                        <a href="javascript:void(0)" class="btndelete dlt-div" data-type="quiz"> Delete Section</a>
                    </div>
                </div>

                <input type="hidden" name="type[${countForm}]" id="type${countForm}" value="quiz" />
                <input type="hidden" name="queue[${countForm}]" id="type${countForm}" value="${countForm}" />

                <div class="add-course-content-section questions-${countForm}">

                    <div class="question">
                        <div class="plas-edit-questionnaire-box">
                            <div class="plas-label">
                                <div class="plas-badge">Q</div>
                            </div>
                            <div class="plas-questionnaire-content">
                                <input type="text" class="form-control" placeholder="Enter question" name="question[${countForm}][${questionCounter}][text]" id="question-${countForm}-${questionCounter}" required>
                            </div>
                            <div class="plas-questionnaire-ans mb-0" style="padding: 5px; width: 15%;">
                                <div class="plas-questionnaire-text">
                                    <input type="number" class="form-control" placeholder="Enter marks" name="question[${countForm}][${questionCounter}][marks]" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="lp-answer-option-list">
                            <div class="options">
                                <div class="plas-answer-box">
                                    <input type="hidden" class="hidden${countForm}${questionCounter}" value="0">
                                    <div class="plas-questionnaire-ans">
                                        <div class="plas-ans-label">
                                            <div class="a-badge">A</div>
                                        </div>
                                        <div class="plas-questionnaire-text">
                                            <input type="text" class="form-control" placeholder="Type Here..." name="question[${countForm}][${questionCounter}][options][]" value="" required>
                                        </div>
                                        <div class="plas-answer-action-item">
                                            <div class="plas-btn-info">
                                                
                                            </div>
                                            <div class="plasradio1">
                                                <input checked type="radio" class="" name="question[${countForm}][${questionCounter}][correct]" id="answer-option-${oplength}-${questionCounter}-${countForm}" value="${oplength}" required>
                                                <label for="answer-option-${oplength}-${questionCounter}-${countForm}">&nbsp</label>
                                            </div>
                                            <div class="plas-add-questionnaire-tooltip">
                                                <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Select Correct Answer">
                                                    <img src="${baseUrl}/public/assets/images/info-icon.svg">
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
                            </div>
                            <a class="add-answer add-option mt-2" href="javascript:void(0)" id="addOption-${countForm}-${questionCounter}">Add Option</a>
                        </div>
                    </div>

                </div>
            </div>`;
        countForm += 1;
    } else if (div_type == "assignment") {
    } else if (div_type == "survey") {
    }

    $("#add-lesson-section").append(htmlForm);
});


const previewVideo = (event) => {
    let count = event.target.getAttribute("data-count")
    $(".video-preview-"+count).attr("src", URL.createObjectURL(event.target.files[0]));
    $(".video-preview-"+count).removeClass('d-none');
    $(".upload-file-item-"+count).removeClass('d-none');
}

const previewPdf = (event) => {
    let count = event.target.getAttribute("data-count")
    $(".pdf-preview-"+count).attr("href", URL.createObjectURL(event.target.files[0]));
    $(".pdf-preview-"+count).removeClass('d-none');
    $(".upload-pdf-item-"+count).removeClass('d-none');
};

$(document).on("click", ".dlt-div", function () {
    let type = $(this).attr("data-type");
    $(this).closest(".section-item").remove();
    type_arr = type_arr.filter(function (item) {
        return item != type;
    });
    if (type_arr.length == 0) {
        $(".save-section-btn").addClass("d-none");
    }
});

$(document).on("submit", "#lesson-section-form", function (e) {
    e.preventDefault();
    var form = $(this);
    let formData = new FormData(this);
    $.ajax({
        type: "post",
        url: form.attr("action"),
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#wait").removeClass('d-none')
            $("#save-lesson-section").addClass('d-none')
        },
        success: function (response) {
            if (response.status) {
                $(".toastdesc").text(response.message).addClass('text-success');
                launch_toast();
                setInterval(() => {
                    window.location.reload()
                }, 2000);
                return false;
            } else {
                $(".toastdesc").text(response.message).addClass('text-danger');
                launch_toast();
                return false;
            }
        },
        complete: function() {
            $("#save-lesson-section").removeClass('d-none')
            $("#wait").addClass('d-none')
        },
    });
});

$(document).on('click', '.add-option', function () {
    let id = ($(this).attr('id').split('-'));
    let oplength = $('.options .plas-answer-box .hidden'+id[1]+questionCounter).length;
    var op_html = `<div class="options">
            <div class="plas-answer-box">
                <input type="hidden" class="hidden${id[1]}${questionCounter}" value="0">
                <div class="plas-questionnaire-ans">
                    <div class="plas-ans-label">
                        <div class="a-badge">A</div>
                    </div>
                    <div class="plas-questionnaire-text">
                        <input type="text" class="form-control" placeholder="Type Here..." name="question[${id[1]}][${id[2] ?? questionCounter}][options][]" value="" required>
                    </div>
                    <div class="plas-answer-action-item">
                        <div class="plas-btn-info">
                            <button class="remove-btn remove-option">Remove</button>
                        </div>
                        <div class="plasradio1">
                            <input type="radio" class="" name="question[${id[1]}][${id[2] ?? questionCounter}][correct]"  id="answer-option-${oplength}-${id[2] ?? questionCounter}-${id[1]}" value="${oplength}" required>
                            <label for="answer-option-${oplength}-${id[2] ?? questionCounter}-${id[1]}">&nbsp</label>
                        </div>
                        <div class="plas-add-questionnaire-tooltip">
                            <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Select Correct Answer">
                                <img src="${baseUrl}/public/assets/images/info-icon.svg">
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
        </div>`;
    $(this).siblings('.options').append(op_html);
});

$(document).on('click', '.remove-option', function () {
    $(this).closest('.options').remove();
});

$(document).on('click', '.add-question-create', function () {
    let id = ($(this).attr('id').split('-'))[1];
    questionCounter++;
    let oplength = $('.options .plas-answer-box .hidden'+id+questionCounter).length;
    var html = `<div class="question">
            <div class="plas-edit-questionnaire-box">
                <div class="plas-label">
                    <div class="plas-badge">Q</div>
                </div>
                <div class="plas-questionnaire-content">
                    <input type="text" class="form-control" placeholder="Enter question" name="question[${id}][${questionCounter}][text]" id="question-${id}-${questionCounter}" required>
                </div>
                <div class="plas-questionnaire-ans mb-0" style="padding: 5px; width: 15%;">
                    <div class="plas-questionnaire-text">
                        <input type="number" class="form-control" placeholder="Enter marks" name="question[${id}][${questionCounter}][marks]" value="" required>
                    </div>
                </div>
            </div>
            <div class="lp-answer-option-list">
                <div class="options">
                    <div class="plas-answer-box">
                        <input type="hidden" class="hidden${id}${questionCounter}" value="0">
                        <div class="plas-questionnaire-ans">
                            <div class="plas-ans-label">
                                <div class="a-badge">A</div>
                            </div>
                            <div class="plas-questionnaire-text">
                                <input type="text" class="form-control" placeholder="Type Here..." name="question[${id}][${questionCounter}][options][]" value="" required>
                            </div>
                            <div class="plas-answer-action-item">
                                <div class="plas-btn-info">
                                    
                                </div>
                                <div class="plasradio1">
                                    <input checked type="radio" class="" name="question[${id}][${questionCounter}][correct]" id="answer-option-${oplength}-${questionCounter}-${id}" value="${oplength}" required>
                                    <label for="answer-option-${oplength}-${questionCounter}-${id}">&nbsp</label>
                                </div>
                                <div class="plas-add-questionnaire-tooltip">
                                    <div class="" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Select Correct Answer">
                                        <img src="${baseUrl}/public/assets/images/info-icon.svg">
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
                </div>
                <a class="add-answer add-option mt-2" href="javascript:void(0)" id="addOption-${id}-${questionCounter}">Add Option</a>
                <button class="remove-btn remove-question" style="font-weight: 600; font-size: 14px; text-transform: uppercase;">Remove Question</button>
            </div>
        </div>`;
    $('.questions-'+id).append(html);
});

$(document).on('click', '.remove-question', function () {
    $(this).closest('.question').remove();
});