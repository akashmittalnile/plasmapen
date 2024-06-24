@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/product.css') }}">
@endpush

@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Products</h2>
        </div>
        <div class="plas-search-filter wd80">
            <div class="row g-1">
                <div class="col-md-3">
                    <div class="form-group search-form-group">
                        <input id="searchInput" type="text" class="form-control" name="search" placeholder="Search by Title">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <ul class="statusradio-list">
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="status" value="1" id="Published" checked>
                                    <label for="Published">Published</label>
                                </div>
                            </li>
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="status" value="0" id="Unpublished">
                                    <label for="Unpublished">Unpublished</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="javascript:void(0)" class="btn-pi">Manage Coupon</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a data-bs-toggle="modal" data-bs-target="#Createproduct" class="btn-bl">Create New product</a>
                    </div>
                </div>
            </div>
        </div>
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

<!-- Create  product -->
<div class="modal lm-modal fade" id="Createproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>New Product</h2>
                    <div class="row">
                        <form action="{{ route('admin.product.store') }}" id="add-product-form" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Product Title" name="title" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="number" min="0" step="any" class="form-control" placeholder="Product Price" name="price">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" placeholder="Product Description"></textarea>
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
                                    <button type="submit" class="save-btn">Save & Published Product</button>
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
                    <p>{{ translate('You want to delete this product!') }}</p>
                    <form action="{{ route('admin.product.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="hidden" name="id" id="productId">
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
        url: "{{ route('admin.image-upload', ['type' => 'product']) }}",
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
                    type: 'product'
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
        $("#productId").val($(this).data('id'));
        $("#openDeleteModal").modal("show");
    });

    $(document).ready(function() {
        const getList = (page, search = null, status = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.product.list') }}",
                data: {
                    page,
                    search,
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
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status);
        });
        $(document).on('change', "input[name='status']", function() {
            let status = $("input[name='status']:checked").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status);
        });
    })

    $(document).ready(function() {
        $("#add-product-form").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                price: {
                    required: true,
                    number: true
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
                price: {
                    required: "Please enter a product price",
                    number: "Please enter a valid price"
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