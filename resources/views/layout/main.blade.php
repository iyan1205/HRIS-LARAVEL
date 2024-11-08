<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRIS') }}</title>

    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="{{ asset('lte/dist/img/logo.png') }}"">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/toastr/toastr.min.css') }}">
    <style>
        .red-star {
            color: red;
        }
        .custom-file {
        position: relative;
            }

            .custom-file-input {
                display: none;
            }

            .form-control1 {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #ffffff; /* Warna latar belakang tombol */
                color: rgb(99, 99, 99); /* Warna ikon */
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
                text-align: center;
                font-size: 24px; /* Ukuran ikon */
                border: 1px solid #057aff; /* Border sesuai warna latar belakang */
            }

            .form-control1 i {
                margin: 0;
            }

    </style>
    <style>
        /* Add styles to change the background color to gray */
        body {
            background-color: #f8f9fa; /* Light gray background for the entire body */
        }
    
        .certificate-viewer {
            background-color: #d3d3d3; /* Gray background for the certificate viewer */
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
@php
    $user = Auth::user();
    
@endphp

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div> --}}

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                {{-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> --}}
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                        {{ Auth::user()->name }}
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
                {{-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                        role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ asset('lte/dist/img/LogoRS.png') }}" alt="HR Logo"
                    class="brand-image">
                <span class="brand-text font-weight-light">HRIS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @if(Auth::user()->image)
                        <img src="{{ asset('storage/avatar/' . auth()->user()->image) }}"
                            class="img" alt="User Image">
                        @else
                        <p>No image available</p>
                        @endif
                    </div>
                    <div class="info">
                        <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>-->

                <!-- Sidebar Menu -->
                @include('layout.sidebar')
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->

        </aside>

        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="https://rs-hamori.co.id">Rumah Sakit HAMORI</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 0.0.2
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('lte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('lte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('lte/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.js') }}"></script>
   
    <!-- DataTables  & Plugins -->
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
    <script>
        $(document).ready(function() {
            $('#karyawanTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#allTable').DataTable({
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    </script>
    <!-- Page Select script -->

        <!-- Cuti -->
        <script>
            // Mendapatkan tanggal sekarang
            var currentDate = new Date();
            // Menambahkan 5 hari ke tanggal sekarang
            var targetDate = new Date(currentDate);
            targetDate.setDate(currentDate.getDate() + 5);
        
            // Inisialisasi datetimepicker untuk elemen input dengan id "start_date5"
            $('#start_date5').datetimepicker({
                format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                },
                minDate: targetDate, // Tidak memungkinkan pemilihan tanggal sebelum tanggal sekarang
                maxDate: targetDate // Tidak memungkinkan pemilihan tanggal lebih dari 5 hari ke depan
            });
        
            // Inisialisasi datetimepicker untuk elemen input dengan id "end_date5"
            $('#end_date5').datetimepicker({
                format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                },
                useCurrent: false, // Tidak menggunakan tanggal saat ini secara default
                minDate: targetDate // Mengatur tanggal minimum menjadi targetDate
            });
        
            // Mengatur bahwa tanggal di end_date5 tidak bisa sebelum tanggal di start_date5
            $("#start_date5").on("change.datetimepicker", function (e) {
                $('#end_date5').datetimepicker('minDate', e.date);
            });
        
            // Mengatur bahwa tanggal di start_date5 tidak bisa setelah tanggal di end_date5
            $("#end_date5").on("change.datetimepicker", function (e) {
                $('#start_date5').datetimepicker('maxDate', e.date);
            });
        </script>

        <!-- Overtime -->
        <script>
            // Inisialisasi datetimepicker untuk elemen input dengan id "start_dateover"
            $('#start_dateover').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format tanggal dan waktu yang diinginkan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-check'
                },
                sideBySide: false, // Menampilkan input waktu secara berdampingan dengan input tanggal
                toolbarPlacement: 'bottom', // Menempatkan toolbar di bagian bawah
                buttons: {
                    showClose: true, // Menampilkan tombol Close
                    showToday: true, // Menampilkan tombol Today
                    showClear: true, // Menampilkan tombol Clear
                    showApply: true // Menampilkan tombol Apply
                }
            });
        
            // Inisialisasi datetimepicker untuk elemen input dengan id "end_dateover"
            $('#end_dateover').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format tanggal dan waktu yang diinginkan
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-check'
                },
                useCurrent: false, // Tidak menggunakan tanggal saat ini secara default
                sideBySide: false, // Menampilkan input waktu secara berdampingan dengan input tanggal
                toolbarPlacement: 'bottom', // Menempatkan toolbar di bagian bawah
                buttons: {
                    showClose: true, // Menampilkan tombol Close
                    showToday: true, // Menampilkan tombol Today
                    showClear: true, // Menampilkan tombol Clear
                    showApply: true // Menampilkan tombol Apply
                }
            });
        
            // Mengatur bahwa tanggal di end_dateover tidak bisa sebelum tanggal di start_dateover
            $("#start_dateover").on("change.datetimepicker", function (e) {
                $('#end_dateover').datetimepicker('minDate', e.date);
            });
        
            // Mengatur bahwa tanggal di start_dateover tidak bisa setelah tanggal di end_dateover
            $("#end_dateover").on("change.datetimepicker", function (e) {
                $('#start_dateover').datetimepicker('maxDate', e.date);
            });
        </script>
        
        
    
        <!-- Cuti -->
            <script>
                // Mendapatkan tanggal sekarang
                var currentDate = new Date();

                // Inisialisasi datetimepicker untuk elemen input dengan id "start_dateabsen"
                $('#start_date').datetimepicker({
                    format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-calendar-check-o',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    },
                
                });

                $('#time_in').datetimepicker({
                    format: 'HH:mm'
                });
                // Inisialisasi datetimepicker untuk elemen input dengan id "end_date"
                $('#end_date').datetimepicker({
                    format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-calendar-check-o',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    },
                    useCurrent: false // Tidak menggunakan tanggal saat ini secara default
                });

                // Mengatur bahwa tanggal di end_date tidak bisa sebelum tanggal di start_date
                $("#start_date").on("change.datetimepicker", function (e) {
                    $('#end_date').datetimepicker('minDate', e.date);
                });

                // Mengatur bahwa tanggal di start_date tidak bisa setelah tanggal di end_date
                $("#end_date").on("change.datetimepicker", function (e) {
                    $('#start_date').datetimepicker('maxDate', e.date);
                });
            </script>
        <!-- //Cuti -->
        <!-- Absen -->
            <script>
                // Mendapatkan tanggal sekarang
                var currentDate = new Date();

                // Inisialisasi datetimepicker untuk elemen input dengan id "start_dateabsen"
                $('#start_dateabsen').datetimepicker({
                    format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-calendar-check-o',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    },
                
                });

                // Inisialisasi datetimepicker untuk elemen input dengan id "end_dateabsen"
                $('#end_dateabsen').datetimepicker({
                    format: 'YYYY-MM-DD', // Format tanggal yang diinginkan
                    icons: {
                        time: 'fa fa-clock',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-calendar-check-o',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    },
                    useCurrent: false // Tidak menggunakan tanggal saat ini secara default
                });

                // Mengatur bahwa tanggal di end_dateabsen tidak bisa sebelum tanggal di start_dateabsen
                $("#start_dateabsen").on("change.datetimepicker", function (e) {
                    $('#end_dateabsen').datetimepicker('minDate', e.date);
                });

                // Mengatur bahwa tanggal di start_dateabsen tidak bisa setelah tanggal di end_dateabsen
                $("#end_dateabsen").on("change.datetimepicker", function (e) {
                    $('#start_dateabsen').datetimepicker('maxDate', e.date);
                });
            </script>
        <!-- Absen -->
    
    <script>
        $(function () {
          //Initialize Select2 Elements
          $('.select2').select2()
      
          //Initialize Select2 Elements
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          })
      
          //Datemask dd/mm/yyyy
          $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
          //Datemask2 mm/dd/yyyy
          $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
          //Money Euro
          $('[data-mask]').inputmask()
      
          //Date picker
          $('#reservationdate').datetimepicker({
              format: 'L',
              locale: 'id'
          });
      
          //Date and time picker
          $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
      
          //Date range picker
          $('#reservation').daterangepicker()
          //Date range picker with time picker
          $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
              format: 'MM/DD/YYYY hh:mm A'
            }
          })
          //Date range as a button
          $('#daterange-btn').daterangepicker(
            {
              ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate  : moment()
            },
            function (start, end) {
              $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
          )
      
          //Timepicker
          $('#timepicker').datetimepicker({
            format: 'LT'
          })

          $('#time_in').datetimepicker({
            format: 'HH:mm'
          })
      
          //Bootstrap Duallistbox
          $('.duallistbox').bootstrapDualListbox()
      
          //Colorpicker
          $('.my-colorpicker1').colorpicker()
          //color picker with addon
          $('.my-colorpicker2').colorpicker()
      
          $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
          })
      
          $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
          })
      
        })
        
      
      
      </script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Page specific script SweetAlert2-->
    @if (session('success')) //Login
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: '{{ session('success') }}',
            });
        </script>
    @endif
    @if (session('successAdd'))
        <script>
            Swal.fire({
                position: "top",
                icon: "success",
                title: "{{ session('successAdd') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    
    <script>
        $(function () {
            var today = new Date().toISOString().slice(0, 10); // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
    
            $("#laporan").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        extend: 'excel',
                        filename: function() {
                            return 'Laporan_Cuti_' + today; // Menetapkan nama file sebagai "Laporan_Cuti_tanggal_hari_ini"
                        }
                    }
                ]
            }).buttons().container().appendTo('#laporan_wrapper .col-md-6:eq(0)');
    
            $("#laporan_lembur").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        extend: 'excel',
                        filename: function() {
                            return 'Laporan_Lembur_' + today; // Menetapkan nama file sebagai "Laporan_Lembur_tanggal_hari_ini"
                        }
                    }
                ]
            }).buttons().container().appendTo('#laporan_lembur_wrapper .col-md-6:eq(0)');
            
            $("#laporan_oncall").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        extend: 'excel',
                        filename: function() {
                            return 'Laporan_Oncall_' + today; // Menetapkan nama file sebagai "Laporan_Lembur_tanggal_hari_ini"
                        }
                    }
                ]
            }).buttons().container().appendTo('#laporan_oncall_wrapper .col-md-6:eq(0)');
        });
    </script>
