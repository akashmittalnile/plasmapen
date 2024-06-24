@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/blog.css') }}">
@endpush

@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Blogs</h2>
        </div>
        <div class="plas-search-filter wd60">
            <div class="row g-1">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control selector" name="date" id="selectDate" placeholder="MM-DD-YYYY" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group search-form-group">
                        <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by title">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <a href="javascript:void(0)" class="Accountapproval-btn" data-bs-toggle="modal" data-bs-target="#AddNewblog">Add New Blog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blog-section">
        <div class="row" id="appendData">
    
        </div>
        <div class="plas-table-pagination">
            <ul class="plas-pagination" id="appendPagination">
            </ul>
        </div>
    </div>
</div>

<!-- Create blog -->
<div class="modal lm-modal fade" id="AddNewblog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Add New Blog</h2>
                    <div class="row">
                        <form action="{{ route('admin.blog.store') }}" id="add-blog-form" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Blog Title" name="title" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" placeholder="Blog Description"></textarea>
                                </div>
                            </div>

                            <div class="product-item-card">
                                <div class="card-header form-group" style="border-bottom: none;">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="dropzone" id="multipleImage">
                                            <div class="dz-default dz-message">
                                                <span>Click once inside the box to upload an image
                                                    <br>
                                                    <small class="text-danger">Make sure the image size is less than 5 MB</small>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="arrayOfImage" name="array_of_image" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="save-btn">Save & Published Blog</button>
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
<script type="text/javascript">
    let arrOfImg = [];
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
        url: "{{ route('admin.image-upload', ['type' => 'blog']) }}",
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
                    type: 'blog'
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

    $(document).on("click", ".deletebtn", function() {
        $("#blogId").val($(this).data('id'));
        $("#openDeleteModal").modal("show");
    });

    $(document).ready(function() {
        $(".selector").datepicker({
            dateFormat: 'mm-dd-yy', //check change
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            closeText: 'Clear',
            onClose: function(dateText, inst) {
                if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                    document.getElementById(this.id).value = '';
                    let search = $("#searchInput").val();
                    let type = $("#selectType").val();
                    getList($(this).data('page'), search, '', type);
                }
            }
        });

        const getList = (page, search = null, date = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.blog.list') }}",
                data: {
                    page,
                    search,
                    date
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
            let search = $(this).val();
            let date = $("#selectDate").val();
            getList($(this).data('page'), search, date);
        });
        $(document).on('change', "#selectDate", function() {
            let date = $(this).val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, date);
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
                    required: "Please enter a blog title",
                    minlength: "Blog title must be at least 2 characters long"
                },
                description: {
                    required: "Please enter a blog description",
                    minlength: "Blog description must be at least 10 characters long"
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
    });
</script>
@endpush