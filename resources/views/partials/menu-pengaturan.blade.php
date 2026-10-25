@php
    $pengaturanActive = request()->is('admin/users*') || request()->is('admin/pengaturan/tema*') || request()->is('admin/pengaturan/tata-letak*') || request()->is('admin/pengaturan/profile*');
    $isTop  = ($layPos === 'top');
    $isMini = ($layPos !== 'top' && $layMode === 'mini');
    $isFlyout = $isTop || $isMini;

    $subItem = $isFlyout
        ? 'flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100'
        : 'flex items-center space-x-3 px-4 py-2 rounded-lg text-sm text-white/90 ' . $hoverClass;
    $subActive = $isFlyout ? 'bg-gray-100 font-semibold text-gray-900' : $activeClass;

    if ($isTop) {
        $panelPos = 'absolute right-0 top-full mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50';
    } elseif ($isMini) {
        // Mode Mini: posisi fixed diatur oleh JS agar tidak terpotong sidebar
        $panelPos = 'mini-flyout w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50';
    } else {
        $panelPos = 'mt-1 ml-4 space-y-1';
    }
@endphp

<div class="relative" data-dropdown>
    <button type="button" onclick="toggleDropdown('menu-pengaturan')"
        class="flex items-center justify-between rounded-lg {{ $hoverClass }} {{ $pengaturanActive ? $activeClass : '' }} {{ $isTop ? 'px-3 py-2 whitespace-nowrap text-sm' : 'w-full px-4 py-2' }}">
        <span class="flex items-center {{ $isTop ? 'space-x-2' : 'space-x-3' }}">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </span>
        <i class="fas fa-chevron-down text-xs ml-2 {{ $pengaturanActive ? 'rotate-180' : '' }}"></i>
    </button>

    {{-- Di mode Mini panel selalu mulai tertutup agar tidak menyempil di sidebar --}}
    <div id="menu-pengaturan" class="{{ ($pengaturanActive && !$isMini) ? '' : 'hidden' }} {{ $panelPos }}">
        <a href="{{ url('/admin/pengaturan/profile') }}" class="{{ $subItem }} {{ request()->is('admin/pengaturan/profile*') ? $subActive : '' }}">
            <i class="fas fa-user-edit w-4 text-center"></i>
            <span>Profil & Branding</span>
        </a>
        <a href="{{ url('/admin/users') }}" class="{{ $subItem }} {{ request()->is('admin/users*') ? $subActive : '' }}">
            <i class="fas fa-users-cog w-4 text-center"></i>
            <span>Manajemen User</span>
        </a>
        <a href="{{ url('/admin/pengaturan/tema') }}" class="{{ $subItem }} {{ request()->is('admin/pengaturan/tema*') ? $subActive : '' }}">
            <i class="fas fa-palette w-4 text-center"></i>
            <span>Tema Tampilan</span>
        </a>
        <a href="{{ url('/admin/pengaturan/tata-letak') }}" class="{{ $subItem }} {{ request()->is('admin/pengaturan/tata-letak*') ? $subActive : '' }}">
            <i class="fas fa-columns w-4 text-center"></i>
            <span>Tata Letak</span>
        </a>
    </div>
</div>