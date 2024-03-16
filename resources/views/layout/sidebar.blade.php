<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item {{ request()->is('dashboard') ? 'menu-open' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        @can('sidebar organisasi')
        <li class="nav-item {{ request()->is('organisasi/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('organisasi/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-sitemap"></i>
                <p>
                    Organisasi
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('departemen') }}"
                        class="nav-link {{ request()->is('organisasi/departemen') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Departemen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('unit') }}"
                        class="nav-link {{ request()->is('organisasi/unit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unit</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jabatan') }}"
                        class="nav-link {{ request()->is('organisasi/jabatan') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Jabatan</p>
                    </a>
                </li>
            </ul>
        </li>@endcan 

        @can('sidebar masterkaryawan')
        <li class="nav-item {{ request()->is('master-karyawan/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('master-karyawan/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>Master Karyawan
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('karyawan') }}"
                        class="nav-link {{ request()->is('master-karyawan/karyawan') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('resign') }}"
                        class="nav-link {{ request()->is('master-karyawan/resign') ? 'active' : '' }}">
                        <i class="fas fa-users-slash"></i>
                        <p>Resign</p>
                    </a>
                </li>
            </ul>
        </li>
            @endcan        
        @can('sidebar masteruser')
            <li class="nav-item {{ request()->is('master-users/*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('master-users/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>Master Users
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('user') }}"
                            class="nav-link {{ request()->is('master-users/user') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('permissions.index') }}"
                            class="nav-link {{ request()->is('master-users/permissions') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->is('master-users/roles') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan
            
        <li class="nav-header">Kehadiran</li>
        <li class="nav-item {{ request()->is('absen') ? 'menu-open' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('absen') ? 'active' : '' }}">
                <i class="nav-icon far fa-calendar-alt"></i>
                <p>
                    Absen
                    <span class="badge badge-info right">2</span>
                </p>
            </a>
        </li>
        @can('sidebar pengajuancuti')
        <li class="nav-item {{ request()->is('pengajuan-cuti') ? 'menu-open' : '' }}">
            <a href="{{ route('pengajuan-cuti') }}" class="nav-link {{ request()->is('pengajuan-cuti') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>
                    Pengajuan Cuti
                </p>
            </a>
        </li>
        @endcan
    </ul>
</nav>
