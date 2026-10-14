<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Master Template SI</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <!-- Logo/Brand -->
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Master Template SI</h1>
                <p class="text-sm text-gray-400">{{ auth()->user()->role->nama_role }}</p>
            </div>
            
            <!-- Menu Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                @if(auth()->user()->isAdmin())
                    {{-- Menu Admin --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-users"></i>
                        <span>Manajemen User</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                @else
                    {{-- Menu Pimpinan --}}
                    <a href="{{ route('pimpinan.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan SPK</span>
                    </a>
                @endif
            </nav>
            
            <!-- User Profile & Logout -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>