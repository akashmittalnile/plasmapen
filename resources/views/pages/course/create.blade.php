@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/course.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Create Course</h2>
        </div>
        <div class="plas-search-filter wd40">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <a href="coupon.html" class="btn-pi">Back</a>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <a href="#" class="btn-bl">Save</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="course-section">
        <div class="row">
            <div class="col-md-12">
                <div class="courses-form-section">
                    <div class="courses-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Course Title</h4>
                                    <input type="text" class="form-control" name="" placeholder="Title" value="2- About: Accessories & Consumables">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Description</h4>
                                    <textarea type="text" class="form-control" name="" placeholder="Type Course Description Here…"></textarea>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Course Fees</h4>
                                    <input type="text" class="form-control" name="" placeholder="Enter Course Fees">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Upload Course Certificates</h4>
                                    <input type="file" class="form-control" name="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Valid Up-To</h4>
                                    <input type="month" class="form-control" name="" placeholder="4 Month">
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <h4>Disclaimers & Introduction</h4>
                                    <input type="file" class="form-control" name="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <button class="add-more">Add More Video Or PDF</button>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="added-course-card">
                                    <div class="added-course-card-image">
                                        <img src="images/2.png">
                                    </div>
                                    <div class="added-course-card-content">
                                        <h2>About: Transderma Serums</h2>
                                        <div class="coursefee-text">$13.00</div>
                                        <p>Louise Walsh Academy is renowned for its expertise in the field, and the courses are designed and taught by industry experts. Enrolling in their courses means you're learning from the best. </p>
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