<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Banner;
use Illuminate\Support\Collection;

new #[Layout('layouts.app', ['hideHeaderFooter' => true])] class extends Component
{
    public Collection $gridBanners;

    public function mount(): void
    {
        $now = now();
        $this->gridBanners = Banner::query()
            ->where('banner_type', 'grid')
            ->where('is_active', true)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('position', 'asc')
            ->get();
    }
};
?>

<div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col items-center justify-start pt-4 pb-12 px-4 relative antialiased">
    <!-- Ambient Light-themed Color Orbs -->
    <div class="absolute top-[-20%] left-[-10%] w-[70%] h-[60%] rounded-full bg-blue-200/40 blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-amber-100/50 blur-[120px] pointer-events-none"></div>
    <div class="absolute top-[40%] right-[20%] w-[40%] h-[40%] rounded-full bg-red-100/30 blur-[100px] pointer-events-none"></div>

    <!-- Header Section -->
    <div class="flex flex-col items-center mb-8 text-center transition-all duration-500 hover:scale-102">
        <div class="w-24 h-24 mb-4 p-1.5 rounded-full bg-gradient-to-tr from-amber-400 via-red-500 to-red-600 shadow-lg shadow-red-500/10">
            <div class="w-full h-full rounded-full bg-white flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/trat.png') }}" alt="ตราวิทยาลัยเทคนิคตราด" class="w-16 h-16 object-contain">
            </div>
        </div>
        <h1 class="text-3xl md:text-4xl font-black tracking-wide text-slate-800 drop-shadow-xs">
            วิทยาลัยเทคนิคตราด
        </h1>
        <p class="text-xs md:text-sm font-bold text-red-600 uppercase tracking-widest mt-1">
            Trat Technical College
        </p>
    </div>

    <!-- Banners Section -->
    @if($gridBanners->isEmpty())
        <div class="w-full max-w-6xl bg-white/70 backdrop-blur-md rounded-3xl p-3 border border-white shadow-[0_20px_50px_rgba(31,38,135,0.06)] transition-all duration-500 hover:border-amber-400/50 hover:shadow-lg mb-8 group">
            <div class="relative w-full overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                <img src="{{ asset('images/banner-nogift.jpg') }}" alt="No Gift Policy วิทยาลัยเทคนิคตราด" class="w-full h-auto block transition-transform duration-700 group-hover:scale-[1.02]">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
            </div>
            <div class="mt-4 px-2 pb-2 text-left">
                <h3 class="font-extrabold text-lg text-slate-800 group-hover:text-red-600 transition-colors line-clamp-2 leading-snug">
                    นโยบาย No Gift Policy
                </h3>
                <p class="text-sm text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                    วิทยาลัยเทคนิคตราด งดรับ งดให้ ของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่
                </p>
            </div>
        </div>
    @else
        <div class="w-full max-w-6xl grid grid-cols-1 {{ $gridBanners->count() > 1 ? 'md:grid-cols-2' : '' }} gap-6 mb-8">
            @foreach($gridBanners as $banner)
                <div class="bg-white/70 backdrop-blur-md rounded-3xl p-3 border border-white shadow-[0_20px_50px_rgba(31,38,135,0.06)] transition-all duration-500 hover:border-amber-400/50 hover:shadow-lg hover:-translate-y-1 group">
                    <div class="relative w-full overflow-hidden rounded-2xl bg-slate-100 shadow-inner">
                        @if($banner->button_url)
                            <a href="{{ $banner->button_url }}" class="block w-full">
                                <img src="{{ $banner->getFirstMediaUrl('banner_image') }}" alt="{{ $banner->title }}" class="w-full h-auto block transition-transform duration-700 group-hover:scale-[1.02]">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                    <span class="text-white text-xs font-semibold px-3 py-1.5 bg-red-600/90 backdrop-blur-md rounded-lg shadow-md">คลิกลิงก์เพื่ออ่านต่อ</span>
                                </div>
                            </a>
                        @else
                            <img src="{{ $banner->getFirstMediaUrl('banner_image') }}" alt="{{ $banner->title }}" class="w-full h-auto block transition-transform duration-700 group-hover:scale-[1.02]">
                        @endif
                    </div>

                    @if($banner->title || $banner->subtitle)
                        <div class="mt-4 px-2 pb-2 text-left">
                            @if($banner->title)
                                <h3 class="font-extrabold text-lg text-slate-800 group-hover:text-red-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $banner->title }}
                                </h3>
                            @endif
                            @if($banner->subtitle)
                                <p class="text-sm text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Menu Links Section -->
    <div class="w-full max-w-6xl grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 px-2 mb-12">
        <!-- Main Web Entry -->
        <a wire:navigate href="/app" class="sm:col-span-2 md:col-span-3 flex items-center justify-between p-5 bg-gradient-to-r from-red-600 via-red-500 to-amber-500 hover:from-red-700 hover:to-amber-600 text-white rounded-2xl shadow-lg shadow-red-500/10 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center border border-white/30 backdrop-blur-md">
                    <i class="fas fa-globe text-xl text-white animate-pulse"></i>
                </div>
                <div class="flex flex-col text-left">
                    <span class="font-black text-base tracking-wide">เข้าสู่เว็บไซต์หลัก วท.ตราด</span>
                    <span class="text-[11px] text-white/90 font-medium">เข้าชมข้อมูลข่าวสารและบริการของวิทยาลัยหลัก</span>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                <i class="fas fa-chevron-right text-xs"></i>
            </div>
        </a>

        <!-- Sub Menu 1: ITA -->
        <a href="http://www.trattc.ac.th:3008/" target="_blank" class="flex items-center gap-4 p-4 bg-white/80 backdrop-blur-md border border-slate-200/50 rounded-2xl hover:bg-white hover:border-red-500/30 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center border border-blue-100 text-blue-500 group-hover:bg-blue-100 transition-colors">
                <i class="fas fa-shield-alt text-base"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-[13px] text-slate-700 group-hover:text-red-600 transition-colors">ประเมิน ITA</span>
                <span class="text-[10px] text-slate-400">ประเมินคุณธรรมและความโปร่งใส</span>
            </div>
        </a>

        <!-- Sub Menu 2: V-COP -->
        <a href="https://v-cop.go.th/" target="_blank" class="flex items-center gap-4 p-4 bg-white/80 backdrop-blur-md border border-slate-200/50 rounded-2xl hover:bg-white hover:border-red-500/30 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center border border-purple-100 text-purple-500 group-hover:bg-purple-100 transition-colors">
                <i class="fas fa-network-wired text-base"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-[13px] text-slate-700 group-hover:text-red-600 transition-colors">ศูนย์ V-COP</span>
                <span class="text-[10px] text-slate-400">ศูนย์เครือข่ายกำลังคนอาชีวศึกษา</span>
            </div>
        </a>

        <!-- Sub Menu 3: Blessing -->
        <a href="http://www.trattc.ac.th:3006/products/add" target="_blank" class="flex items-center gap-4 p-4 bg-white/80 backdrop-blur-md border border-slate-200/50 rounded-2xl hover:bg-white hover:border-red-500/30 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center border border-amber-100 text-amber-600 group-hover:bg-amber-100 transition-colors">
                <i class="fas fa-pen-nib text-base"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-[13px] text-slate-700 group-hover:text-red-600 transition-colors">ลงนามถวายพระพร</span>
                <span class="text-[10px] text-slate-400">ลงนามร่วมถวายพระพรออนไลน์</span>
            </div>
        </a>

        <!-- Sub Menu 4: Oath -->
        <a href="http://www.trattc.ac.th:3009/products/add" target="_blank" class="sm:col-span-2 md:col-span-3 flex items-center gap-4 p-4 bg-white/80 backdrop-blur-md border border-slate-200/50 rounded-2xl hover:bg-white hover:border-red-500/30 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_15px_30px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                <i class="fas fa-scroll text-base"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-[13px] text-slate-700 group-hover:text-red-600 transition-colors">ขอถวายสัตย์ปฏิญาณ</span>
                <span class="text-[10px] text-slate-400">ขอถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดีและพลังของแผ่นดิน</span>
            </div>
        </a>
    </div>

    <!-- Footer Signature -->
    <div class="text-center text-[11px] text-slate-500 tracking-wider">
        &copy; {{ date('Y') }} วิทยาลัยเทคนิคตราด. All Rights Reserved.
    </div>

    <!-- Floating Contact Button -->
    <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50 flex flex-col items-center gap-3.5">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="flex flex-col items-center gap-3"
             style="display: none;">

            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        โทรศัพท์ติดต่อ
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="tel:039511141" class="w-12 h-12 bg-[#22c55e] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-phone text-base"></i>
                </a>
            </div>

            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        Line Official
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="#" target="_blank" class="w-12 h-12 bg-[#06c755] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fab fa-line text-2xl"></i>
                </a>
            </div>

            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        Facebook Fanpage
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="https://www.facebook.com/trattech.ac.th" target="_blank" class="w-12 h-12 bg-[#1877f2] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fab fa-facebook-f text-base"></i>
                </a>
            </div>

            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        ส่งอีเมลสอบถาม
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="mailto:info@trattc.ac.th" class="w-12 h-12 bg-[#ea4335] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-envelope text-base"></i>
                </a>
            </div>

            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        ส่งข้อความแชท
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="#" class="w-12 h-12 bg-[#2563eb] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-comment-alt text-base"></i>
                </a>
            </div>
        </div>

        <div class="flex items-center relative group">
            <div x-show="!open" class="absolute right-16 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                <div class="bg-slate-900 text-white text-[13px] font-medium px-4 py-2 rounded-xl shadow-lg tracking-wide whitespace-nowrap relative">
                    ติดต่อเรา
                    <div class="absolute top-1/2 -right-1.5 -translate-y-1/2 w-3 h-3 bg-slate-900 rotate-45 rounded-sm"></div>
                </div>
            </div>

            <button @click="open = !open"
                    :class="open ? 'bg-[#ef4444] rotate-135 shadow-[0_0_15px_rgba(239,68,68,0.5)]' : 'bg-[#22c55e] shadow-[0_0_15px_rgba(34,197,94,0.4)]'"
                    class="w-14 h-14 text-white rounded-full flex items-center justify-center shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 relative z-10">
                <span x-show="!open" class="absolute inset-0 rounded-full bg-green-500/30 animate-ping opacity-75 pointer-events-none"></span>
                <template x-if="!open">
                    <i class="fas fa-headset text-2xl"></i>
                </template>
                <template x-if="open">
                    <i class="fas fa-times text-xl"></i>
                </template>
            </button>
        </div>
    </div>
</div>
