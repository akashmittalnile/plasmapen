@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/product.css') }}">
<style>
    ul:nth-child(1){
        color: #455a64;
    }
</style>
@endpush

@section('content')
<meta name="_token" content="{{csrf_token()}}" />
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Product Info</h2>
        </div>
        <div class="plas-search-filter wd50">
            <div class="row g-1">
                <!-- <div class="col-md-3">
                    <div class="form-group search-form-group">
                        <input type="text" class="form-control" name="" placeholder="Enter Order ID To Get Order Details">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <input type="date" class="form-control">
                    </div>
                </div> -->

                <div class="col-md-2">
                    <div class="form-group">
                        <a href="{{ route('admin.product.list') }}" class="btn-pi">Back</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#Updateproduct" class="btn-pi">Edit Product</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openDeleteModal" class="btn-bl">Delete Product</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-section pt-3">
        <div class="overview-section">
            <div class="row row-cols-xl-3 row-cols-xl-3 row-cols-md-2 g-2">
                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Revenue</p>
                                    <h2>$0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/Revenue.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Overall Products Rating</p>
                                    <h2>4.7</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/star2.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Orders</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/listedproducts.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Orders Placed</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/OrdersPlaced.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Ready To Pick-Up Orders</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/Pickorder.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Delivered Successfully</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/OrdersPlaced.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="product-details-section">
            <div class="product-details-card">
                <div class="product-details-image owl-carousel owl-theme">
                    @forelse($imgs as $item)
                    <div class="item">
                        <div class="community-posts-media">
                            <a data-fancybox="" href="{{ assets('uploads/product/'.$item->item_name) }}">
                                <img src="{{ assets('uploads/product/'.$item->item_name) }}">
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
                <div class="product-details-content">
                    <h2>{{ $product->title ?? '0' }}</h2>
                    <div class="product-card-point-text">
                        <div class="productfee-text">${{ $product->price ?? '0' }}</div>
                        <div class="Purchases-text">0 Purchases</div>
                    </div>
                    <p>{{ $product->description ?? '0' }}</p>
                </div>
            </div>

            <div class="product-details-tabs">
                <ul class="nav nav-tabs">
                    <li><a class="active" href="#ProductDetails" data-bs-toggle="tab">Product Details</a></li>
                    <li><a href="#RatingReviews" data-bs-toggle="tab">Rating & Reviews</a></li>
                </ul>
            </div>

            <div class="product-details-content-info tab-content">
                <div class="tab-pane active" id="ProductDetails">
                    <div class="product-details-content">
                        <p>CoolJet™ Introductory Starter Kit is the ideal way to start off your business. We have put together a bundle of our technician’s favorite products for CoolJet™ treatments!</p>
                        <p>PlasmaPen Transdermal Serums are made specifically for deep absorption and skin correction. Serum Three is packed with ingredients perfect for early onset of aging and skin congestion. Serum Five is our “Plasma in a Bottle”, super hydrating to enhance the skin with moisture and elasticity. Each of our Plasma Tips can do three (20 minute) treatments can bring in an income of $300-$500, one treatment and you instantly get your return of investment!</p>
                        <p><b>Contents of the kit</b></p>
                        <p>5 – Plasma Super Tips – Round</p>
                        <p>5 – Plasma Super Tips – Square</p>
                        <p>1-Transdermal Serum 3</p>
                        <p>1-Transdermal Serum 5</p>
                        <p>1-Plasma Pen Headband</p>
                        <p>1-Cleansing Tonic</p>
                        <p>1- Enzyme Peel</p>
                    </div>
                </div>
                <div class="tab-pane" id="RatingReviews">

                    <div class="">
                        <div class="comment-item">
                            <div class="comment-profile">
                                <img src="{{ assets('assets/images/user.png') }}">
                            </div>
                            <div class="comment-content">
                                <div class="comment-head">
                                    <h2>Katty</h2>
                                    <div class="comment-date"><i class="las la-calendar"></i>On 06 May, 2024:09:21 AM</div>
                                </div>
                                <div class="comment-descr">By Offering Plamsapen To Your Clients, You Are Able To Address Concerns Such As Sagging Skin, Fine Lines And Wrinkles, Sun Damage And Scarring</div>
                                <div class="comment-action">
                                    <a class="delete-btn1" href="#"><img src="{{ assets('assets/images/Trash.svg') }}"> Delete Comment</a>
                                </div>
                            </div>
                        </div>
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
                                <input type="hidden" name="id" id="productId" value="{{ encrypt_decrypt('encrypt', $product->id) }}">
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

<!-- Update  product -->
<div class="modal lm-modal fade" id="Updateproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Update Product</h2>
                    <div class="row">
                        <form action="{{ route('admin.product.update') }}" id="update-product-form" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $product->title ?? '' }}" placeholder="Product Title" id="update-title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="number" min="0" step="any" class="form-control" id="update-price" placeholder="Product Price" name="price" value="{{ $product->price ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" id="update-description" placeholder="Product Description">{{ $product->description ?? '' }}</textarea>
                                    <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $product->id) }}">
                                    <input type="hidden" id="arrayOfImage" name="array_of_image" value="">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="lesson[]" multiple="multiple" required class="selectLesson form-control">
                                        @foreach($combined as $val)
                                        <option @if($val['selected']) selected @endif value="{{ $val['id'] }}">{{ $val['name'] ?? 'NA' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="product-item-card">
                                <div class="card-header form-group" style="border-bottom: none;">
                                    <div class="d-flex flex-column justify-content-between">
                                        <div class="dropzone" id="multipleImage">
                                            @foreach($imgs as $val)
                                            <div class="dz-preview dz-image-preview">
                                                <div class="dz-image">
                                                    <img src="{{ assets('uploads/product/'.$val->item_name) }}" alt="{{ $val->item_name }}" />
                                                </div>
                                                <a href="{{ route('admin.uploaded-image-delete', ['id'=> encrypt_decrypt('encrypt', $val->id), 'type'=> 'product']) }}" class="dz-remove dz-remove-image">Remove</a>
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
                                    <button type="submit" class="save-btn">Update Product</button>
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
    $('.selectLesson').select2({
        placeholder: 'Select Lessons',
        dropdownParent: $('#Updateproduct .modal-content')
    });

    $(".select2-container").css({'width':"100%"})
    $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');
    $(".select2-search__field.form-control").css({"background": "#fff","border-radius": "5px","font-size": "14px","border": "1px solid rgb(255 255 255 / 15%)","color": "var(--white)"});
    $(".select2-selection.select2-selection--multiple").css({"border-color": "#e7e9eb"})
    $("ul:nth-child(1)").css("color", "#455a64 !important")
    
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

        $("#update-product-form").validate({
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
        url: baseUrl+"/admin/image-upload?type=product&id={{$product->id}}",
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
                    type: 'product',
                    id: "{{ $product->id }}"
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

    $('.product-details-image').owlCarousel({
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
</script>
@endpush