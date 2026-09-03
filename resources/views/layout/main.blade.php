<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRIS') }}</title>

    <link rel="shortcut icon" href="{{ asset('lte/dist/img/logo.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">

    <style>
        .red-star { color: red; }
        .custom-file { position: relative; }
        .custom-file-input { display: none; }
        .form-control1 {
            display: flex; align-items: center; justify-content: center;
            background-color: #ffffff; color: rgb(99, 99, 99);
            padding: 10px; border-radius: 5px; cursor: pointer;
            text-align: center; font-size: 24px;
            border: 1px solid #057aff;
        }
        .form-control1 i { margin: 0; }
        body { background-color: #f8f9fa; }
        .certificate-viewer {
            background-color: #d3d3d3; padding: 20px;
            border-radius: 8px; border: 1px solid #ccc;
        }

        /* ══════════════════════════════════════════
           NOTIFIKASI STYLES
        ══════════════════════════════════════════ */
        .notif-nav-item { position: relative; }

        .notif-panel {
            width: 400px; padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,.13);
            border: 1px solid #ffffff;
            overflow: hidden;
            background-color: #ffffff !important; /* ← TAMBAHKAN INI */
            opacity: 1 !important;
        }
        @media (max-width: 576px) {
            .notif-panel {
                width: 400px;
                right: auto;
                left: 50%;
                transform: translateX(-50%);
                position: fixed !important;
                top: 60px !important;
            }
        }
        .notif-panel-header {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 10px 14px; background: #f8f9fa;
        }
        .notif-panel-title { font-size: 13px; font-weight: 700; color: #343a40; }
        .notif-btn-readall {
            background: none; border: none; cursor: pointer;
            font-size: 11px; color: #007bff; font-weight: 600;
            padding: 3px 8px; border-radius: 4px; transition: background .15s;
        }
        .notif-btn-readall:hover { background: #e8f0fe; }

        .notif-list {
            max-height: 340px; overflow-y: auto;
            scrollbar-width: thin; scrollbar-color: #dee2e6 transparent;
        }
        .notif-loading {
            display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 28px 0; color: #6c757d; font-size: 13px;
        }
        .notif-spinner {
            width: 18px; height: 18px;
            border: 2px solid #dee2e6; border-top-color: #007bff;
            border-radius: 50%; animation: notif-spin .7s linear infinite; flex-shrink: 0;
        }
        @keyframes notif-spin { to { transform: rotate(360deg); } }

        /* ✅ FIX KLIK: pointer-events none pada child elements,
           pastikan <a> yang menerima klik, bukan child div */
        .notif-item {
            display: flex !important;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 14px !important;
            border-bottom: 1px solid #f1f1f1;
            transition: background .12s;
            text-decoration: none !important;
            color: #343a40 !important;
            cursor: pointer;
            /* Penting: pastikan tidak ada yang menghalangi klik */
            position: relative;
            z-index: 1;
        }
        /* Child elements tidak boleh intercept klik navigasi */
        .notif-item .notif-icon,
        .notif-item .notif-content,
        .notif-item .notif-unread-dot { pointer-events: none; }

        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f0f4f8 !important; }
        .notif-item.unread { background: #eaf1fb !important; }
        .notif-item.unread:hover { background: #dce8f8 !important; }

        .notif-icon {
            flex-shrink: 0; width: 36px; height: 36px;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-size: 14px; margin-top: 1px;
        }
        .notif-icon.blue   { background: #cce5ff; color: #004085; }
        .notif-icon.green  { background: #d4edda; color: #155724; }
        .notif-icon.red    { background: #f8d7da; color: #721c24; }
        .notif-icon.yellow { background: #fff3cd; color: #856404; }
        .notif-icon.gray   { background: #e2e3e5; color: #383d41; }

        .notif-content { flex: 1; min-width: 0; }
        .notif-item-title {
            font-size: 12px; font-weight: 700; color: #212529;
            margin-bottom: 2px; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .notif-item-msg {
            font-size: 11.5px; color: #6c757d; line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden;
        }
        .notif-item-time {
            display: block; font-size: 10.5px; color: #adb5bd; margin-top: 3px;
        }
        .notif-unread-dot {
            flex-shrink: 0; align-self: center;
            width: 7px; height: 7px;
            background: #007bff; border-radius: 50%;
        }
        .notif-empty {
            display: flex; flex-direction: column;
            align-items: center; gap: 8px;
            padding: 32px 0; color: #adb5bd; font-size: 13px;
        }
        .notif-empty i { font-size: 28px; opacity: .4; }
    </style>
</head>

@php $user = Auth::user(); @endphp

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- ═══════════════════════════════════════════
                     NOTIFIKASI
                ═══════════════════════════════════════════ -->
                <li class="nav-item notif-nav-item">
                    {{-- Bell button: TIDAK pakai data-toggle/onclick
                         agar Bootstrap tidak intercept klik --}}
                    <a class="nav-link" href="#" id="notifToggleBtn" role="button">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger navbar-badge d-none" id="notifBellBadge">0</span>
                    </a>

                    <div class="notif-panel" id="notifDropdown"
                         style="display:none; position:absolute; right:0; top:calc(100% + 6px); z-index:9999;">

                        <div class="notif-panel-header">
                            <span class="notif-panel-title">
                                <i class="far fa-bell mr-1"></i> Notifikasi
                            </span>
                        </div>
                        <div class="dropdown-divider m-0"></div>

                        <div class="notif-list" id="notifList">
                            <div class="notif-loading">
                                <div class="notif-spinner"></div>
                                <span>Memuat notifikasi…</span>
                            </div>
                        </div>

                        <div class="dropdown-divider m-0"></div>
                        <a href="#" onclick="notifMarkAllRead()"
                           class="dropdown-item dropdown-footer text-center">
                            Baca Semua Notifikasi
                        </a>
                    </div>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @can('edit user')
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        @endcan
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="fas fa-arrow-right mr-2"></i>Log Out
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="{{ asset('lte/dist/img/LogoRS.png') }}" alt="HR Logo" class="brand-image">
                <span class="brand-text font-weight-light">HRIS</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @if(Auth::user()->image)
                            <img src="{{ asset('storage/avatar/' . auth()->user()->image) }}" class="img" alt="User Image">
                        @else
                            <p>No image available</p>
                        @endif
                    </div>
                    <div class="info">
                        <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>
                @include('layout.sidebar')
            </div>
        </aside>

        @yield('content')

        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="https://rs-hamori.co.id">Rumah Sakit HAMORI</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>$.widget.bridge('uibutton', $.ui.button)</script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/rs.js') }}"></script>
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- SweetAlert Session -->
    @if (session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Login Berhasil', text: '{{ session('success') }}' });
        </script>
    @endif
    @if (session('successAdd'))
        <script>
            Swal.fire({ position: "top", icon: "success", title: "{{ session('successAdd') }}", showConfirmButton: false, timer: 1500 });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({ position: "top", icon: "error", title: "{{ session('error') }}", showConfirmButton: false, timer: 4000 });
        </script>
    @endif

    <!-- Badge Sidebar (khusus role karyawan) -->
    @role('karyawan')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ✅ Fungsi ini namanya updateSidebarBadge agar tidak konflik
            //    dengan updateNotifBadge milik sistem notifikasi
            function updateSidebarBadge(url, badgeId, dataKey) {
                const badge = document.getElementById(badgeId);
                if (!badge) return;
                fetch(url)
                    .then(res => res.ok ? res.json() : null)
                    .then(data => {
                        if (!data) return;
                        const count = data[dataKey] ?? 0;
                        badge.textContent = count;
                        badge.style.display = count > 0 ? 'inline-block' : 'none';
                    })
                    .catch(err => console.warn('Badge error:', err));
            }

            updateSidebarBadge('{{ route('api.pending-count') }}', 'pendingCountBadge', 'pendingCount');
            updateSidebarBadge('{{ route('api.over-count') }}',    'lemburCountBadge',  'countOvertime');
            updateSidebarBadge('{{ route('api.oncall-count') }}',  'oncallCountBadge',  'countOncall');
        });
    </script>
    @endrole

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector('#quote')) {
                ClassicEditor.create(document.querySelector('#quote')).catch(console.error);
            }
        });
    </script>

    {{-- ═══════════════════════════════════════════════════════════
         NOTIFIKASI SCRIPT
         Semua fungsi diberi prefix "notif" agar tidak konflik
         dengan fungsi lain di halaman (terutama updateBadge)
    ═══════════════════════════════════════════════════════════ --}}
    <script>
        const NOTIF_ICONS = {
            'calendar-plus'   : 'fas fa-calendar-plus',
            'check-circle'    : 'fas fa-check-circle',
            'x-circle'        : 'fas fa-times-circle',
            'arrow-up-circle' : 'fas fa-arrow-circle-up',
            'bell'            : 'far fa-bell',
        };

        /* ── Escape HTML ── */
        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(String(str ?? '')));
            return d.innerHTML;
        }

        /* ── Toggle dropdown ──
        Dipasang via addEventListener (bukan onclick di HTML)
        agar Bootstrap tidak intercept lebih dulu            */
        function notifToggle(e) {
            e.preventDefault();
            e.stopPropagation();

            const panel  = document.getElementById('notifDropdown');
            const isOpen = panel.style.display !== 'none';

            if (isOpen) {
                panel.style.display = 'none';
            } else {
                // ── Tutup semua Bootstrap dropdown yang sedang terbuka ──
                $('.dropdown-menu.show').each(function () {
                    $(this).removeClass('show');
                    $(this).closest('.nav-item.dropdown')
                        .find('[data-toggle="dropdown"]')
                        .attr('aria-expanded', 'false');
                });

                panel.style.display = 'block';
                notifLoad();
            }
        }

        /* ── Tutup saat klik di luar ── */
        document.addEventListener('click', (e) => {
            const wrapper = document.querySelector('.notif-nav-item');
            const panel   = document.getElementById('notifDropdown');
            const btn     = document.getElementById('notifToggleBtn');

            if (!wrapper || !panel) return;

            // Jangan tutup jika klik di dalam panel atau di tombol bell
            if (panel.contains(e.target) || (btn && btn.contains(e.target))) return;

            panel.style.display = 'none';
        });

        /* ── Pasang listener ke bell setelah DOM siap ── */
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('notifToggleBtn');
            if (btn) {
                btn.addEventListener('click', notifToggle);
            }
            // ── Tutup notif panel saat Bootstrap dropdown lain dibuka ──
            $(document).on('show.bs.dropdown', function (e) {
                const panel = document.getElementById('notifDropdown');
                if (panel) panel.style.display = 'none';
            });
        });

        /* ── Fetch & render notifikasi ── */
        async function notifLoad() {
            const list = document.getElementById('notifList');
            list.innerHTML = `<div class="notif-loading">
                <div class="notif-spinner"></div>
                <span>Memuat notifikasi…</span>
            </div>`;

            try {
                const res  = await fetch('{{ route("notifications.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                notifUpdateBadge(json.unread_count);
                notifRender(json.notifications);
            } catch {
                list.innerHTML = `<div class="notif-empty">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Gagal memuat notifikasi</span>
                </div>`;
            }
        }

        /* ── Render list ── */
        function notifRender(notifications) {
            const list = document.getElementById('notifList');

            if (!notifications || !notifications.length) {
                list.innerHTML = `<div class="notif-empty">
                    <i class="far fa-bell-slash"></i>
                    <span>Belum ada notifikasi</span>
                </div>`;
                return;
            }

            // ✅ FIX KLIK: Tidak pakai onclick di sini.
            //    Navigasi ditangani lewat href pada <a>.
            //    markRead dipanggil via event listener setelah render.
            list.innerHTML = notifications.map(n => `
                <a class="notif-item ${n.is_read ? '' : 'unread'}"
                href="${escHtml(n.url)}"
                data-notif-id="${escHtml(n.id)}"
                data-is-read="${n.is_read ? '1' : '0'}"
                data-url="${escHtml(n.url)}">
                    <div class="notif-icon ${escHtml(n.color)}">
                        <i class="${NOTIF_ICONS[n.icon] || NOTIF_ICONS['bell']}"></i>
                    </div>
                    <div class="notif-content">
                        <div class="notif-item-title">${escHtml(n.title)}</div>
                        <div class="notif-item-msg">${escHtml(n.message)}</div>
                        <span class="notif-item-time">
                            <i class="far fa-clock mr-1"></i>${escHtml(n.time)}
                        </span>
                    </div>
                    ${!n.is_read ? '<div class="notif-unread-dot"></div>' : ''}
                </a>
            `).join('');

            // ✅ FIX KLIK: Bootstrap .dropdown-menu intercept semua klik di dalamnya.
            //    Solusi: gunakan window.location.href secara eksplisit,
            //    jangan andalkan navigasi href alami di dalam dropdown Bootstrap.
            list.querySelectorAll('.notif-item').forEach(el => {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // cegah Bootstrap menutup sebelum navigasi

                    const id     = this.dataset.notifId;
                    const isRead = this.dataset.isRead === '1';
                    const url    = this.dataset.url;

                    if (!isRead) {
                        // Tandai dibaca, lalu navigasi setelah request selesai (atau timeout)
                        fetch(`{{ url('notifications') }}/${id}/read`, {
                            method : 'POST',
                            headers: {
                                'X-CSRF-TOKEN'     : '{{ csrf_token() }}',
                                'X-Requested-With' : 'XMLHttpRequest',
                            },
                        })
                        .catch(() => {})
                        .finally(() => { window.location.href = url; });
                    } else {
                        window.location.href = url;
                    }
                });
            });
        }

        /* ── Tandai satu notifikasi dibaca (background) ── */
        function notifMarkOneRead(id) {
            fetch(`{{ url('notifications') }}/${id}/read`, {
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN'     : '{{ csrf_token() }}',
                    'X-Requested-With' : 'XMLHttpRequest',
                },
            }).catch(() => {}); // silent fail — navigasi sudah berjalan
        }

        /* ── Tandai semua dibaca ── */
        async function notifMarkAllRead() {
            try {
                await fetch('{{ route("notifications.read-all") }}', {
                    method : 'POST',
                    headers: {
                        'X-CSRF-TOKEN'     : '{{ csrf_token() }}',
                        'X-Requested-With' : 'XMLHttpRequest',
                    },
                });
                notifUpdateBadge(0);
                notifLoad(); // reload list setelah semua dibaca
            } catch (_) {}
        }

        /* ── Update badge bell ── */
        // ✅ Nama fungsi notifUpdateBadge — tidak konflik dengan updateBadge sidebar
        function notifUpdateBadge(count) {
            const badge = document.getElementById('notifBellBadge');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }

        /* ── Load badge saat halaman pertama kali dibuka ── */
        (async () => {
            try {
                const res  = await fetch('{{ route("notifications.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                notifUpdateBadge(json.unread_count);
            } catch (_) {}
        })();
    
    </script>
    @stack('scripts')
</body>
</html>
