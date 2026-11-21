<?php

use Livewire\Component;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;
use Subham\FilamentDynamicSettings\Facades\Settings;
use Livewire\Attributes\Computed;

new class extends Component
{
        /**
     * ดึงข้อมูลเมนูโดยใช้ Computed Property
     * ข้อมูลจะถูกโหลดเพียงครั้งเดียวต่อการ Render
     */
    #[Computed]
    public function mainMenuItems()
    {
        // 1. ดึง Setting ID ของเมนู
        $menuId = Settings::get('header_menu', 'menu');

        if (empty($menuId)) {
            return collect();
        }

        // 2. จัดการ Cache และ Query ข้อมูล (Logic เดิมจาก MenuComposer)
        $menuData = Cache::rememberForever("menu_items_array_{$menuId}", function () use ($menuId) {
            return MenuItem::whereNull('parent_id')
            ->with('children')
            ->where('menu_id', $menuId)
            ->orderBy('order')
            ->get()
            ->toArray();
        });

        // 3. แปลง Array กลับเป็น Object เพื่อให้ Blade ใช้งาน $item->title ได้ตามเดิม
        $menuItems = json_decode(json_encode($menuData));
        return collect($menuItems);
    }
};
?>

<div>
    <header class="shadow-md font-[sarabun] bg-white min-h-15 tracking-tight w-full fixed top-0 z-50">
        <section class="bg-[#801810] min-h-[40] px-4 py-3 sm:px-40 flex items-center max-sm:flex-col">
            <button type="button" class="text-white text-sm flex items-center">
                <x-heroicon-o-clock class="mr-1 h-4 w-4" />
                เปิด: จันทร์ - ศุกร์, 8.00AM to 16.30PM
            </button>
            <span class="border-l h-3 mx-6 max-sm:hidden"></span>
            <button type="button" class="text-white text-sm max-sm:my-2 flex items-center">
                <x-heroicon-s-envelope class="mr-1 h-4 w-4" />
                official@trattc.ac.th
            </button>

            <div class="sm:ml-auto flex">
                <a href="#" class="text-white text-sm mr-1 flex items-center">
                    <x-bi-facebook class="mr-1 h-4 w-4" />
                    วิทยาลัยเทคนิคตราด
                </a>
                <a href="#" class="text-white text-sm mr-1 flex items-center">
                    <x-heroicon-o-device-phone-mobile class="mr-1 h-5 w-5" />
                    ติอต่อ: 039-518-105
                </a>
            </div>
        </section>

          <hr class="grow border-[#801810] border-0 shadow-2xl" />

        <div class="px-4 py-2 w-full flex items-center bg-[#ffffff] min-h-10">
            <div class="flex flex-wrap items-center justify-between sm:px-2 gap-2 w-full max-w-7xl mx-auto">
                <a href="{{ route('home') }}"
                    class="max-sm:hidden flex mr-10 hover:text-amber-70 text-amber-900 text-[16px] font-semibold lg:hover:fill-blue-700">
                    <div class="flex">
                        <img src="{{ asset('images/trat.png') }}" class="shrink-0 rounded-md object-contain size-14" />
                        <span class="ml-1 mt-3 text-xl font-semibold">วิทยาลัยเทคนิคตราด</span>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="hidden max-sm:block text-[#801810]">
                    <x-heroicon-c-academic-cap class="h-8 w-8" />
                </a>

                <div id="collapseMenu"
                    class="max-lg:hidden lg:block! max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-50 max-lg:before:inset-0 max-lg:before:z-50">
                    <button id="toggleClose"
                        class="lg:hidden fixed top-2 right-4 z-100 rounded-full bg-white w-9 h-9 flex items-center justify-center border border-gray-200 cursor-pointer">
                        <x-heroicon-s-x-mark class="h-5 w-5" />
                    </button>

                    {{-- เรียกใช้ Computed Property ใน Blade --}}
                    {{-- {{ $this->mainMenuItems }} --}}

                    <ul class="lg:flex lg:gap-x-5 max-lg:space-y-3 max-lg:fixed items-center max-lg:bg-white max-lg:w-1/2 max-lg:min-w-75 max-lg:top-0 max-lg:left-0 max-lg:p-6 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50">
                        {{-- เรียกใช้ผ่าน $this->mainMenuItems --}}
                        @foreach ($this->mainMenuItems as $item)
                            @if (!empty($item->children) && count((array) $item->children) > 0)
                                <li class="group max-lg:border-b max-lg:border-gray-300 max-lg:py-3 relative"
                                    x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                    <button
                                        class="hover:text-amber-700 text-amber-900 lg:hover:fill-blue-700 flex items-center focus:outline-none">
                                        <x-heroicon-s-building-library class="mr-1 h-5 w-5" />
                                        {{ $item->title ?? 'Untitled' }}
                                        <x-bi-caret-down-fill class="ml-1 h-3 w-3" />
                                    </button>

                                    <ul x-show="open" x-transition
                                        class="absolute left-0 mt-2 w-max bg-white shadow-lg rounded-md z-10 py-1"
                                        style="display: none;">
                                        @foreach ($item->children as $childItem)
                                            <li>
                                                <a href="{{ $childItem->url ?? '#' }}"
                                                    class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                                    {{ $childItem->title ?? 'Untitled Child' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li
                                    class="group max-lg:border-b max-lg:border-gray-300 max-lg:py-3 hover:text-amber-700 text-amber-900 flex">
                                    <x-heroicon-s-home class="mr-1 h-5 w-5" />
                                    <a href="{{ $item->url ?? '#' }}"
                                        class="hover:text-amber-700 text-amber-900 lg:hover:fill-blue-700">
                                        {{ $item->title ?? 'Untitled' }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="flex items-center space-x-8 max-lg:ml-auto">
                    <button id="toggleOpen" class="lg:hidden cursor-pointer">
                        <x-heroicon-s-bars-3 class="h-8 w-8" />
                    </button>
                </div>
            </div>
        </div>
    </header>
</div>

<script>
    var toggleOpen = document.getElementById('toggleOpen');
    var toggleClose = document.getElementById('toggleClose');
    var collapseMenu = document.getElementById('collapseMenu');

    function handleClick() {
        if (collapseMenu.style.display === 'block') {
            collapseMenu.style.display = 'none';
        } else {
            collapseMenu.style.display = 'block';
        }
    }

    if (toggleOpen) toggleOpen.addEventListener('click', handleClick);
    if (toggleClose) toggleClose.addEventListener('click', handleClick);
</script>
