@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Community </h2>
        </div>
        <div class="plas-search-filter wd40">
            <div class="row g-1">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Accountapproval-btn" href="{{ route('admin.community.list') }}">Back</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Accountapproval-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#EditCommunity">Edit</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="Delete-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openDeleteModal">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="community-section">
        <div class="row">
            <div class="col-md-3">
                <div class="community-sidebar">
                    <h2>Member List</h2>
                    <div class="community-sidebar-Memberlist" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">

                        @forelse($community->communityFollower as $val)
                        <div class="community-sidebar-Memberitem">
                            <div class="community-sidebar-Memberitem-image">
                                <img src="{{ isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg') }}">
                            </div>
                            <div class="community-sidebar-Memberitem-content">
                                <h5>{{ $val->user->name ?? 'NA' }}</h5>
                                <p>Joined On {{ date('m-d-Y', strtotime($val->created_at)) }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="sidebar-member-item" style="border: none;">
                            <div class="mx-auto">
                                No followers
                            </div>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="community-detail-card">
                    <div class="community-detail-image owl-carousel owl-theme">
                        @forelse($imgs as $val)
                        <div class='item'>
                            <div class='community-media'>
                                <a data-fancybox='' href="{{ assets("uploads/community/$val->item_name") }}">
                                    <img src="{{ assets("uploads/community/$val->item_name") }}">
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
                    <div class="community-detail-content">
                        <h2>{{ $community->name ?? 'NA' }}</h2>
                        <p>{{ $community->description ?? 'NA' }}</p>

                        <div class="community-card-point-text">
                            <div class="blogby-text">Total Posts <span>{{ count($community->communityPost) }}</span></div>
                            <div class="date-text">Created on <span>{{ date('m-d-Y h:iA') }}</span></div>
                        </div>
                        <div class="community-detail-member-item">
                            <div class="community-detail-member-info">
                                @forelse($follow as $key => $val)
                                <span class="community-detail-member-image image{{$key+1}}">
                                    <img src="{{ isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </span>
                                @empty
                                <span class="community-detail-member-image image1">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </span>
                                @endforelse
                            </div>
                            <p>{{ count($community->communityFollower) }} Member Follows</p>
                        </div>

                        <div class="community-switch-toggle">
                            <h4>Mark as @if($community->status == 1) Active @else Inactive @endif</h4>
                            <div class="community-switch-toggle-content">
                                <p>Inactive</p>
                                <div class="">
                                    <label class="toggle" for="myToggle">
                                        <input data-id="{{encrypt_decrypt('encrypt', $community->id)}}" class="toggle__input" type="checkbox" name="status" @if($community->status==1) checked @endif type="checkbox" id="myToggle">
                                        <div class="toggle__fill"></div>
                                    </label>
                                </div>
                                <p>Active</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="plas-filter-section">
                    <div class="plas-filter-heading">
                        <h2>Posts</h2>
                    </div>
                    <div class="plas-search-filter wd60">
                        <div class="row g-1">
                            <div class="col-md-8">
                                <div class="form-group search-form-group">
                                    <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by title">
                                    <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <a class="Accountapproval-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#AddNewPost">Post On Community</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
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
</div>

<!-- Delete -->
<div class="modal lm-modal fade" id="openDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Are You Sure?') }}</h2>
                    <p>{{ translate('You want to delete this community!') }}</p>
                    <form action="{{ route('admin.community.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $community->id) }}">
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

<!--   Edit Community -->
<div class="modal lm-modal fade" id="EditCommunity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Edit Community</h2>
                    <form action="{{ route('admin.community.update') }}" id="update-community-form" method="post" enctype='multipart/form-data'>@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" value="{{ $community->name ?? '' }}" placeholder="Community Name">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="description" placeholder="Community Description">{{ $community->description }}</textarea>
                                    <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $community->id) }}">
                                </div>
                            </div>

                            <div class="product-item-card">
                                <div class="card-header form-group" style="border-bottom: none;">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="dropzone" id="multipleImage">
                                            @foreach($imgs as $val)
                                            <div class="dz-preview dz-image-preview">
                                                <div class="dz-image">
                                                    <img src="{{ assets('uploads/community/'.$val->item_name) }}" alt="{{ $val->item_name }}" />
                                                </div>
                                                <a href="{{ route('admin.uploaded-image-delete', ['id'=> encrypt_decrypt('encrypt', $val->id), 'type'=> 'community']) }}" class="dz-remove dz-remove-image">Remove</a>
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
                                    <button class="save-btn" type="submit">Update Community</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--   Add New Community -->
