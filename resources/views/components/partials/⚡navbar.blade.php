<?php

use Livewire\Component;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Subham\FilamentDynamicSettings\Facades\Settings;
use Livewire\Attributes\Computed;
use App\Models\Post;
use App\Models\Category;

new class extends Component {
    public $search = '';
    public $searchOpen = false;

    #[Computed]
    public function searchResults()
    {
        if (!$this->search || strlen($this->search) < 2) {
            return [
                'posts' => collect(),
                'categories' => collect(),
            ];
        }

        try {
            return [
                'posts' =>
                    Post::where('title', 'like', '%' . $this->search . '%')
                        ->latest()
                        ->take(5)
                        ->get() ?? collect(),

                'categories' =>
                    Category::where('name', 'like', '%' . $this->search . '%')
                        ->latest()
                        ->take(3)
                        ->get() ?? collect(),
            ];
        } catch (\Exception $e) {
            return ['posts' => collect(), 'categories' => collect()];
        }
    }

    #[Computed]
    public function mainMenuItems()
    {
        $menuId = Settings::get('header_menu', 'menu');

        if (empty($menuId)) {
            return collect();
        }

        $menuData = Cache::rememberForever("menu_items_array_{$menuId}", function () use ($menuId) {
            return MenuItem::whereNull('parent_id')->with('children')->where('menu_id', $menuId)->orderBy('order')->get()->toArray();
        });

        $menuItems = json_decode(json_encode($menuData));
        return collect($menuItems);
    }

    public function getIcon($title)
    {
        $title = trim($title);
        if (str_contains($title, 'หน้าแรก') || str_contains($title, 'Home')) {
            return 'heroicon-s-home';
        }
        if (str_contains($title, 'เกี่ยวกับ') || str_contains($title, 'About') || str_contains($title, 'วิทยาลัย')) {
            return 'heroicon-s-building-library';
        }
        if (str_contains($title, 'บริหาร') || str_contains($title, 'ผู้บริหาร') || str_contains($title, 'Executive')) {
            return 'heroicon-s-user-group';
        }
        if (str_contains($title, 'สาขา') || str_contains($title, 'แผนก') || str_contains($title, 'ช่าง') || str_contains($title, 'Department')) {
            return 'heroicon-s-squares-2x2';
        }
        if (str_contains($title, 'หลักสูตร') || str_contains($title, 'Curriculum') || str_contains($title, 'Course')) {
            return 'heroicon-s-book-open';
        }
        if (str_contains($title, 'สมัคร') || str_contains($title, 'รับสมัคร') || str_contains($title, 'Admission') || str_contains($title, 'Register')) {
            return 'heroicon-s-pencil-square';
        }
        if (str_contains($title, 'ข่าว') || str_contains($title, 'News') || str_contains($title, 'ประชาสัมพันธ์')) {
            return 'heroicon-s-megaphone';
        }
        if (str_contains($title, 'บริการ') || str_contains($title, 'Service')) {
            return 'heroicon-s-wrench-screwdriver';
        }
        if (str_contains($title, 'นักเรียน') || str_contains($title, 'นักศึกษา') || str_contains($title, 'Student') || str_contains($title, 'Parent')) {
            return 'heroicon-s-academic-cap';
        }
        if (str_contains($title, 'ติดต่อ') || str_contains($title, 'Contact')) {
            return 'heroicon-s-phone';
        }
        if (str_contains($title, 'คลัง') || str_contains($title, 'ดาวน์โหลด') || str_contains($title, 'Download') || str_contains($title, 'เอกสาร')) {
            return 'heroicon-s-arrow-down-tray';
        }
        return 'heroicon-s-document-text';
    }
};
?>

