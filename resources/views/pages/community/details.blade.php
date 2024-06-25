@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community.css') }}">
@endpush

@section('content')
<div class="body-main-content">
    <div class="plas-filter-section">
        <div class="plas-filter-heading">
            <h2>Manage Community </h2>
        </div>
        <div class="plas-search-filter wd40">
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="Accountapproval-btn" href="javascript:void(0)">Edit</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <a class="Delete-btn" href="javascript:void(0)">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="community-section">
        <div class="row">
            <div class="col-md-3">
                <div class="community-sidebar">
                    <h2>Member List</h2>
                    <div class="community-sidebar-Memberlist" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">

                        @forelse($community->communityFollower as $val)
                        <div class="community-sidebar-Memberitem">
                            <div class="community-sidebar-Memberitem-image">
                                <img src="{{ isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg') }}">
                            </div>
                            <div class="community-sidebar-Memberitem-content">
                                <h5>{{ $val->user->name ?? 'NA' }}</h5>
                                <p>Joined On {{ date('m-d-Y', strtotime($val->created_at)) }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="sidebar-member-item" style="border: none;">
                            <div class="mx-auto">
                                No followers
                            </div>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="community-detail-card">
                    <div class="community-detail-image owl-carousel owl-theme">
                        @forelse($imgs as $val)
                        <div class='item'>
                            <div class='community-media'>
                                <a data-fancybox='' href="{{ assets("uploads/community/$val->item_name") }}">
                                    <img src="{{ assets("uploads/community/$val->item_name") }}">
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class='item'>
                            <div class='community-media'>
                                <img src="{{ assets('assets/images/no-image.jpg') }}">
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <div class="community-detail-content">
                        <h2>{{ $community->name ?? 'NA' }}</h2>
                        <p>{{ $community->description ?? 'NA' }}</p>

                        <div class="community-card-point-text">
                            <div class="blogby-text">Total Posts <span>{{ count($community->communityPost) }}</span></div>
                            <div class="date-text">Created on <span>{{ date('m-d-Y h:iA') }}</span></div>
                        </div>
                        <div class="community-detail-member-item">
                            <div class="community-detail-member-info">
                                @forelse($follow as $key => $val)
                                <span class="community-detail-member-image image{{$key+1}}">
                                    <img src="{{ isset($val->user->profile) ? assets('uploads/profile/'.$val->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </span>
                                @empty
                                <span class="community-detail-member-image image1">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </span>
                                @endforelse
                            </div>
                            <p>{{ count($community->communityFollower) }} Member Follows</p>
                        </div>

                        <div class="community-switch-toggle">
                            <h4>Mark AS INACTIVE</h4>
                            <div class="community-switch-toggle-content">
                                <p>ACTIVE</p>
                                <div class="">
                                    <label class="toggle" for="myToggle">
                                        <input class="toggle__input" name="" type="checkbox" id="myToggle">
                                        <div class="toggle__fill"></div>
                                    </label>
                                </div>
                                <p>INACTIVE</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="plas-filter-section">
                    <div class="plas-filter-heading">
                        <h2>Posts</h2>
                    </div>
                    <div class="plas-search-filter wd60">
                        <div class="row g-1">
                            <div class="col-md-8">
                                <div class="form-group search-form-group">
                                    <input type="text" class="form-control" name="" placeholder="Search by Student name, posts">
                                    <span class="search-icon"><img src="images/search-icon.svg"></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <a class="Accountapproval-btn" data-bs-toggle="modal" data-bs-target="#PostonCommunity">Post On Community</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="Post-card">
                                <div class="Post-card-head">
                                    <h2>Appeex Bundle</h2>
                                </div>
                                <div class="Post-card-image">
                                    <a href="post-details.html"><img src="images/p1.png"></a>
                                </div>
                                <div class="Post-card-content">
                                    <div class="Post-card-date"><img src="images/calendar1.svg"> January 10 2023 </div>
                                    <p>Pay in 4 payments of $25.00 USD with ⓘ For the treatment of: uneven skin texture, facial skin laxity, sunspots and sun damage, enlarged pores, chloasma, melasma and blotchiness, dull complexion, atrophic and hypertrophic scars etcAppeex Peel is a three phase peel of a new generation with a unique, patented formula consisting of three main components:TCA 20%</p>
                                    <div class="Post-action">
                                        <a class="Like-btn"><img src="images/like1.svg"> Like (45k)</a>
                                        <a class="Comment-btn" href="#"><img src="images/Comment1.svg"> Comment (45k)</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="Post-card">
                                <div class="Post-card-head">
                                    <h2>Appeex Bundle</h2>
                                </div>
                                <div class="Post-card-image">
                                    <a href="post-details.html"><img src="images/p1.png"></a>
                                </div>
                                <div class="Post-card-content">
                                    <div class="Post-card-date"><img src="images/calendar1.svg"> January 10 2023 </div>
                                    <p>Pay in 4 payments of $25.00 USD with ⓘ For the treatment of: uneven skin texture, facial skin laxity, sunspots and sun damage, enlarged pores, chloasma, melasma and blotchiness, dull complexion, atrophic and hypertrophic scars etcAppeex Peel is a three phase peel of a new generation with a unique, patented formula consisting of three main components:TCA 20%</p>
                                    <div class="Post-action">
                                        <a class="Like-btn"><img src="images/like1.svg"> Like (45k)</a>
                                        <a class="Comment-btn" href="#"><img src="images/Comment1.svg"> Comment (45k)</a>
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

@push('js')
<script>
    $('.community-detail-image').owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            1000: {
                items: 1
            }
        }
    });
</script>
@endpush