<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sistem Informasi Magang</title>
    <!-- Gunakan Tailwind CDN untuk preview cepat, atau ganti dengan @vite('resources/css/app.css') jika sudah setup Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0D8ABC', // Biru utama sesuai request
                    }
                }
            }
        }
    </script>
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js untuk interaksi sidebar/dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js untuk grafik Donut -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased overflow-hidden">
    <div class="flex h-screen w-full" x-data="{ sidebarOpen: false }">
        
        <!-- Komponen Sidebar -->
        @include('magang.components.sidebar')

        <!-- Wrapper Konten Utama -->
        <div class="flex-1 flex flex-col h-full relative overflow-hidden">
            
            <!-- Komponen Navbar -->
            @include('magang.components.navbar')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>
