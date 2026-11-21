<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Post;
use App\Models\Category;

new #[Title('วิทยาลัยเทคนิคตราด - หน้าแรก')] class extends Component
{
    #[Computed]
    public function categories()
    {
        return Category::active()
            ->withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->get();
    }

    #[Computed]
    public function categoriesCount()
    {
        return Category::active()->count();
    }

    #[Computed]
    public function postsCount()
    {
        return Post::published()->count();
    }

    #[Computed]
    public function tagsCount()
    {
        return \Spatie\Tags\Tag::count();
    }

    #[Computed]
    public function registrantsCount()
    {
        return 70;
    }

    /**
     * ใช้ #[Computed] เพื่อทำ Cache ข้อมูลใน Request เดียวกัน
     * และสามารถเรียกใช้ใน Blade ผ่าน $this->posts
     */
    #[Computed]
    public function posts()
    {
        return Post::with('categories')
            ->post() // ดึงเฉพาะที่เป็น Post: เรียกว่า Local Scope (เป็นฟังก์ชันที่นักพัฒนาเขียนดักไว้ในไฟล์ Post.php มักจะเขียนว่า scopePost($query))
            ->published()
            ->orderBy('published_at', 'desc') // เพิ่มเพื่อให้โพสต์ใหม่ขึ้นก่อน
            ->paginate(3);
    }
};
?>

