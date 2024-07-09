<header class="navbar">
    <div class="header-signup container-fluid navbar-default d-flex">
        <div class="container position-relative d-flex justify-content-between">
            <div class="logo">
                <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/images/logo-signup.png') }}" alt="XCHANGE"></a>
            </div>

            <div class="header-right-inner">
                <div id="mark-drop2">
                    <a href="#" class="" style="float: right;">{{ auth()->user()->name == '' ? 'Administrator' : auth()->user()->name }}</a>
                    <ul class="drop-profile">
                        <!--<li onclick="#">Profile Settings</li>-->
                        <!--<li onclick="#">Subscriptions</li>-->
                        <li onclick="window.location.href = '{{ route('logout') }}'">Logout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

