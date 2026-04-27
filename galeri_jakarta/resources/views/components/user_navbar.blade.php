<!-- USER NAVBAR COMPONENT -->
<style>
    .global-user-navbar {
        position: absolute;
        top: 20px;
        right: 40px;
        z-index: 10000;
    }
    .user-profile-btn {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 30px;
        padding: 5px 15px 5px 5px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        color: #333;
        text-decoration: none;
    }
    .user-profile-btn:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .user-profile-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #212529;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    .user-profile-name {
        font-size: 14px;
        font-weight: 600;
        font-family: sans-serif;
    }
    
    .profile-dropdown-menu {
        display: none;
        position: absolute;
        top: 55px;
        right: 0;
        width: 250px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        overflow: hidden;
        border: 1px solid #eee;
        font-family: sans-serif;
    }
    .profile-dropdown-menu.show {
        display: block;
        animation: fadeInDown 0.3s ease;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-header-custom {
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    .dropdown-header-custom .avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #212529;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        margin: 0 auto 10px;
    }
    .dropdown-header-custom h6 { margin: 0; font-size: 16px; font-weight: bold; color: #333; }
    .dropdown-header-custom p { margin: 5px 0 0; font-size: 12px; color: #666; }
    .dropdown-header-custom .badge { margin-top: 8px; }

    .dropdown-item-custom {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #444;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s;
        border-bottom: 1px solid #f1f1f1;
    }
    .dropdown-item-custom:hover {
        background: #f8f9fa;
        color: #000;
    }
    .dropdown-item-custom i { font-size: 18px; color: #555; }
    .dropdown-item-custom.logout { color: #dc3545; }
    .dropdown-item-custom.logout i { color: #dc3545; }

    @media (max-width: 768px) {
        .user-profile-name { display: none; }
        .user-profile-btn { padding: 5px; }
        .global-user-navbar { top: 15px; right: 15px; }
    }
</style>

<div class="global-user-navbar" id="globalUserNavbar">
    @auth
        <div class="user-profile-btn" onclick="toggleProfileDropdown(event)">
            <div class="user-profile-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-profile-name">
                {{ explode(' ', Auth::user()->name)[0] }} <i class="bi bi-chevron-down ms-1" style="font-size:12px;"></i>
            </div>
        </div>

        <div class="profile-dropdown-menu" id="profileDropdown">
            <div class="dropdown-header-custom">
                <div class="avatar-large">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <h6>{{ Auth::user()->name }}</h6>
                <p>{{ Auth::user()->email }}</p>
                @if(Auth::user()->subscription === 'premium')
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Premium</span>
                @else
                    <span class="badge bg-secondary">Free</span>
                @endif
            </div>
            
            <a href="{{ route('user.profile') }}#detail" class="dropdown-item-custom">
                <i class="bi bi-person-vcard"></i> Detail Profile
            </a>
            <a href="{{ route('user.profile') }}#edit" class="dropdown-item-custom">
                <i class="bi bi-pencil-square"></i> Kelola Profile
            </a>
            <a href="{{ route('user.profile') }}#langganan" class="dropdown-item-custom">
                <i class="bi bi-card-checklist"></i> Status Langganan
            </a>
            
            <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none;">@csrf</form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="dropdown-item-custom logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    @endauth
</div>

<script>
    function toggleProfileDropdown(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    });

    // Prevent closing when clicking inside dropdown
    document.getElementById('profileDropdown')?.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>