<div x-data="{ mobileMenuOpen: false, searchModalOpen: false, langOpen: false, scrolled: false, isDark: document.documentElement.classList.contains('dark') }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     @dark-mode-changed.window="isDark = $event.detail.isDark"
     class="w-full fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white dark:bg-slate-900"
     :class="scrolled ? 'shadow-md border-b border-gray-100 dark:border-slate-800' : 'border-b border-gray-100 dark:border-slate-800'">

    <!-- Top Information Bar -->
    <div class="bg-linear-to-r from-[#7a1a40] to-[#8f1e4a] text-white transition-all duration-300 ease-in-out transform origin-top font-[sarabun]"
        :class="scrolled ? 'h-0 opacity-0 overflow-hidden py-0' : 'min-h-10 opacity-100 py-2'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm font-medium tracking-wide">
            <div class="flex items-center space-x-4 max-sm:mb-2">
                <span class="flex items-center">
                    <x-heroicon-o-clock class="mr-1.5 h-4 w-4 text-amber-300" />
                    เปิด: จันทร์ - ศุกร์, 8.00 - 16.30 น.
                </span>
                <span class="hidden sm:block border-l border-white/30 h-4"></span>
                <a href="mailto:official@trattc.ac.th" class="flex items-center hover:text-amber-200 transition-colors">
                    <x-heroicon-s-envelope class="mr-1.5 h-4 w-4 text-amber-300" />
                    official@trattc.ac.th
                </a>
            </div>

            <div class="flex items-center space-x-5">
                <a href="https://www.facebook.com/profile.php?id=100093014446740" target="_blank" class="flex items-center hover:text-amber-200 transition-colors">
                    <x-bi-facebook class="mr-1.5 h-4 w-4 text-amber-300" />
                    วิทยาลัยเทคนิคตราด
                </a>
                <span class="hidden sm:block border-l border-white/30 h-4"></span>
                <a href="tel:039-518-105" class="flex items-center hover:text-amber-200 transition-colors">
                    <x-heroicon-o-device-phone-mobile class="mr-1.5 h-4 w-4 text-amber-300" />
                    039-518-105
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="font-[sarabun] w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo & College Name -->
                <div class="shrink-0 flex items-center">
                    <a wire:navigate href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="relative overflow-hidden rounded-full bg-white dark:bg-slate-800 p-1 transition-all duration-300">
                            <img src="{{ asset('images/trat.png') }}"
                                class="w-12 h-12 sm:w-13 sm:h-13 object-contain group-hover:scale-105 transition-transform duration-300"
                                alt="ตราวิทยาลัยเทคนิคตราด" />
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-lg sm:text-xl font-extrabold text-[#7a1a40] dark:text-rose-400 tracking-tight leading-tight group-hover:text-[#5c132f] dark:group-hover:text-rose-300 transition-colors">
                                วิทยาลัยเทคนิคตราด
                            </span>
                            <span class="text-[10px] sm:text-[11px] text-[#7a1a40]/80 dark:text-rose-300/80 font-bold tracking-wider uppercase mt-0.5">
                                TRAT TECHNICAL COLLEGE
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:flex lg:items-center lg:space-x-1 xl:space-x-2">
                    {{-- 1. หน้าแรก (Pill Button Active Style) --}}
                    @php
                        $isHome = request()->routeIs('home') || request()->is('/') || request()->is('app');
                    @endphp
                    <a wire:navigate href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ $isHome ? 'bg-[#7a1a40] text-white shadow-xs hover:bg-[#661434]' : 'text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/60 dark:hover:bg-slate-800' }}">
                        <x-heroicon-s-home class="w-4 h-4 {{ $isHome ? 'text-white' : 'text-slate-600 dark:text-slate-300' }}" />
                        <span>หน้าแรก</span>
                    </a>

                    {{-- Dynamic Menus or Fallback Menus --}}
                    @if($this->mainMenuItems->isNotEmpty())
                        @foreach ($this->mainMenuItems as $item)
                            {{-- Skip Home since we already rendered it as the active pill --}}
                            @if(trim($item->title) === 'หน้าแรก' || trim($item->title) === 'Home')
                                @continue
                            @endif

                            @if (!empty($item->children) && count((array) $item->children) > 0)
                                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                    <button @click="open = !open" class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                                        <x-dynamic-component :component="$this->getIcon($item->title)" class="w-4 h-4 text-slate-400 dark:text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                        <span>{{ $item->title }}</span>
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-transform duration-200"
                                            x-bind:class="open ? 'rotate-180 text-[#7a1a40] dark:text-amber-300' : ''" />
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-2"
                                         class="absolute left-0 mt-1 w-64 rounded-xl bg-white dark:bg-slate-800 shadow-[0_10px_30px_rgba(0,0,0,0.08)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.4)] border border-gray-100 dark:border-slate-700 overflow-hidden z-50 py-1.5"
                                         style="display: none;">
                                        @foreach ($item->children as $childItem)
                                            @php
                                                $childUrl = $childItem->url ?? '#';
                                                $isChildExternal = \Illuminate\Support\Str::startsWith($childUrl, ['http://', 'https://']) && !\Illuminate\Support\Str::contains($childUrl, request()->getHost());
                                            @endphp
                                            <a href="{{ $childUrl }}"
                                               @if($isChildExternal) target="_blank" rel="noopener noreferrer" @endif
                                               class="group/item flex items-center justify-between px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-[#7a1a40] dark:hover:text-rose-300 hover:bg-rose-50/60 dark:hover:bg-slate-700/60 transition-colors">
                                                <div class="flex items-center gap-2 truncate">
                                                    <x-heroicon-s-chevron-right class="w-3 h-3 text-slate-300 dark:text-slate-500 group-hover/item:text-[#7a1a40] dark:group-hover/item:text-rose-300 group-hover/item:translate-x-0.5 transition-all shrink-0" />
                                                    <span class="truncate">{{ $childItem->title ?? 'Untitled' }}</span>
                                                </div>
                                                @if($isChildExternal)
                                                    <i class="fas fa-external-link-alt text-[10px] text-slate-400 group-hover/item:text-[#7a1a40] dark:group-hover/item:text-rose-300 shrink-0 opacity-70"></i>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                @php
                                    $itemUrl = $item->url ?? '#';
                                    $isItemExternal = \Illuminate\Support\Str::startsWith($itemUrl, ['http://', 'https://']) && !\Illuminate\Support\Str::contains($itemUrl, request()->getHost());
                                @endphp
                                <a href="{{ $itemUrl }}"
                                   @if($isItemExternal) target="_blank" rel="noopener noreferrer" @endif
                                   class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                                    <x-dynamic-component :component="$this->getIcon($item->title)" class="w-4 h-4 text-slate-400 dark:text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                    <span>{{ $item->title }}</span>
                                    @if($isItemExternal)
                                        <i class="fas fa-external-link-alt text-[10px] text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 shrink-0 opacity-70"></i>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback default menus matching the mockup --}}
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                                <x-heroicon-s-building-library class="w-4 h-4 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                <span>เกี่ยวกับวิทยาลัย</span>
                                <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300" />
                            </button>
                            <div x-show="open" class="absolute left-0 mt-1 w-56 rounded-xl bg-white dark:bg-slate-800 shadow-lg border border-gray-100 dark:border-slate-700 py-1.5 z-50" style="display: none;">
                                <a href="{{ route('executive.board') }}" class="block px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-[#7a1a40] dark:hover:text-rose-300 hover:bg-rose-50/60 dark:hover:bg-slate-700/60">คณะผู้บริหาร</a>
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                                <x-heroicon-s-book-open class="w-4 h-4 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                <span>หลักสูตร</span>
                                <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300" />
                            </button>
                        </div>

                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                                <x-heroicon-s-megaphone class="w-4 h-4 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                <span>ข่าวสาร</span>
                                <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300" />
                            </button>
                        </div>

                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                                <x-heroicon-s-wrench-screwdriver class="w-4 h-4 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300 transition-colors" />
                                <span>บริการนักเรียน/นักศึกษา</span>
                                <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-amber-300" />
                            </button>
                        </div>

                        <a href="tel:039511141" class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50/50 dark:hover:bg-slate-800 transition-colors">
                            <x-heroicon-s-phone class="w-4 h-4 text-slate-400 group-hover:text-[#7a1a40] transition-colors" />
                            <span>ติดต่อเรา</span>
                        </a>
                    @endif

                    <!-- Action Buttons: Dark Mode, Search & Language -->
                    <div class="flex items-center gap-2 pl-3 ml-2 border-l border-gray-200 dark:border-slate-700">
                        <!-- Dark Mode Toggle Button (Desktop) -->
                        <button onclick="toggleDarkMode()"
                                @click="isDark = !isDark"
                                type="button"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-slate-600 dark:text-amber-400 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-rose-50 dark:hover:bg-slate-800 transition-colors focus:outline-none cursor-pointer"
                                title="สลับโหมดสว่าง/มืด (Dark Mode)"
                                aria-label="สลับโหมดสว่างหรือมืด">
                            <i x-show="!isDark" class="fas fa-moon text-base"></i>
                            <i x-show="isDark" class="fas fa-sun text-base text-amber-400" style="display: none;"></i>
                        </button>

                        <!-- Search Icon Button -->
                        <button @click="searchModalOpen = true"
                                type="button"
                                class="w-9 h-9 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-[#7a1a40] dark:hover:text-amber-400 hover:bg-rose-50 dark:hover:bg-slate-800 transition-colors focus:outline-none cursor-pointer"
                                title="ค้นหา">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </button>

                        <!-- Language Selector (Thai Flag + Dropdown Chevron) -->
                        <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
                            <button @click="langOpen = !langOpen"
                                    type="button"
                                    class="inline-flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition-colors focus:outline-none cursor-pointer"
                                    title="เลือกภาษา / Select Language">
                                <!-- Thai Flag SVG -->
                                <svg class="w-5 h-3.5 rounded-[2px] shadow-2xs overflow-hidden border border-black/10" viewBox="0 0 900 600">
                                    <rect fill="#ED1C24" width="900" height="600"/>
                                    <rect fill="#FFFFFF" y="100" width="900" height="400"/>
                                    <rect fill="#241D4F" y="200" width="900" height="200"/>
                                </svg>
                                <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-slate-400" />
                            </button>

                            <div x-show="langOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-36 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 py-1.5 z-50 text-left"
                                 style="display: none;">
                                <a href="#" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-rose-50/60 dark:hover:bg-slate-700/60 transition-colors">
                                    <svg class="w-4 h-3 rounded-[2px] shadow-2xs" viewBox="0 0 900 600">
                                        <rect fill="#ED1C24" width="900" height="600"/>
                                        <rect fill="#FFFFFF" y="100" width="900" height="400"/>
                                        <rect fill="#241D4F" y="200" width="900" height="200"/>
                                    </svg>
                                    ไทย (TH)
                                </a>
                                <a href="#" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/40 transition-colors">
                                    <svg class="w-4 h-3 rounded-[2px] shadow-2xs" viewBox="0 0 7410 3900">
                                        <rect width="7410" height="3900" fill="#b22234"/>
                                        <path d="M0,450H7410M0,1050H7410M0,1650H7410M0,2250H7410M0,2850H7410M0,3450H7410" stroke="#fff" stroke-width="300"/>
                                        <rect width="2964" height="2100" fill="#3c3b6e"/>
                                    </svg>
                                    English (EN)
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 lg:hidden">
                    <!-- Dark Mode Toggle Button (Mobile) -->
                    <button onclick="toggleDarkMode()"
                            @click="isDark = !isDark"
                            type="button"
                            class="p-2 rounded-xl text-slate-600 dark:text-amber-400 hover:text-[#7a1a40] dark:hover:text-amber-300 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none cursor-pointer"
                            title="สลับโหมดสว่าง/มืด (Dark Mode)"
                            aria-label="สลับโหมดสว่างหรือมืด">
                        <i x-show="!isDark" class="fas fa-moon text-base"></i>
                        <i x-show="isDark" class="fas fa-sun text-base text-amber-400" style="display: none;"></i>
                    </button>

                    <button @click="searchModalOpen = true"
                            type="button"
                            class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-[#7a1a40] hover:bg-gray-100 dark:hover:bg-slate-800">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </button>

                    <button @click="mobileMenuOpen = true"
                            type="button"
                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-700 dark:text-slate-300 hover:text-[#7a1a40] dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 focus:outline-none transition-colors duration-200 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
                        <span class="sr-only">เปิดเมนู</span>
                        <x-heroicon-s-bars-3 class="block h-6 w-6" />
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Modal Overlay -->
    <div x-show="searchModalOpen"
         class="fixed inset-0 z-100 overflow-y-auto"
         style="display: none;">
        <!-- Backdrop -->
        <div x-show="searchModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
             @click="searchModalOpen = false"></div>

        <!-- Modal Box -->
        <div class="relative min-h-screen flex items-start justify-center pt-20 px-4">
            <div x-show="searchModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden z-10"
                 @click.away="searchModalOpen = false">

                <!-- Search Input Bar -->
                <div class="p-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                    <x-heroicon-o-magnifying-glass class="w-6 h-6 text-[#7a1a40] dark:text-rose-400 shrink-0" />
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           placeholder="พิมพ์คำค้นหา เช่น ข่าวสาร, หลักสูตร, ประกาศ..."
                           class="w-full text-base sm:text-lg text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 bg-transparent border-none focus:outline-none focus:ring-0"
                           autofocus>
                    <button @click="searchModalOpen = false; $wire.set('search', '')"
                            class="p-1.5 rounded-full text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <!-- Live Search Results -->
                @if (!empty($search))
                    <div class="max-h-96 overflow-y-auto p-4 space-y-4">
                        @if ($this->searchResults['categories']->isNotEmpty())
                            <div>
                                <div class="text-[11px] font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-2">หมวดหมู่</div>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($this->searchResults['categories'] as $cat)
                                        <a href="{{ route('category.show', $cat->slug) }}"
                                           @click="searchModalOpen = false"
                                           class="flex items-center gap-2 p-2.5 rounded-xl hover:bg-rose-50 dark:hover:bg-slate-700/60 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-[#7a1a40] dark:hover:text-rose-400 transition-colors border border-gray-100 dark:border-slate-700">
                                            <x-heroicon-s-tag class="w-4 h-4 text-[#7a1a40] dark:text-rose-400" />
                                            <span>{{ $cat->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="text-[11px] font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-2">บทความและข่าวสาร</div>
                            @forelse($this->searchResults['posts'] as $res)
                                <a wire:navigate href="{{ route('posts.show', $res->slug) }}"
                                   @click="searchModalOpen = false"
                                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 dark:hover:bg-slate-700/60 transition-colors border-b border-gray-50 dark:border-slate-700/50 last:border-0 group">
                                    <div class="shrink-0 w-12 h-12 rounded-lg bg-gray-100 dark:bg-slate-700 overflow-hidden">
                                        @if($res->feature_images && count($res->feature_images) > 0)
                                            <img src="{{ asset('storage/' . $res->feature_images[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <x-heroicon-o-photo class="w-5 h-5" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors line-clamp-1">{{ $res->title }}</p>
                                        <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5">{{ $res->published_at ? $res->published_at->format('j M Y') : '' }}</p>
                                    </div>
                                </a>
                            @empty
                                @if ($this->searchResults['categories']->isEmpty())
                                    <div class="py-8 text-center text-sm text-gray-400 dark:text-slate-400">
                                        ไม่พบข้อมูลที่ตรงกับ "{{ $search }}"
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <div x-show="mobileMenuOpen" class="lg:hidden relative z-100" style="display: none;">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="mobileMenuOpen = false"></div>

        <!-- Drawer Content -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white dark:bg-slate-900 shadow-2xl">

            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/trat.png') }}" class="w-10 h-10 object-contain" alt="Logo" />
                    <span class="text-base font-bold text-[#7a1a40] dark:text-rose-400">วิทยาลัยเทคนิคตราด</span>
                </div>
                <button @click="mobileMenuOpen = false" class="rounded-full p-2 text-gray-400 hover:bg-rose-50 dark:hover:bg-slate-800 hover:text-[#7a1a40] dark:hover:text-rose-400">
                    <x-heroicon-s-x-mark class="h-5 w-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-6 px-4">
                <nav class="space-y-1.5 font-[sarabun]">
                    <a wire:navigate href="{{ route('home') }}"
                       class="flex items-center gap-2.5 rounded-xl px-4 py-3 text-sm font-bold bg-[#7a1a40] text-white shadow-xs">
                        <x-heroicon-s-home class="w-4 h-4 text-white" />
                        <span>หน้าแรก</span>
                    </a>

                    @foreach ($this->mainMenuItems as $item)
                        @if(trim($item->title) === 'หน้าแรก' || trim($item->title) === 'Home')
                            @continue
                        @endif

                        @if (!empty($item->children) && count((array) $item->children) > 0)
                            <div x-data="{ expanded: false }" class="space-y-1">
                                <button @click="expanded = !expanded"
                                        class="group flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-slate-800 hover:text-[#7a1a40] dark:hover:text-rose-400 transition-colors">
                                    <div class="flex items-center gap-2.5">
                                        <x-dynamic-component :component="$this->getIcon($item->title)" class="w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors" />
                                        <span>{{ $item->title }}</span>
                                    </div>
                                    <x-heroicon-s-chevron-down class="h-4 w-4 transition-transform duration-200"
                                        x-bind:class="expanded ? 'rotate-180 text-[#7a1a40] dark:text-rose-400' : 'text-gray-400'" />
                                </button>
                                <div x-show="expanded" class="space-y-1 px-4 pb-2" style="display: none;">
                                    @foreach ($item->children as $childItem)
                                        @php
                                            $childUrl = $childItem->url ?? '#';
                                            $isChildExternal = \Illuminate\Support\Str::startsWith($childUrl, ['http://', 'https://']) && !\Illuminate\Support\Str::contains($childUrl, request()->getHost());
                                        @endphp
                                        <a href="{{ $childUrl }}"
                                           @if($isChildExternal) target="_blank" rel="noopener noreferrer" @endif
                                           class="flex items-center justify-between rounded-lg px-4 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-rose-50 dark:hover:bg-slate-800 hover:text-[#7a1a40] dark:hover:text-rose-400">
                                            <div class="flex items-center gap-2 truncate">
                                                <x-heroicon-s-chevron-right class="w-3 h-3 text-slate-300 dark:text-slate-600 shrink-0" />
                                                <span class="truncate">{{ $childItem->title ?? 'Untitled' }}</span>
                                            </div>
                                            @if($isChildExternal)
                                                <i class="fas fa-external-link-alt text-[10px] text-slate-400 shrink-0 opacity-70"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $itemUrl = $item->url ?? '#';
                                $isItemExternal = \Illuminate\Support\Str::startsWith($itemUrl, ['http://', 'https://']) && !\Illuminate\Support\Str::contains($itemUrl, request()->getHost());
                            @endphp
                            <a href="{{ $itemUrl }}"
                               @if($isItemExternal) target="_blank" rel="noopener noreferrer" @endif
                               class="group flex items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-slate-700 dark:text-slate-200 hover:bg-rose-50 dark:hover:bg-slate-800 hover:text-[#7a1a40] dark:hover:text-rose-400 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <x-dynamic-component :component="$this->getIcon($item->title)" class="w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors" />
                                    <span>{{ $item->title }}</span>
                                </div>
                                @if($isItemExternal)
                                    <i class="fas fa-external-link-alt text-[10px] text-slate-400 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 shrink-0 opacity-70"></i>
                                @endif
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>

            <div class="border-t border-gray-100 dark:border-slate-800 p-5 bg-white dark:bg-slate-900">
                <a href="tel:039511141"
                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#7a1a40] px-4 py-3 text-sm font-bold text-white shadow-md hover:bg-[#661434] transition-colors">
                    <x-heroicon-s-phone class="h-4 w-4" />
                    <span>ติดต่อเรา</span>
                </a>
            </div>
        </div>
    </div>
</div>
