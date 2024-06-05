@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Create Course</h2>
        </div>
        <div class="plas-search-filter wd40">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="{{ route('admin.course.list') }}" class="btn-pi">Back</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="javascript:void(0)" id="create-course-submit" class="btn-bl">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course-section">
        <div class="row">
            <div class="col-md-12">
                <div class="courses-form-section">
                    <div class="courses-form">
                        <form action="" id="course-create-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Course Title</h4>
                                        <input type="text" class="form-control" onchange="showData(event, 'title')" name="title" id="title" placeholder="Course title" value="">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Description</h4>
                                        <textarea type="text" class="form-control" onchange="showData(event, 'description')" id="description" name="description" placeholder="Course description"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Course Fees</h4>
                                        <input type="text" class="form-control" onchange="showData(event, 'fees')" id="fees" name="fees" placeholder="Course fees">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Upload Course Image</h4>
                                        <input type="file" onchange="loadImageFile(event)" class="form-control" name="image" accept="image/png, image/jpg, image/jpeg">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Prerequisite</h4>
                                        <select name="prerequisite" id="" class="form-control">
                                            <option value="">Select Course</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>Disclaimers & Introduction</h4>
                                        <input type="file" class="form-control" name="video" accept="video/mp4">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="added-course-card">
                                        <div class="added-course-card-image">
                                            <img id="show-image" src="{{ assets('assets/images/no-image.jpg') }}">
                                        </div>
                                        <div class="added-course-card-content">
                                            <h2 id="show-title">Title : Course Title</h2>
                                            <div id="show-fees" class="coursefee-text">$0</div>
                                            <p id="show-description">Course Description</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).on("click", "#create-course-submit", function() {
        $('#course-create-form').submit();
    });

    const loadImageFile = (event) => {
        $("#show-image").attr({src: URL.createObjectURL(event.target.files[0])});
    };

    const showData = (event, id) => {
        alert(id);
        $("#show-"+id).text($(this).val());
    };

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');

    $('#course-create-form').validate({
        rules: {
            title: {
                required: true,
            },
            description: {
                required: true,
            },
            fees: {
                required: true,
            },
            image: {
                required: true,
                filesize: 4
            },
            video: {
                required: true,
                filesize: 10
            },
        },
        messages: {
            title: {
                required: 'Please enter course title',
            },
            description: {
                required: 'Please enter course description',
            },
            fees: {
                required: 'Please enter course fees',
            },
            image: {
                required: 'Please upload course image',
            },
            video: {
                required: 'Please upload disclaimers & introduction',
            },
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            let formData = new FormData(form);
            $.ajax({
                type: 'post',
                url: form.action,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#wait").removeClass('d-none')
                    $("#login").addClass('d-none')
                },
                success: function(response) {
                    if (response.status) {
                        window.location = response.route;
                        return false;
                    } else {
                        $(".toastdesc").text(response.message).addClass('text-danger');
                        launch_toast();
                        return false;
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
                complete: function() {
                    $("#login").removeClass('d-none')
                    $("#wait").addClass('d-none')
                },
            })
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