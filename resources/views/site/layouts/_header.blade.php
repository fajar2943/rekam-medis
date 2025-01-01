<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center"><a
                        href="mailto:gumilangcode@gmail.com">gumilangcode@gmail.com</a></i>
                <i class="bi bi-phone d-flex align-items-center ms-4"><span>+62 821 5886 3345</span></i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="{{ asset('medilab/img/logo.png') }}" alt=""> -->
                <h1 class="sitename">{{ config('app.name') }}</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{isUri('') ? '#hero' : '/#hero'}}">Home<br></a></li>
                    <li><a href="{{isUri('') ? '#about' : '/#about'}}">About</a></li>
                    <li><a href="{{isUri('') ? '#services' : '/#services'}}">Services</a></li>
                    <li><a href="{{isUri('') ? '#departments' : '/#departments'}}">Departments</a></li>
                    <li><a href="{{isUri('') ? '#doctors' : '/#doctors'}}">Doctors</a></li>
                    <li><a href="{{isUri('') ? '#contact' : '/#contact'}}">Contact</a></li>
                    @if (authAs('patient'))
                        <li><a href="/appointment" class="{{uriActive('appointment')}}">Appointment</a></li>
                    @elseif(authAs('doctor'))
                        <li><a href="/workspace" class="{{uriActive('workspace')}}">Workspace</a></li>                        
                    @elseif(authAs('pharmacist'))
                        <li><a href="/pharmacy" class="{{uriActive('pharmacy')}}">Pharmacy</a></li>                        
                    @endif
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            @if (authAs())
                <a class="cta-btn d-none d-sm-block" id="userProfile" href="#!">{{auth()->user()->name}}</a>
            @else
                <a class="cta-btn d-none d-sm-block" href="/login">Login</a>
            @endif

        </div>

    </div>

</header>
