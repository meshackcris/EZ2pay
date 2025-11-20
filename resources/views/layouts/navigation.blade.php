<!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{ config('app.url')}}" class="brand-logo">
               <img src="{{asset('images/logo-full.webp') }}"alt="" width="200px" style="display: flex; justify-content: center;">
            
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
        
        <!--**********************************
            Header start
        ***********************************-->
        <div class="header border-bottom">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Dashboard
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
                <div class="dropdown header-profile2 ">
                    <a class="nav-link " href="javascript:void(0);"  role="button" data-bs-toggle="dropdown">
                        <div class="header-info2 d-flex align-items-center border">
                            <img src="{{asset('images/pic1.png') }}" alt=""/>
                            <div class="d-flex align-items-center sidebar-info">
                                <div>
                                    <span class="font-w700 d-block mb-2">{{ Auth::user()->name }}</span>
                                    <small class="text-end font-w400">Super Admin</small>
                                </div>
                                <i class="fas fa-sort-down ms-4"></i>
                            </div>
                            
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
    <!-- Profile -->
    <a href="{{ route('settings') }}" class="dropdown-item d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary me-2" width="18" height="18"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>Profile</span>
    </a>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item d-flex align-items-center text-danger bg-transparent border-0 w-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Log Out</span>
        </button>
    </form>
</div>

                <ul class="metismenu" id="menu">

    <li>
        <a class="has-arrow" href="{{ route('dashboard') }}" aria-expanded="false">
            <i class="fas fa-tachometer-alt"></i> <!-- Dashboard -->
            <span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('user.management') }}" aria-expanded="false">
            <i class="fas fa-users"></i> <!-- User Management -->
            <span class="nav-text">User Management</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('payment.management') }}" aria-expanded="false">
            <i class="fas fa-file-invoice-dollar"></i> <!-- Payment Management -->
            <span class="nav-text">Payment Management</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('interact.transactions') }}" aria-expanded="false">
            <i class="fas fa-envelope-open-text"></i> <!-- Interac Payments -->
            <span class="nav-text">Interac Payments</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('eft.transactions') }}" aria-expanded="false">
            <i class="fas fa-university"></i> <!-- EFT Payments -->
            <span class="nav-text">EFT Payments</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('ftd.transactions') }}" aria-expanded="false">
            <i class="fas fa-wallet"></i> <!-- FTD Payments -->
            <span class="nav-text">FTD Payments</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('kyc') }}" aria-expanded="false">
            <i class="fas fa-id-card"></i> <!-- KYC Applications -->
            <span class="nav-text">KYC Applications</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('withdrawals') }}" aria-expanded="false">
            <i class="fas fa-hand-holding-usd"></i> <!-- Withdrawals -->
            <span class="nav-text">Client Withdrawals</span>
        </a>
    </li><li>
        <a class="has-arrow" href="{{ route('refwithdrawals') }}" aria-expanded="false">
            <i class="fas fa-hand-holding-usd"></i> <!-- Withdrawals -->
            <span class="nav-text">Referral Withdrawals</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('brands') }}" aria-expanded="false">
            <i class="fas fa-tags"></i> <!-- Brands -->
            <span class="nav-text">Brands</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('referrers') }}" aria-expanded="false">
            <i class="fas fa-tags"></i> <!-- Brands -->
            <span class="nav-text">Referrers</span>
        </a>
    </li>

    <li>
        <a class="has-arrow" href="{{ route('settings') }}" aria-expanded="false">
            <i class="fas fa-cogs"></i> <!-- Settings -->
            <span class="nav-text">Settings</span>
        </a>
    </li>

</ul>

                
                <div class="copyright">
                    <p><strong>Orion Pay Admin Dashboard</strong> © 2025 All Rights Reserved</p>
                    <p class="fs-12">Made with &nbsp;<i class=" text-red fas fa-heart"></i> &nbsp; by Orion Dev Team</p>
                </div>
            </div>
        </div>
    </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
        