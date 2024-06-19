@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/blog.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
<style>
    .dropzone .dz-message .dz-button {
        color: black !important;
        font-size: 15px;
    }

    .dropzone .dz-preview .dz-remove {
        color: black !important;
    }
</style>
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Blogs</h2>
        </div>
        <div class="plas-search-filter wd60">
            <div class="row g-1">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="date" id="select-date" class="form-control" name="date">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group search-form-group">
                        <input id="searchInput" type=" text" class="form-control" name="search" placeholder="Search by title">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Accountapproval-btn" data-bs-toggle="modal" data-bs-target="#AddNewblog">Add New Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-section">
        <div class="row">
            <div class="col-md-4">
                <!-- <div class="blog-card">
                    <div class="blog-card-image">
                        <img src="{{ assets('assets/images/b1.webp') }}">
                        <div class="Views-text"> 19.k Views</div>
                    </div>
                    <div class="blog-card-content">
                        <h2>Non-Surgical Solutions for Common Skin Concerns</h2>
                        <div class="blog-card-point-text">
                            <div class="blogby-text">By <span>Plasm Pen UK</span></div>
                            <div class="date-text">Apr 22,2024</div>
                        </div>
                        <p>Whether you’re starting to notice the fine lines and wrinkles that come with ageing, or you are struggling to hide acne scars, Plasma Pen and CoolJet can help. Though cosmetic</p>

                        <a class="deletebtn" href=""><img src="{{ assets('assets/images/trash.svg') }}"> Delete</a>
                    </div>
                </div> -->
            </div>
            <div class="course-section">
                <div class="row" id="appendData">

                </div>
                <div class="plas-table-pagination">
                    <ul class="plas-pagination" id="appendPagination">
                    </ul>
                </div>
            </div>


        </div>
    </div>
</div>

<!--   Add New Blog -->
<div class="modal lm-modal fade" id="AddNewblog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Add New Blog</h2>
                    <div class="row">
                        <form action="{{ route('admin.blog.store') }}" method="post" enctype='multipart/form-data' id="add-blog-form">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" placeholder="Headline">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="dropzone" id="myDropzone"></div>
                                    <!-- <input type="file" name="image" class="form-control"> -->
                                    <input type="hidden" id="arrayOfImage" name="blog_images">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" placeholder="Type your description"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                    <button type="submit" class="save-btn">Post</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--   Edit Blog -->
