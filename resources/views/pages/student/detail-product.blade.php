@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/students.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="user-details-section">
        <div class="plas-filter-section">
            <div class="plas-filter-heading">
                <h2>{{ googleTranslate('Student Details') }}</h2>
            </div>
            <div class="plas-search-filter wd80">
                <div class="row g-1">
                    <div class="col-md-4">
                        <div class="form-group search-form-group">
                            <input type="text" class="form-control" placeholder="{{ googleTranslate('Search by products name, Tags Price') }}">
                            <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn-pi">{{ googleTranslate('Mark as inactive') }}</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.student.details.course', encrypt_decrypt('encrypt', $user->id)) }}" class="btn-pi">{{ googleTranslate('Course') }}</a>
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
                                <p>01 Order Placed Successfully</p>
                            </div>
                        </div>

                        <div class="side-notification-item">
                            <div class="side-notification-icon"><img src="{{ assets('assets/images/notification.svg') }}"></div>
                            <div class="side-notification-text">
                                <p>Order No. 9827394723</p>
                                <a class="noti-btn" href=""> Ready For Pickup</a>
                            </div>
                        </div>

                        <div class="side-notification-item">
                            <div class="side-notification-icon"><img src="{{ assets('assets/images/notification.svg') }}"></div>
                            <div class="side-notification-text">
                                <p>Order Successfully Picked-Up</p>
                                <a class="noti-btn" href="">Order Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="lp-product-card">
                        <div class="lp-product-header">
                            <div class="purchased-course-recent">Order ID: HBD898DMND8333</div>
                            <div class="lp-product-action">
                                <a class="pi-btn" href="">Download Invoice</a>
                            </div>
                        </div>
                        <div class="lp-product-card-inner">
                            <div class="lp-product-card-media">
                                <img src="{{ assets('assets/images/p1.png') }}">
                            </div>
                            <div class="lp-product-card-content">
                                <h2>Complete Cool Jet™ Plasma Kit</h2>
                                <div class="product-card-point-text">
                                    <div class="productfee-text">$299.00</div>
                                    <div class="rating-text"><img src="{{ assets('assets/images/star.svg') }}">4.7</div>
                                    <div class="product-point-text">Manage Orders Status <b>Packed</b> </div>
                                </div>
                                <div class="product-point-text"><img src="{{ assets('assets/images/paymentcard.svg') }}">Payment completed via Credit Card <b>XXXX8987</b></div>
                                <div class="product-point-text"><img src="{{ assets('assets/images/clock1.svg') }}">26 May, 2024; 09:30AM</div>
                            </div>
                        </div>
                    </div>
                    <div class="lp-product-card">
                        <div class="lp-product-header">
                            <div class="purchased-course-recent">Order ID: HBD898DMND8333</div>
                            <div class="lp-product-action">
                                <a class="pi-btn" href="">Download Invoice</a>
                            </div>
                        </div>
                        <div class="lp-product-card-inner">
                            <div class="lp-product-card-media">
                                <img src="{{ assets('assets/images/p1.png') }}">
                            </div>
                            <div class="lp-product-card-content">
                                <h2>Complete Cool Jet™ Plasma Kit</h2>
                                <div class="product-card-point-text">
                                    <div class="productfee-text">$299.00</div>
                                    <div class="rating-text"><img src="{{ assets('assets/images/star.svg') }}">4.7</div>
                                    <div class="product-point-text">Manage Orders Status <b>Packed</b> </div>
                                </div>
                                <div class="product-point-text"><img src="{{ assets('assets/images/paymentcard.svg') }}">Payment completed via Credit Card <b>XXXX8987</b></div>
                                <div class="product-point-text"><img src="{{ assets('assets/images/clock1.svg') }}">26 May, 2024; 09:30AM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection