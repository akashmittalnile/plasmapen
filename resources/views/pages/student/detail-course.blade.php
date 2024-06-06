@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/students.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="user-details-section">
        <div class="plas-filter-section">
            <div class="plas-filter-heading">
                <h2>{{ translate('Student Details') }}</h2>
            </div>
            <div class="plas-search-filter wd80">
                <div class="row g-1">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" placeholder="{{ translate('Search by course name, price') }}">
                            <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn-pi">{{ translate('Mark as inactive') }}</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.student.details.product', encrypt_decrypt('encrypt', $user->id)) }}" class="btn-pi">{{ translate('Products') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-details-content">
            <div class="row">
                <div class="col-md-3">
                    <div class="user-details-sidebar">
                        <div class="user-side-profile">
                            <div class="side-profile-item">
                                <div class="side-profile-media"><img src="{{ isset($user->profile) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/no-image.jpg') }}"></div>
                                <div class="side-profile-text">
                                    <h2>{{ $user->name ?? 'NA' }}</h2>
                                    @if($user->status == 1)
                                    <p>Account Status : <span style="color:#acce39;"> {{ config('constant.userStatus')[$user->status] }}</span></p>
                                    @else
                                    <p>Account Status : <span style="color:#d7564e;"> {{ config('constant.userStatus')[$user->status] }}</span></p>
                                    @endif
                                </div>
                            </div>

                            <div class="side-profile-overview-info">
                                <div class="row g-1">
                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{{ assets('assets/images/sms1.svg') }}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Email Address</h2>
                                                <p>{{ $user->email ?? 'NA' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="side-profile-total-order">
                                            <div class="side-profile-total-icon">
                                                <img src="{{ assets('assets/images/call1.svg') }}">
                                            </div>
                                            <div class="side-profile-total-content">
                                                <h2>Phone Number</h2>
                                                <p>{{ $user->country_code ?? 'NA' }}{{ $user->mobile ?? 'NA' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-notification-sidebar">
                        <h2>Notifications</h2>
                        <div class="side-notification-item">
                            <div class="side-notification-icon"><img src="{{ assets('assets/images/notification.svg') }}"></div>
                            <div class="side-notification-text">
                                <p>01 New Course Purchase</p>
                            </div>
                        </div>

                        <div class="side-notification-item">
                            <div class="side-notification-icon"><img src="{{ assets('assets/images/notification.svg') }}"></div>
                            <div class="side-notification-text">
                                <p>Certicficate Completed</p>
                                <a class="noti-btn" href="">View Certificates</a>
                            </div>
                        </div>

                        <div class="side-notification-item">
                            <div class="side-notification-icon"><img src="{{ assets('assets/images/notification.svg') }}"></div>
                            <div class="side-notification-text">
                                <p>Product Order Placed Successfully</p>
                                <a class="noti-btn" href="">Order Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="student-course-card">
                                <div class="student-course-content">
                                    <div class="student-course-head">
                                        <div class="student-course-status">Recent Purchased Course - Started Studying…</div>
                                    </div>
                                    <div class="student-course-card-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                    <h2>2 – About: Accessories & Consumables</h2>
                                    <div class="student-course-price">$499.00</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Course Start Date:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jun 2024</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Reattempt Test Date:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jul 2024</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4> Last Open:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jul 2024</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Payment completed via Credit Card</h4>
                                                <p><img src="{{ assets('assets/images/paymentcard.svg') }}"> XXXX8987</p>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="student-course-progress pi-progress">
                                                <div class="student-course-use-text">
                                                    Course progress: <span>50%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="student-course-action">
                                                <a class="bl-btn" href="">Send Invoice to email</a>
                                                <a class="pi-btn" href="">Download Invoice</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="student-course-card">
                                <div class="student-course-content">
                                    <div class="student-course-head">
                                        <div class="student-course-status">Recent Purchased Course - Started Studying…</div>
                                    </div>
                                    <div class="student-course-card-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                    <h2>2 – About: Accessories & Consumables</h2>
                                    <div class="student-course-price">$499.00</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Course Start Date:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jun 2024</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Reattempt Test Date:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jul 2024</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4> Last Open:</h4>
                                                <p><img src="{{ assets('assets/images/clock1.svg') }}"> 26 Jul 2024</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="student-course-date-item">
                                                <h4>Payment completed via Credit Card</h4>
                                                <p><img src="{{ assets('assets/images/paymentcard.svg') }}"> XXXX8987</p>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="student-course-progress pi-progress">
                                                <div class="student-course-use-text">
                                                    Course progress: <span>50%</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="student-course-action">
                                                <a class="bl-btn" href="">Send Invoice to email</a>
                                                <a class="pi-btn" href="">Download Invoice</a>
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
    </div>
</div>
@endsection