<div class="modal lm-modal fade" id="AddNewPost" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Add New Post</h2>
                    <form action="{{ route('admin.community.post.store') }}" id="add-post-form" method="post" enctype='multipart/form-data'>@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title" placeholder="Post Title">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="description" placeholder="Post Description"></textarea>
                                </div>
                            </div>

                            <div class="product-item-card">
                                <div class="card-header form-group" style="border-bottom: none;">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="dropzone" id="multipleImagePost">
                                            <div class="dz-default dz-message">
                                                <span>Click once inside the box to upload an image
                                                    <br>
                                                    <small class="text-danger">Make sure the image size is less than 5 MB</small>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="arrayOfImagePost" name="array_of_image_post" value="">
                                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button class="save-btn" type="submit">Save & Published Post</button>
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
<script>
    $('.community-detail-image').owlCarousel({
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
    $(document).ready(function() {
        var existingImages = {!! json_encode($imgs)  !!};
        existingImages.forEach(function(image) {
            arrOfImg.push(image.name);
        });
        console.log(existingImages);
        let oplength = arrOfImg.length;
        if(oplength>0){
           $('#update-community-form .dz-default.dz-message').hide(); 
        } else $('#update-community-form .dz-default.dz-message').show();

        $("#update-community-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                description: {
                    required: true,
                    minlength: 10
                },
            },
            messages: {
                name: {
                    required: "Please enter a community name",
                    minlength: "Community name must be at least 2 characters long"
                },
                description: {
                    required: "Please enter a community description",
                    minlength: "Community description must be at least 10 characters long"
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

        $("#add-post-form").validate({
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
                    required: "Please enter a post title",
                    minlength: "Post title must be at least 2 characters long"
                },
                description: {
                    required: "Please enter a post description",
                    minlength: "Post description must be at least 10 characters long"
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

        const getList = (page, search = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.community.info', $id) }}",
                data: {
                    page,
                    search,
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
                        $("#appendData").html(result.data.html);
                        $('.Post-card-image').owlCarousel({
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
        $(document).on('keyup', "#searchInput", function() {
            let search = $(this).val();
            getList($(this).data('page'), search);
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
        url: baseUrl + "/admin/image-upload?type=community&id={{$community->id}}",
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
                    type: 'community',
                    id: "{{ $community->id }}"
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
                            $('#update-community-form .dz-default.dz-message').hide();
                        } else $('#update-community-form .dz-default.dz-message').show();
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
                    $('#update-community-form .dz-default.dz-message').hide();
                } else $('#update-community-form .dz-default.dz-message').show();
            }
        },
        error: function(file, response) {
            let oplength = arrOfImg.length;
            if (oplength > 0) {
                $('#update-community-form .dz-default.dz-message').hide();
            } else $('#update-community-form .dz-default.dz-message').show();
            console.log(file.previewElement);
            var fileRef;
            return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : null;
        }
    };

    $(document).on('change', '.toggle__input', function(e) {
        let status = ($(this).is(":checked")) ? 1 : 2;
        let id = $(this).data('id');
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: "{{ route('admin.community.change.status') }}",
            data: {
                id,
                status,
                '_token': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $(".toastdesc").text(response.message).addClass('text-success');
                    launch_toast();
                    setInterval(() => {
                        window.location.reload();
                    }, 2000);
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
        });
    });

    let arrOfImgPost = [];
    Dropzone.options.multipleImagePost = {
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
        url: "{{ route('admin.image-upload', ['type' => 'post']) }}",
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
                    type: 'post'
                },
                success: function(data) {
                    if (data.status) {
                        console.log("File deleted successfully!!");
                        if (data.key == 2) {
                            const inde = arrOfImgPost.indexOf(data.file_name);
                            if (inde > -1) {
                                arrOfImgPost.splice(inde, 1);
                                $("#arrayOfImagePost").val(JSON.stringify(arrOfImgPost));
                            }
                        }
                        let oplength = arrOfImgPost.length;
                        if (oplength > 0) {
                            $('#add-post-form .dz-default.dz-message').hide();
                        } else $('#add-post-form .dz-default.dz-message').show();
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
                arrOfImgPost.push(response.file_name);
                $("#arrayOfImagePost").val(JSON.stringify(arrOfImgPost));
                console.log(arrOfImgPost);
                file.upload.filename = response.file_name;
                let oplength = arrOfImgPost.length;
                if (oplength > 0) {
                    $('#add-post-form .dz-default.dz-message').hide();
                } else $('#add-post-form .dz-default.dz-message').show();
            }
        },
        error: function(file, response) {
            let oplength = arrOfImgPost.length;
            if (oplength > 0) {
                $('#add-post-form .dz-default.dz-message').hide();
            } else $('#add-post-form .dz-default.dz-message').show();
            console.log(file.previewElement);
            var fileRef;
            return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : null;
        }
    };
    console.log(arrOfImgPost);

    
</script>
@endpush