<div class="w-full pt-32 pb-24 bg-gradient-to-br from-[#0a0f24] via-[#101438] to-[#1e1b4b] text-center relative overflow-hidden">
    {{-- Cyberpunk/Neon Glowing Shapes --}}
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-cyan-400 via-purple-600 to-transparent"></div>
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-blue-500/25 rounded-full blur-3xl"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>

    <div class="max-w-5xl mx-auto px-4 relative z-10">
        <!-- Badge -->
        <div class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white/10 text-white text-sm backdrop-blur-md border border-white/20 mb-6 font-medium transition-all hover:bg-white/15">
            <x-heroicon-s-sparkles class="w-4 h-4 text-white animate-pulse" />
            <span class="font-medium">สถานศึกษา</span>
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight mb-4 drop-shadow-md">
            ลงทะเบียนเข้าร่วมกิจกรรมและโครงการ
        </h1>

        <!-- Subtitle -->
        <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-8 font-light leading-relaxed">
            ค้นหา - สมัคร - ติดตามสถานะ ได้ทุกที่ทุกเวลา
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/admin/login" class="inline-flex items-center gap-2 px-8 py-3 bg-white hover:bg-gray-100 text-[#1e40af] font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                <x-heroicon-o-arrow-right-end-on-rectangle class="w-5 h-5 text-[#1e40af]" />
                <span>เข้าสู่ระบบ</span>
            </a>
            <a href="#latest-posts" class="inline-flex items-center gap-2 px-8 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl border border-white/30 backdrop-blur-sm hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 text-white" />
                <span>ดูกิจกรรม</span>
            </a>
        </div>
    </div>
</div>

{{-- Overlapping Stats/Category Cards Container --}}
<div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-16 relative z-20 mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-6xl mx-auto">
        <!-- Card 1 -->
        <div class="flex flex-col items-center justify-center py-8 px-6 bg-white dark:bg-slate-800 rounded-2xl shadow-xl hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group">
            <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform duration-200 mb-2">
                {{ $this->categoriesCount }}
            </span>
            <span class="text-sm md:text-base font-bold text-gray-700 dark:text-gray-300 text-center uppercase tracking-wider">
                เปิดรับสมัคร
            </span>
        </div>

        <!-- Card 2 -->
        <div class="flex flex-col items-center justify-center py-8 px-6 bg-white dark:bg-slate-800 rounded-2xl shadow-xl hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group">
            <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform duration-200 mb-2">
                {{ $this->postsCount }}
            </span>
            <span class="text-sm md:text-base font-bold text-gray-700 dark:text-gray-300 text-center uppercase tracking-wider">
                กิจกรรมทั้งหมด
            </span>
        </div>

        <!-- Card 3 -->
        <div class="flex flex-col items-center justify-center py-8 px-6 bg-white dark:bg-slate-800 rounded-2xl shadow-xl hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group">
            <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform duration-200 mb-2">
                {{ $this->tagsCount }}
            </span>
            <span class="text-sm md:text-base font-bold text-gray-700 dark:text-gray-300 text-center uppercase tracking-wider">
                โครงการ
            </span>
        </div>

        <!-- Card 4 -->
        <div class="flex flex-col items-center justify-center py-8 px-6 bg-white dark:bg-slate-800 rounded-2xl shadow-xl hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group">
            <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform duration-200 mb-2">
                {{ $this->registrantsCount }}
            </span>
            <span class="text-sm md:text-base font-bold text-gray-700 dark:text-gray-300 text-center uppercase tracking-wider">
                ผู้ลงทะเบียน
            </span>
        </div>
    </div>
</div>

<div class="mx-auto my-2 p-2">
    <div class="max-w-7xl mx-auto p-2 my-2 bg-white rounded-lg">
        {{-- Slider --}}
        <section id="latest-posts" class="max-w-7xl mx-auto p-2 my-2 bg-white shadow-2xl rounded-lg">
            <div x-data="{ activeSlide: 0, slides: 3, timer: null }"
                x-init="timer = setInterval(() => activeSlide = (activeSlide + 1) % slides, 5000)"
                class="max-w-8xl mx-auto relative group">
                <div class="text-center text-2xl text-teal-600 my-2">"ข่าวสารประชาสัมพันธ์ <span
                        class="text-amber-600">วิทยาลัยเทคนิคตราด</span>"</div>
                <div class="relative overflow-hidden rounded-lg shadow-xl">
                    <div class="flex transition-transform duration-500 ease-in-out md:h-125"
                        :style="`transform: translateX(-${activeSlide * 100}%)`">
                        <div
                            class="min-w-full bg-blue-500 flex items-center justify-center text-white text-4xl relative">
                            <img src="https://images.unsplash.com/photo-1680725779155-456faadefa26" alt="Random image">
                            <div class="absolute inset-10 flex items-end justify-center">
                                <h2 class="text-white text-base">1.ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี
                                    และพลังของแผ่นดิน
                                    เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม ๒๕๖๗</h2>
                            </div>
                        </div>

                        <div
                            class="min-w-full bg-green-500 flex items-center justify-center text-white text-4xl relative">
                            <img src="https://images.unsplash.com/photo-1759675795062-a657fcb278b1" alt="">
                            <div class="absolute inset-10 flex items-end justify-center">
                                <h2 class="text-white text-base">2.ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี
                                    และพลังของแผ่นดิน เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม
                                    ๒๕๖๗</h2>
                            </div>
                        </div>

                        <div
                            class="min-w-full bg-purple-500 flex items-center justify-center text-white text-4xl relative">
                            <img src="https://images.unsplash.com/photo-1760311174612-4cc057f93e2c" alt="">
                            <div class="absolute inset-10 flex items-end justify-center">
                                <h2 class="text-white text-base">3.ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี
                                    และพลังของแผ่นดิน เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม
                                    ๒๕๖๗</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <button @click="activeSlide = (activeSlide - 1 + slides) % slides; clearInterval(timer)"
                    class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/30 hover:bg-white/50 rounded-full p-2.5 transition-colors">
                    <x-heroicon-o-chevron-left class="mr-1 ml-1 h-6 w-5 text-white" />
                </button>

                <button @click="activeSlide = (activeSlide + 1) % slides; clearInterval(timer)"
                    class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/30 hover:bg-white/50 rounded-full p-2.5 transition-colors">
                    <x-heroicon-o-chevron-right class="mr-1 ml-1 h-6 w-5 text-white" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                    <template x-for="i in slides" :key="i">
                        <button @click="activeSlide = i - 1; clearInterval(timer)"
                            class="w-3 h-3 rounded-full transition-colors"
                            :class="activeSlide === i - 1 ? 'bg-white' : 'bg-white/50'">
                        </button>
                    </template>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto p-2 my-2 bg-white shadow-2xl rounded-lg">
            <div class="max-w-3xl">
                <h2 class="text-[#801810] xl:text-5xl md:text-5xl text-4xl font-bold leading-tight!">สาส์นจากผู้อำนวยการ
                </h2>
            </div>
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-12 mt-6">
                <div>
                    <p class="text-slate-600 md:text-md leading-relaxed mx-auto max-w-7xl">
                        ขอขอบคุณผู้ปกครองนักเรียน นักศึกษาที่ให้ความไว้วางใจวิทยาลัย
                        ที่ให้ความร่วมมืออย่างดีในกิจกรรมต่างๆ ของวิทยาลัยฯ
                        ขอขอบใจนักเรียนนักศึกษาทุกคนที่ปฏิบัติตนอยู่ในระเบียบของวิทยาลัย อยู่ในโอวาทของทุกคน
                        เป็นนักเรียนนักศึกษาที่ วิทยาลัยภาคภูมิใจ.
                    </p>

                    <div class="max-w-3xl mt-2">
                        <h2 class="xl:text-xl md:text-xl text-xl font-sm leading-tight! text-[#801810]">
                            "เทคนิคตราดโฉมใหม่ ใส่ใจบุตรหลาน จัดการศึกษา เพื่อการมีงานทำ"
                        </h2>
                    </div>

                    <div class="mt-12 flex gap-6 items-center flex-wrap">
                        <a href="{{ route('home') }}">
                            <button type='button'
                                class="bg-[#55F5A3] hover:bg-green-400 transition-all text-slate-900 font-semibold text-[15px] rounded-full px-6 py-3 cursor-pointer">
                                Getting Started
                            </button>
                        </a>
                        <a href="javascript:void(0)"
                            class="text-slate-900 text-[15px] font-semibold underline max-sm:block whitespace-nowrap">
                            API Documentation
                        </a>
                    </div>

                    <div class="mt-12 flex flex-wrap gap-x-2 gap-y-2">
                        <a href="https://rms.trattc.ac.th/?p=login" target="_blank">
                            <img src="images/rms.png"
                                class="shrink-0 w-48 h-32 shadow-2xl rounded-md p-2 hover:scale-[1.05] transition-all"
                                alt="brand-logo1" />
                        </a>

                        <a href="https://std2018.vec.go.th/web/" target="_blank">
                            <img src="images/std02.png"
                                class="w-48 h-32 shrink-0 shadow-2xl rounded-md p-2 hover:scale-[1.05] transition-all"
                                alt="brand-logo2" />
                        </a>

                        <a href="https://admission.vec.go.th/web/Login.htm?mode=index" target="_blank">
                            <img src="images/std_register.png"
                                class="w-48 h-32 shrink-0 shadow-2xl rounded-md p-2 hover:scale-[1.05] transition-all"
                                alt="brand-logo3" />
                        </a>
                    </div>
                </div>

                <div class="max-w-full">
                    <img src='images/child2568.jpg'
                        class="shrink-0 shadow-2xl rounded-md object-contain hover:scale-[1.01] transition-all" />
                </div>
            </div>
        </section>

        <!-- Featured Post -->
        <section class="max-w-7xl mx-auto p-2 bg-white rounded-lg">
            <h2 class="text-2xl font-bold text-amber-800 mb-8">โพสล่าสุด (Latest Posts) </h2>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex shadow-2xl rounded-md p-2">
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800" alt="Featured"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-4">
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full">Technology</span>
                            <span>•</span>
                            <span>March 15, 2024</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">The Future of Web Development</h3>
                        <p class="text-gray-600 mb-6">
                            อนาคตของการพัฒนาเว็บมุ่งเน้นไปที่การปรับแต่งเฉพาะบุคคลที่ขับเคลื่อนด้วย AI
                            ประสบการณ์เสมือนจริง
                            (AR/VR) การกระจายอำนาจ (Web3) และประสิทธิภาพที่เพิ่มขึ้น (WebAssembly, Edge Computing)
                            โดยเปลี่ยนไปสู่การออกแบบที่รวดเร็ว ปลอดภัย และเน้นผู้ใช้เป็นศูนย์กลางมากขึ้น
                            พร้อมทั้งให้ความสำคัญกับการเข้าถึงได้ง่าย แนวโน้มสำคัญ ได้แก่ แพลตฟอร์ม No-Code/Low-Code,
                            PWA ขั้นสูง, อินเทอร์เฟซเสียง (VUI) และเครื่องมือ AI ที่ช่วยเสริมศักยภาพนักพัฒนา
                            โดยให้ความสำคัญกับความปลอดภัยทางไซเบอร์ที่แข็งแกร่งและการออกแบบที่ครอบคลุมและเน้นอุปกรณ์พกพาเป็นหลัก
                            เพื่อให้เว็บมีความโต้ตอบ ชาญฉลาด และเข้าถึงได้ง่ายยิ่งขึ้น
                        </p>
                        <a href="#"
                            class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog -->
        @include('components.⚡bloglist-page', [
            'sectionTitle' => 'โพสล่าสุด',
            'posts' => $this->posts,
            'categories' => $this->categories, // ส่งข้อมูลหมวดหมู่ไปด้วยเพื่อแสดงใน sidebar
        ])
    </div>
</div>
