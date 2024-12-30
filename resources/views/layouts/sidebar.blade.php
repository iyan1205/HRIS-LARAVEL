
<div class="w-64 bg-gray-800 text-white p-6">
    <h2 class="text-2xl font-semibold text-center mb-6">Dashboard</h2>

    <ul class="space-y-4">
        <!-- Dashboard Item -->
        <li class="nav-item {{ request()->is('dashboard') ? 'menu-open' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <!-- Organisasi Menu -->
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
                    <a href="{{ route('departemen') }}" class="nav-link {{ request()->is('organisasi/departemen') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Departemen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('unit') }}" class="nav-link {{ request()->is('organisasi/unit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unit</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jabatan') }}" class="nav-link {{ request()->is('organisasi/jabatan') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Jabatan</p>
                    </a>
                </li>
            </ul>
        </li>
        @endcan

        <!-- Master Karyawan Menu -->
        @can('sidebar masterkaryawan')
        <li class="nav-item {{ request()->is('master/karyawan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('master/karyawan/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                    Master Karyawan
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('karyawan') }}" class="nav-link {{ request()->is('master/karyawan/active*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('resign') }}" class="nav-link {{ request()->is('master/karyawan/resign*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Resign</p>
                    </a>
                </li>
            </ul>
        </li>
        @endcan
    </ul>
</div>