<div class="modal lm-modal fade" id="EditNewblog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Update Blog</h2>
                    <div class="row">
                        <form action="{{ route('admin.blog.update') }}" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="title" id="update-title" class="form-control" placeholder="Headline">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- <input type="file" name="image" id="update-image" class="form-control"> -->
                                    <div class="dropzone" id="myDropzoneUpdate"></div>
                                    <input type="hidden" id="arrayOfImageUpdate" name="blog_images_update">
                                    <input type="text" name="removed_files" id="removed_files" style="height:1px !important;opacity:0 !important;position: absolute;  z-index: -1;">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" id="update-description" class="form-control" placeholder="Type your description"></textarea>
                                    <input type="hidden" name="id" id="blog-id-update">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                    <button type="submit" class="save-btn">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                                <input type="hidden" name="id" id="blogId">
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
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    // dropzone for adding image adds here....
    let arrOfImg = [];
    Dropzone.options.myDropzone = {
        dictDefaultMessage: 'Drop files here to upload',
        maxFilesize: 1,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        maxFiles: 5,
        acceptedFiles: ".jpeg,.jpg,.png,.mp3",
        timeout: 5000,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        url: "{{ route('admin.image-upload') }}",
        addRemoveLinks: true,
        removedfile: function(file) {
            var name = file.upload.filename;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: 'POST',
                url: "{{ route('admin.image-delete') }}",
                data: {
                    filename: name
                },
                success: function(data) {
                    const inde = arrOfImg.indexOf(data);
                    if (inde > -1) {
                        arrOfImg.splice(inde, 1);
                        $("#arrayOfImage").val(JSON.stringify(arrOfImg));
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
            // if (response.key == 1) {
            arrOfImg.push(response);
            $("#arrayOfImage").val(JSON.stringify(arrOfImg));
            // }
        },
        error: function(file, response) {
            return false;
        }
    };
    console.log(arrOfImg);


    function removeImageExist(id) {
        removeFiles.push(id);
        $(`#removeImageExist${id}`).remove();
        $("#removed_files").val(removeFiles.join(","));
    }

    // dropzone for update image starts here....

    var existing_images_loaded = 0;
    var arrOfImgUpdate = []
    var removeFiles = [];

    function initializeDropzoneUpdate(BlogImages) {
        // Check if the Dropzone instance exists and destroy it
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(instance => {
                if (instance.element.id === "myDropzoneUpdate") {
                    instance.destroy();
                }
            });
        }

        // Initialize a new Dropzone instance
        var myDropzoneUpdate = new Dropzone("#myDropzoneUpdate", {
            dictDefaultMessage: 'Drag and drop an image here or click to select one',
            maxFilesize: 1,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            maxFiles: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.mp3",
            timeout: 5000,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('admin.image-upload') }}",
            addRemoveLinks: true,
            removedfile: function(file) {
                if (existing_images_loaded != 0) {
                    $(".dz-message").hide();
                } else {
                    $(".dz-message").show();
                }
                var name = file.upload ? file.upload.filename : file.name;
                if (file.name) {
                    removeImageExist(file.id);
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    type: 'POST',
                    url: "{{ route('admin.image-delete') }}",
                    data: {
                        filename: name
                    },
                    success: function(data) {
                        const inde = arrOfImgUpdate.indexOf(data);
                        if (inde > -1) {
                            arrOfImgUpdate.splice(inde, 1);
                            $("#arrayOfImageUpdate").val(JSON.stringify(arrOfImgUpdate));
                        }
                        console.log(arrOfImgUpdate);
                        existing_images_loaded -= 1;
                        if (existing_images_loaded != 0) {
                            $(".dz-message").hide();
                        } else {
                            $(".dz-message").show();
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
                arrOfImgUpdate.push(response);
                $("#arrayOfImageUpdate").val(JSON.stringify(arrOfImgUpdate));
            },
            error: function(file, response) {
                return false;
            },
            init: function() {
                var existingImages = BlogImages;
                existing_images_loaded = existingImages.length;
                existingImages.forEach(function(image) {
                    var mockFile = {
                        name: image.name,
                        id: image.id
                    };
                    this.displayExistingFile(mockFile, image.path);
                    mockFile.serverId = image.id;
                }, this); // Pass the context of Dropzone instance
            }
        });
        console.log(arrOfImgUpdate);
    }


    // dropzone for update image ends here....

    $(document).on("click", ".deletebtn", function() {
        $("#blogId").val($(this).data('id'));
        $("#openDeleteModal").modal("show");
    });

    $(document).on("click", ".editbtn", function() {
        let id = $(this).data('id');

        $.ajax({
            type: 'get',
            url: baseUrl +
                "/admin/blog/detail/" +
                id,
            dataType: 'json',
            success: function(result) {
                if (result.status) {
                    $("#update-title").val(result.data.title);
                    $("#update-description").val(result.data.description);
                    $("#show-image-update").attr("src", result.data.image);
                    $("#blog-id-update").val(result.data.id);
                    initializeDropzoneUpdate(result.data.images_arr)
                    $("#EditNewblog").modal("show");
                    console.log(result.data.images_arr);
                } else {

                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            },
        });

    });
    $(document).ready(function() {
        const getList = (page, date = null, status = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.blog.list') }}",
                data: {
                    page,
                    date,
                    status
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
                        $("#appendData").html(result.data.html);
                        $("#appendPagination").html('');
                        if (result.data.lastPage != 1) {
                            let paginate = `<li class="${result.data.currentPage==1 ? 'disabled' : ''}" id="example_previous">
                                    <a href="javascript:void(0)" data-page="${result.data.currentPage-1}" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                </li>`;
                            for (let i = 1; i <= result.data.lastPage; i++) {
                                paginate += `<li class="${result.data.currentPage==i ? 'active' : ''}">
                                        <a href="javascript:void(0)" data-page="${i}" class="page-link">${i}</a>
                                    </li>`;
                            }
                            paginate += `<li class="${result.data.currentPage==result.data.lastPage ? 'disabled next' : 'next'}" id="example_next">
                                        <a href="javascript:void(0)" data-page="${result.data.currentPage+1}" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                    </li>`;
                            $("#appendPagination").append(paginate);
                        }
                    } else {
                        let html = `<div class="d-flex justify-content-center align-items-center flex-column" style="height: 60vh">
                                    <div>
                                        <img width="200" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                    </div>
                                    <div class="mt-3" style="font-size: 1rem;">
                                        No Data Found
                                    </div>
                                </div>`;
                        $("#appendData").html(html);
                        $("#appendPagination").html('');
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
            });
        };
        getList(1);
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            getList($(this).data('page'));
        })
        $(document).on('keyup', "#searchInput", function() {
            let status = $("input[name='status']:checked").val();
            let date = $("#select-date").val();
            getList($(this).data('page'), date, status);
        });
        $(document).on('change', "#select-date", function() {
            let date = $(this).val();
            getList($(this).data('page'), date, status);
        });
    })

    $(document).ready(function() {
        $("#add-blog-form").validate({
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
                image: {
                    required: "Please upload a product image",
                    extension: "Only image files (jpg, jpeg, png, gif) are allowed"
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                if (arrOfImg.length == 0) {
                    $(".toastdesc").text('Please add atleast one Image').addClass('text-success');
                    launch_toast();
                    return false;
                }
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
                                window.location.reload();
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
</script>
@endpush