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
        <li class="nav-item {{ request()->is('master/karyawan*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('master/karyawan/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>Master Karyawan
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('karyawan') }}"
                        class="nav-link {{ request()->is('master/karyawan/active*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Karyawan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('resign') }}"
                        class="nav-link {{ request()->is('master/karyawan/resign*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
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
                    <a href="{{ route('user') }}" class="nav-link {{ request()->is('master-users/user*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->is('master-users/permissions*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Permissions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->is('master-users/roles*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Roles</p>
                    </a>
                </li>
            </ul>
        </li>
        @endcan

        @can('pelatihan')
        <li class="nav-item {{ request()->is('pelatihan/*') ? 'menu-open' : '' }}">
            <a href="{{ route('pelatihan') }}" class="nav-link {{ request()->is('pelatihan') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Pelatihan
                </p>
            </a>
        </li>
        @endcan
        @can('sidebar_cuti')
        <li class="nav-item {{ request()->is('Cuti/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('Cuti/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-paper-plane"></i>
                <p>Cuti
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right" id="pendingCountBadge" style="display:none;"></span> 
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('sidebar pengajuancuti')
                <li class="nav-item">
                    <a href="{{ route('pengajuan-cuti') }}"
                        class="nav-link {{ request()->is('Cuti/pengajuan-cuti*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pengajuan Cuti</p>
                    </a>
                </li>
                @endcan
                @can('approve cuti')
                <li class="nav-item">
                    <a href="{{ route('approval-cuti') }}"
                        class="nav-link {{ request()->is('Cuti/approval-cuti*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Approval Cuti</p>
                    </a>
                </li>
                @endcan

                @can('sidebar laporan cuti')
                <li class="nav-item">
                    <a href="{{ route('laporan-cuti') }}"
                        class="nav-link {{ request()->is('Cuti/laporan-cuti*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Laporan Cuti</p>
                    </a>
                </li>
                @endcan

                @can('sidebar saldocuti')
                <li class="nav-item">
                    <a href="{{ route('saldo-cuti') }}"
                        class="nav-link {{ request()->is('Cuti/saldo-cuti*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Saldo Cuti</p>
                    </a>
                </li>
                @endcan

                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('btn-sc.cuti') }}"
                        class="nav-link {{ request()->is('Cuti/approval-cuti/*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cancel Cuti</p>
                    </a>
                </li>
                @endrole

            </ul>
        </li>
        @endcan
        @can('sidebar_lembur')
        <li class="nav-item {{ request()->is('Lembur/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('Lembur/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-plus"></i>
                <p>Lembur
                    <i class="fas fa-angle-left right"></i>
                    <span class="badge badge-info right" id="lemburCountBadge" style="display:none;"></span>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('view overtime')
                <li class="nav-item">
                    <a href="{{ route('overtime') }}"
                        class="nav-link {{ request()->is('Lembur/overtime*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pengajuan Lembur</p>
                    </a>
                </li>
                @endcan
                @can('approve overtime')
                <li class="nav-item">
                    <a href="{{ route('approval-overtime') }}"
                        class="nav-link {{ request()->is('Lembur/approval-overtime*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Approval Lembur</p>
                    </a>
                </li>
                @endcan

                @can('sidebar laporan lembur')
                <li class="nav-item">
                    <a href="{{ route('laporan-lembur') }}"
                        class="nav-link {{ request()->is('Lembur/laporan-overtime*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Laporan Lembur</p>
                    </a>
                </li>
                @endcan
                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('overtime.sl') }}"
                        class="nav-link {{ request()->is('Lembur/approval-overtime/*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cancel Lembur</p>
                    </a>
                </li>
                @endrole
            </ul>
        </li>
        @endcan

        @can('sidebar_oncall')
        <li class="nav-item {{ request()->is('oncall/*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('oncall/*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>On-Call
                    <i class="fas fa-angle-left right"></i>
                    <span id="oncallCountBadge" class="badge badge-info right" style="display:none;"></span>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @can('view overtime')
                <li class="nav-item">
                    <a href="{{ route('oncall') }}"
                        class="nav-link {{ request()->is('oncall/oncall*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pengajuan On-Call</p>
                    </a>
                </li>
                @endcan
                @can('approve overtime')
                <li class="nav-item">
                    <a href="{{ route('approval-oncall') }}"
                        class="nav-link {{ request()->is('oncall/approval-oncall*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Approval On-Call</p>
                    </a>
                </li>
                @endcan

                @can('sidebar laporan oncall')
                <li class="nav-item">
                    <a href="{{ route('laporan-oncall') }}"
                        class="nav-link {{ request()->is('oncall/laporan-oncall*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Laporan On-Call</p>
                    </a>
                </li>
                @endcan
                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('oncall.ol') }}"
                        class="nav-link {{ request()->is('oncall/approval-oncall/*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cancel On-Call</p>
                    </a>
                </li>
                @endrole
            </ul>
        </li>
        @endcan

        {{-- <li class="nav-item {{ request()->is('attendance/*') ? 'menu-open' : '' }}">
            <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->is('attendance') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Kehadiran
                </p>
            </a>
        </li> --}}
    </ul>
</nav>
