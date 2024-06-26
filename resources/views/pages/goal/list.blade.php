@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2>Manage Goals</h2>
            <div class="form-group search-form-group mb-4">
                <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search by user name">
                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
            </div>
            <div id="appendData">
                <!-- Dynamic data will be appended here -->
            </div>
            <div class="d-flex justify-content-center mt-4">
                <ul class="pagination" id="appendPagination">
                    <!-- Pagination will be appended here -->
                </ul>
            </div>
        </div>
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

@push('css')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/goal.css') }}">
@endpush

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    const getList = (page, search = null) => {
        $.ajax({
            type: 'get',
            url: "{{ route('admin.goal.list') }}",
            data: { page, search },
            dataType: 'json',
            success: function(result) {
                if (result.status === 'success') {
                    $("#appendData").html(result.data.html);
                    $("#appendPagination").html('');
                    if (result.data.lastPage != 1) {
                        let paginate = `<li class="page-item ${result.data.currentPage == 1 ? 'disabled' : ''}">
                            <a href="javascript:void(0)" class="page-link" data-page="${result.data.currentPage - 1}">Previous</a>
                        </li>`;
                        for (let i = 1; i <= result.data.lastPage; i++) {
                            paginate += `<li class="page-item ${result.data.currentPage == i ? 'active' : ''}">
                                <a href="javascript:void(0)" class="page-link" data-page="${i}">${i}</a>
                            </li>`;
                        }
                        paginate += `<li class="page-item ${result.data.currentPage == result.data.lastPage ? 'disabled' : ''}">
                            <a href="javascript:void(0)" class="page-link" data-page="${result.data.currentPage + 1}">Next</a>
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
                        </div>`;
                    $("#appendData").html(html);
                    $("#appendPagination").html('');
                }
            },
            error: function(data, textStatus, errorThrown) {
                console.error('AJAX error:', data.responseText);
            },
        });
    };

    getList(1);

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        getList($(this).data('page'));
    });

    $(document).on('keyup', '#searchInput', function() {
        getList(1, $(this).val());
    });

    var myModal = new bootstrap.Modal(document.getElementById('viewGoalModal'));

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

});


</script>


@endpush

<style>

a.Viewiconbtn {
    background: var(--green);
    border: 1px solid var(--green);
    display: inline-block;
    font-size: 12px;
    color: var(--white);
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);
    width: 32px;
    height: 32px;
}
.viewgoal-modal-form {
    position: relative;
    padding: 2rem 1rem 2rem 1rem;
}.viewgoal-modal-form button.btn-close {
    position: absolute;
    right: 0px;
    top: 0px;
}

.goal-card-section {
    margin-bottom: 1rem;
    border: 1px solid #263238;
    padding: 10px;
    border-radius: 5px;
}


.goal-profile-item {
    padding: 0 0 0rem 0;
    display: flex;
    align-items: center;
}.goal-profile-media {
    margin: 0 10px 0 0;
    width: 60px;
    height: 60px;
    position: relative;
    overflow: hidden;
    border-radius: 50%;
    border: 1px solid #eaedf7;
}.goal-profile-media img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

.goal-profile-text{flex: 1;}
.goal-profile-text h2 {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 5px 0;
    padding: 0;
    color: #263238;
}




.goal-contact-info {
    position: relative;
    width: 100%;
    border-radius: 0;
    padding: 0;
    display: flex;
    margin-bottom: 0rem;
}

.goal-contact-info-icon {
    width: 40px;
    height: 40px;
    margin-right: 10px;
    line-height: 40px;
}

.goal-contact-info-icon img {
    height: 32px;
}

.goal-contact-info-content h2 {
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 5px 0;
    padding: 0;
    color: var(--blue);
}

.goal-contact-info-content p {
    font-size: 14px;
    margin: 0;
    color: var(--lightgray);
    line-height: normal;
    font-weight: normal;
}

.goal-point-item {
    padding: 10px 10px;
    margin-bottom: 5px;
    border-radius: 5px;
    border: 1px solid #3c3a3a ;
    background: #fff;
    position: relative;
}
.goal-point-item h2 {
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 5px 0;
    padding: 0;
    color: var(--blue);
}

.goal-point-value {
    position: relative;
    font-size: 14px;
    margin: 0;
    color: var(--lightgray);
    line-height: normal;
    font-weight: normal;
}
</style>