<!-- TAMBAH/EDIT cuti -->
<script>
$(document).ready(function() {
    // Handle kategori_cuti change event
    $('#kategori_cuti').change(function() {
        var kategoriCuti = $(this).val();
        if (kategoriCuti) {
            $.ajax({
                url: '/pengajuan-cuti/create/' + kategoriCuti,
                type: 'GET',
                success: function(data) {
                    $('#leave_type_id').empty();
                    $('#leave_type_id').append('<option value="" disabled selected>Pilih Jenis Cuti</option>');
                    $.each(data, function(key, value) {
                        $('#leave_type_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#leave_type_id_container').show();

                    // Show/hide file upload container based on kategori_cuti
                    if (kategoriCuti === 'CUTI TAHUNAN') {
                        $('#leave_type_id_container').hide();
                        $('#leave_type_id').val('20'); // Set leave_type_id value to 20
                        $('#max_amount_display').text('Maksimal Jumlah Cuti: 5').show();
                    } else {
                        $('#leave_type_id_container').show();
                        $('#max_amount_display').hide();
                    }

                    if (kategoriCuti === 'CUTI KHUSUS') {
                        $('#file_upload_container').show();
                        $('#file_upload').prop('required', true);
                    } else {
                        $('#file_upload_container').hide();
                        $('#file_upload').prop('required', false);
                    }
                }
            });
        } else {
            $('#leave_type_id_container').hide();
            $('#file_upload_container').hide();
            $('#file_upload').prop('required', false);
            $('#max_amount_display').hide();
        }
    });

    // Handle leave_type_id change event
    $('#leave_type_id').change(function() {
        var leaveTypeId = $(this).val();
        if (leaveTypeId) {
            $.ajax({
                url: '/pengajuan-cuti/leave-types/' + leaveTypeId,
                type: 'GET',
                success: function(data) {
                    if (data.max_amount) {
                        $('#max_amount_display').text('Maksimal Jumlah Cuti: ' + data.max_amount + ' Hari').show();
                    } else {
                        $('#max_amount_display').hide();
                    }

                    if (data.file_upload === 'yes') {
                        $('#file_upload_container').show();
                        $('#file_upload').prop('required', true);
                    } else {
                        $('#file_upload_container').hide();
                        $('#file_upload').prop('required', false);
                    }
                }
            });
        } else {
            $('#max_amount_display').hide();
            $('#file_upload_container').hide();
            $('#file_upload').prop('required', false);
        }
    });


});

    // Handle file upload change event to update the label with the selected file name
    $('#file_upload').change(function() {
        var fileName = $(this).val().split('\\').pop(); // Extract the file name from the file path
        $(this).next('.custom-file-label').html(fileName); // Update the label text
    });

$(document).ready(function() {
    $('.select2bst4').select2({
        theme: 'bootstrap4'
    });
});
</script> 
 

<!-- Menampilkan kalkulasi waktu -->
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize datetime pickers
        $('#start_dateover').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#end_dateover').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            useCurrent: false
        });

        // Calculate the difference in hours and minutes when either date changes
        $('#start_dateover, #end_dateover').on('change.datetimepicker', function() {
            var startDate = $('#start_dateover').find('input').val();
            var endDate = $('#end_dateover').find('input').val();

            if (startDate && endDate) {
                var start = moment(startDate, 'YYYY-MM-DD HH:mm');
                var end = moment(endDate, 'YYYY-MM-DD HH:mm');
                
                // Calculate the difference in minutes
                var diff = end.diff(start, 'minutes');

                // Convert total minutes to hours and minutes
                var hours = Math.floor(diff / 60);
                var minutes = diff % 60;

                $('#total_duration').val(hours + ' jam ' + minutes + ' menit');
            }
        });
    });
</script>

<script>
    $(function () {
        // Initialize the date pickers
        $('#start_date').datetimepicker({
            format: 'L'
        });
        $('#end_date').datetimepicker({
            format: 'L'
        });

        // Update total days when either date changes
        $('#start_date, #end_date').on('change.datetimepicker', function () {
            calculateDays();
        });

        function calculateDays() {
            var startDate = $('#start_date').datetimepicker('date');
            var endDate = $('#end_date').datetimepicker('date');
            
            if (startDate && endDate) {
                var start = moment(startDate);
                var end = moment(endDate);
                var days = end.diff(start, 'days') + 1; // Add 1 to include both start and end dates

                $('#total_days').val(days + ' Hari');
            }
        }
    });
</script>

@role('Super-Admin|karyawan')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateBadge(url, badgeId, dataKey) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById(badgeId);
                    if (data[dataKey] > 0) {
                        badge.textContent = data[dataKey];
                        badge.style.display = 'inline-block'; // Menampilkan badge jika nilainya lebih dari 0
                    } else {
                        badge.style.display = 'none'; // Menyembunyikan badge jika nilainya 0
                    }
                })
                .catch(() => {}); 
        }

        // Initial calls to update all counts
        updateBadge('{{ route('api.pending-count') }}', 'pendingCountBadge', 'pendingCount');
        updateBadge('{{ route('api.over-count') }}', 'lemburCountBadge', 'countOvertime');
        updateBadge('{{ route('api.oncall-count') }}', 'oncallCountBadge', 'countOncall');

        // Optionally, update the counts periodically
        setInterval(function() {
            updateBadge('{{ route('api.pending-count') }}', 'pendingCountBadge', 'pendingCount');
            updateBadge('{{ route('api.over-count') }}', 'lemburCountBadge', 'countOvertime');
            updateBadge('{{ route('api.oncall-count') }}', 'oncallCountBadge', 'countOncall');
        }, 5000); // Update every minute
    });
</script>
@endrole
<script>
    // Show input fields when pelatihan is selected
    $('#pelatihan').on('change', function() {
        var selected = $(this).val();
        $('.pelatihan-group').hide(); // Hide all
        selected.forEach(function(id) {
            if ($('#pelatihan-' + id + '-details').length) {
                // Show existing pelatihan details
                $('#pelatihan-' + id + '-details').show();
            } else {
                // Add new pelatihan details if not already present
                addPelatihanDetails(id);
            }
        });
    });

    // Initially show fields for already selected pelatihan
    $(document).ready(function() {
        var selected = $('#pelatihan').val();
        selected.forEach(function(id) {
            if ($('#pelatihan-' + id + '-details').length) {
                $('#pelatihan-' + id + '-details').show(); // Show existing selected on load
            }
        });
    });

    // Function to add new pelatihan details dynamically
    function addPelatihanDetails(id) {
        var pelatihanName = $('#pelatihan option[value="' + id + '"]').text().trim(); // Get selected pelatihan name
        var pelatihanDetails = `
            <div class="form-group pelatihan-group" id="pelatihan-` + id + `-details">
                <!-- Edit Nama Pelatihan -->
                <label for="name_` + id + `">Nama Pelatihan untuk ` + pelatihanName + `</label>
                <input type="text" name="nama_pelatihan[` + id + `]" value="` + pelatihanName + `" class="form-control" disabled>
                
                <!-- Tanggal Expired Pelatihan -->
                <label for="tanggal_expired_` + id + `">Tanggal Expired untuk ` + pelatihanName + `</label>
                <input type="date" name="tanggal_expired[` + id + `]" class="form-control">
                
                <!-- File Upload Pelatihan -->
                <label for="file_` + id + `">File Sertifikat untuk ` + pelatihanName + `</label>
                <input type="file" name="file[` + id + `]" class="form-control" accept=".pdf">
                <hr style="border: 2px solid #000;">
            </div>
        `;
        $('#pelatihan-details').append(pelatihanDetails);
    }

    // Add new Pelatihan input field
    $('#add-pelatihan').on('click', function() {
        // Set the flag to true when new pelatihan is added
        $('#add_pelatihan_flag').val('true');

        var newPelatihanFields = `
            <div class="form-group new-pelatihan-div">
                <input type="text" name="new_pelatihan[]" class="form-control mb-2 new-pelatihan" placeholder="Nama Pelatihan Baru" required>
                <label for="new_tanggal_expired[]">Tanggal Expired Pelatihan Baru</label>
                <input type="date" name="new_tanggal_expired[]" class="form-control mb-2 new-expired">
                <label for="new_file[]">File Sertifikat Pelatihan Baru</label>
                <input type="file" name="new_file[]" class="form-control mb-2 new-file" accept=".pdf">
                <!-- Cancel button to remove the new pelatihan div -->
                <button type="button" class="btn btn-danger remove-pelatihan-btn">Batal</button>
                <hr style="border: 2px solid #000;">
            </div>
        `;
        $('#new-pelatihan-container').append(newPelatihanFields);
    });

    // Function to remove the new pelatihan fieldset
    $(document).on('click', '.remove-pelatihan-btn', function() {
        $(this).closest('.new-pelatihan-div').remove();
    });
</script>

</body>

</html>
