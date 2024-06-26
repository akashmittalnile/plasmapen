@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/blog.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Blog Info</h2>
        </div>
        <div class="plas-search-filter wd50">
            <div class="row g-1">
                <div class="col-md-2">
                    <div class="form-group">
                        <a href="{{ route('admin.blog.list') }}" class="btn-pi">Back</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#Updateblog" class="btn-pi">Edit Blog</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openDeleteModal" class="btn-bl">Delete Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-section">
        <div class="row">
            <div class="@if(count($latestBlog) > 0) col-md-8 @else col-md-12 @endif">
                <div class="blog-details-card">
                    <h2>{{ $blog->title ?? 'NA' }}</h2>
                    <div class="blog-details-point-text">
                        <div class="blogby-text">By <span>Plasm Pen UK</span></div>
                        <div class="date-text">{{ date('m-d-Y h:iA', strtotime($blog->created_at)) }}</div>
                    </div>
                    <div class="blog-details-image owl-carousel owl-theme">
                        @forelse($imgs as $item)
                        <div class="item">
                            <div class="community-posts-media">
                                <a data-fancybox="" href="{{ assets('uploads/blog/'.$item->item_name) }}">
                                    <img src="{{ assets('uploads/blog/'.$item->item_name) }}">
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class='item'>
                            <div class='community-media'>
                                <img src="{{ assets('assets/images/no-image.jpg') }}">
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <div class="blog-details-content">
                        <p>{{ $blog->description ?? 'NA' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                @forelse($latestBlog as $val)
                <div class="blog-card">
                    <div class="blog-card-image">
                        @foreach($val->images as $name)
                        <div class='item'>
                            <div class='community-media'>
                                <a data-fancybox='' href="{{ assets('uploads/blog/'.$name->item_name) }}">
                                    <img src="{{ assets('uploads/blog/'.$name->item_name) }}">
                                </a>
                            </div>
                        </div>
                        @endforeach
                        <div class="Views-text">0 Views</div>
                    </div>
                    <div class="blog-card-content">
                        <h2>{{ $val->title ?? 'NA' }}</h2>
                        <div class="blog-card-point-text">
                            <div class="blogby-text">By <span>Plasm Pen UK</span></div>
                            <div class="date-text">{{ date('m-d-Y h:iA', strtotime($val->created_at)) }}</div>
                        </div>
                        <p>{{ $val->description ?? 'NA' }}</p>
                        <div class='blog-card-action-text'>
                            <a class='Addbtn' style='padding: 5px 46.5%;' href="{{route('admin.blog.info', encrypt_decrypt('encrypt', $val->id))}}">Info</a>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>

        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal lm-modal fade" id="openDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Are You Sure?') }}</h2>
                    <p>{{ translate('You want to delete this blog!') }}</p>
                    <form action="{{ route('admin.blog.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="hidden" name="id" id="blogId" value="{{ encrypt_decrypt('encrypt', $blog->id) }}">
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

<!-- Update blog -->
<div class="modal lm-modal fade" id="Updateblog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Update Blog</h2>
                    <div class="row">
                        <form action="{{ route('admin.blog.update') }}" id="update-blog-form" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $blog->title ?? '' }}" placeholder="Blog Title" id="update-title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" id="update-description" placeholder="Blog Description">{{ $blog->description ?? '' }}</textarea>
                                    <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $blog->id) }}">
                                    <input type="hidden" id="arrayOfImage" name="array_of_image" value="">
                                </div>
                            </div>

                            <div class="product-item-card">
                                <div class="card-header form-group" style="border-bottom: none;">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="dropzone" id="multipleImage">
                                            @foreach($imgs as $val)
                                            <div class="dz-preview dz-image-preview">
                                                <div class="dz-image">
                                                    <img src="{{ assets('uploads/blog/'.$val->item_name) }}" alt="{{ $val->item_name }}" />
                                                </div>
                                                <a href="{{ route('admin.uploaded-image-delete', ['id'=> encrypt_decrypt('encrypt', $val->id), 'type'=> 'blog']) }}" class="dz-remove dz-remove-image">Remove</a>
                                            </div>
                                            @endforeach
                                            <div class="dz-default dz-message">
                                                <span>Click once inside the box to upload an image
                                                    <br>
                                                    <small class="text-danger">Make sure the image size is less than 5 MB</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button type="submit" class="save-btn">Update Blog</button>
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
<script type="text/javascript">
    $('.blog-details-image').owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            1000: {
                items: 1
            }
        }
    });

    let arrOfImg = [];
    $(document).ready(function(){
        var existingImages = {!! json_encode($imgs)  !!};
        existingImages.forEach(function(image) {
            arrOfImg.push(image.name);
        });
        console.log(existingImages);
        let oplength = arrOfImg.length;
        if(oplength>0){
           $('.dz-default.dz-message').hide(); 
        } else $('.dz-default.dz-message').show();

        $("#update-blog-form").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                description: {
                    required: true,
                    minlength: 10
                },
            },
            messages: {
                title: {
                    required: "Please enter a product title",
                    minlength: "Product title must be at least 2 characters long"
                },
                description: {
                    required: "Please enter a product description",
                    minlength: "Product description must be at least 10 characters long"
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
                        // $("#create-course-submit").addClass('d-none')
                    },
                    success: function(response) {
                        if (response.status) {
                            $(".toastdesc").text(response.message).addClass('text-success');
                            launch_toast();
                            setInterval(() => {
                                window.location = response.route;
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
                        // $("#create-course-submit").removeClass('d-none')
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
            }
        });
    });

    Dropzone.options.multipleImage = {
        maxFilesize: 5,
        renameFile: function(file) {
            return file.name;
        },
        acceptedFiles: ".jpeg,.jpg,.png",
        timeout: 5000,
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        url: baseUrl+"/admin/image-upload?type=blog&id={{$blog->id}}",
        removedfile: function(file) {
            var name = file.upload.filename;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.image-delete") }}',
                data: {
                    filename: name,
                    type: 'blog',
                    id: "{{ $blog->id }}"
                },
                success: function(data) {
                    if (data.status) {
                        console.log("File deleted successfully!!");
                        if (data.key == 2) {
                            const inde = arrOfImg.indexOf(data.file_name);
                            if (inde > -1) {
                                arrOfImg.splice(inde, 1);
                                $("#arrayOfImage").val(JSON.stringify(arrOfImg));
                            }
                        }
                        let oplength = arrOfImg.length;
                        if (oplength > 0) {
                            $('.dz-default.dz-message').hide();
                        } else $('.dz-default.dz-message').show();
                    } else {
                        console.log("File not deleted!!");
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
            var fileRef;
            return (fileRef = file.previewElement) != null ?
                fileRef.parentNode.removeChild(file.previewElement) : void 0;
        },
        success: function(file, response) {
            if (response.key == 1) {
                arrOfImg.push(response.file_name);
                $("#arrayOfImage").val(JSON.stringify(arrOfImg));
                console.log(arrOfImg);
                file.upload.filename = response.file_name;
                let oplength = arrOfImg.length;
                if (oplength > 0) {
                    $('.dz-default.dz-message').hide();
                } else $('.dz-default.dz-message').show();
            }
        },
        error: function(file, response) {
            let oplength = arrOfImg.length;
            if (oplength > 0) {
                $('.dz-default.dz-message').hide();
            } else $('.dz-default.dz-message').show();
            console.log(file.previewElement);
            var fileRef;
            return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : null;
        }
    };
    console.log(arrOfImg);
</script>
@endpush