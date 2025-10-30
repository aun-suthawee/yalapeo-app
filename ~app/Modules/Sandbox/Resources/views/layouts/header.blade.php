<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid px-4">

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sandbox.dashboard') }}">
                        <i class="fas fa-chart-bar"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sandbox.schools.index') }}">
                        <i class="fas fa-school"></i> โรงเรียน
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sandbox.infographic') }}">
                        <i class="fas fa-chart-pie"></i> Infographic
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sandbox.experiments.index') }}">
                        <i class="fas fa-flask"></i> What-If Analysis
                    </a>
                </li>

                @auth
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar me-2">
                            @if(auth()->user()->image)
                                <img src="{{ asset('userfiles/images/' . auth()->user()->image) }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; font-size: 16px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="user-info d-none d-lg-block">
                            <div class="user-name fw-semibold" style="font-size: 14px; line-height: 1.2;">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="user-role text-muted" style="font-size: 12px;">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown" style="min-width: 250px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                @if(auth()->user()->image)
                                    <img src="{{ asset('userfiles/images/' . auth()->user()->image) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; font-size: 18px;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ url('/admin/dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2" style="width: 20px;"></i>
                                Dashboard หลัก
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ url('/admin/account') }}">
                                <i class="fas fa-user-circle me-2" style="width: 20px;"></i>
                                บัญชีของฉัน
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ url('/admin/account/password') }}">
                                <i class="fas fa-key me-2" style="width: 20px;"></i>
                                เปลี่ยนรหัสผ่าน
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ url('/admin/logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="fas fa-sign-out-alt me-2" style="width: 20px;"></i>
                                    ออกจากระบบ
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <!-- Login Button -->
                <li class="nav-item ms-3">
                    <a class="btn btn-primary btn-sm" href="{{ url('/admin/login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> เข้าสู่ระบบ
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
/* User Dropdown Styles */
.navbar {
    padding: 0.75rem 0;
}

.navbar-brand {
    font-size: 1.1rem;
}

.nav-link {
    font-weight: 500;
    color: #495057;
    padding: 0.5rem 1rem;
    transition: color 0.3s ease;
}

.nav-link:hover {
    color: #007bff;
}

.nav-link i {
    margin-right: 0.25rem;
}

.user-avatar {
    position: relative;
}

.avatar-placeholder {
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.dropdown-menu {
    border: none;
    border-radius: 12px;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
}

.dropdown-item {
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.75rem;
}

.dropdown-item i {
    opacity: 0.7;
}

.dropdown-item:hover i {
    opacity: 1;
}

.dropdown-item.text-danger:hover {
    background-color: #fff5f5;
    color: #dc3545 !important;
}

/* Responsive */
@media (max-width: 991px) {
    .user-info {
        display: none !important;
    }
    
    .navbar-nav {
        padding-top: 1rem;
    }
    
    .nav-item.dropdown {
        margin-left: 0 !important;
        margin-top: 0.5rem;
    }
}
</style>
