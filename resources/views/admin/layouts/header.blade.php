 <header class="navbar">
        <div class="header header-inner container-fluid navbar-default d-flex">
            <div class="logo d-flex">
                <a href="{{ route('admin.dashboard') }}" class="line-high"><img src="{{ asset('assets/images/logo-signup.png') }}" alt="XCHANGE"></a>
                @if(auth()->user()->company->logo)
                <a href="{{ route('admin.dashboard') }}" class="company-header-logo"><img src="{{ asset(auth()->user()->company->logo) }}" alt="XCHANGE"></a>
                @endif
            </div>
            @if(auth()->user()->company->is_verified == 1)
            <span class="header-verify-btn">Verified</span>
            @endif
            <div class="header-right-inner d-flex align-items-center">
                <span class="notification">
                    <small class="notification-count d-flex align-items-center justify-content-center"></small>

                    <div id="mark-drop5">
                        <a href="#" class="dummy-btn d-flex"></a>
                                <ul class="drop-head-notf">
                                    <li>
                                        <ul class="nav nav-tabs tab" role="tablist2">
                                            <li class="nav-item">
            
                                                <a class="nav-link tablinks5" onclick="openCity5(event, 'notification')">Notifications</a>
                                                <small class="tab-notf-count2 d-flex align-items-center justify-content-center notifications-count">0</small>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link tablinks5 active" onclick="openCity5(event, 'announcements')">Announcements</a>
                                                <small class="tab-notf-count2 d-flex align-items-center justify-content-center announcements-count">0</small>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                <li id="notification" class="tabcontent5 notification-box" style="display: none;">
                                    
                                </li>

                                <li id="announcements" class="tabcontent5 announcement-box" style="display: block;">
                                    
                                </li>

                               
                            </ul>
                        </div>
                </span>
                <div id="mark-drop4">
                    <span class="user-type-float"><marquee scrollamount="3">{{ auth()->user()->company->name ?? '' }}</marquee></span>
                    <a href="#" class="" style="float: right;">{{ auth()->user()->name }}</a>
                    <ul class="drop-profile2">
                        <li onclick="window.location.href = '{{ route('profile.index') }}'">Profile Settings</li>
                        <li onclick="window.location.href = '{{ route('subscription') }}'">Subscription</li>
                        <li onclick="window.location.href = '{{ route('billing') }}'">Billing</li>
                        <li onclick="window.location.href = '{{ route('logout') }}'">Logout</li>
                    </ul>
                </div>
            </div>
            
            
            <span class="tooltip-nav-main nav-tooltip-1"><span class="tooltip-arrow2"></span>Dashboard</span>
            <span class="tooltip-nav-main nav-tooltip-2"><span class="tooltip-arrow2"></span>Upcoming Events</span>


            <div class="side-nav-main position-fixed">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="logo-nav hide-logo" id="logo-toogle">
                        <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/logo-signup.png') }}" alt="XCHANGE"></a>
                    </div>
                    <a href="#" class="nav-expand-ico"></a>
                </div>
                 <ul>
                    <li class="d-flex align-items-center active my-quotes"><a href="{{ route('admin.dashboard') }}"> <i><img
                                    src="{{ asset('assets/images/dashboard-apps-ico.svg') }}"></i></a><a href="{{ route('admin.dashboard') }}" class="nav-txt">Dashboard</a>
                            
                    </li>
                    <li class="d-flex align-items-center review-list"><a href="{{ route('admin.upcomingEvents') }}"> <i><img
                        src="{{ asset('assets/images/upcoming-events-ico.svg') }}"></i></a><a href="{{ route('admin.upcomingEvents') }}"
                class="nav-txt">
                Upcoming Events</a>
                    </li>
                    
                </ul>
            </div>

        </div>

    </header>   