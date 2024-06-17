@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/help.css') }}">
@endpush
@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Help & Support</h2>
        </div>
        <div class="plas-search-filter wd60">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group search-form-group">
                        <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by student name">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" class="form-control selector" name="date" id="selectDate" placeholder="MM-DD-YYYY" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <a href="chat.html" class="btn-pi">Student Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="help-card">
        <div class="card-body">
            <div class="support-page-section">

                <div class="support-content">
                    <div class="row" id="appendData">

                    </div>
                </div>

                <div class="plas-table-pagination">
                    <ul class="plas-pagination" id="appendPagination">
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Send Reply -->
<div class="modal lm-modal fade" id="adminSendReply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>Reply</h2>
                    <form action="{{ route('admin.support.send.reply') }}" method="post" id="replyForm">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="jwj-review-card">
                                        <div class="jwj-review-card-head">
                                            <div class="review-rating-user-avtar">
                                                <img src="{{ assets('assets/images/no-image.jpg') }}" id="modalImg" alt="">
                                            </div>
                                            <div class="review-rating-user-text">
                                                <h3 id="modalName"></h3>
                                                <p style="font-size: 11px; color: #074f7c; text-align: start;" class="mb-0">Student</p>
                                            </div>
                                        </div>
                                        <div class="jwj-review-card-body">
                                            <span class="review-quotes-shape"></span>
                                            <div class="review-desc" id="modalMsg"></div>
                                            <div class="review-date" id="modalTime" style="color: gray;font-size: 13px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="status" id="selectStatus">
                                        <option value="">Select Status</option>
                                        <option value="1">Closed</option>
                                        <option value="2">In-Progress</option>
                                        <option value="3">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="modalId">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="message" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button class="save-btn" type="submit" id="reply">Send Reply</button>
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

<!-- Your Answer -->
<div class="modal lm-modal fade" id="seeReply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h2>Admin Reply</h2>
                        </div>
                        <div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <img src="{{ (isset(auth()->user()->profile) && file_exists(public_path('uploads/profile/'.auth()->user()->profile)) ) ? assets('uploads/profile/'.auth()->user()->profile) : assets('assets/images/user.png') }}" alt="">
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3>{{ auth()->user()->name ?? 'NA' }}</h3>
                                            <p style="font-size: 11px; color: #074f7c; text-align: start;" class="mb-0">Administrator</p>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc" id="modalRplyMsg"></div>
                                        <div class="review-date" id="modalRplyTime" style="color: gray;font-size: 13px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <img src="{{ assets('assets/images/no-image.jpg') }}" id="modal2Img" alt="">
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3 id="modal2Name"></h3>
                                            <p style="font-size: 11px; color: #074f7c; text-align: start;" class="mb-0">Student</p>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc" id="modal2Msg"></div>
                                        <div class="review-date" id="modal2Time" style="color: gray;font-size: 13px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script type="text/javascript">
    $(document).on("click", ".send-rply", function() {
        $("#modalId").val($(this).data('id'));
        $("#modalMsg").text($(this).data('msg'));
        $("#modalTime").text($(this).data('time'));
        $("#modalName").text($(this).data('name'));
        $("#modalImg").attr("src", $(this).data('img'));
        $("#selectStatus").val($(this).data('status'));
        $("#adminSendReply").modal('show');
    });

    $(document).on("click", "#seeRply", function() {
        $("#modal2Msg").text($(this).data('msg'));
        $("#modal2Time").text($(this).data('time'));
        $("#modal2Name").text($(this).data('name'));
        $("#modal2Img").attr("src", $(this).data('img'));
        $("#modalRplyMsg").text($(this).data('past'));
        $("#modalRplyTime").text($(this).data('updatetime'));
        $("#seeReply").modal('show');
    });

    $('#replyForm').validate({
        rules: {
            status: {
                required: true,
            },
            message: {
                required: true,
            },
        },
        messages: {
            status: {
                required: 'Please select your status',
            },
            message: {
                required: 'Please enter your message',
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
                    $("#reply").addClass('d-none')
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
                    $("#reply").removeClass('d-none')
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
        const getList = (page, search = null, date = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.support.list') }}",
                data: {
                    page,
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
            let search = $(this).val();
            getList($(this).data('page'), search, date);
        });
        $(document).on('change', "#selectDate", function() {
            let date = $(this).val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, date);
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