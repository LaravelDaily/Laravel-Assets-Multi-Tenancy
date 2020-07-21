<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                Home
            </a>
        </li>
        @if (auth()->user()->is_admin)
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.tenants.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    Tenant management
                </a>
            </li>
        @elseif (auth()->user()->is_tenant_admin)
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    User management
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    Role management
                </a>
            </li>
        @endif
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.profile.edit") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                </i>
                My profile
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                Logout
            </a>
        </li>
    </ul>

</div>
