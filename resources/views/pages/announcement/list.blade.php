@extends('layouts.app')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/announcement.css') }}">
@endpush

@section('content')
    <div class="body-main-content">
        <div class="plas-filter-section">
            <div class="plas-filter-heading">
                <h2>Announcements</h2>
            </div>
            <div class="plas-search-filter wd80">
                <div class="row g-1">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control selector" name="date" id="selectDate"
                                placeholder="MM-DD-YYYY" readonly>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-goup search-form-group">
                            <input type="text" class="form-control" id="searchInput" name="search"
                                placeholder="Search by title">
                            <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-goup search-form-group">
                            <a id="resetFilter" class="btn-gr" href="{{ route('admin.announcement.list') }}">
                                <img src="{{ assets('assets/images/reset.svg') }}" alt="Reset">
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <a class="Accountapproval-btn" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#CreateNotification">Create New Announcement</a>
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

    <!-- Create Announcement Modal -->
    <div class="modal lm-modal fade" id="CreateNotification" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="notification-modal-form">
                        <h2>Create Announcements</h2>
                        <form action="{{ route('admin.announcements.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Announcement Title"
                                            name="title" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control" placeholder="Announcement Description" name="description" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_on_home_page"
                                                name="show_on_home_page" value="show">
                                            <label class="form-check-label" for="show_on_home_page">Show on home
                                                page</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="cancel-btn" data-bs-dismiss="modal"
                                            aria-label="Close">Discard</button>
                                        <button type="submit" class="save-btn">Create Announcements</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div class="modal lm-modal fade" id="EditAnnouncement" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="notification-modal-form">
                        <h2>Edit Announcement</h2>
                        <form id="editAnnouncementForm" action="{{ route('admin.announcements.update') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Announcement Title"
                                            name="title" id="editTitle" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Announcement Description" name="description" id="editDescription"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="editStatus">Status:</label>
                                        <select name="status" id="editStatus" class="form-control" required>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="editImage" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="editImage" name="image">
                                    <img id="editImagePreview" src="" alt="Announcement Image"
                                        class="img-fluid mt-2" style="max-height: 200px; display: none;">
                                </div>
                                <input type="hidden" name="id" id="announcementId" value="">
                                <input type="hidden" name="id" id="editAnnouncementId" value="">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="editShowOnHomePage"
                                                name="show_on_home_page" value="show">
                                            <label class="form-check-label" for="editShowOnHomePage">Show on home
                                                page</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" class="cancel-btn" data-bs-dismiss="modal"
                                            aria-label="Close">Cancel</button>
                                        <button type="submit" class="save-btn">Update Announcement</button>
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
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                document.getElementById('deleteForm_' + id).submit();
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            const getList = (page, search = null, date = null, type = null) => {
                $.ajax({
                    type: 'get',
                    url: "{{ route('admin.announcement.list') }}",
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
            $(document).on('change', "#selectType", function() {
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
                        let type = $("#selectType").val();
                        getList($(this).data('page'), search, '', type);
                    }
                }
            });
        })

        $(document).ready(function() {
            $(document).on('click', '.editbtn', function(e) {
                e.preventDefault();
                const announcementId = $(this).data('id');

                if (!announcementId) {
                    console.error('Invalid announcement ID');
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.announcements.edit') }}",
                    data: {
                        id: announcementId
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success Response:', response);

                        if (response.status === 'success') {
                            const announcement = response.data;
                            console.log('Fetched Announcement:', announcement);

                            // Populate form fields
                            $('#editTitle').val(announcement.title);
                            $('#editDescription').val(announcement.description);
                            $('#editStatus').val(announcement.status === 1 ?
                                'active' : 'inactive');

                            if (announcement.image) {
                                const announcementImage =
                                    `{{ assets('uploads/announcement/image') }}/${announcement.image}`;
                                $('#editImagePreview').attr('src',
                                    announcementImage).show();
                            } else {
                                $('#editImagePreview').hide();
                            }

                            $('#announcementId').val(announcement.id);
                            $('#editShowOnHomePage').prop('checked', announcement
                                .is_homepage == 1);

                            // Show modal
                            $('#EditAnnouncement').modal('show');
                        } else {
                            console.error('Failed to fetch announcement data');
                        }
                    },
                    error: function(error) {
                        console.error('AJAX error:', error.responseText);
                    }
                });
            });
        });


        $(document).on('click', '.editbtn', function(e) {
            e.preventDefault();
            const announcementId = $(this).data('id');

            $('#editTitle').val(''); 
            $('#editDescription').val(''); 
            $('#announcementId').val(announcementId);
            $('#EditAnnouncement').modal('show');
        });



        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#editAnnouncementForm').on('submit', function(e) {
                e.preventDefault();

                const announcementId = $('#announcementId').val();

                const formData = new FormData(this);

                formData.set('_method', 'PUT');

                if (announcementId) {
                    formData.set('id', announcementId);
                }

                $.ajax({
                    type: 'POST', 
                    url: $(this).attr(
                        'action'), 
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Form submission success:', response);
                        window.location.href =
                            "{{ route('admin.announcement.list') }}";
                    },
                    error: function(error) {
                        console.error('Form submission error:', error.responseText);
                    }
                });
            });
        });
    </script>
@endpush
