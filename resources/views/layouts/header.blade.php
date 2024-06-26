<div class="header-1">
    <nav class="navbar">
        <div class="navbar-menu-wrapper">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link toggle-sidebar mon-icon-bg" href="javascript:void(0)">
                        <img src="{{ assets('assets/images/sidebartoggle.svg') }}">
                    </a>
                </li>
            </ul>
            <!-- <div class="col-md-1" style="position: absolute; right: 8.5%">
                <select class="form-control changeLang">
                    <option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="es" {{ session()->get('locale') == 'es' ? 'selected' : '' }}>Spanish</option>
                </select>
            </div> -->
            <ul class="navbar-nav">
                <li class="nav-item lang-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="lang-icon">
                            <img src="{{ assets('assets/images/'.config('app.locale').'.png' ) }}" alt="{{config('app.locale')}}">
                            {{ session()->get('locale') == 'es' ? 'ES' : 'EN' }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('changeLang', ) }}?lang=en" class="dropdown-item">
                            English (EN)
                        </a>
                        <a href="{{ route('changeLang', ) }}?lang=es" class="dropdown-item">
                            Spanish (ES)
                        </a>
                    </div>
                </li>
                <li class="nav-item noti-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="noti-icon">
                            <img src="{{ assets('assets/images/notification.svg') }}" alt="user">
                            <span class="noti-badge"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-header">
                            <span class="heading">Notifications</span>
                            <span class="count ng-binding" id="dd-notifications-count">3</span>
                        </div>
                        <div class="dropdown-body">
                            <div class="notification-dropdown-box">
                                <div class="notification-dropdown-item">
                                    <div class="notification-dropdown-icon">
                                        <a href="#"><img src="{{ assets('assets/images/notification.svg') }}"></a>
                                    </div>
                                    <div class="notification-dropdown-content">
                                        <div class="notification-dropdown-descr">
                                            <p>Your order has been confirmed. Thank you for shopping with us!</p>
                                        </div>
                                        <div class="notification-dropdown-date">Pushed on:
                                            05-30-2024 11:37AM
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-dropdown-item">
                                    <div class="notification-dropdown-icon">
                                        <a href="#"><img src="{{ assets('assets/images/notification.svg') }}"></a>
                                    </div>
                                    <div class="notification-dropdown-content">
                                        <div class="notification-dropdown-descr">
                                            <p>Your order has been confirmed. Thank you for shopping with us!</p>
                                        </div>
                                        <div class="notification-dropdown-date">Pushed on:
                                            05-30-2024 11:37AM
                                        </div>
                                    </div>
                                </div>

                                <div class="notification-dropdown-item">
                                    <div class="notification-dropdown-icon">
                                        <a href="#"><img src="{{ assets('assets/images/notification.svg') }}"></a>
                                    </div>
                                    <div class="notification-dropdown-content">
                                        <div class="notification-dropdown-descr">
                                            <p>Your order has been confirmed. Thank you for shopping with us!</p>
                                        </div>
                                        <div class="notification-dropdown-date">Pushed on:
                                            05-30-2024 11:37AM
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic"><img src="{{ assets('assets/images/user.png') }}" alt="user"> </div>
                    </a>
                    <div class="dropdown-menu">
                        <a href="javascript:void(0)" class="dropdown-item">
                            <i class="las la-user"></i> {{ translate('Profile') }}
                        </a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openLogoutModal" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i> {{ translate('Logout') }}
                        </a>
                    </div>
                </li>
            </ul>
            </ul>
        </div>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </nav>
</div>
<!-- LOGOUT -->
<div class="modal lm-modal fade" id="openLogoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Plasma-modal-form">
                    <h2>{{ translate('Are You Sure?') }}</h2>
                    <p>{{ translate('You want to logout!') }}</p>
                    <form action="{{ route('admin.logout') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="save-btn">{{ translate('Yes! Logout') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>