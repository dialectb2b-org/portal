<header class="navbar">
        <div class="header header-inner container-fluid navbar-default d-flex">
            <div class="logo">
                <a href="{{ route('sales.dashboard') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
            </div>
            <div class="header-right-inner d-flex align-items-center">
                <span  class="notification">
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
                    <a href="#" class="" style="float: right;">{{ auth()->user()->name ?? '' }}</a>
                    <ul class="drop-profile2">
                        <li onclick="window.location.href = '{{ route('profile.index') }}'">Profile Settings</li>
                        <li onclick="window.location.href = '#'">Subscription</li>
                        <li onclick="window.location.href = '{{ route('subscription') }}'">Billing</li>
                        <li onclick="window.location.href = '{{ route('logout') }}'">Logout</li>
                    </ul>
                </div>
            </div>

            <span class="tooltip-nav-main nav-tooltip-1"><span class="tooltip-arrow2"></span>Received</span>
            <span class="tooltip-nav-main nav-tooltip-2"><span class="tooltip-arrow2"></span>Replied</span>
            <span class="tooltip-nav-main nav-tooltip-3"><span class="tooltip-arrow2"></span>Draft</span>
            <span class="tooltip-nav-main nav-tooltip-4"><span class="tooltip-arrow2"></span>Expired</span>
            <span class="tooltip-nav-main nav-tooltip-5"><span class="tooltip-arrow2"></span>Upcoming Events</span>
            
            <div class="side-nav-main">
                
                <div class="d-flex align-items-center justify-content-end">
                    <div class="logo-nav hide-logo" id="logo-toogle">
                        <a href="{{ route('sales.dashboard') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
                    </div>
                    <a href="#" class="nav-expand-ico"></a>
                </div>
                <ul>
                    <li class="d-flex align-items-center {{ (request()->is('sales/dashboard')) ? 'active' : '' }} my-quotes"><a href="{{ route('sales.dashboard') }}"> <i><img
                                    src="{{ asset('assets/images/my-quotes-ico.svg') }}"></i></a><a href="{{ route('sales.dashboard') }}" class="nav-txt"> Received</a>
                            
                    </li>
                    <li class="d-flex align-items-center {{ (request()->is('sales/replied-enquiry')) ? 'active' : '' }} review-list"><a href="{{ route('sales.repliedEnquiry') }}"> <i><img
                                    src="{{ asset('assets/images/replied-ico.svg') }}"></i></a><a href="{{ route('sales.repliedEnquiry') }}"
                            class="nav-txt">Replied</a>
                    </li>
                    <li class="d-flex align-items-center {{ (request()->is('sales/draft')) ? 'active' : '' }} draft"><a href="{{ route('sales.draft') }}"> <i><img
                                    src="{{ asset('assets/images/draft-ico.svg') }}"></i></a><a href="{{ route('sales.draft') }}" class="nav-txt">Draft</a>
                    </li>
                    <li class="d-flex align-items-center {{ (request()->is('sales/expired-enquiry')) ? 'active' : '' }} completed-bidding"><a href="{{ route('sales.expiredEnquiry') }}"> <i><img
                                    src="{{ asset('assets/images/expired-ico.svg') }}"></i></a><a href="{{ route('sales.expiredEnquiry') }}"
                            class="nav-txt">Expired</a></li>
                    <li class="d-flex align-items-center {{ (request()->is('sales/upcoming-events')) ? 'active' : '' }} team-settings"><a href="{{ route('sales.events') }}"> <i><img
                                    src="{{ asset('assets/images/upcoming-events-ico.svg') }}"></i></a><a href="{{ route('sales.events') }}"
                            class="nav-txt">Upcoming Events</a></li>
                    
                </ul>
            </div>
        </div>

    </header>