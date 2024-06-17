@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/notification.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Notifications</h2>
        </div>
        <div class="plas-search-filter wd80">
            <div class="row g-1">
                <div class="col-md-2">
                    <div class="form-group">
                        <select class="form-control" id="selectType">
                            <option value="">Select Type</option>
                            <option value="course">Course</option>
                            <option value="product">Product</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
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
                        <a class="Accountapproval-btn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#CreateNotification">Push New App Notification</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="notifications-section">
        <div class="row" id="appendData">
            
            
            
        </div>
    </div>
    <div class="plas-table-pagination">
        <ul class="plas-pagination" id="appendPagination">

        </ul>
    </div>
</div>

<!-- Create  Notification -->
<div class="modal lm-modal fade" id="CreateNotification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Push New App Notification</h2>
                    <form action="{{ route('admin.notification.store') }}" method="post" id="notification-form">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title" placeholder="Notification Title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="type">
                                        <option value="">Select Type</option>
                                        <option value="course">Course</option>
                                        <option value="product">Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                                </div>
                            </div>
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button class="save-btn" type="submit" id="push">Push Notification</button>
                                    <button class="save-btn d-none" id="wait" type="button">Please Wait <img style="padding: 0px;" width="30" src="{{ assets('assets/images/spinner4.svg') }}" alt=""></button>
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
    $('#notification-form').validate({
        rules: {
            title: {
                required: true,
            },
            type: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            title: {
                required: 'Please select title',
            },
            type: {
                required: 'Please select type',
            },
            description: {
                required: 'Please enter your description',
            }
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
                    $("#push").addClass('d-none')
                },
                success: function(response) {
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
                    $("#push").removeClass('d-none')
                    $("#wait").addClass('d-none')
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
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

    $(document).ready(function() {
        const getList = (page, search = null, date = null, type = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.notification.list') }}",
                data: {
                    page,
                    type,
                    search,
                    date
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                    if (result.status) {
                        let html = ``;
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
        $(document).on('keyup', '#searchInput', function() {
            let date = $("#selectDate").val();
            let type = $("#selectType").val();
            let search = $(this).val();
            getList($(this).data('page'), search, date, type);
        });
        $(document).on('change', "#selectDate", function() {
            let date = $(this).val();
            let search = $("#searchInput").val();
            let type = $("#selectType").val();
            getList($(this).data('page'), search, date, type);
        });
        $(document).on('change', "#searchType", function() {
            let type = $(this).val();
            let search = $("#searchInput").val();
            let date = $("#selectDate").val();
            getList($(this).data('page'), search, date, type);
        });

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
                    getList($(this).data('page'), search, '');
                }
            }
        });
    })
</script>
@endpush