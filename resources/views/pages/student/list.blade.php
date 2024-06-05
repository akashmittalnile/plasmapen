@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/students.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>{{ googleTranslate('Students') }}</h2>
        </div>
        <div class="plas-search-filter wd50">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group search-form-group">
                        <input type="text" id="searchInput" class="form-control" name="Start Date" placeholder="{{ googleTranslate('Search by student name, email & phone number') }}">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <ul class="statusradio-list">
                            <li>
                                <div class="statusradio">
                                    <input value="1" type="radio" checked name="antigentype" id="Active">
                                    <label for="Active">{{ googleTranslate('Active') }}</label>
                                </div>
                            </li>
                            <li>
                                <div class="statusradio">
                                    <input value="2" type="radio" name="antigentype" id="Inactive">
                                    <label for="Inactive">{{ googleTranslate('Inactive') }}</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="creator-table-section">
        <div class="creator-table-list" id="appendData">


        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(document).ready(function() {
        const getList = (page, search = null, status = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.student.list') }}",
                data: {
                    page,
                    search,
                    status
                },
                dataType: 'json',
                success: function(result) {
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
                        let html = `<tr class="text-center">
                                        <td colspan="8"> No record found</td>
                                    </tr>`;
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
            let status = $("input[name='antigentype']").val();
            let search = $(this).val();
            getList($(this).data('page'), search, status);
        });
        $(document).on('change', "input[name='antigentype']", function() {
            let status = $(this).val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status);
        });
    })
</script>
@endpush