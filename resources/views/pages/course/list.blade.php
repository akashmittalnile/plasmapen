@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>{{ translate('Manage Course') }}</h2>
        </div>
        <div class="plas-search-filter wd80">
            <div class="row g-1">
                <div class="col-md-3">
                    <div class="form-group search-form-group">
                        <input id="searchInput" type="text" class="form-control" name="search" placeholder="{{ translate('Search by course name, price') }}">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <ul class="statusradio-list">
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="status" value="1" id="Published">
                                    <label for="Published">Published</label>
                                </div>
                            </li>
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="status" value="2" id="Unpublished">
                                    <label for="Unpublished">Unpublished</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <a href="coupon.html" class="btn-pi">{{ translate('Manage Coupon') }}</a>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('admin.course.create') }}" class="btn-bl">{{ translate('Create New Course') }}</a>
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
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const getList = (page, search = null, status = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.course.list') }}",
                data: {
                    page, search, status
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
                        let html = `<div class="d-flex justify-content-center align-items-center flex-column">
                                    <div>
                                        <img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
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
</script>
@endpush