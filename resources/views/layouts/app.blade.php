@php
    $themeMode   = \App\Models\Setting::get('theme_mode', 'solid');
    $themeC1     = \App\Models\Setting::get('theme_primary', '#1f2937');
    $themeC2     = \App\Models\Setting::get('theme_secondary', '#111827');
    $themeAccent = \App\Models\Setting::get('theme_accent', '#2563eb');
    $sidebarStyle = $themeMode === 'gradient'
        ? "background: linear-gradient(180deg, {$themeC1} 0%, {$themeC2} 100%);"
        : "background-color: {$themeC1};";

    $layPos    = \App\Models\Setting::get('layout_sidebar_position', 'left');
    $layMode   = \App\Models\Setting::get('layout_sidebar_mode', 'full');
    $layWidth  = \App\Models\Setting::get('layout_content_width', 'full');
    $layCorner = \App\Models\Setting::get('layout_corner', 'rounded');
    $layScale  = \App\Models\Setting::get('layout_scale', 'normal');
    $appName   = \App\Models\Setting::get('app_name', 'Master Template SI');

    $activeClass = 'bg-white/20';
    $hoverClass  = 'hover:bg-white/10';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ $appName }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            body { background: white !important; }
            table { border-collapse: collapse !important; }
            table th, table td { border: 1px solid black !important; padding: 8px !important; }
        }

        :root { --accent: {{ $themeAccent }}; }
        .bg-blue-600 { background-color: var(--accent) !important; }
        .hover\:bg-blue-700:hover { background-color: var(--accent) !important; filter: brightness(0.9); }

        html { font-size: {{ $layScale === 'small' ? '14px' : ($layScale === 'large' ? '18px' : '16px') }}; }
        
        /* PERBAIKAN MODE MINI: CSS presisi dengan child combinator (>) */
        .sidebar-mini nav > a > span, 
        .sidebar-mini nav > [data-dropdown] > button > span > span { display: none; }
        
        .sidebar-mini nav > a, 
        .sidebar-mini nav > [data-dropdown] > button { 
            justify-content: center; 
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .sidebar-mini nav > [data-dropdown] > button > i.fa-chevron-down { display: none; }

        .layout-sharp .rounded-lg, .layout-sharp .rounded, .layout-sharp .rounded-full { border-radius: 0 !important; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .topbar-nav a, .topbar-nav button { white-space: nowrap; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .topbar-user p { white-space: nowrap; }
    </style>
</head>

<body class="bg-gray-100 {{ $layCorner === 'sharp' ? 'layout-sharp' : '' }}">

<div class="flex {{ $layPos === 'top' ? 'flex-col' : ($layPos === 'right' ? 'flex-row-reverse' : '') }} h-screen overflow-hidden print:block print:h-auto print:overflow-visible">

    {{-- PERBAIKAN: aside mode mini tidak overflow-hidden agar flyout dropdown bisa keluar --}}
    <aside class="text-white flex print:hidden {{ $layPos === 'top' 
        ? 'w-full flex-row items-center justify-between gap-4 px-4'  
        : (($layMode === 'mini') ? 'w-20 sidebar-mini flex-col overflow-visible' : 'w-64 flex-col overflow-y-auto') }}" 
        style="{{ $sidebarStyle }}">
        
        <div class="{{ $layPos === 'top' ? 'py-3 flex items-center flex-shrink-0' : 'p-4 border-b border-gray-700 ' . (($layMode === 'mini') ? 'text-center px-2' : '') }}">
            @if ($layPos !== 'top' && $layMode === 'mini')
                <h1 class="text-xl font-bold">SI</h1>
            @else
                <h1 class="text-xl font-bold">{{ $appName }}</h1>
                @if ($layPos !== 'top')
                    <p class="text-sm text-gray-400">{{ auth()->user()->role->nama_role }}</p>
                @endif
            @endif
        </div>

        <nav class="{{ $layPos === 'top' 
            ? 'topbar-nav flex flex-row items-center justify-end space-x-1 py-2 flex-1 min-w-0 overflow-x-auto no-scrollbar'    
            : 'flex-1 min-h-0 p-4 space-y-2 overflow-y-auto no-scrollbar' }}">
            
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.spk.hasil') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->routeIs('admin.spk.*') ? $activeClass : '' }}">
                    <i class="fas fa-calculator"></i>
                    <span>Perhitungan SAW</span>
                </a>

                <a href="{{ url('/admin/kriteria') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->is('admin/kriteria*') ? $activeClass : '' }}">
                    <i class="fas fa-list-ul"></i>
                    <span>Kriteria SPK</span>
                </a>

                <a href="{{ url('/admin/alternatif') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->is('admin/alternatif*') ? $activeClass : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Alternatif SPK</span>
                </a>

                <a href="{{ url('/admin/penilaian') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->is('admin/penilaian*') ? $activeClass : '' }}">
                    <i class="fas fa-table"></i>
                    <span>Input Penilaian</span>
                </a>

                {{-- Dropdown Pengaturan (mode sidebar: di dalam nav) --}}
                @if ($layPos !== 'top')
                    @include('partials.menu-pengaturan')
                @endif
            @else
                <a href="{{ route('pimpinan.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }} {{ request()->routeIs('pimpinan.dashboard') ? $activeClass : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg {{ $hoverClass }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan SPK</span>
                </a>
            @endif
        </nav>

        {{-- Dropdown Pengaturan (mode topbar: di kanan setelah nav) --}}
        @if ($layPos === 'top' && auth()->user()->isAdmin())
            @include('partials.menu-pengaturan')
        @endif

        <div class="{{ $layPos === 'top' 
           ? 'topbar-user flex items-center space-x-4 py-2 flex-shrink-0' 
            : 'p-4 border-t border-gray-700' }}">
            <div class="flex items-center space-x-3 {{ $layPos !== 'top' ? 'mb-3' : '' }} {{ ($layPos !== 'top' && $layMode === 'mini') ? 'justify-center w-full' : '' }}">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user"></i>
                </div>
                @if ($layPos === 'top' || $layMode !== 'mini')
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        @if ($layPos !== 'top')
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        @endif
                    </div>
                @endif
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="{{ $layPos === 'top' ? 'px-4' : 'w-full' }} bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm">
                    <i class="fas fa-sign-out-alt {{ ($layPos === 'top' || $layMode !== 'mini') ? 'mr-2' : '' }}"></i>
                    @if ($layPos === 'top' || $layMode !== 'mini')Logout @endif
                </button>
            </form>
        </div>
    </aside>
    
    <div class="flex-1 flex flex-col overflow-hidden print:overflow-visible">
        <header class="bg-white shadow-sm print:hidden">
            <div class="px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            </div>
        </header>
        
        <main class="flex-1 overflow-y-auto p-6 print:p-0 print:overflow-visible">
            <div class="{{ $layWidth === 'boxed' ? 'max-w-6xl mx-auto' : '' }}">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        const el = document.getElementById(id);
        const akanMuncul = el.classList.contains('hidden');
        el.classList.toggle('hidden');

        // Mode Mini: posisikan panel FIXED di samping tombol gerigi
        if (akanMuncul && el.classList.contains('mini-flyout')) {
            const tombol = el.closest('[data-dropdown]').querySelector('button');
            const kotak = tombol.getBoundingClientRect();
            const lebarPanel = el.offsetWidth;
            const ruangKanan = window.innerWidth - kotak.right;

            el.style.position = 'fixed';
            el.style.top = kotak.top + 'px';

            // Jika ruang di KANAN tidak cukup (sidebar kanan), buka ke KIRI
            if (ruangKanan < lebarPanel + 16) {
                el.style.left = (kotak.left - lebarPanel - 8) + 'px';
            } else {
                el.style.left = (kotak.right + 8) + 'px';
            }
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-dropdown]')) {
            const el = document.getElementById('menu-pengaturan');
            if (el) el.classList.add('hidden');
        }
    });
</script>
</body>
</html>