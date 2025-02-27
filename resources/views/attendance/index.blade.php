
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="{{ asset('lte/dist/img/logo.png') }}"">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .no-border {
            border: none; /* Menghilangkan border */
            outline: none; /* Menghilangkan outline */
            background-color: transparent; /* Pastikan latar belakang transparan */
        }

        .no-border:focus {
            outline: none; /* Menghilangkan outline saat fokus */
            box-shadow: none; /* Menghilangkan bayangan saat fokus */
            background-color: transparent; /* Tetap transparan saat fokus */
        }

    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <a href="{{ route('attendance.list') }}" 
        class="absolute top-2 right-2 text-red-500 hover:text-red-700 focus:outline-none">
         <i class="fas fa-times text-xl"></i>
        </a>
     
        <div class="text-center mb-6">
            <div class="inline-flex items-center bg-teal-500 text-white px-4 py-2 rounded-full">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
            </div>
        </div>
        <div class="text-center mb-6">
            <p class="text-lg font-semibold">{{ Auth::user()->karyawan->name }}</p>
            <p>{{ Auth::user()->karyawan->jabatan->name }}</p>
            <p>{{ Auth::user()->karyawan->nik }}</p>
        </div>
        @if($attendance)
            <div class="text-center mb-6">
                <p class="text-xl font-bold">Data kehadiran aktif ditemukan:</p>
                <p>Total Kehadiran Hari Ini: {{ $totalAttendanceToday }}</p> <br>
                <form action="{{ route('attendance.checkOut') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center relative">
                            <!-- Ikon Kamera -->
                            <div id="cameraIcon" class="text-gray-400 flex flex-col items-center">
                                <i class="fas fa-camera text-4xl"></i>
                                <span class="text-sm mt-2">Ambil Foto</span>
                            </div>
                    
                            <!-- Pratinjau Gambar -->
                            <img id="previewImage" src="#" alt="Pratinjau Gambar" class="hidden w-40 h-40 object-cover rounded-lg shadow-md mt-2">
                    
                            <!-- Input File -->
                            <input type="file" id="foto" name="foto_jam_keluar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" capture="environment" required>
                    
                            <!-- Tombol Clear -->
                            <button type="button" id="clearButton" class="hidden mt-4 text-red-500 underline text-sm">Clear</button>
                        </div>
                    </div>
                    
                    <button type="submit" id="checkOutButton" class="bg-teal-500 text-white px-20 py-2 rounded">Check Out</button>
                    <div id="checkOutLoadingSpinner" class="hidden mt-4 text-center">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-6 w-6 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span class="ml-2 text-teal-500">Mengirim data, mohon tunggu...</span>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="text-center mb-6">
                <p class="text-xl font-bold">Belum ada data kehadiran aktif.</p>
                <p>Total Kehadiran Hari Ini: {{ $totalAttendanceToday }}</p> <br>
                <form action="{{ route('attendance.checkIn') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="jam_masuk" class="block text-base font-medium text-gray-100">Jam Masuk</label>
                        
                        <input type="text" id="jam_masuk" name="jam_masuk" class="text-base px-2 py-2 rounded text-center no-border" readonly>
                    </div>
                    
                    <div class="mb-6">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center relative">
                            <!-- Ikon Kamera -->
                            <div id="cameraIcon" class="text-gray-400 flex flex-col items-center">
                                <i class="fas fa-camera text-4xl"></i>
                                <span class="text-sm mt-2">Ambil Foto</span>
                            </div>
                    
                            <!-- Pratinjau Gambar -->
                            <img id="previewImage" src="#" alt="Pratinjau Gambar" class="hidden w-40 h-40 object-cover rounded-lg shadow-md mt-2">
                    
                            <!-- Input File -->
                            <input type="file" id="foto" name="foto_jam_masuk" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" capture="environment" required>
                    
                            <!-- Tombol Clear -->
                            <button type="button" id="clearButton" class="hidden mt-4 text-red-500 underline text-sm">Clear</button>
                        </div>
                    </div>
                    
                    
                    <button type="submit" id="checkInButton" class="bg-teal-500 text-white px-20 py-2 rounded">Check In</button>
                    <div id="checkInLoadingSpinner" class="hidden mt-4 text-center">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-6 w-6 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span class="ml-2 text-teal-500">Mengirim data, mohon tunggu...</span>
                        </div>
                    </div>
                </form>
            </div>
        @endif
        
        @if($attendance)
            <div class="border p-4 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-briefcase mr-2"></i>
                    <span class="font-bold">Total Jam Kerja</span>
                </div>
                <div class="flex justify-between">
                    <div>
                        <p>Hari ini</p>
                        <p class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($attendance->created_at)->diffInDays(now()) }} Hari
                            {{ \Carbon\Carbon::parse($attendance->created_at)->diffInHours(now()) % 24 }} Jam
                            {{ \Carbon\Carbon::parse($attendance->created_at)->diffInMinutes(now()) % 60 }} Menit
                        </p>
                    </div>
                    <div class="border-l-2 border-gray-300 mx-4"></div>
                    <div>
                        <p>Jam Check in</p>
                        <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($attendance->jam_masuk)->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const jamMasukInput = document.getElementById('jam_masuk');
    const jamKeluarInput = document.getElementById('jam_keluar');

    // Fungsi untuk mendapatkan waktu saat ini dalam format HH:MM
    const getCurrentTime = () => {
        const currentDate = new Date();
        return `${String(currentDate.getHours()).padStart(2, '0')}:${String(currentDate.getMinutes()).padStart(2, '0')}`;
    };

    if (jamMasukInput) {
        jamMasukInput.value = getCurrentTime();
    }

    if (jamKeluarInput && !jamKeluarInput.value) {
        jamKeluarInput.value = getCurrentTime();
    }

    // Mengambil URL dari Blade dengan JSON encoding
    const checkInRoute = @json(route('attendance.checkIn'));
    const checkOutRoute = @json(route('attendance.checkOut'));

    const checkInForm = document.querySelector(`form[action="${checkInRoute}"]`);
    const checkInButton = document.getElementById('checkInButton');
    const checkInLoadingSpinner = document.getElementById('checkInLoadingSpinner');

    if (checkInForm) {
        checkInForm.addEventListener('submit', () => {
            if (checkInButton && checkInLoadingSpinner) {
                checkInButton.disabled = true;
                checkInButton.innerText = 'Mengirim...';
                checkInLoadingSpinner.classList.remove('hidden');
            }
        });
    }

    // Check-Out Form Handling
    const checkOutForm = document.querySelector(`form[action="${checkOutRoute}"]`);
    const checkOutButton = document.getElementById('checkOutButton');
    const checkOutLoadingSpinner = document.getElementById('checkOutLoadingSpinner');

    if (checkOutForm) {
        checkOutForm.addEventListener('submit', () => {
            if (checkOutButton && checkOutLoadingSpinner) {
                checkOutButton.disabled = true;
                checkOutButton.innerText = 'Mengirim...';
                checkOutLoadingSpinner.classList.remove('hidden');
            }
        });
    }
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const inputFile = document.getElementById('foto');
    const previewImage = document.getElementById('previewImage');
    const cameraIcon = document.getElementById('cameraIcon');
    const clearButton = document.getElementById('clearButton');

    inputFile.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result; // Tampilkan gambar
                previewImage.classList.remove('hidden'); // Tampilkan elemen pratinjau
                cameraIcon.classList.add('hidden'); // Sembunyikan ikon kamera
                clearButton.classList.remove('hidden'); // Tampilkan tombol clear
            };
            reader.readAsDataURL(file); // Membaca file
        }
    });

    clearButton.addEventListener('click', () => {
        inputFile.value = ''; // Reset input file
        previewImage.src = '#'; // Reset pratinjau gambar
        previewImage.classList.add('hidden'); // Sembunyikan elemen pratinjau
        cameraIcon.classList.remove('hidden'); // Tampilkan kembali ikon kamera
        clearButton.classList.add('hidden'); // Sembunyikan tombol clear
    });
});

</script>
</html>