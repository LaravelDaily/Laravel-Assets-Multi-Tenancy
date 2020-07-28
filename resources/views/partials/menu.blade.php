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
        @can('tenant_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.tenants.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    Tenant management
                </a>
            </li>
        @endcan
        @can('user_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    User management
                </a>
            </li>
        @endcan
        @can('role_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user">

                    </i>
                    Role management
                </a>
            </li>
        @endcan
        @can('asset_group_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.asset-groups.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-list">

                    </i>
                    Asset groups management
                </a>
            </li>
        @endcan
        @can('asset_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.assets.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-industry">

                    </i>
                    Asset management
                </a>
            </li>
        @endcan
        @can('image_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.images.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-image">

                    </i>
                    Image management
                </a>
            </li>
        @endcan
        @can('document_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.documents.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-file">

                    </i>
                    Document management
                </a>
            </li>
        @endcan
        @can('note_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.notes.index") }}" class="c-sidebar-nav-link">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sticky-note">

                    </i>
                    Note management
                </a>
            </li>
        @endcan
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
