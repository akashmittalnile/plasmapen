@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>{{ translate('Edit Course') }}</h2>
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
                        <a href="javascript:void(0)" id="create-course-submit" class="btn-bl">{{ translate('Update') }}</a>
                        <button class="btn-bl d-none" id="wait" type="button">Please Wait <img style="padding: 0px;" width="30" src="{{ assets('assets/images/spinner4.svg') }}" alt=""></button>
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
                        <form action="{{ route('admin.course.update.store') }}" id="course-create-form">@csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>{{ translate('Course Title') }}</h4>
                                        <input type="text" class="form-control" onchange="showData(event, 'title')" name="title" id="title" placeholder="Course title" value="{{ $data->title ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>{{ translate('Description') }}</h4>
                                        <textarea type="text" class="form-control" onchange="showData(event, 'description')" id="description" name="description" placeholder="Course description">{{ $data->description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>{{ translate('Course Fees') }}</h4>
                                        <input type="text" class="form-control" onchange="showData(event, 'fees')" id="fees" name="fees" placeholder="Course fees" value="{{ $data->course_fee ?? '' }}">
                                        <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>{{ translate('Upload Course Image') }}</h4>
                                        <input type="file" onchange="loadImageFile(event)" class="form-control" name="image" accept="image/png, image/jpg, image/jpeg">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>{{ translate('Prerequisite') }}</h4>
                                        <select name="prerequisite" id="" class="form-control text-capitalize">
                                            <option value="">Select Course</option>
                                            @foreach($course as $val)
                                            <option @if(isset($data->prerequisite) && ($data->prerequisite == $val->id)) selected @endif value="{{ $val->id }}">{{ $val->title ?? 'NA' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="@if(isset($data->video)) col-md-11 @else col-md-12 @endif">
                                    <div class="form-group">
                                        <h4>{{ translate('Disclaimers & Introduction') }}</h4>
                                        <input type="file" class="form-control" name="video" accept="video/mp4">
                                    </div>
                                </div>

                                @if(isset($data->video))
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <a href="{{ assets('uploads/course/video/'.$data->video) }}" id="uploaded-video" data-fancybox="">
                                            <img width="80" src="{{ assets('assets/images/image-icon.png') }}" alt="{{$data->video}}">
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-12">
                                    <div class="added-course-card">
                                        <div class="added-course-card-image">
                                            <img id="show-image" src="{{ isset($data->image) ? assets('uploads/course/image/'.$data->image) : assets('assets/images/no-image.jpg') }}">
                                        </div>
                                        <div class="added-course-card-content">
                                            <h2>Title : <span id="show-title">{{ $data->title ?? '' }}</span></h2>
                                            <div class="coursefee-text">$<span id="show-fees">{{ $data->course_fee ?? '' }}</span></div>
                                            <p id="show-description">{{ $data->description ?? '' }}</p>
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
        $("#show-image").attr({
            src: URL.createObjectURL(event.target.files[0])
        });
    };

    const showData = (event, id) => {
        $("#show-" + id).text($("#" + id).val());
    };

    $.validator.addMethod('filesize', function(value, element, param) {
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
                filesize: 4
            },
            video: {
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
                    $("#create-course-submit").addClass('d-none')
                },
                success: function(response) {
                    if (response.status) {
                        $(".toastdesc").text(response.message).addClass('text-success');
                        launch_toast();
                        setInterval(() => {
                            window.location = response.route
                        }, 2000);
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
                    $("#create-course-submit").removeClass('d-none')
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