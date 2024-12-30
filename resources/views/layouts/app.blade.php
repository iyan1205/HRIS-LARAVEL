<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Vite Stylesheet -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Additional Meta Tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white p-6">
            <h2 class="text-2xl font-semibold text-center mb-6">Dashboard</h2>

            <ul class="space-y-4">
                <!-- Dashboard Menu -->
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

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">

            <!-- Navbar -->
            <nav class="bg-gray-900 text-white p-4 shadow-md">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-semibold">
                        {{ config('app.name', 'Laravel') }}
                    </div>

                    <!-- Navbar Right (user info, logout, etc) -->
                    <div class="flex items-center space-x-4">
                        <!-- User Dropdown (if needed) -->
                        <div class="relative">
                            <button class="flex items-center space-x-2">
                                <img src="{{ asset('images/default-profile.jpg') }}" alt="Profile" class="w-8 h-8 rounded-full">
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                            <!-- Dropdown (if any) -->
                            <div class="absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg hidden">
                                <a href="#" class="block px-4 py-2">Profile</a>
                                <a href="{{ route('logout') }}" class="block px-4 py-2">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-6 flex-1 bg-white">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
</body>
</html>
