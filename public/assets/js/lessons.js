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
                        <input type="hidden" name="type[${countForm}]" id="type" value="video" />
                        <input type="hidden" name="queue[${countForm}]" id="type" value="${countForm}" />
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
                        <input type="hidden" name="type[${countForm}]" id="type" value="pdf" />
                        <input type="hidden" name="queue[${countForm}]" id="type" value="${countForm}" />
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
