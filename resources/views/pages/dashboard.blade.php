@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/home.css') }}">
@endpush
@push('js')
<script src="{{ assets('assets/js/dashboard.js') }}" type="text/javascript"></script>
@endpush
@section('content')
<div class="body-main-content">
    <div class="overview-section">
        <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>{{ translate('Total Active Course') }}</p>
                                <h2>6,502</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/activebook.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>{{ translate('Total Inactive Course') }}</p>
                                <h2>6,502</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/inactivebook.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>{{ translate('Total Active Students') }}</p>
                                <h2>{{ $activeStu ? sprintf("%02d", $activeStu) : 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/studentsactive.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>{{ translate('Total Inactive Student') }}</p>
                                <h2>{{ $inactiveStu ? sprintf("%02d", $inactiveStu) : 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/inactivestudents.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>{{ translate('Total Listed Products') }}</p>
                                <h2>6,502</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/listedproducts.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="plas-chart-section">
        <div class="plas-chart-head-overview">
            <div class="row">
                <div class="col-md-4">
                    <div class="plas-chart-card">
                        <div class="plas-chart-card-image"><img src="{{ assets('assets/images/dollar-circle.png') }}"></div>
                        <div class="plas-chart-card-text">
                            <h2>{{ translate('Total Revenue') }}</h2>
                            <div class="Overview-price">$1799.00</div>
                            <div class="overview-date">May,2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plas-chart-card">
                        <div class="plas-chart-card-image"><img src="{{ assets('assets/images/book-square.svg') }}"></div>
                        <div class="plas-chart-card-text">
                            <h2>{{ translate('Total Course Enrollments') }}</h2>
                            <div class="Overview-price">12</div>
                            <div class="overview-date">May,2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plas-chart-card">
                        <div class="plas-chart-card-image"><img src="{{ assets('assets/images/star1.svg') }}"></div>
                        <div class="plas-chart-card-text">
                            <h2>{{ translate('Total courses rating') }}</h2>
                            <div class="Overview-rating"> 4.7</div>
                            <div class="overview-date">May,2023</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="plas-card-chart">
            <div class="plas-chart-filter wd20">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="date" class="form-control" name="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="" id="salechart"></div>
        </div>
    </div>
</div>
@endsection