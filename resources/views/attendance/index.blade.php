<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <div class="text-center mb-6">
            <div class="inline-flex items-center bg-teal-500 text-white px-4 py-2 rounded-full">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>{{ \Carbon\Carbon::now()->format('D, M jS, Y') }}</span>
            </div>
        </div>
        <div class="text-center mb-6">
            <p class="text-lg font-semibold">{{ Auth::user()->karyawan->name }}</p>
            <p>{{ Auth::user()->karyawan->jabatan->name }}</p>
            <p>{{ Auth::user()->karyawan->nik }}</p>
        </div>
        @if($attendance)
            <div class="text-center mb-6">
                <p class="text-xl font-bold">Anda sudah Check In</p>
            </div>
        @else
            <div class="text-center mb-6">
                <p class="text-xl font-bold">Anda belum Check In</p>
                <form action="{{ route('attendance.checkIn') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                        <input type="text" id="jam_masuk" name="jam_masuk" class="w-full px-4 py-2 border rounded" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="foto_jam_masuk" class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer inline-flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Foto Check In
                        </label>
                        <input type="file" id="foto_jam_masuk" name="foto_jam_masuk" class="hidden" accept="image/*" >
                    </div>
                    
                    <button type="submit" class="bg-teal-500 text-white px-20 py-2 rounded">Check In</button>
                </form>
            </div>
        @endif
        <div class="text-center mb-6">
            @if($attendance)
                <form action="{{ route('attendance.checkOut') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                        <input type="text" id="jam_masuk" name="jam_keluar" class="w-full px-4 py-2 border rounded" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="foto_jam_keluar" class="border border-black px-4 py-2 rounded cursor-pointer inline-flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Foto Outs
                        </label>
                        <input type="file" id="foto_jam_keluar" name="foto_jam_keluar" class="hidden" accept="image/*" capture="camera">
                    </div>
                    
                    <button type="submit" class="border border-black px-4 py-2 rounded">Check Out</button>
                </form>
            @endif
        </div>
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
                            {{ \Carbon\Carbon::parse($attendance->jam_masuk)->diffInHours(\Carbon\Carbon::parse($attendance->jam_keluar)) }} Jam
                            {{ \Carbon\Carbon::parse($attendance->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($attendance->jam_keluar)) % 60 }} Menit</p>
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
    // Mengatur input jam_masuk dengan waktu server
    document.addEventListener('DOMContentLoaded', () => {
        const jamMasukInput = document.getElementById('jam_masuk');
        const currentDate = new Date();
        const hours = String(currentDate.getHours()).padStart(2, '0');
        const minutes = String(currentDate.getMinutes()).padStart(2, '0');
        jamMasukInput.value = `${hours}:${minutes}`;
    });
</script>
</html>