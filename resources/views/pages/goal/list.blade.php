@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/goal.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>{{ translate('Manage Goals') }}</h2>
        </div>
        <div class="plas-search-filter wd50">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group search-form-group">
                        <input type="text" id="searchInput" class="form-control" name="Start Date" placeholder="{{ translate('Search by student name, email & phone number') }}">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="selectType">
                            <option value="">Select Type</option>
                            <option value="A-Type">{{ translate('A-Type') }}</option>
                            <option value="B-Type">{{ translate('B-Type') }}</option>
                            <option value="C-Type">{{ translate('C-Type') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="creator-table-section">
        <div class="creator-table-list" id="appendData">
            <!-- Data will be appended here -->
        </div>
    </div>

    <div class="plas-table-pagination">
        <ul class="plas-pagination" id="appendPagination">
            <!-- Pagination will be appended here -->
        </ul>
    </div>
</div>
<!-- Goal Detail Modal -->
<div class="modal fade" id="viewGoalModal" tabindex="-1" aria-labelledby="viewGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="viewgoal-modal-form">
                    <div class="goal-card-section">
                        <div class="row g-1 align-items-center">
                            <div class="col-md-3">
                                <div class="goal-profile-item">
                                    <div class="goal-profile-media">
                                        <img id="userProfileImage"  alt="Profile Image">
                                    </div>
                                    
                                    <div class="goal-profile-text">
                                        <h2 id="userName">User Name</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="goal-contact-info">
                                            <div class="goal-contact-info-icon">
                                                <img src="{{ assets('assets/images/sms1.svg') }}">
                                            </div>
                                            <div class="goal-contact-info-content">
                                                <h2>Email Address</h2>
                                                <p id="userEmail">Email</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="goal-contact-info">
                                            <div class="goal-contact-info-icon">
                                                <img src="{{ assets('assets/images/calendar.svg') }}">
                                            </div>
                                            <div class="goal-contact-info-content">
                                                <h2>Date</h2>
                                                <p id="goalAchieveDate">Date</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="goal-contact-info">
                                            <div class="goal-contact-info-icon">
                                                <img src="{{ assets('assets/images/Target.svg') }}">
                                            </div>
                                            <div class="goal-contact-info-content">
                                                <h2>Type</h2>
                                                <p id="goalType">Type</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="goal-point-list">
                        <div class="goal-point-item">
                            <h2>My Statement</h2>
                            <div class="goal-point-value" id="goalStatement">Statement</div>
                        </div>

                        <div class="goal-point-item">
                            <h2>What will it take for me to get this done?</h2>
                            <div class="goal-point-value" id="goalForMe">Details</div>
                        </div>

                        <div class="goal-point-item">
                            <h2>In Six Months, I will Accomplish</h2>
                            <div class="goal-point-value" id="sixMonthMilestones">Milestones</div>
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
    $(document).ready(function() {
        const getList = (page = 1, search = '', status = '', type = '') => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.goal.list') }}",
                data: {
                    page,
                    search,
                    status,
                    type // Pass type parameter
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result);
                    if (result.status === 'success') {
                        $("#appendData").html(result.data.html);

                        $("#appendPagination").html('');
                        if (result.data.lastPage != 1) {
                            let paginate = `<li class="${result.data.currentPage == 1 ? 'disabled' : ''}" id="example_previous">
                                    <a href="javascript:void(0)" data-page="${result.data.currentPage - 1}" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                </li>`;
                            for (let i = 1; i <= result.data.lastPage; i++) {
                                paginate += `<li class="${result.data.currentPage == i ? 'active' : ''}">
                                        <a href="javascript:void(0)" data-page="${i}" class="page-link">${i}</a>
                                    </li>`;
                            }
                            paginate += `<li class="${result.data.currentPage == result.data.lastPage ? 'disabled next' : 'next'}" id="example_next">
                                        <a href="javascript:void(0)" data-page="${result.data.currentPage + 1}" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
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

        // Initial call to load the list
        getList();

        // Handle pagination click
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            let page = $(this).data('page');
            let search = $('#searchInput').val();
            let type = $("#selectType").val(); // Get selected type
            let status = $("input[name='antigentype']:checked").val();
            getList(page, search, status, type); // Pass type to getList function
        });

        // Handle search input keyup
        $(document).on('keyup', '#searchInput', function() {
            let search = $(this).val();
            let type = $("#selectType").val(); // Get selected type
            let status = $("input[name='antigentype']:checked").val();
            getList(1, search, status, type); // Pass type to getList function
        });

        // Handle type dropdown change
        $(document).on('change', '#selectType', function() {
            let type = $(this).val();
            let search = $('#searchInput').val();
            let status = $("input[name='antigentype']:checked").val();
            getList(1, search, status, type); // Pass type to getList function
        });
    });

    $(document).on('click', '.Viewiconbtn', function() {
    const goalId = $(this).data('goal-id');
    $.ajax({
    type: 'get',
    url: `{{ route('admin.goals.detail', ['id' => ':id']) }}`.replace(':id', goalId),
    dataType: 'json',
    success: function(result) {
        console.log('AJAX Success:', result);
        if (result.status === 'success') {
            const goal = result.data;
            const user = result.data.user;
            
            const userProfileImage = user && user.profile 
                ? `{{ asset('public/uploads/profile/') }}/${user.profile}` 
                : `{{ asset('public/assets/images/no-image.jpg') }}`;
            $('#userProfileImage').attr('src', userProfileImage);
            $('#userName').text(`${user ? user.first_name + ' ' + user.last_name : 'Unknown'}`);
            $('#userEmail').text(user ? user.email : 'Unknown');
            $('#goalAchieveDate').text(moment(goal.achieve_date).format('DD MMM YYYY'));
            $('#goalType').text(goal.goal_type);
            $('#goalStatement').text(goal.goal_statement);
            $('#goalForMe').text(goal.goal_for_me);
            $('#sixMonthMilestones').text(goal.six_month_milestones);

            myModal.show(); // Show modal after data is fetched
        } else {
            console.error('Server Error:', result.message);
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
    }
});
});
</script>
@endpush
