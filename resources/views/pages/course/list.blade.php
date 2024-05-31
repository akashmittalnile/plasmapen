@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Course</h2>
        </div>
        <div class="plas-search-filter wd80">
            <div class="row g-1">
                <div class="col-md-3">
                    <div class="form-group search-form-group">
                        <input type="text" class="form-control" name="Start Date" placeholder="Search by course name">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <ul class="statusradio-list">
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="antigentype" id="Published">
                                    <label for="Published">Published</label>
                                </div>
                            </li>
                            <li>
                                <div class="statusradio">
                                    <input type="radio" name="antigentype" id="Deleted">
                                    <label for="Deleted">Deleted</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <a href="coupon.html" class="btn-pi">Manage Coupon</a>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <a href="{{ route('admin.course.create') }}" class="btn-bl">Create New Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course-section">
        <div class="row">
            <div class="col-md-3">
                <div class="course-card">
                    <div class="course-card-image">
                        <img src="images/1.png">
                        <div class="published-text"><img src="images/tick-circle.svg">Published</div>
                    </div>
                    <div class="course-card-content">
                        <h2>About: Transderma Serums</h2>
                        <div class="course-card-point-text">
                            <div class="coursefee-text">$13.00</div>
                            <div class="lesson-text">7 Lessons</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="course-card">
                    <div class="course-card-image">
                        <img src="images/2.png">
                        <div class="published-text"><img src="images/tick-circle.svg">Published</div>
                    </div>
                    <div class="course-card-content">
                        <h2>About: Accessories & Consumables</h2>
                        <div class="course-card-point-text">
                            <div class="coursefee-text">$13.00</div>
                            <div class="lesson-text">18 Lessons</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="course-card">
                    <div class="course-card-image">
                        <img src="images/3.png">
                        <div class="published-text"><img src="images/tick-circle.svg">Published</div>
                    </div>
                    <div class="course-card-content">
                        <h2>1- Introduction to the Academy</h2>
                        <div class="course-card-point-text">
                            <div class="coursefee-text">$13.00</div>
                            <div class="lesson-text">1 Lessons</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="course-card">
                    <div class="course-card-image">
                        <img src="images/4.png">
                        <div class="published-text"><img src="images/tick-circle.svg">Published</div>
                    </div>
                    <div class="course-card-content">
                        <h2>About: Exosomes</h2>
                        <div class="course-card-point-text">
                            <div class="coursefee-text">$13.00</div>
                            <div class="lesson-text">1 Lesson</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection