$(document).ready(function() {
    $('#karyawanTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

    $('#allTable').DataTable({
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });

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

    //Overtime
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

    $("#start_dateover").on("change.datetimepicker", function (e) {
        $('#end_dateover').datetimepicker('minDate', e.date);
    });

    $("#end_dateover").on("change.datetimepicker", function (e) {
        $('#start_dateover').datetimepicker('maxDate', e.date);
    });

    // ================= CUTI =================
    var currentDate = moment();

    $('#start_date').datetimepicker({
        format: 'YYYY-MM-DD',
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
        }
    });

    $('#end_date').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
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
        }
    });

    $('#time_in').datetimepicker({
        format: 'HH:mm'
    });

    // 🔹 Set batas end_date (max 90 hari)
    $("#start_date").on("change.datetimepicker", function (e) {
        if (e.date) {
            let minStart = e.date.clone();
            let maxEnd   = e.date.clone().add(89, 'days');

            $('#end_date').datetimepicker('minDate', minStart);
            $('#end_date').datetimepicker('maxDate', maxEnd);
            $('#end_date').datetimepicker('date', minStart);
            calculateDays();
        }
    });

    $('#end_date').on('change.datetimepicker', function () {
        calculateDays();
    });

    // 🔹 Hitung total hari cuti
    function calculateDays() {
        var startDate = $('#start_date').datetimepicker('date');
        var endDate   = $('#end_date').datetimepicker('date');

        if (startDate && endDate) {
            var days = endDate.diff(startDate, 'days') + 1; // inklusif
            $('#total_days').val(days + ' Hari');
        } else {
            $('#total_days').val('');
        }
    }
    // ================= END CUTI =================

    //Laporan
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

            $("#lap_absensi").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    filename: 'Laporan_Absensi_' + today, // Nama file ekspor
                }
            ]
        }).buttons().container().appendTo('#lap_absensi_wrapper .col-md-6:eq(0)');

        // Menampilkan kalkulasi waktu Initialize datetime pickers
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

        /* ================= GLOBAL ================= */
        let maxCuti = null;

        $(document).ready(function () {

            /* ================= DATE PICKER ================= */
            $('#start_date_cuti, #end_date_cuti').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false
            });

            /* ================= LOCK KALENDER ================= */
            function lockEndDateCalendar() {
            let startDate = $('#start_date_cuti').datetimepicker('date');
            if (!startDate) return;

            const endPicker = $('#end_date_cuti');

            // selalu set minimal = start
            endPicker.datetimepicker('minDate', startDate);

            // jika unlimited → jangan set maxDate sama sekali
            if (isUnlimitedCuti()) {
                endPicker.datetimepicker('maxDate', null);
                return;
            }

            let maxEndDate = startDate.clone().add(maxCuti - 1, 'days');
            endPicker.datetimepicker('maxDate', maxEndDate);

            // koreksi kalau melewati batas
            let endDate = endPicker.datetimepicker('date');
            if (endDate && endDate.isAfter(maxEndDate)) {
                endPicker.datetimepicker('date', maxEndDate);
            }
            }


            /* ================= HITUNG TOTAL HARI ================= */
            function calculateDays() {
                let startDate = $('#start_date_cuti').datetimepicker('date');
                let endDate   = $('#end_date_cuti').datetimepicker('date');

                if (!startDate || !endDate) {
                    $('#total_days').val('');
                    return;
                }
                if (endDate.isBefore(startDate)) {
                    $('#end_date_cuti').datetimepicker('date', startDate);
                    endDate = startDate.clone();
                }

                let totalDays = endDate.diff(startDate, 'days') + 1;

            if (!isUnlimitedCuti() && totalDays > maxCuti) {
                    let maxEnd = startDate.clone().add(maxCuti - 1, 'days');
                    $('#end_date_cuti').datetimepicker('date', maxEnd);
                    $('#total_days').val(maxCuti + ' Hari');
                    return;
                }

                $('#total_days').val(totalDays + ' Hari');
            }

            function isUnlimitedCuti() {
                return maxCuti === null || maxCuti === 0;
            }

            /* ================= START DATE CHANGE ================= */
            $("#start_date_cuti").on("change.datetimepicker", function (e) {
                if (!e.date) return;

                let endDate = $('#end_date_cuti').datetimepicker('date');

                // hanya set end_date jika belum ada
                if (!endDate || endDate.isBefore(e.date)) {
                    $('#end_date_cuti').datetimepicker('date', e.date);
                }

                lockEndDateCalendar();
                calculateDays();
            });


            /* ================= END DATE CHANGE ================= */
            $("#end_date_cuti").on("change.datetimepicker", function () {
                calculateDays();
            });

            /* ================= KATEGORI CUTI ================= */
            $('#kategori_cuti').change(function () {
                let kategoriCuti = $(this).val();
                maxCuti = null;

                if (!kategoriCuti) {
                    $('#leave_type_id_container').hide();
                    $('#file_upload_container').hide();
                    $('#max_amount_display').hide();
                    return;
                }

                $.ajax({
                    url: '/pengajuan-cuti/create/' + kategoriCuti,
                    type: 'GET',
                    success: function (data) {

                        $('#leave_type_id').empty()
                            .append('<option value="" disabled selected>Pilih Jenis Cuti</option>');

                        $.each(data, function (key, value) {
                            $('#leave_type_id')
                                .append('<option value="' + key + '">' + value + '</option>');
                        });

                        // CUTI TAHUNAN
                        if (kategoriCuti === 'CUTI TAHUNAN') {
                            $('#leave_type_id_container').hide();
                            $('#leave_type_id').val('20');

                            maxCuti = null;
                            $('#max_amount_display').hide();
                                // .text('Maksimal Jumlah Cuti: -')
                                // .show()
                        } else {
                            $('#leave_type_id_container').show();
                            $('#max_amount_display').hide();
                        }

                        // CUTI KHUSUS → file wajib
                        if (kategoriCuti === 'CUTI KHUSUS') {
                            $('#file_upload_container').show();
                            $('#file_upload').prop('required', true);
                        } else {
                            $('#file_upload_container').hide();
                            $('#file_upload').prop('required', false);
                        }

                        lockEndDateCalendar();
                        calculateDays();
                    }
                });
            });

            /* ================= JENIS CUTI ================= */
            $('#leave_type_id').change(function () {
                let leaveTypeId = $(this).val();

                if (!leaveTypeId) {
                    maxCuti = null;
                    $('#max_amount_display').hide();
                    $('#file_upload_container').hide();
                    $('#file_upload').prop('required', false);
                    return;
                }

                $.ajax({
                    url: '/pengajuan-cuti/leave-types/' + leaveTypeId,
                    type: 'GET',
                    success: function (data) {

                        if (data.max_amount !== null) {
                            maxCuti = parseInt(data.max_amount);
                        } else {
                            maxCuti = null;
                        }

                        if (isUnlimitedCuti()) {
                            $('#max_amount_display').hide();
                        } else {
                            $('#max_amount_display')
                                .text('Maksimal Jumlah Cuti: ' + maxCuti + ' Hari')
                                .show();
                        }
                        // ===== FILE UPLOAD (INI INTI NYA) =====
                        if (data.file_upload === 'yes') {
                            $('#file_upload_container').show();
                            $('#file_upload').prop('required', true);
                        } else {
                            $('#file_upload_container').hide();
                            $('#file_upload').prop('required', false);
                            $('#file_upload').val('');
                    }
                        lockEndDateCalendar();
                        calculateDays();
                    }
                });
            });

        });

    $(document).ready(function() {
        $('.select2bst4').select2({
            theme: 'bootstrap4'
        });
    });
});