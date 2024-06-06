<div class="sidebar-wrapper">
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}">
            <img class="" src="{{ assets('assets/images/logo2.svg') }}" alt="">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
    </div>
    <div class="sidebar-nav">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon"><img src="{{ assets('assets/images/dashboard.svg') }}"></span>
                        <span class="menu-title">{{ translate('Dashboard') }}</span>
                    </a>

                </li>
                <li class="nav-item {{ Route::is('admin.student.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.student.list') }}">
                        <span class="menu-icon"><img src="{{ assets('assets/images/students.svg') }}"></span>
                        <span class="menu-title">{{ translate('Students') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('admin.course.*') ? 'active' : '' }}"">
                    <a class="nav-link" href="{{ route('admin.course.list') }}">
                        <span class="menu-icon"><img src="{{ assets('assets/images/book.svg') }}"></span>
                        <span class="menu-title">{{ translate('Manage Course') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="products.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/products.svg') }}"></span>
                        <span class="menu-title">{{ translate('Manage Products') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="community.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/community.svg') }}"></span>
                        <span class="menu-title">{{ translate('Manage Community') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="blogs.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/blogs.svg') }}"></span>
                        <span class="menu-title">{{ translate('Manage Blogs') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="notifications.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/notification1.svg') }}"></span>
                        <span class="menu-title">{{ translate('Manage Notifications') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="earnings.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/earnings.svg') }}"></span>
                        <span class="menu-title">{{ translate('Earnings') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="performance.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/chart.svg') }}"></span>
                        <span class="menu-title">{{ translate('Performance') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="help-support.html">
                        <span class="menu-icon"><img src="{{ assets('assets/images/Help.svg') }}"></span>
                        <span class="menu-title">{{ translate('Help & Support') }}</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.logout') }}">
                        <span class="menu-icon"><img src="{{ assets('assets/images/logout.svg') }}"></span>
                        <span class="menu-title">{{ translate('Logout') }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>