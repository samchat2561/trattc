<?php

use Livewire\Component;
use App\Models\Banner;
use Illuminate\Support\Collection;

new class extends Component
{
    public Collection $sliders;
    public Collection $grids;

    public function mount(): void
    {
        $now = now();

        // 1. ดึงข้อมูลแบนเนอร์ประเภท Slider (สำหรับทำสไลด์ด้านบนสุด)
        $this->sliders = $this->getBannersByType('slider', $now);

        // 2. ดึงข้อมูลแบนเนอร์ประเภท Grid (สำหรับทำกล่องเมนูโซนล่าง)
        $this->grids = $this->getBannersByType('grid', $now);
    }

    private function getBannersByType(string $type, $now): Collection
    {
        return Banner::query()
            ->where('banner_type', $type)
            ->where('is_active', true)
            ->orderBy('position', 'asc')
            ->get();
    }
};
?>

{{-- Hero Banner Slider Section (Hover to Show Arrows Version) --}}
{{-- [จุดสำคัญ 1] เพิ่มคลาส `group` ที่ตัวคลุมนอกสุด เพื่อให้ลูกศรด้านในรู้ว่าเมาส์กำลังชี้อยู่หรือไม่ --}}
<div x-data="{
        activeHero: 0,
        totalHero: {{ $sliders->count() }},
        heroTimer: null,
        mouseX: 0,
        mouseY: 0,
        isFlashing: false,
        triggerFlashEffect() {
            this.isFlashing = true;
            setTimeout(() => { this.isFlashing = false; }, 550);
        },
        createWaterRipple(event) {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            const size = Math.max(rect.width, rect.height);
            const baseDiameter = size * 0.22;

            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.width = baseDiameter + 'px';
            ripple.style.height = baseDiameter + 'px';
            ripple.style.left = (x - baseDiameter / 2) + 'px';
            ripple.style.top = (y - baseDiameter / 2) + 'px';
            container.appendChild(ripple);

            setTimeout(() => {
                const wave1 = document.createElement('span');
                wave1.className = 'ripple-wave';
                wave1.style.width = baseDiameter + 'px';
                wave1.style.height = baseDiameter + 'px';
                wave1.style.left = (x - baseDiameter / 2) + 'px';
                wave1.style.top = (y - baseDiameter / 2) + 'px';
                container.appendChild(wave1);
                setTimeout(() => wave1.remove(), 1400);
            }, 40);

            setTimeout(() => {
                const wave2 = document.createElement('span');
                wave2.className = 'ripple-wave-2';
                wave2.style.width = (baseDiameter * 0.9) + 'px';
                wave2.style.height = (baseDiameter * 0.9) + 'px';
                wave2.style.left = (x - baseDiameter * 0.45) + 'px';
                wave2.style.top = (y - baseDiameter * 0.45) + 'px';
                container.appendChild(wave2);
                setTimeout(() => wave2.remove(), 1800);
            }, 100);

            setTimeout(() => {
                const wave3 = document.createElement('span');
                wave3.className = 'ripple-wave-3';
                wave3.style.width = (baseDiameter * 0.8) + 'px';
                wave3.style.height = (baseDiameter * 0.8) + 'px';
                wave3.style.left = (x - baseDiameter * 0.4) + 'px';
                wave3.style.top = (y - baseDiameter * 0.4) + 'px';
                container.appendChild(wave3);
                setTimeout(() => wave3.remove(), 2200);

                const caustics = document.createElement('span');
                caustics.className = 'ripple-glow';
                caustics.style.width = (baseDiameter * 2) + 'px';
                caustics.style.height = (baseDiameter * 2) + 'px';
                caustics.style.left = (x - baseDiameter) + 'px';
                caustics.style.top = (y - baseDiameter) + 'px';
                container.appendChild(caustics);
                setTimeout(() => caustics.remove(), 1000);
            }, 180);

            const dropletCount = 6;
            for (let i = 0; i < dropletCount; i++) {
                setTimeout(() => {
                    const angle = (360 / dropletCount) * i * (Math.PI / 180);
                    const distance = baseDiameter * 0.35;
                    const particleX = Math.cos(angle) * distance;
                    const particleY = Math.sin(angle) * distance;

                    const droplet = document.createElement('span');
                    droplet.className = 'ripple-particle';
                    droplet.style.left = (x + particleX) + 'px';
                    droplet.style.top = (y + particleY) + 'px';
                    droplet.style.setProperty('--particle-x', (particleX * 2.5) + 'px');
                    droplet.style.setProperty('--particle-y', (particleY * 2.5) + 'px');
                    container.appendChild(droplet);
                    setTimeout(() => droplet.remove(), 1200);
                }, i * 30);
            }

            setTimeout(() => ripple.remove(), 1000);
        },
        startAutoRipple() {
            setInterval(() => {
                const activeItem = this.$el.querySelector('.slide-item.active-slide');
                if (!activeItem) return;
                const rect = activeItem.getBoundingClientRect();
                const x = rect.width * (0.2 + Math.random() * 0.6);
                const y = rect.height * (0.2 + Math.random() * 0.6);
                const size = Math.max(rect.width, rect.height);
                const baseDiameter = size * 0.18;

                const ripple = document.createElement('span');
                ripple.className = 'auto-ripple';
                ripple.style.width = baseDiameter + 'px';
                ripple.style.height = baseDiameter + 'px';
                ripple.style.left = (x - baseDiameter / 2) + 'px';
                ripple.style.top = (y - baseDiameter / 2) + 'px';
                activeItem.appendChild(ripple);

                const wave = document.createElement('span');
                wave.className = 'auto-ripple-wave';
                wave.style.width = baseDiameter + 'px';
                wave.style.height = baseDiameter + 'px';
                wave.style.left = (x - baseDiameter / 2) + 'px';
                wave.style.top = (y - baseDiameter / 2) + 'px';
                activeItem.appendChild(wave);

                setTimeout(() => {
                    ripple.remove();
                    wave.remove();
                }, 2800);
            }, 3500);
        },
        next() {
            if (this.totalHero <= 1) return;
            this.activeHero = (this.activeHero + 1) % this.totalHero;
        },
        prev() {
            if (this.totalHero <= 1) return;
            this.activeHero = (this.activeHero - 1 + this.totalHero) % this.totalHero;
        },
        resetTimer() {
            clearInterval(this.heroTimer);
            if (this.totalHero > 1) {
                this.heroTimer = setInterval(() => this.next(), 6000);
            }
        },
        handleMouseMove(e) {
            const rect = this.$el.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width - 0.5) * 30;
            const y = ((e.clientY - rect.top) / rect.height - 0.5) * 30;
            this.mouseX = x;
            this.mouseY = y;
        }
     }"
     x-init="totalHero = {{ $sliders->count() }}; resetTimer(); startAutoRipple();"
     @mousemove="handleMouseMove($event)"
     class="group relative w-full overflow-hidden bg-slate-100 dark:bg-slate-900 mt-32">

    {{-- ปุ่มถอยหลัง (ซ้าย) --}}
    @if($sliders->count() > 1)
        <button @click="prev(); resetTimer()"
                type="button"
                aria-label="สไลด์ก่อนหน้า"
                class="absolute left-3 md:left-5 top-1/2 -translate-y-1/2 z-40 w-10 h-10 md:w-12 md:h-12 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center shadow-lg cursor-pointer group/btn focus:outline-none opacity-75 hover:opacity-100 transition-all duration-300 backdrop-blur-xs">
            <svg class="w-5 h-5 md:w-6 md:h-6 transition-transform group-hover/btn:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
        </button>

        {{-- ปุ่มไปข้างหน้า (ขวา) --}}
        <button @click="next(); resetTimer()"
                type="button"
                aria-label="สไลด์ถัดไป"
                class="absolute right-3 md:right-5 top-1/2 -translate-y-1/2 z-40 w-10 h-10 md:w-12 md:h-12 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center shadow-lg cursor-pointer group/btn focus:outline-none opacity-75 hover:opacity-100 transition-all duration-300 backdrop-blur-xs">
            <svg class="w-5 h-5 md:w-6 md:h-6 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
    @endif

    {{-- Slide Indicators (Clean Dots matching uploaded layout) --}}
    @if($sliders->count() > 1)
        <div class="absolute bottom-3 md:bottom-4 left-1/2 -translate-x-1/2 z-40 flex items-center gap-2.5">
            @foreach($sliders as $slide)
                <button @click.stop="activeHero = {{ $loop->index }}; resetTimer()"
                        type="button"
                        :class="activeHero === {{ $loop->index }} ? 'w-3 h-3 bg-[#7a1a40] scale-110 shadow-sm ring-2 ring-white/60' : 'w-2.5 h-2.5 bg-slate-300/80 hover:bg-slate-400 dark:bg-slate-600'"
                        class="rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                        title="สไลด์ที่ {{ $loop->iteration }}"
                        aria-label="ไปยังสไลด์ที่ {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>
    @endif

    {{-- Slides Container (Aspect ratio 2.73:1 matching 2112x774 uploaded banners, full display) --}}
    <div class="relative w-full aspect-[16/10] sm:aspect-[21/9] lg:aspect-[2.73/1]">

        @forelse($sliders as $slide)
            <div @click="createWaterRipple($event); next(); resetTimer()"
                 class="slide-item ripple-container absolute inset-0 w-full h-full cursor-pointer overflow-hidden flex items-center bg-transparent transition-opacity duration-700 ease-in-out {{ $loop->first ? 'active-slide opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none' }}"
                 :class="activeHero === {{ $loop->index }} ? 'active-slide opacity-100 z-10 pointer-events-auto' : 'opacity-0 z-0 pointer-events-none'">
                {{-- Background Image layer (Clean & Bright, No Dark Tint) --}}
                <div class="absolute inset-0 z-0 pointer-events-none">
                    <img src="{{ $slide->image_url ?: $slide->getFirstMediaUrl('banner_image') }}"
                         alt="{{ $slide->title }}"                         class="w-full h-full object-cover object-center"
                         loading="eager">
                </div>

                {{-- Action Button (if specified in CMS) --}}
                @if($slide->button_text && $slide->button_url)
                    <div class="absolute bottom-16 md:bottom-22 left-6 md:left-14 z-30 slide-content">
                        <div class="slide-buttons">
                            <a href="{{ $slide->button_url }}" @click.stop class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-sm font-bold rounded-xl shadow-2xl transition-all duration-200 cursor-pointer hover:scale-105 border border-amber-300">
                                <span>{{ $slide->button_text }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="min-w-full relative w-full aspect-[21/9] flex items-center justify-center bg-[#07162c] text-white/50">
                <p>ยังไม่มีข้อมูลแบนเนอร์ประชาสัมพันธ์</p>
            </div>
        @endforelse

    </div>
</div>
