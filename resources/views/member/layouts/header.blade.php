<header class="navbar">
    <div class="header header-inner container-fluid navbar-default d-flex">
        <div class="logo">
            <a href="{{ route('member.dashboard') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
        </div>
        <div class="header-right-inner d-flex align-items-center">
            <!-- Todo List -->
                <span class="todo-list">
                    <small class="notification-count-to-do d-flex align-items-center justify-content-center"></small>
                    <div id="mark-drop">
                        <a href="#" class="dummy-btn d-flex"></a>
                        <ul class="drop-todo">
                
                            <li class="pb-0 border-bottom-0 d-flex justify-content-between">
                                <h1 class="to-do-head">To Do List
                                    <small class="tab-notf-count3 d-flex align-items-center justify-content-center todo-list-holder-count">0</small>
                                    </h2>
                                    <a href="#" class="close-big"></a>
                            </li>
                            <ul class="todo-list-holder">
                               
                               
                            </ul>
                        </ul>
                    </div>
                </span>
            <!-- End todo list -->
            <span  class="notification">
                <small class="notification-count d-flex align-items-center justify-content-center"></small>
                <div id="mark-drop5">
                        <a href="#" class="dummy-btn2 d-flex"></a>
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
                    <li onclick="window.location.href = '{{ route('logout') }}'">Logout</li>
                </ul>
            </div>
        </div>

        <span class="tooltip-nav-main nav-tooltip-1"><span class="tooltip-arrow2"></span>My Quotes</span>
        <span class="tooltip-nav-main nav-tooltip-2"><span class="tooltip-arrow2"></span>Review List</span>
        <span class="tooltip-nav-main nav-tooltip-3"><span class="tooltip-arrow2"></span>Draft</span>
        <span class="tooltip-nav-main nav-tooltip-4"><span class="tooltip-arrow2"></span>Completed Bidding</span>
        <span class="tooltip-nav-main nav-tooltip-6"><span class="tooltip-arrow2"></span>Upcoming Events</span>
        
        <div class="side-nav-main">
            
            <div class="d-flex align-items-center justify-content-end">
                <div class="logo-nav hide-logo" id="logo-toogle">
                    <a href="{{ route('member.dashboard') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="XCHANGE"></a>
                </div>
                <a href="#" class="nav-expand-ico"></a>
            </div>
            <ul>
                <li class="d-flex align-items-center {{ (request()->is('member/dashboard')) ? 'active' : '' }} my-quotes"><a href="{{ route('member.dashboard') }}"> <i><img
                                src="{{ asset('assets/images/my-quotes-ico.svg') }}"></i></a><a href="{{ route('member.dashboard') }}" class="nav-txt"> My
                        Quotes</a>
                        
                </li>
                <li class="d-flex align-items-center {{ (request()->is('member/review-list/*')) ? 'active' : '' }} review-list"><a href="{{ route('member.reviewList.send') }}"> <i><img
                                src="{{ asset('assets/images/review-list-ico.svg') }}"></i></a><a href="{{ route('member.reviewList.send') }}"
                        class="nav-txt">Review List</a>
                </li>
                <li class="d-flex align-items-center {{ (request()->is('member/draft')) ? 'active' : '' }} draft"><a href="{{ route('member.draft') }}"> <i><img
                                src="{{ asset('assets/images/draft-ico.svg') }}"></i></a><a href="{{ route('member.draft') }}" class="nav-txt">Draft</a>
                </li>
                <li class="d-flex align-items-center {{ (request()->is('member/completed-bidding/*')) ? 'active' : '' }} completed-bidding"><a href="{{ route('member.completedBidding.send') }}"> <i><img
                                src="{{ asset('assets/images/completed-bidding-ico.svg') }}"></i></a><a href="{{ route('member.completedBidding.send') }}"
                        class="nav-txt">Completed
                        Bidding </a></li>
                <li class="d-flex align-items-center {{ (request()->is('member/upcoming-events')) ? 'active' : '' }} upcoming-events"><a href="{{ route('member.upcomingEvents') }}"> <i><img
                                src="{{ asset('assets/images/upcoming-events-ico.svg') }}"></i></a><a href="{{ route('member.upcomingEvents') }}"
                        class="nav-txt">
                        Upcoming Events</a></li>
            </ul>
        </div>
    </div>
</header>