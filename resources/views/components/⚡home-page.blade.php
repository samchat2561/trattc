<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use App\Models\Image;
use Livewire\WithPagination;
use Illuminate\Support\Str;

new #[Title('วิทยาลัยเทคนิคตราด - หน้าแรก')] class extends Component {
    use WithPagination;

    public $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

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
                'posts' => Post::post()
                    ->published()
                    ->where(function($query) {
                        $query->where('title', 'like', '%' . $this->search . '%')
                              ->orWhere('content', 'like', '%' . $this->search . '%')
                              ->orWhere('excerpt', 'like', '%' . $this->search . '%');
                    })
                    ->latest()
                    ->take(5)
                    ->get(),

                'categories' => Category::active()
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->latest()
                    ->take(3)
                    ->get(),
            ];
        } catch (\Exception $e) {
            return ['posts' => collect(), 'categories' => collect()];
        }
    }

    #[Computed]
    public function categories()
    {
        $categories = Category::all();
        // dump($categories);
        return $categories;
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

    #[Computed]
    public function featuredPost()
    {
        return Post::with('categories')
            ->post()
            ->published()
            ->orderBy('published_at', 'desc')
            ->first();
    }

    #[Computed]
    public function breakingNews()
    {
        return Post::with(['categories', 'author'])
            ->post()
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->take(6)
            ->get();
    }

    /**
     * ใช้ #[Computed] เพื่อทำ Cache ข้อมูลใน Request เดียวกัน
     * และสามารถเรียกใช้ใน Blade ผ่าน $this->posts
     */
    #[Computed]
    public function posts()
    {
        $query = Post::with('categories')
            ->post()
            ->published()
            ->orderBy('published_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                    ->orWhereHas('categories', function ($catQuery) {
                        $catQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query->paginate(3);
    }

    #[Computed]
    public function dashboardImages()
    {
        return Image::with('media')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(3, ['*'], 'imagesPage');
    }

    #[Computed]
    public function teacherCongratulations()
    {
        return \App\Models\TeacherCongratulation::active()->get();
    }

    #[Renderless]
    public function incrementCongratulationView($id): void
    {
        \App\Models\TeacherCongratulation::where('id', $id)->increment('views_count');
    }

    #[Computed]
    public function achievements()
    {
        return \App\Models\Achievement::active()->get();
    }

    #[Renderless]
    public function incrementAchievementView($id): void
    {
        \App\Models\Achievement::where('id', $id)->increment('views_count');
    }

    #[Computed]
    public function procurements()
    {
        return \App\Models\Procurement::active()->get();
    }

    #[Renderless]
    public function incrementProcurementView($id): void
    {
        \App\Models\Procurement::where('id', $id)->increment('views_count');
    }

    #[Computed]
    public function newsletters()
    {
        return \App\Models\Newsletter::active()->get();
    }

    #[Renderless]
    public function incrementNewsletterView($id): void
    {
        \App\Models\Newsletter::where('id', $id)->increment('views_count');
    }

    #[Computed]
    public function collegeStatistics()
    {
        return \App\Models\CollegeStatistic::active()->get();
    }
};
?>

<div class="w-full">
    {{-- Hero Banner Slider Section --}}
    <livewire:homepage-banners />

    {{-- Quick-Link 5 Cards Container (Design matching the uploaded sample) --}}
    <div class="max-w-7xl mx-auto px-4 mt-6 md:mt-8 relative z-20 mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4 max-w-7xl mx-auto">
            <!-- Card 1: หลักสูตรที่เปิดสอน -->
            <a href="https://l1nq.com/0tbt2hy" target="_blank"
                class="flex items-center justify-between gap-3 p-3.5 sm:p-4 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-xs hover:shadow-md hover:bg-rose-50/30 dark:hover:bg-slate-700/60 hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#7a1a40] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-graduation-cap text-base"></i>
                    </div>
                    <span class="text-[13.5px] sm:text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] transition-colors truncate">
                        หลักสูตรที่เปิดสอน
                    </span>
                </div>
                <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-[#7a1a40] group-hover:translate-x-0.5 transition-all shrink-0"></i>
            </a>

            <!-- Card 2: ข่าวประชาสัมพันธ์ -->
            <a href="#latest-news-section"
                class="flex items-center justify-between gap-3 p-3.5 sm:p-4 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-xs hover:shadow-md hover:bg-rose-50/30 dark:hover:bg-slate-700/60 hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#7a1a40] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-file-alt text-base"></i>
                    </div>
                    <span class="text-[13.5px] sm:text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] transition-colors truncate">
                        ข่าวประชาสัมพันธ์
                    </span>
                </div>
                <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-[#7a1a40] group-hover:translate-x-0.5 transition-all shrink-0"></i>
            </a>

            <!-- Card 3: กิจกรรม -->
            <a href="#event-gallery"
                class="flex items-center justify-between gap-3 p-3.5 sm:p-4 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-xs hover:shadow-md hover:bg-rose-50/30 dark:hover:bg-slate-700/60 hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#7a1a40] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-bullhorn text-base"></i>
                    </div>
                    <span class="text-[13.5px] sm:text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] transition-colors truncate">
                        กิจกรรม
                    </span>
                </div>
                <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-[#7a1a40] group-hover:translate-x-0.5 transition-all shrink-0"></i>
            </a>

            <!-- Card 4: สำหรับนักเรียน-นักศึกษา -->
            <a href="https://std2018.vec.go.th/web/" target="_blank"
                class="flex items-center justify-between gap-3 p-3.5 sm:p-4 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-xs hover:shadow-md hover:bg-rose-50/30 dark:hover:bg-slate-700/60 hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#7a1a40] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-user-graduate text-base"></i>
                    </div>
                    <span class="text-[13.5px] sm:text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] transition-colors truncate">
                        สำหรับนักเรียน-นักศึกษา
                    </span>
                </div>
                <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-[#7a1a40] group-hover:translate-x-0.5 transition-all shrink-0"></i>
            </a>

            <!-- Card 5: บริการออนไลน์ -->
            <a href="https://rms.trattc.ac.th/?p=login" target="_blank"
                class="flex items-center justify-between gap-3 p-3.5 sm:p-4 bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-xs hover:shadow-md hover:bg-rose-50/30 dark:hover:bg-slate-700/60 hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-[#7a1a40] flex items-center justify-center text-white shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                        <i class="fas fa-book-open text-base"></i>
                    </div>
                    <span class="text-[13.5px] sm:text-sm font-bold text-slate-800 dark:text-slate-100 group-hover:text-[#7a1a40] transition-colors truncate">
                        บริการออนไลน์
                    </span>
                </div>
                <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-[#7a1a40] group-hover:translate-x-0.5 transition-all shrink-0"></i>
            </a>
        </div>
    </div>

    {{-- Section: นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ (No Gift Policy Banner matching reference) --}}
    <div x-data="{ noGiftModalOpen: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 mt-1 relative z-20">
        <!-- Banner Card -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-800 rounded-3xl p-5 sm:p-6 border border-amber-200/70 dark:border-slate-700/80 shadow-[0_4px_25px_rgba(245,158,11,0.08)] dark:shadow-[0_4px_25px_rgba(0,0,0,0.3)] flex flex-col md:flex-row md:items-center justify-between gap-5 group transition-all duration-300 hover:shadow-[0_8px_30px_rgba(245,158,11,0.12)]">

            <!-- Left Vertical Accent Gradient Bar -->
            <div class="absolute left-0 top-3 bottom-3 w-2 rounded-r-full bg-gradient-to-b from-amber-400 via-orange-500 to-rose-600"></div>

            <!-- Left Content: Icon + Texts -->
            <div class="flex items-center gap-4 sm:gap-5 pl-2 sm:pl-3">
                <!-- Gift Icon Box -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-[#FEF3E8] dark:bg-amber-950/60 border border-amber-200/70 dark:border-amber-800/50 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-gift text-2xl sm:text-3xl text-amber-600 dark:text-amber-400"></i>
                </div>

                <!-- Text Details -->
                <div>
                    <span class="text-[11px] sm:text-xs font-black tracking-wider text-amber-600 dark:text-amber-400 uppercase block mb-1">
                        NO GIFT POLICY
                    </span>
                    <h2 class="text-base sm:text-lg lg:text-xl font-black text-slate-800 dark:text-white leading-snug">
                        นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        ประกาศวิทยาลัยเทคนิคตราด เรื่อง นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ เพื่อส่งเสริมวัฒนธรรมองค์กรที่โปร่งใส สุจริต และตรวจสอบได้
                    </p>
                </div>
            </div>

            <!-- Right Action Button -->
            <div class="shrink-0 pl-2 md:pl-0">
                <button type="button"
                        @click="noGiftModalOpen = true"
                        class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-full bg-[#0095da] hover:bg-[#0084c2] active:bg-[#0073a8] text-white text-xs sm:text-sm font-bold shadow-[0_4px_15px_rgba(0,149,218,0.35)] hover:shadow-[0_6px_20px_rgba(0,149,218,0.45)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 cursor-pointer whitespace-nowrap">
                    <span>ดูตัวอย่างประกาศ</span>
                    <i class="fas fa-file-pdf text-xs sm:text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Official Announcement Modal -->
        <div x-show="noGiftModalOpen"
             x-cloak
             @keydown.escape.window="noGiftModalOpen = false"
             class="fixed inset-0 z-[9999] overflow-y-auto"
             style="display: none;">

            <!-- Backdrop -->
            <div x-show="noGiftModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="noGiftModalOpen = false"
                 class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity"></div>

            <!-- Centering Wrapper with vertical scrolling -->
            <div class="min-h-full flex items-center justify-center p-4 sm:p-6 text-center">
                <!-- Modal Card Container -->
                <div x-show="noGiftModalOpen"
                     @click.stop
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                     class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200 dark:border-slate-700 flex flex-col text-left my-8">

                    <!-- Modal Top Header -->
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white">
                                <i class="fas fa-gift text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-sm sm:text-base leading-tight">
                                    ตัวอย่างประกาศนโยบาย No Gift Policy
                                </h3>
                                <p class="text-[11px] text-amber-100">
                                    วิทยาลัยเทคนิคตราด สังกัดสำนักงานคณะกรรมการการอาชีวศึกษา
                                </p>
                            </div>
                        </div>
                        <button @click="noGiftModalOpen = false"
                                type="button"
                                class="p-2 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors cursor-pointer">
                            <i class="fas fa-times text-base"></i>
                        </button>
                    </div>

                    <!-- Modal Body: Document Preview -->
                    <div class="p-6 sm:p-8 space-y-6 bg-slate-50/50 dark:bg-slate-900/60">
                        <!-- Document Paper Style -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-xs text-center">

                            <!-- Emblem -->
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('images/trat.png') }}" alt="ตราวิทยาลัยเทคนิคตราด" class="w-20 h-20 sm:w-24 sm:h-24 object-contain filter drop-shadow-xs">
                            </div>

                            <!-- Formal Header -->
                            <h4 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                                ประกาศวิทยาลัยเทคนิคตราด
                            </h4>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1">
                                เรื่อง นโยบายไม่รับของขวัญและของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ (No Gift Policy)
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                ประจำปีงบประมาณ พ.ศ. ๒๕๖๘
                            </p>

                            <!-- Divider line -->
                            <div class="w-24 h-0.5 bg-amber-400 mx-auto my-5"></div>

                            <!-- Policy Text -->
                            <div class="text-left text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3.5">
                                <p class="indent-8 text-justify">
                                    ตามที่ วิทยาลัยเทคนิคตราด มุ่งเน้นที่จะบริหารจัดการองค์กรด้วยความซื่อสัตย์ สุจริต โปร่งใส มีคุณธรรม และตรวจสอบได้ตามหลักธรรมาภิบาล เพื่อเสริมสร้างวัฒนธรรมองค์กรและค่านิยมสุจริตในการปฏิบัติงานอย่างมีประสิทธิภาพ ป้องกันผลประโยชน์ทับซ้อนและการทุจริตประพฤติมิชอบ
                                </p>
                                <p class="indent-8 text-justify">
                                    วิทยาลัยเทคนิคตราด จึงขอประกาศเจตนารมณ์ว่า ผู้บริหาร ข้าราชการครู บุคลากรทางการศึกษา และเจ้าหน้าที่ทุกคน <strong>"งดรับและงดให้ของขวัญ รวมถึงของกำนัลทุกชนิดจากการปฏิบัติหน้าที่ (No Gift Policy)"</strong> ทั้งในก่อน ขณะ และหลังการปฏิบัติหน้าที่ อันจะส่งผลให้เกิดการทุจริตและประพฤติมิชอบ
                                </p>
                            </div>

                            <!-- 3 Policy Highlights -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mt-6 text-left">
                                <div class="p-3.5 bg-amber-50/80 dark:bg-amber-950/40 rounded-xl border border-amber-200/60 dark:border-amber-800/40">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-sm mb-2">
                                        <i class="fas fa-ban"></i>
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800 dark:text-white">๑. งดรับ</h5>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-normal">
                                        งดรับของขวัญ ของกำนัล หรือผลประโยชน์อื่นใดจากการปฏิบัติงานทุกกรณี
                                    </p>
                                </div>

                                <div class="p-3.5 bg-rose-50/80 dark:bg-rose-950/40 rounded-xl border border-rose-200/60 dark:border-rose-800/40">
                                    <div class="w-8 h-8 rounded-lg bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black text-sm mb-2">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800 dark:text-white">๒. งดให้</h5>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-normal">
                                        งดให้ของขวัญ ของกำนัล แก่ผู้บังคับบัญชาหรือบุคคลภายนอกในทุกเทศกาล
                                    </p>
                                </div>

                                <div class="p-3.5 bg-emerald-50/80 dark:bg-emerald-950/40 rounded-xl border border-emerald-200/60 dark:border-emerald-800/40">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-sm mb-2">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800 dark:text-white">๓. อวยพรเชิงสัญลักษณ์</h5>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-normal">
                                        แสดงความปรารถนาดีผ่านบัตรอวยพร สื่อออนไลน์ หรือการลงนามคำอวยพร
                                    </p>
                                </div>
                            </div>

                            <!-- Sign-off Block -->
                            <div class="mt-8 text-center text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                <p>ประกาศ ณ วิทยาลัยเทคนิคตราด</p>
                                <div class="mt-4 font-semibold">
                                    <p>(นายจิรพงค์ ร่มเงิน)</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">ผู้อำนวยการวิทยาลัยเทคนิคตราด</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal Bottom Bar (Actions) -->
                    <div class="px-5 sm:px-6 py-3.5 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3 shrink-0">
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i class="fas fa-shield-alt text-amber-500"></i>
                            <span>นโยบายความโปร่งใสและธรรมาภิบาล</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <button type="button"
                                    onclick="window.print()"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold transition-colors cursor-pointer">
                                <i class="fas fa-print"></i>
                                <span>พิมพ์ประกาศ</span>
                            </button>
                            <button type="button"
                                    @click="noGiftModalOpen = false"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0095da] hover:bg-[#0084c2] text-white text-xs font-bold transition-colors cursor-pointer shadow-sm">
                                <span>ปิดหน้าต่าง</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Section: ข้อมูลสถานศึกษา วิทยาลัยเทคนิคตราด (College Statistics with Interactive Charts) --}}
    @php
        $statistics = $this->collegeStatistics;
        $statsItems = $statistics ? $statistics->map(function ($stat) {
            $items = is_array($stat->items) ? $stat->items : [];
            $total = (int) $stat->total_count;
            if ($total <= 0 && count($items) > 0) {
                $total = collect($items)->sum(fn ($i) => (int) ($i['value'] ?? 0));
            }
            return [
                'id' => $stat->id,
                'code' => $stat->code,
                'title' => $stat->title,
                'subtitle' => $stat->subtitle ?? '',
                'icon' => $stat->icon ?: 'fas fa-chart-pie',
                'color' => $stat->color ?: 'maroon',
                'chart_type' => $stat->chart_type ?: 'doughnut',
                'total_count' => $total,
                'unit' => $stat->unit ?: 'คน',
                'description' => $stat->description ?? '',
                'items' => $items,
            ];
        })->values()->all() : [];
    @endphp

    @if(count($statsItems) > 0)
    <div id="college-statistics-section"
         wire:key="college-statistics-container"
         x-data="{
             stats: @js($statsItems),
             selectedStat: null,
             statModalOpen: false,
             activeTab: 'chart',
             openModal(stat) {
                 this.selectedStat = stat;
                 this.statModalOpen = true;
                 this.activeTab = 'chart';
                 setTimeout(() => {
                     this.renderChart();
                 }, 60);
             },
             closeModal() {
                 this.statModalOpen = false;
                 if (window._statChart) {
                     try {
                         window._statChart.destroy();
                     } catch (e) {}
                     window._statChart = null;
                 }
             },
             getChartHeight() {
                 if (!this.selectedStat) return '340px';
                 const items = this.selectedStat.items || [];
                 const isHorizontal = (this.selectedStat.chart_type === 'horizontal-bar');
                 if (isHorizontal) {
                     return Math.max(340, items.length * 32) + 'px';
                 }
                 return (items.length > 8 && this.selectedStat.chart_type === 'bar') ? '380px' : '340px';
             },
             renderChart() {
                 if (!this.selectedStat) return;

                 if (typeof window.Chart === 'undefined') {
                     const script = document.createElement('script');
                     script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                     script.onload = () => {
                         this.renderChart();
                     };
                     document.head.appendChild(script);
                     return;
                 }

                 try {
                     const wrapper = document.getElementById('statisticChartCanvasWrapper');
                     if (!wrapper) return;

                     // Safely destroy existing chart
                     if (window._statChart) {
                         try {
                             window._statChart.destroy();
                         } catch (e) {}
                         window._statChart = null;
                     }

                     // Recreate canvas to completely avoid 'Canvas is already in use' error
                     wrapper.innerHTML = '';
                     const canvas = document.createElement('canvas');
                     canvas.id = 'statisticChartCanvas';
                     wrapper.appendChild(canvas);

                     const items = this.selectedStat.items || [];
                     const labels = items.map(i => i.label || '');
                     const data = items.map(i => Number(i.value || 0));
                     const defaultPalette = [
                         '#7a1a40', '#2563eb', '#10b981', '#f59e0b', '#8b5cf6',
                         '#ec4899', '#06b6d4', '#ea580c', '#64748b', '#14b8a6',
                         '#6366f1', '#db2777', '#0284c7', '#d97706', '#84cc16'
                     ];
                     const colors = items.map((i, idx) => i.color || defaultPalette[idx % defaultPalette.length]);

                     let rawType = this.selectedStat.chart_type || 'doughnut';
                     let chartType = rawType;
                     let isHorizontal = false;
                     if (rawType === 'horizontal-bar') {
                         chartType = 'bar';
                         isHorizontal = true;
                     }

                     const isDark = document.documentElement.classList.contains('dark');
                     const textColor = isDark ? '#e2e8f0' : '#334155';
                     const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';

                     const isCircle = (chartType === 'doughnut' || chartType === 'pie' || chartType === 'polarArea');

                     const config = {
                         type: chartType,
                         data: {
                             labels: labels,
                             datasets: [{
                                 label: this.selectedStat.title,
                                 data: data,
                                 backgroundColor: colors,
                                 borderColor: isCircle ? (isDark ? '#0f172a' : '#ffffff') : colors,
                                 borderWidth: isCircle ? 2 : 1,
                                 borderRadius: (chartType === 'bar') ? 6 : 0,
                             }]
                         },
                         options: {
                             responsive: true,
                             maintainAspectRatio: false,
                             indexAxis: isHorizontal ? 'y' : 'x',
                             plugins: {
                                 legend: {
                                     display: isCircle,
                                     position: 'bottom',
                                     labels: {
                                         color: textColor,
                                         padding: 14,
                                         font: {
                                             family: 'Sarabun, sans-serif',
                                             size: 13,
                                             weight: '500'
                                         }
                                     }
                                 },
                                 tooltip: {
                                     backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                     titleColor: isDark ? '#f8fafc' : '#0f172a',
                                     bodyColor: isDark ? '#cbd5e1' : '#334155',
                                     borderColor: isDark ? '#334155' : '#e2e8f0',
                                     borderWidth: 1,
                                     padding: 12,
                                     boxPadding: 6,
                                     usePointStyle: true,
                                     titleFont: { family: 'Sarabun, sans-serif', size: 13, weight: 'bold' },
                                     bodyFont: { family: 'Sarabun, sans-serif', size: 12 },
                                     callbacks: {
                                         label: (context) => {
                                             const val = context.raw || 0;
                                             const total = data.reduce((a, b) => a + b, 0);
                                             const pct = total > 0 ? ((val / total) * 100).toFixed(1) + '%' : '';
                                             return ` ${context.label}: ${val.toLocaleString()} ${this.selectedStat.unit || 'คน'} (${pct})`;
                                         }
                                     }
                                 }
                             },
                             scales: (!isCircle) ? {
                                 x: {
                                     ticks: {
                                         color: textColor,
                                         font: { family: 'Sarabun, sans-serif', size: 12 },
                                         autoSkip: !isHorizontal
                                     },
                                     grid: { color: gridColor },
                                     beginAtZero: true
                                 },
                                 y: {
                                     ticks: {
                                         color: textColor,
                                         font: { family: 'Sarabun, sans-serif', size: (isHorizontal && items.length > 10) ? 11 : 12 },
                                         autoSkip: false
                                     },
                                     grid: { color: gridColor },
                                     beginAtZero: true
                                 }
                             } : {}
                         }
                     };

                     window._statChart = new Chart(canvas, config);
                 } catch (err) {
                     console.error('Error rendering statistic chart:', err);
                 }
             },
             calculatePercentage(value, total) {
                 if (!total || total <= 0) return 0;
                 return ((value / total) * 100).toFixed(1);
             }
         }"
         @keydown.escape.window="closeModal()"
         class="mx-auto my-4 p-2 max-w-7xl relative">

        {{-- White Background Box matching Teacher Congratulations & Achievements --}}
        <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">

            <!-- Header matching the solid maroon bar of Latest News, Congratulations & Achievements -->
            <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <i class="fas fa-chart-pie text-amber-300 text-base shrink-0"></i>
                    <span class="font-bold text-base tracking-wide truncate">ข้อมูลสถานศึกษา วิทยาลัยเทคนิคตราด</span>
                    <span class="hidden md:inline-block text-xs text-white/80 font-normal pl-2 border-l border-white/20 truncate">
                        สถิตินักเรียน-นักศึกษา คณะครูและบุคลากรทางการศึกษา (ระบบสารสนเทศ RMS)
                    </span>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="https://rms.trattc.ac.th/pms_graph.php#directory"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors cursor-pointer"
                       title="เปิดดูระบบ RMS จริง trattc.ac.th">
                        <span>ระบบสารสนเทศ RMS (trattc.ac.th)</span>
                        <i class="fas fa-arrow-up-right-from-square text-xs transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i>
                    </a>
                </div>
            </div>

            {{-- 8 Statistics Grid Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <template x-for="(stat, index) in stats" :key="stat.id">
                    <div @click="openModal(stat)"
                         class="group relative bg-slate-50/70 hover:bg-white dark:bg-slate-800/80 dark:hover:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-5 shadow-xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 cursor-pointer overflow-hidden flex flex-col justify-between"
                         :class="{
                             'border-t-4 border-t-[#7a1a40]': stat.color === 'maroon',
                             'border-t-4 border-t-blue-600': stat.color === 'blue',
                             'border-t-4 border-t-pink-500': stat.color === 'rose',
                             'border-t-4 border-t-amber-500': stat.color === 'amber',
                             'border-t-4 border-t-purple-600': stat.color === 'purple',
                             'border-t-4 border-t-emerald-600': stat.color === 'emerald',
                             'border-t-4 border-t-indigo-600': stat.color === 'indigo',
                             'border-t-4 border-t-teal-600': stat.color === 'teal',
                         }">

                        {{-- Card Background Gradient on Hover --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-50/20 to-transparent dark:from-slate-700/20 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                        <div>
                            {{-- Top Bar: Icon + Chart Badge --}}
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-base shadow-xs group-hover:scale-110 transition-transform"
                                     :class="{
                                         'bg-[#7a1a40] text-white': stat.color === 'maroon',
                                         'bg-blue-600 text-white': stat.color === 'blue',
                                         'bg-pink-500 text-white': stat.color === 'rose',
                                         'bg-amber-500 text-white': stat.color === 'amber',
                                         'bg-purple-600 text-white': stat.color === 'purple',
                                         'bg-emerald-600 text-white': stat.color === 'emerald',
                                         'bg-indigo-600 text-white': stat.color === 'indigo',
                                         'bg-teal-600 text-white': stat.color === 'teal',
                                     }">
                                    <i :class="stat.icon || 'fas fa-chart-pie'"></i>
                                </div>

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white dark:bg-slate-700/80 text-slate-600 dark:text-slate-300 border border-slate-200/60 dark:border-slate-600/60 shadow-2xs">
                                    <i class="fas fa-chart-column text-[10px] text-slate-400"></i>
                                    <span x-text="stat.items ? stat.items.length + ' หมวด' : '0'"></span>
                                </span>
                            </div>

                            {{-- Card Title & Subtitle --}}
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors line-clamp-1"
                                x-text="stat.title"></h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2"
                               x-text="stat.subtitle || stat.description || ''"></p>

                            {{-- Total Metric Number --}}
                            <div class="mt-4 flex items-baseline gap-1.5">
                                <span class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight"
                                      x-text="stat.total_count ? Number(stat.total_count).toLocaleString() : '0'"></span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400"
                                      x-text="stat.unit || 'คน'"></span>
                            </div>

                            {{-- Mini Stacked Progress Bar Preview --}}
                            <div class="w-full bg-slate-200/70 dark:bg-slate-700/60 rounded-full h-2 flex overflow-hidden mt-3"
                                 x-show="stat.items && stat.items.length > 0">
                                <template x-for="(item, idx) in (stat.items || []).slice(0, 5)" :key="idx">
                                    <div :style="'width: ' + calculatePercentage(item.value, stat.total_count) + '%; background-color: ' + (item.color || '#3b82f6')"
                                         :title="item.label + ': ' + Number(item.value).toLocaleString() + ' ' + stat.unit"
                                         class="h-full transition-all"></div>
                                </template>
                            </div>

                            {{-- Top 2 Items Pills --}}
                            <div class="mt-3 flex flex-wrap gap-1.5" x-show="stat.items && stat.items.length > 0">
                                <template x-for="(item, idx) in (stat.items || []).slice(0, 2)" :key="idx">
                                    <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-md bg-white dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full" :style="'background-color: ' + (item.color || '#3b82f6')"></span>
                                        <span class="truncate max-w-[90px]" x-text="item.label"></span>:
                                        <span class="font-bold" x-text="Number(item.value).toLocaleString()"></span>
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Bottom Action Link --}}
                        <div class="mt-5 pt-3 border-t border-slate-200/70 dark:border-slate-700/60 flex items-center justify-between text-xs font-bold text-[#7a1a40] dark:text-rose-400">
                            <span class="group-hover:underline">คลิกแสดงกราฟสถิติ</span>
                            <div class="w-7 h-7 rounded-full bg-rose-50 dark:bg-slate-700 flex items-center justify-center group-hover:bg-[#7a1a40] group-hover:text-white transition-all shadow-2xs">
                                <i class="fas fa-chart-pie text-xs"></i>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Statistics Graph Modal Dialog --}}
        <div x-show="statModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">

            {{-- Backdrop --}}
            <div x-show="statModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"
                 class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Content Box --}}
            <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
                <div x-show="statModalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.stop
                     class="relative w-full max-w-4xl transform overflow-hidden rounded-3xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all border border-slate-200 dark:border-slate-800 flex flex-col max-h-[90vh]">

                    {{-- Modal Header --}}
                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4 bg-gradient-to-r from-rose-50/50 via-white to-white dark:from-slate-800/80 dark:via-slate-900 dark:to-slate-900 shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-[#7a1a40] text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                                <i :class="selectedStat?.icon || 'fas fa-chart-pie'"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white truncate"
                                    x-text="selectedStat?.title"></h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate"
                                   x-text="selectedStat?.subtitle || selectedStat?.description || 'สถิติสารสนเทศ วิทยาลัยเทคนิคตราด'"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 shrink-0">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-[#7a1a40] dark:text-rose-300 border border-rose-200/60 dark:border-rose-900 text-xs font-bold">
                                <span>ยอดรวม:</span>
                                <span class="text-sm font-extrabold" x-text="selectedStat?.total_count ? Number(selectedStat.total_count).toLocaleString() : '0'"></span>
                                <span x-text="selectedStat?.unit || 'คน'"></span>
                            </span>

                            <button @click="closeModal()"
                                    class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center cursor-pointer">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>
                    </div>

                    {{-- View Switcher Tab (Chart vs Table) --}}
                    <div class="px-5 sm:px-6 pt-3 pb-2 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3 bg-slate-50/70 dark:bg-slate-800/40 shrink-0">
                        <div class="flex items-center gap-1.5 p-1 bg-slate-200/60 dark:bg-slate-800 rounded-xl text-xs font-semibold">
                            <button @click="activeTab = 'chart'; setTimeout(() => renderChart(), 60);"
                                    :class="activeTab === 'chart' ? 'bg-white dark:bg-slate-700 text-[#7a1a40] dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-chart-pie text-xs"></i>
                                <span>แผนภูมิ / กราฟ</span>
                            </button>
                            <button @click="activeTab = 'table'"
                                    :class="activeTab === 'table' ? 'bg-white dark:bg-slate-700 text-[#7a1a40] dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-table-list text-xs"></i>
                                <span>ตารางแจกแจงข้อมูล</span>
                            </button>
                        </div>

                        <div class="text-xs text-slate-400 hidden sm:flex items-center gap-1.5">
                            <i class="fas fa-database text-amber-500"></i>
                            <span>ระบบสารสนเทศ RMS วิทยาลัยเทคนิคตราด</span>
                        </div>
                    </div>

                    {{-- Modal Body (Scrollable) --}}
                    <div class="p-5 sm:p-6 overflow-y-auto space-y-6">

                        {{-- Tab 1: Chart View --}}
                        <div x-show="activeTab === 'chart'" class="space-y-6">
                            {{-- Canvas Wrapper with dynamic height --}}
                            <div class="w-full bg-slate-50/70 dark:bg-slate-800/40 rounded-2xl border border-slate-200/70 dark:border-slate-700/70 p-4 sm:p-6 overflow-x-auto">
                                <div id="statisticChartCanvasWrapper"
                                     class="relative w-full transition-all"
                                     :style="'height: ' + getChartHeight() + '; min-height: 320px;'">
                                    <canvas id="statisticChartCanvas"></canvas>
                                </div>
                            </div>

                            {{-- Detailed Breakdown Chips Below Chart --}}
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                                    รายการข้อมูลแจกแจงและร้อยละ
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <template x-for="(item, idx) in (selectedStat?.items || [])" :key="idx">
                                        <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between gap-3 shadow-2xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <span class="w-3.5 h-3.5 rounded-md shrink-0 shadow-2xs" :style="'background-color: ' + (item.color || '#3b82f6')"></span>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate" x-text="item.label"></p>
                                                    <p class="text-[11px] text-slate-400 truncate" x-text="item.note || ''"></p>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <p class="text-xs font-extrabold text-slate-900 dark:text-white">
                                                    <span x-text="Number(item.value).toLocaleString()"></span>
                                                    <span class="text-[10px] font-normal text-slate-400" x-text="selectedStat?.unit || 'คน'"></span>
                                                </p>
                                                <span class="text-[11px] font-bold text-[#7a1a40] dark:text-rose-400"
                                                      x-text="calculatePercentage(item.value, selectedStat?.total_count) + '%'"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Table View --}}
                        <div x-show="activeTab === 'table'" class="space-y-4">
                            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                                <table class="w-full text-left text-xs sm:text-sm">
                                    <thead class="bg-slate-100/80 dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold border-b border-slate-200 dark:border-slate-700">
                                        <tr>
                                            <th class="py-3 px-4 w-12 text-center">ลำดับ</th>
                                            <th class="py-3 px-4">หมวดหมู่ / รายการ</th>
                                            <th class="py-3 px-4 text-right">จำนวน (<span x-text="selectedStat?.unit || 'คน'"></span>)</th>
                                            <th class="py-3 px-4 text-right">สัดส่วน (%)</th>
                                            <th class="py-3 px-4">ความคืบหน้าสัดส่วน</th>
                                            <th class="py-3 px-4">หมายเหตุ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                        <template x-for="(item, idx) in (selectedStat?.items || [])" :key="idx">
                                            <tr class="hover:bg-rose-50/30 dark:hover:bg-slate-800/50 transition-colors">
                                                <td class="py-3 px-4 text-center font-bold text-slate-400" x-text="idx + 1"></td>
                                                <td class="py-3 px-4 font-bold flex items-center gap-2">
                                                    <span class="w-3 h-3 rounded-full shrink-0" :style="'background-color: ' + (item.color || '#3b82f6')"></span>
                                                    <span x-text="item.label"></span>
                                                </td>
                                                <td class="py-3 px-4 text-right font-bold text-slate-900 dark:text-white"
                                                    x-text="Number(item.value).toLocaleString()"></td>
                                                <td class="py-3 px-4 text-right font-extrabold text-[#7a1a40] dark:text-rose-400"
                                                    x-text="calculatePercentage(item.value, selectedStat?.total_count) + '%'"></td>
                                                <td class="py-3 px-4 w-36">
                                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                                        <div class="h-full rounded-full transition-all"
                                                             :style="'width: ' + calculatePercentage(item.value, selectedStat?.total_count) + '%; background-color: ' + (item.color || '#3b82f6')"></div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400 text-xs" x-text="item.note || '-'"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot class="bg-slate-50 dark:bg-slate-800/90 font-bold border-t-2 border-slate-200 dark:border-slate-700">
                                        <tr>
                                            <td colspan="2" class="py-3 px-4 text-right">ยอดรวมทั้งหมด</td>
                                            <td class="py-3 px-4 text-right text-base text-[#7a1a40] dark:text-rose-400">
                                                <span x-text="selectedStat?.total_count ? Number(selectedStat.total_count).toLocaleString() : '0'"></span>
                                                <span class="text-xs font-normal" x-text="selectedStat?.unit || 'คน'"></span>
                                            </td>
                                            <td class="py-3 px-4 text-right">100.0%</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Footer Bar --}}
                    <div class="px-5 sm:px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 shrink-0">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>แหล่งข้อมูล: ระบบบริหารจัดการสถานศึกษา (RMS) trattc.ac.th</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="https://rms.trattc.ac.th/pms_graph.php#directory"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-200/80 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold transition-colors cursor-pointer">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                <span>เปิดหน้า RMS จริง</span>
                            </a>
                            <button type="button"
                                    @click="closeModal()"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#7a1a40] hover:bg-[#631433] text-white text-xs font-bold transition-colors cursor-pointer shadow-sm">
                                <span>ปิดหน้าต่าง</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- Section: แสดงความยินดี คณะครูและบุคลากรทางการศึกษา (Congratulations Section matching uploaded sample) --}}
    @php
        $congratulations = $this->teacherCongratulations;
        $congratulationsItems = $congratulations ? $congratulations->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'position' => $item->position ?? '',
                'description' => $item->description ?? '',
                'image' => $item->image_url,
                'date' => $item->formatted_date ?: ($item->published_at ? $item->published_at->format('d/m/Y') : ''),
                'facebook_url' => $item->facebook_url ?? '',
                'views' => $item->views_count,
                'uploader' => $item->uploader_name ?: 'นายศิริรักษ์',
            ];
        })->values() : collect();
    @endphp

    @if($congratulations && $congratulations->isNotEmpty())
        <div class="mx-auto my-4 p-2 max-w-7xl"
             wire:key="teacher-congratulations-container"
             x-data="{
                modalOpen: false,
                selectedItem: null,
                showAll: false,
                currentSlide: 0,
                itemsPerView: 3,
                autoplayTimer: null,
                isPaused: false,
                touchStartX: 0,
                items: @js($congratulationsItems),
                get total() {
                    return this.items.length;
                },
                get maxSlide() {
                    return Math.max(0, this.total - this.itemsPerView);
                },
                updateItemsPerView() {
                    if (window.innerWidth < 640) {
                        this.itemsPerView = 1;
                    } else if (window.innerWidth < 1024) {
                        this.itemsPerView = 2;
                    } else {
                        this.itemsPerView = 3;
                    }
                    if (this.currentSlide > this.maxSlide) {
                        this.currentSlide = this.maxSlide;
                    }
                },
                next() {
                    if (this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    } else {
                        this.currentSlide = 0;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    } else {
                        this.currentSlide = this.maxSlide;
                    }
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = setInterval(() => {
                        if (!this.isPaused && !this.modalOpen && !this.showAll && this.maxSlide > 0) {
                            this.next();
                        }
                    }, 5000);
                },
                stopAutoplay() {
                    if (this.autoplayTimer) {
                        clearInterval(this.autoplayTimer);
                        this.autoplayTimer = null;
                    }
                },
                openModal(index) {
                    const item = this.items[index];
                    if (!item) return;
                    item.views = (parseInt(item.views) || 0) + 1;
                    this.selectedItem = item;
                    this.modalOpen = true;
                    $wire.incrementCongratulationView(item.id);
                }
             }"
             x-init="
                updateItemsPerView();
                window.addEventListener('resize', () => updateItemsPerView());
                startAutoplay();
             ">
            <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">
                <!-- Header matching the solid maroon bar of Latest News, Director & Featured Post -->
                <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-award text-amber-300 text-base"></i>
                        <span class="font-bold text-base tracking-wide">แสดงความยินดี คณะครูและบุคลากรทางการศึกษา</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Navigation Arrows in Header -->
                        <div class="flex items-center gap-1.5" x-show="!showAll && maxSlide > 0">
                            <button @click="prev()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ก่อนหน้า">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-xs font-semibold text-white/80 min-w-10 text-center"
                                  x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                            <button @click="next()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ถัดไป">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Toggle Grid / Slider View -->
                        <button @click="showAll = !showAll; currentSlide = 0"
                                type="button"
                                class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors cursor-pointer pl-2 border-l border-white/20">
                            <span x-text="showAll ? 'แสดงแบบสไลด์' : 'ดูทั้งหมด'">ดูทั้งหมด</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1" :class="showAll ? 'rotate-90' : ''"></i>
                        </button>
                    </div>
                </div>

                <!-- Slider Container -->
                <div class="relative group/slider px-0 sm:px-1"
                     @mouseenter="isPaused = true"
                     @mouseleave="isPaused = false"
                     @touchstart.passive="touchStartX = $event.touches[0].clientX"
                     @touchend="let diff = $event.changedTouches[0].clientX - touchStartX; if (diff > 45) prev(); else if (diff < -45) next();">

                    <!-- Floating Prev Button (Left) -->
                    <button @click="prev()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -left-2 sm:-left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ก่อนหน้า">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>

                    <!-- Floating Next Button (Right) -->
                    <button @click="next()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -right-2 sm:-right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ถัดไป">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Overflow wrapper -->
                    <div :class="showAll ? '' : 'overflow-hidden py-2 -my-2'">
                        <!-- Sliding Track or Grid -->
                        <div :class="showAll ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex transition-transform duration-500 ease-out'"
                             :style="showAll ? '' : `transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                            @foreach($congratulations as $idx => $item)
                                <div wire:key="teacher-congratulations-item-{{ $item->id }}"
                                     :class="showAll ? 'w-full' : 'w-full sm:w-1/2 lg:w-1/3 shrink-0 px-3'">

                                    <div class="group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">

                                        <!-- Image Section (Aspect 16/10) -->
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer"
                                             @click="openModal({{ $idx }})">
                                            <img src="{{ $item->image_url }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" />

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                                            <!-- Category Badge -->
                                            <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs">
                                                แสดงความยินดี
                                            </span>

                                            <!-- Facebook Badge Button -->
                                            @if($item->facebook_url)
                                                <a href="{{ $item->facebook_url }}" target="_blank"
                                                   @click.stop
                                                   class="absolute top-3 right-3 z-10 px-2.5 py-1 bg-[#1877f2]/90 hover:bg-[#1877f2] backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 flex items-center gap-1 shadow-xs transition-colors">
                                                    <i class="fab fa-facebook-f text-[10px]"></i>
                                                    <span>Facebook</span>
                                                </a>
                                            @endif

                                            <!-- Hover Zoom Icon Overlay -->
                                            <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <div class="w-12 h-12 rounded-full bg-white/95 dark:bg-slate-900/95 text-[#7a1a40] dark:text-amber-400 flex items-center justify-center shadow-2xl backdrop-blur-md transform scale-75 group-hover:scale-100 transition-all duration-300 border border-white/50 dark:border-slate-700">
                                                    <i class="fas fa-search-plus text-lg"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                            <div>
                                                <!-- Meta Row (Date & Views) -->
                                                <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-calendar-alt text-[#7a1a40] dark:text-rose-400"></i>
                                                        <span>{{ $item->formatted_date ?: ($item->published_at ? $item->published_at->format('j M Y') : '') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-eye text-slate-400"></i>
                                                        <span x-text="items[{{ $idx }}] ? Number(items[{{ $idx }}].views).toLocaleString() : '{{ number_format($item->views_count) }}'">{{ number_format($item->views_count) }}</span>
                                                        <span>ครั้ง</span>
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white line-clamp-1 leading-snug group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors mb-1 cursor-pointer"
                                                    @click="openModal({{ $idx }})">
                                                    {{ $item->title }}
                                                </h3>

                                                <!-- Position -->
                                                @if($item->position)
                                                    <p class="text-xs font-semibold text-[#7a1a40] dark:text-rose-400 line-clamp-1 mb-2">
                                                        {{ $item->position }}
                                                    </p>
                                                @endif

                                                <!-- Excerpt / Description -->
                                                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                                    {{ $item->description }}
                                                </p>
                                            </div>

                                            <!-- Read More Footer -->
                                            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                                <button @click="openModal({{ $idx }})"
                                                        type="button"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                                    <span>อ่านรายละเอียด</span>
                                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                                </button>

                                                <div class="flex items-center gap-1 text-[11px] text-slate-400 truncate min-w-0 font-medium"
                                                     title="{{ $item->uploader_name ?: 'Mr.Chuchart Saeteaw' }}">
                                                    <i class="far fa-user text-slate-400 shrink-0 text-xs"></i>
                                                    <span class="truncate">{{ $item->uploader_name ?: 'Mr.Chuchart Saeteaw' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Slider Indicators / Pagination Dots -->
                <div class="flex items-center justify-center gap-2 mt-6" x-show="!showAll && maxSlide > 0">
                    <template x-for="s in (maxSlide + 1)" :key="s">
                        <button @click="currentSlide = s - 1"
                                type="button"
                                :aria-label="'สไลด์ ' + s"
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === (s - 1) ? 'w-8 bg-[#7a1a40] dark:bg-rose-500' : 'w-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300'"></button>
                    </template>
                </div>

                <!-- Fullscreen Image Lightbox Modal -->
                <div x-show="modalOpen"
                     class="fixed inset-0 z-100 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                     style="display: none;"
                     @keydown.escape.window="modalOpen = false">

                    <!-- Backdrop -->
                    <div x-show="modalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"
                         @click="modalOpen = false"></div>

                    <!-- Modal Dialog -->
                    <div x-show="modalOpen"
                         @click.stop
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200 dark:border-slate-700 max-h-[90vh] flex flex-col">

                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700 bg-[#7a1a40] text-white shrink-0">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-award text-amber-300 text-lg"></i>
                                <h3 class="font-bold text-white text-base sm:text-lg" x-text="selectedItem ? selectedItem.title : ''"></h3>
                            </div>
                            <button @click="modalOpen = false"
                                    type="button"
                                    class="p-2 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors cursor-pointer">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>

                        <!-- Poster Image (Scrollable if needed) -->
                        <div class="p-4 sm:p-6 bg-slate-100/50 dark:bg-slate-900/40 flex justify-center items-center overflow-auto flex-1">
                            <img :src="selectedItem ? selectedItem.image : ''"
                                 :alt="selectedItem ? selectedItem.title : ''"
                                 class="max-h-[60vh] w-auto rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 object-contain" />
                        </div>

                        <!-- Details & Actions -->
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700 shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-800 dark:text-white text-sm" x-text="selectedItem ? selectedItem.position : ''"></p>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 whitespace-pre-line leading-relaxed" x-text="selectedItem && selectedItem.description ? selectedItem.description : ''"></p>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-2">
                                    <span class="flex items-center gap-1"><i class="far fa-calendar-alt"></i> <span x-text="selectedItem ? selectedItem.date : ''"></span></span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><i class="far fa-eye"></i> <span x-text="selectedItem ? Number(selectedItem.views).toLocaleString() : ''"></span> ครั้ง</span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><i class="far fa-user"></i> <span x-text="selectedItem ? selectedItem.uploader : ''"></span></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="selectedItem && selectedItem.image">
                                    <a :href="selectedItem.image" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                        <span>ดูภาพเต็ม</span>
                                    </a>
                                </template>

                                <template x-if="selectedItem && selectedItem.facebook_url">
                                    <a :href="selectedItem.facebook_url" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                        <span>เปิดบน Facebook</span>
                                    </a>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Section: รางวัล ความสำเร็จ (Achievements Section matching congratulations structure) --}}
    @php
        $achievements = $this->achievements;
        $achievementsItems = $achievements ? $achievements->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'position' => $item->position ?? '',
                'description' => $item->description ?? '',
                'image' => $item->image_url,
                'date' => $item->formatted_date ?: ($item->published_at ? $item->published_at->format('d/m/Y') : ''),
                'facebook_url' => $item->facebook_url ?? '',
                'views' => $item->views_count,
                'uploader' => $item->uploader_name ?: 'นายศิริรักษ์',
            ];
        })->values() : collect();
    @endphp

    @if($achievements && $achievements->isNotEmpty())
        <div class="mx-auto my-4 p-2 max-w-7xl"
             wire:key="achievements-container"
             x-data="{
                modalOpen: false,
                selectedItem: null,
                showAll: false,
                currentSlide: 0,
                itemsPerView: 3,
                autoplayTimer: null,
                isPaused: false,
                touchStartX: 0,
                items: @js($achievementsItems),
                get total() {
                    return this.items.length;
                },
                get maxSlide() {
                    return Math.max(0, this.total - this.itemsPerView);
                },
                updateItemsPerView() {
                    if (window.innerWidth < 640) {
                        this.itemsPerView = 1;
                    } else if (window.innerWidth < 1024) {
                        this.itemsPerView = 2;
                    } else {
                        this.itemsPerView = 3;
                    }
                    if (this.currentSlide > this.maxSlide) {
                        this.currentSlide = this.maxSlide;
                    }
                },
                next() {
                    if (this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    } else {
                        this.currentSlide = 0;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    } else {
                        this.currentSlide = this.maxSlide;
                    }
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = setInterval(() => {
                        if (!this.isPaused && !this.modalOpen && !this.showAll && this.maxSlide > 0) {
                            this.next();
                        }
                    }, 5000);
                },
                stopAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = null;
                },
                openModal(index) {
                    const item = this.items[index];
                    if (!item) return;
                    item.views = (parseInt(item.views) || 0) + 1;
                    this.selectedItem = item;
                    this.modalOpen = true;
                    $wire.incrementAchievementView(item.id);
                }
             }"
             x-init="
                updateItemsPerView();
                window.addEventListener('resize', () => updateItemsPerView());
                startAutoplay();
             ">
            <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">
                <!-- Header matching the solid maroon bar of Latest News, Director & Featured Post -->
                <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-trophy text-amber-300 text-base"></i>
                        <span class="font-bold text-base tracking-wide">รางวัล ความสำเร็จ</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Navigation Arrows in Header -->
                        <div class="flex items-center gap-1.5" x-show="!showAll && maxSlide > 0">
                            <button @click="prev()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ก่อนหน้า">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-xs font-semibold text-white/80 min-w-10 text-center"
                                  x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                            <button @click="next()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ถัดไป">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Toggle Grid / Slider View -->
                        <button @click="showAll = !showAll; currentSlide = 0"
                                type="button"
                                class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors cursor-pointer pl-2 border-l border-white/20">
                            <span x-text="showAll ? 'แสดงแบบสไลด์' : 'ดูทั้งหมด'">ดูทั้งหมด</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1" :class="showAll ? 'rotate-90' : ''"></i>
                        </button>
                    </div>
                </div>

                <!-- Slider Container -->
                <div class="relative group/slider px-0 sm:px-1"
                     @mouseenter="isPaused = true"
                     @mouseleave="isPaused = false"
                     @touchstart.passive="touchStartX = $event.touches[0].clientX"
                     @touchend="let diff = $event.changedTouches[0].clientX - touchStartX; if (diff > 45) prev(); else if (diff < -45) next();">

                    <!-- Floating Prev Button (Left) -->
                    <button @click="prev()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -left-2 sm:-left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ก่อนหน้า">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>

                    <!-- Floating Next Button (Right) -->
                    <button @click="next()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -right-2 sm:-right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ถัดไป">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Overflow wrapper -->
                    <div :class="showAll ? '' : 'overflow-hidden py-2 -my-2'">
                        <!-- Sliding Track or Grid -->
                        <div :class="showAll ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex transition-transform duration-500 ease-out'"
                             :style="showAll ? '' : `transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                            @foreach($achievements as $idx => $item)
                                <div wire:key="achievements-item-{{ $item->id }}"
                                     :class="showAll ? 'w-full' : 'w-full sm:w-1/2 lg:w-1/3 shrink-0 px-3'">

                                    <div class="group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">

                                        <!-- Image Section (Aspect 16/10) -->
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer"
                                             @click="openModal({{ $idx }})">
                                            <img src="{{ $item->image_url }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" />

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                                            <!-- Category Badge -->
                                            <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs flex items-center gap-1">
                                                <i class="fas fa-trophy text-amber-300 text-[10px]"></i>
                                                <span>รางวัล ความสำเร็จ</span>
                                            </span>

                                            <!-- Facebook Badge Button -->
                                            @if($item->facebook_url)
                                                <a href="{{ $item->facebook_url }}" target="_blank"
                                                   @click.stop
                                                   class="absolute top-3 right-3 z-10 px-2.5 py-1 bg-[#1877f2]/90 hover:bg-[#1877f2] backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 flex items-center gap-1 shadow-xs transition-colors">
                                                    <i class="fab fa-facebook-f text-[10px]"></i>
                                                    <span>Facebook</span>
                                                </a>
                                            @endif

                                            <!-- Hover Zoom Icon Overlay -->
                                            <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <div class="w-12 h-12 rounded-full bg-white/95 dark:bg-slate-900/95 text-[#7a1a40] dark:text-amber-400 flex items-center justify-center shadow-2xl backdrop-blur-md transform scale-75 group-hover:scale-100 transition-all duration-300 border border-white/50 dark:border-slate-700">
                                                    <i class="fas fa-search-plus text-lg"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                            <div>
                                                <!-- Meta Row (Date & Views) -->
                                                <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-calendar-alt text-[#7a1a40] dark:text-rose-400"></i>
                                                        <span>{{ $item->formatted_date ?: ($item->published_at ? $item->published_at->format('j M Y') : '') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-eye text-slate-400"></i>
                                                        <span x-text="items[{{ $idx }}] ? Number(items[{{ $idx }}].views).toLocaleString() : '{{ number_format($item->views_count) }}'">{{ number_format($item->views_count) }}</span>
                                                        <span>ครั้ง</span>
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white line-clamp-1 leading-snug group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors mb-1 cursor-pointer"
                                                    @click="openModal({{ $idx }})">
                                                    {{ $item->title }}
                                                </h3>

                                                <!-- Position / Subtitle -->
                                                @if($item->position)
                                                    <p class="text-xs font-semibold text-[#7a1a40] dark:text-rose-400 line-clamp-1 mb-2">
                                                        {{ $item->position }}
                                                    </p>
                                                @endif

                                                <!-- Excerpt / Description -->
                                                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                                    {{ $item->description }}
                                                </p>
                                            </div>

                                            <!-- Read More Footer -->
                                            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                                <button @click="openModal({{ $idx }})"
                                                        type="button"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                                    <span>อ่านรายละเอียด</span>
                                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                                </button>

                                                <div class="flex items-center gap-1 text-[11px] text-slate-400 truncate min-w-0 font-medium"
                                                     title="{{ $item->uploader_name ?: 'Mr.Chuchart Saeteaw' }}">
                                                    <i class="far fa-user text-slate-400 shrink-0 text-xs"></i>
                                                    <span class="truncate">{{ $item->uploader_name ?: 'Mr.Chuchart Saeteaw' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Slider Indicators / Pagination Dots -->
                <div class="flex items-center justify-center gap-2 mt-6" x-show="!showAll && maxSlide > 0">
                    <template x-for="s in (maxSlide + 1)" :key="s">
                        <button @click="currentSlide = s - 1"
                                type="button"
                                :aria-label="'สไลด์ ' + s"
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === (s - 1) ? 'w-8 bg-[#7a1a40] dark:bg-rose-500' : 'w-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300'"></button>
                    </template>
                </div>

                <!-- Fullscreen Image Lightbox Modal -->
                <div x-show="modalOpen"
                     class="fixed inset-0 z-100 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                     style="display: none;"
                     @keydown.escape.window="modalOpen = false">

                    <!-- Backdrop -->
                    <div x-show="modalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"
                         @click="modalOpen = false"></div>

                    <!-- Modal Dialog -->
                    <div x-show="modalOpen"
                         @click.stop
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200 dark:border-slate-700 max-h-[90vh] flex flex-col">

                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700 bg-[#7a1a40] text-white shrink-0">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-trophy text-amber-300 text-lg"></i>
                                <h3 class="font-bold text-white text-base sm:text-lg" x-text="selectedItem ? selectedItem.title : ''"></h3>
                            </div>
                            <button @click="modalOpen = false"
                                    type="button"
                                    class="p-2 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors cursor-pointer">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>

                        <!-- Poster Image (Scrollable if needed) -->
                        <div class="p-4 sm:p-6 bg-slate-100/50 dark:bg-slate-900/40 flex justify-center items-center overflow-auto flex-1">
                            <img :src="selectedItem ? selectedItem.image : ''"
                                 :alt="selectedItem ? selectedItem.title : ''"
                                 class="max-h-[60vh] w-auto rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 object-contain" />
                        </div>

                        <!-- Details & Actions -->
                        <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700 shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-800 dark:text-white text-sm" x-text="selectedItem ? selectedItem.position : ''"></p>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 whitespace-pre-line leading-relaxed" x-text="selectedItem && selectedItem.description ? selectedItem.description : ''"></p>
                                <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-2">
                                    <span class="flex items-center gap-1"><i class="far fa-calendar-alt"></i> <span x-text="selectedItem ? selectedItem.date : ''"></span></span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><i class="far fa-eye"></i> <span x-text="selectedItem ? Number(selectedItem.views).toLocaleString() : ''"></span> ครั้ง</span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1"><i class="far fa-user"></i> <span x-text="selectedItem ? selectedItem.uploader : ''"></span></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="selectedItem && selectedItem.image">
                                    <a :href="selectedItem.image" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition-colors">
                                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                        <span>ดูภาพเต็ม</span>
                                    </a>
                                </template>

                                <template x-if="selectedItem && selectedItem.facebook_url">
                                    <a :href="selectedItem.facebook_url" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                        <span>เปิดบน Facebook</span>
                                    </a>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Section: จดหมายข่าวประชาสัมพันธ์ (PR Newsletters Section) --}}
    @php
        $newsletters = $this->newsletters;
        $newsletterItems = $newsletters ? $newsletters->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'issue_no' => $item->issue_no ?? '',
                'category' => $item->category ?: 'ข่าวกิจกรรมสถานศึกษา',
                'description' => $item->description ?? '',
                'image' => $item->image_url,
                'date' => $item->formatted_date ?: ($item->published_at ? $item->published_at->format('d/m/Y') : ''),
                'views' => $item->views_count,
                'uploader' => $item->uploader_name ?: 'งานประชาสัมพันธ์',
                'attachments' => $item->attachment_files,
            ];
        })->values() : collect();
    @endphp

    @if($newsletters && $newsletters->isNotEmpty())
        <div class="mx-auto my-4 p-2 max-w-7xl"
             wire:key="newsletters-container"
             x-data="{
                modalOpen: false,
                selectedItem: null,
                showAll: false,
                currentSlide: 0,
                itemsPerView: 3,
                autoplayTimer: null,
                isPaused: false,
                touchStartX: 0,
                items: @js($newsletterItems),
                get total() {
                    return this.items.length;
                },
                get maxSlide() {
                    return Math.max(0, this.total - this.itemsPerView);
                },
                updateItemsPerView() {
                    if (window.innerWidth < 640) {
                        this.itemsPerView = 1;
                    } else if (window.innerWidth < 1024) {
                        this.itemsPerView = 2;
                    } else {
                        this.itemsPerView = 3;
                    }
                    if (this.currentSlide > this.maxSlide) {
                        this.currentSlide = this.maxSlide;
                    }
                },
                next() {
                    if (this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    } else {
                        this.currentSlide = 0;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    } else {
                        this.currentSlide = this.maxSlide;
                    }
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = setInterval(() => {
                        if (!this.isPaused && !this.modalOpen && !this.showAll && this.maxSlide > 0) {
                            this.next();
                        }
                    }, 5000);
                },
                stopAutoplay() {
                    if (this.autoplayTimer) {
                        clearInterval(this.autoplayTimer);
                        this.autoplayTimer = null;
                    }
                },
                openModal(index) {
                    const item = this.items[index];
                    if (!item) return;
                    item.views = (parseInt(item.views) || 0) + 1;
                    this.selectedItem = item;
                    this.modalOpen = true;
                    $wire.incrementNewsletterView(item.id);
                }
             }"
             x-init="
                updateItemsPerView();
                window.addEventListener('resize', () => updateItemsPerView());
                startAutoplay();
             ">
            <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">
                <!-- Header matching the solid maroon bar -->
                <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-newspaper text-amber-300 text-base"></i>
                        <span class="font-bold text-base tracking-wide">จดหมายข่าวประชาสัมพันธ์</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Navigation Arrows in Header -->
                        <div class="flex items-center gap-1.5" x-show="!showAll && maxSlide > 0">
                            <button @click="prev()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ก่อนหน้า">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-xs font-semibold text-white/80 min-w-10 text-center"
                                  x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                            <button @click="next()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ถัดไป">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Toggle Grid / Slider View -->
                        <button @click="showAll = !showAll; currentSlide = 0"
                                type="button"
                                class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors cursor-pointer pl-2 border-l border-white/20">
                            <span x-text="showAll ? 'แสดงแบบสไลด์' : 'ดูทั้งหมด'">ดูทั้งหมด</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1" :class="showAll ? 'rotate-90' : ''"></i>
                        </button>
                    </div>
                </div>

                <!-- Slider Container -->
                <div class="relative group/slider px-0 sm:px-1"
                     @mouseenter="isPaused = true"
                     @mouseleave="isPaused = false"
                     @touchstart.passive="touchStartX = $event.touches[0].clientX"
                     @touchend="let diff = $event.changedTouches[0].clientX - touchStartX; if (diff > 45) prev(); else if (diff < -45) next();">

                    <!-- Floating Prev Button (Left) -->
                    <button @click="prev()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -left-2 sm:-left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ก่อนหน้า">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>

                    <!-- Floating Next Button (Right) -->
                    <button @click="next()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -right-2 sm:-right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ถัดไป">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Overflow wrapper -->
                    <div :class="showAll ? '' : 'overflow-hidden py-2 -my-2'">
                        <!-- Sliding Track or Grid -->
                        <div :class="showAll ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex transition-transform duration-500 ease-out'"
                             :style="showAll ? '' : `transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                            @foreach($newsletters as $idx => $item)
                                <div wire:key="newsletter-item-{{ $item->id }}"
                                     :class="showAll ? 'w-full' : 'w-full sm:w-1/2 lg:w-1/3 shrink-0 px-3'">

                                    <div class="group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">

                                        <!-- Image / Cover Section (Aspect 16/10) -->
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer"
                                             @click="openModal({{ $idx }})">
                                            <img src="{{ $item->image_url }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" />

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-70 group-hover:opacity-50 transition-opacity"></div>

                                            <!-- Category Badge -->
                                            <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs flex items-center gap-1">
                                                <i class="fas fa-newspaper text-amber-300 text-[10px]"></i>
                                                <span class="truncate max-w-[150px]">{{ $item->category ?: 'จดหมายข่าว' }}</span>
                                            </span>

                                            <!-- Issue No Badge -->
                                            @if($item->issue_no)
                                                <span class="absolute top-3 right-3 z-10 px-2.5 py-1 bg-amber-500/95 backdrop-blur-md text-slate-900 text-[11px] font-black rounded-lg border border-amber-300 shadow-xs flex items-center gap-1">
                                                    <i class="fas fa-bookmark text-[10px]"></i>
                                                    <span>{{ $item->issue_no }}</span>
                                                </span>
                                            @endif

                                            <!-- Hover Zoom Icon Overlay -->
                                            <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <div class="w-12 h-12 rounded-full bg-white/95 dark:bg-slate-900/95 text-[#7a1a40] dark:text-amber-400 flex items-center justify-center shadow-2xl backdrop-blur-md transform scale-75 group-hover:scale-100 transition-all duration-300 border border-white/50 dark:border-slate-700">
                                                    <i class="fas fa-search-plus text-lg"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                            <div>
                                                <!-- Meta Row (Date & Views) -->
                                                <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-calendar-alt text-[#7a1a40] dark:text-rose-400"></i>
                                                        <span>{{ $item->formatted_date ?: ($item->published_at ? $item->published_at->format('j M Y') : '') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-eye text-slate-400"></i>
                                                        <span x-text="items[{{ $idx }}] ? Number(items[{{ $idx }}].views).toLocaleString() : '{{ number_format($item->views_count) }}'">{{ number_format($item->views_count) }}</span>
                                                        <span>ครั้ง</span>
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white line-clamp-2 leading-snug group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors mb-2 cursor-pointer"
                                                    @click="openModal({{ $idx }})">
                                                    {{ $item->title }}
                                                </h3>

                                                <!-- Excerpt / Description -->
                                                @if($item->description)
                                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm line-clamp-2 leading-relaxed mb-3">
                                                        {{ $item->description }}
                                                    </p>
                                                @endif

                                                <!-- Attached Files Direct Download Chips (PDF, Word, Excel, PPT, ZIP) -->
                                                @if(!empty($item->attachment_files))
                                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                        <div class="text-[11px] font-semibold text-slate-400 mb-1.5 flex items-center justify-between">
                                                            <span class="flex items-center gap-1"><i class="fas fa-paperclip text-[10px]"></i> เอกสารแนบ ({{ count($item->attachment_files) }})</span>
                                                            <span class="text-[10px] text-slate-400">คลิกเพื่อดาวน์โหลด</span>
                                                        </div>
                                                        <div class="flex flex-wrap gap-1.5">
                                                            @foreach(array_slice($item->attachment_files, 0, 3) as $file)
                                                                <a href="{{ $file['url'] }}"
                                                                   download
                                                                   @click.stop
                                                                   class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[11px] font-bold border transition-all hover:scale-105 {{ $file['bg_color'] }}"
                                                                   title="{{ $file['name'] }} ({{ $file['size'] }})">
                                                                    <i class="{{ $file['icon'] }} {{ $file['color'] }} text-[11px]"></i>
                                                                    <span class="truncate max-w-[90px]">{{ $file['ext'] }}</span>
                                                                    @if($file['size'])
                                                                        <span class="text-[9.5px] opacity-75">({{ $file['size'] }})</span>
                                                                    @endif
                                                                </a>
                                                            @endforeach

                                                            @if(count($item->attachment_files) > 3)
                                                                <button type="button"
                                                                        @click.stop="openModal({{ $idx }})"
                                                                        class="inline-flex items-center px-1.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                                                                    +{{ count($item->attachment_files) - 3 }} ไฟล์
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Footer Button -->
                                            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                                <button @click="openModal({{ $idx }})"
                                                        type="button"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                                    <span>อ่านฉบับเต็ม / ดาวน์โหลด</span>
                                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                                </button>

                                                <div class="flex items-center gap-1 text-[11px] text-slate-400 truncate min-w-0 font-medium"
                                                     title="{{ $item->uploader_name ?: 'งานประชาสัมพันธ์' }}">
                                                    <i class="far fa-user text-slate-400 shrink-0 text-xs"></i>
                                                    <span class="truncate">{{ $item->uploader_name ?: 'งานประชาสัมพันธ์' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Slider Indicators / Pagination Dots -->
                <div class="flex items-center justify-center gap-2 mt-6" x-show="!showAll && maxSlide > 0">
                    <template x-for="s in (maxSlide + 1)" :key="s">
                        <button @click="currentSlide = s - 1"
                                type="button"
                                :aria-label="'สไลด์ ' + s"
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === (s - 1) ? 'w-8 bg-[#7a1a40] dark:bg-rose-500' : 'w-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300'"></button>
                    </template>
                </div>

                <!-- Fullscreen Lightbox Modal for Newsletter Details & Attachments -->
                <div x-show="modalOpen"
                     class="fixed inset-0 z-100 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                     style="display: none;"
                     @keydown.escape.window="modalOpen = false">

                    <!-- Backdrop -->
                    <div x-show="modalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"
                         @click="modalOpen = false"></div>

                    <!-- Modal Dialog -->
                    <div x-show="modalOpen"
                         @click.stop
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200 dark:border-slate-700 max-h-[92vh] flex flex-col">

                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700 bg-[#7a1a40] text-white shrink-0">
                            <div class="flex items-center gap-2.5 min-w-0 pr-3">
                                <i class="fas fa-newspaper text-amber-300 text-lg shrink-0"></i>
                                <h3 class="font-bold text-white text-base sm:text-lg truncate" x-text="selectedItem ? selectedItem.title : ''"></h3>
                            </div>
                            <button @click="modalOpen = false"
                                    type="button"
                                    class="p-2 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors cursor-pointer shrink-0">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>

                        <!-- Modal Body (Scrollable) -->
                        <div class="p-5 sm:p-6 overflow-y-auto space-y-5 flex-1">
                            <!-- Image Cover -->
                            <template x-if="selectedItem && selectedItem.image">
                                <div class="w-full flex justify-center bg-slate-100/60 dark:bg-slate-900/40 rounded-2xl p-3 border border-slate-200 dark:border-slate-700">
                                    <img :src="selectedItem.image"
                                         :alt="selectedItem.title"
                                         class="max-h-[45vh] w-auto rounded-xl shadow-md object-contain" />
                                </div>
                            </template>

                            <!-- Information Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700 text-xs sm:text-sm">
                                <template x-if="selectedItem && selectedItem.issue_no">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 min-w-[90px]">ฉบับที่ / ปีที่:</span>
                                        <span class="font-bold text-amber-600 dark:text-amber-400" x-text="selectedItem.issue_no"></span>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">หมวดหมู่:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedItem ? selectedItem.category : ''"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">วันที่เผยแพร่:</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300" x-text="selectedItem ? selectedItem.date : ''"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">ผู้เผยแพร่ / ยอดชม:</span>
                                    <span class="text-slate-600 dark:text-slate-300" x-text="(selectedItem ? selectedItem.uploader : '') + ' • ' + (selectedItem ? Number(selectedItem.views).toLocaleString() : '') + ' ครั้ง'"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <template x-if="selectedItem && selectedItem.description">
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-align-left text-[#7a1a40]"></i>
                                        <span>เนื้อหาจดหมายข่าว</span>
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line leading-relaxed p-3.5 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-100 dark:border-slate-700"
                                       x-text="selectedItem.description"></p>
                                </div>
                            </template>

                            <!-- Attachments Download Section (PDF, Word, Excel, PPT, ZIP) -->
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-2 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-cloud-arrow-down text-[#7a1a40] dark:text-amber-400"></i>
                                        <span>ดาวน์โหลดเอกสารจดหมายข่าวฉบับเต็ม</span>
                                    </span>
                                    <span class="text-xs font-normal text-slate-400"
                                          x-text="(selectedItem && selectedItem.attachments ? selectedItem.attachments.length : 0) + ' ไฟล์'"></span>
                                </h4>

                                <template x-if="selectedItem && selectedItem.attachments && selectedItem.attachments.length > 0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <template x-for="(file, fIdx) in selectedItem.attachments" :key="fIdx">
                                            <a :href="file.url"
                                               download
                                               class="group/file flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-[#7a1a40] dark:hover:border-rose-400 bg-white dark:bg-slate-900 hover:bg-rose-50/30 dark:hover:bg-slate-800 transition-all duration-200 shadow-xs">
                                                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 border"
                                                         :class="file.bg_color">
                                                        <i :class="file.icon + ' ' + file.color + ' text-base'"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate group-hover/file:text-[#7a1a40] transition-colors"
                                                           x-text="file.name"></p>
                                                        <p class="text-[10.5px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                                            <span class="font-semibold uppercase" x-text="file.ext"></span>
                                                            <template x-if="file.size">
                                                                <span x-text="'• ' + file.size"></span>
                                                            </template>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="shrink-0">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#7a1a40] text-white text-[11px] font-bold shadow-xs group-hover/file:bg-[#661434] transition-colors">
                                                        <i class="fas fa-download text-[10px]"></i>
                                                        <span class="hidden sm:inline">ดาวน์โหลด</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!selectedItem || !selectedItem.attachments || selectedItem.attachments.length === 0">
                                    <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                                        ไม่มีเอกสารแนบในจดหมายข่าวนี้
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-4 sm:p-5 flex items-center justify-between border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 shrink-0">
                            <span class="text-xs text-slate-400">วิทยาลัยเทคนิคตราด งานประชาสัมพันธ์</span>
                            <button @click="modalOpen = false"
                                    type="button"
                                    class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                ปิดหน้าต่าง
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Section: ประกาศจัดซื้อ-จัดจ้าง (Procurement Announcements Section) --}}
    @php
        $procurements = $this->procurements;
        $procurementItems = $procurements ? $procurements->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'procurement_type' => $item->procurement_type ?: 'ประกาศจัดซื้อจัดจ้าง',
                'budget' => $item->budget ?? '',
                'description' => $item->description ?? '',
                'image' => $item->image_url,
                'date' => $item->formatted_date ?: ($item->published_at ? $item->published_at->format('d/m/Y') : ''),
                'deadline' => $item->formatted_deadline ?: ($item->deadline_at ? $item->deadline_at->format('d/m/Y') : ''),
                'status' => $item->status,
                'status_label' => $item->status_label,
                'status_color' => $item->status_color,
                'views' => $item->views_count,
                'uploader' => $item->uploader_name ?: 'งานพัสดุ',
                'attachments' => $item->attachment_files,
                'link_url' => $item->link_url ?? '',
            ];
        })->values() : collect();
    @endphp

    @if($procurements && $procurements->isNotEmpty())
        <div class="mx-auto my-4 p-2 max-w-7xl"
             wire:key="procurements-container"
             x-data="{
                modalOpen: false,
                selectedItem: null,
                showAll: false,
                currentSlide: 0,
                itemsPerView: 3,
                autoplayTimer: null,
                isPaused: false,
                touchStartX: 0,
                items: @js($procurementItems),
                get total() {
                    return this.items.length;
                },
                get maxSlide() {
                    return Math.max(0, this.total - this.itemsPerView);
                },
                updateItemsPerView() {
                    if (window.innerWidth < 640) {
                        this.itemsPerView = 1;
                    } else if (window.innerWidth < 1024) {
                        this.itemsPerView = 2;
                    } else {
                        this.itemsPerView = 3;
                    }
                    if (this.currentSlide > this.maxSlide) {
                        this.currentSlide = this.maxSlide;
                    }
                },
                next() {
                    if (this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    } else {
                        this.currentSlide = 0;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    } else {
                        this.currentSlide = this.maxSlide;
                    }
                },
                startAutoplay() {
                    this.stopAutoplay();
                    this.autoplayTimer = setInterval(() => {
                        if (!this.isPaused && !this.modalOpen && !this.showAll && this.maxSlide > 0) {
                            this.next();
                        }
                    }, 5000);
                },
                stopAutoplay() {
                    if (this.autoplayTimer) {
                        clearInterval(this.autoplayTimer);
                        this.autoplayTimer = null;
                    }
                },
                openModal(index) {
                    const item = this.items[index];
                    if (!item) return;
                    item.views = (parseInt(item.views) || 0) + 1;
                    this.selectedItem = item;
                    this.modalOpen = true;
                    $wire.incrementProcurementView(item.id);
                }
             }"
             x-init="
                updateItemsPerView();
                window.addEventListener('resize', () => updateItemsPerView());
                startAutoplay();
             ">
            <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">
                <!-- Header matching the solid maroon bar -->
                <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-file-contract text-amber-300 text-base"></i>
                        <span class="font-bold text-base tracking-wide">ประกาศจัดซื้อ-จัดจ้าง</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Navigation Arrows in Header -->
                        <div class="flex items-center gap-1.5" x-show="!showAll && maxSlide > 0">
                            <button @click="prev()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ก่อนหน้า">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-xs font-semibold text-white/80 min-w-10 text-center"
                                  x-text="(currentSlide + 1) + ' / ' + (maxSlide + 1)"></span>
                            <button @click="next()"
                                    type="button"
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 active:bg-white/30 text-white flex items-center justify-center transition-all cursor-pointer"
                                    title="ถัดไป">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Toggle Grid / Slider View -->
                        <button @click="showAll = !showAll; currentSlide = 0"
                                type="button"
                                class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors cursor-pointer pl-2 border-l border-white/20">
                            <span x-text="showAll ? 'แสดงแบบสไลด์' : 'ดูทั้งหมด'">ดูทั้งหมด</span>
                            <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1" :class="showAll ? 'rotate-90' : ''"></i>
                        </button>

                        <!-- Link to original post3 -->
                        <a href="https://trattc.ac.th/post3"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white/90 hover:text-amber-300 text-xs font-semibold transition-all shadow-xs border border-white/15"
                           title="เปิดดูประกาศทั้งหมดที่ trattc.ac.th/post3">
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                            <span>trattc.ac.th/post3</span>
                        </a>
                    </div>
                </div>

                <!-- Slider Container -->
                <div class="relative group/slider px-0 sm:px-1"
                     @mouseenter="isPaused = true"
                     @mouseleave="isPaused = false"
                     @touchstart.passive="touchStartX = $event.touches[0].clientX"
                     @touchend="let diff = $event.changedTouches[0].clientX - touchStartX; if (diff > 45) prev(); else if (diff < -45) next();">

                    <!-- Floating Prev Button (Left) -->
                    <button @click="prev()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -left-2 sm:-left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ก่อนหน้า">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>

                    <!-- Floating Next Button (Right) -->
                    <button @click="next()"
                            x-show="!showAll && maxSlide > 0"
                            type="button"
                            class="absolute -right-2 sm:-right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/95 dark:bg-slate-800/95 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-[#7a1a40] dark:hover:text-white transition-all duration-300 flex items-center justify-center z-20 cursor-pointer hover:scale-110 active:scale-95"
                            aria-label="ถัดไป">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>

                    <!-- Overflow wrapper -->
                    <div :class="showAll ? '' : 'overflow-hidden py-2 -my-2'">
                        <!-- Sliding Track or Grid -->
                        <div :class="showAll ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex transition-transform duration-500 ease-out'"
                             :style="showAll ? '' : `transform: translateX(-${currentSlide * (100 / itemsPerView)}%)`">
                            @foreach($procurements as $idx => $item)
                                <div wire:key="procurement-item-{{ $item->id }}"
                                     :class="showAll ? 'w-full' : 'w-full sm:w-1/2 lg:w-1/3 shrink-0 px-3'">

                                    <div class="group flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">

                                        <!-- Image / Poster Section (Aspect 16/10) -->
                                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer"
                                             @click="openModal({{ $idx }})">
                                            <img src="{{ $item->image_url }}"
                                                 alt="{{ $item->title }}"
                                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" />

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-70 group-hover:opacity-50 transition-opacity"></div>

                                            <!-- Category Badge -->
                                            <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs flex items-center gap-1">
                                                <i class="fas fa-file-invoice text-amber-300 text-[10px]"></i>
                                                <span class="truncate max-w-[170px]">{{ $item->procurement_type ?: 'จัดซื้อจัดจ้าง' }}</span>
                                            </span>

                                            <!-- Status Badge -->
                                            <span class="absolute top-3 right-3 z-10 px-2.5 py-1 {{ $item->status_color }} text-[11px] font-bold rounded-lg shadow-xs flex items-center gap-1 backdrop-blur-md">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $item->status === 'active' ? 'bg-emerald-300 animate-pulse' : 'bg-white' }}"></span>
                                                <span>{{ $item->status_label }}</span>
                                            </span>

                                            <!-- Hover Zoom Icon Overlay -->
                                            <div class="absolute inset-0 bg-slate-950/30 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center pointer-events-none">
                                                <div class="w-12 h-12 rounded-full bg-white/95 dark:bg-slate-900/95 text-[#7a1a40] dark:text-amber-400 flex items-center justify-center shadow-2xl backdrop-blur-md transform scale-75 group-hover:scale-100 transition-all duration-300 border border-white/50 dark:border-slate-700">
                                                    <i class="fas fa-search-plus text-lg"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                            <div>
                                                <!-- Meta Row (Date & Views) -->
                                                <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-calendar-alt text-[#7a1a40] dark:text-rose-400"></i>
                                                        <span>{{ $item->formatted_date ?: ($item->published_at ? $item->published_at->format('j M Y') : '') }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="far fa-eye text-slate-400"></i>
                                                        <span x-text="items[{{ $idx }}] ? Number(items[{{ $idx }}].views).toLocaleString() : '{{ number_format($item->views_count) }}'">{{ number_format($item->views_count) }}</span>
                                                        <span>ครั้ง</span>
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white line-clamp-2 leading-snug group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors mb-2 cursor-pointer"
                                                    @click="openModal({{ $idx }})">
                                                    {{ $item->title }}
                                                </h3>

                                                <!-- Budget Box (if available) -->
                                                @if($item->budget)
                                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs font-bold mb-2">
                                                        <i class="fas fa-coins text-[11px] text-amber-600 dark:text-amber-400"></i>
                                                        <span>งบประมาณ: {{ $item->budget }}</span>
                                                    </div>
                                                @endif

                                                <!-- Deadline (if available) -->
                                                @if($item->deadline_at)
                                                    <div class="text-[11.5px] font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1 mb-2">
                                                        <i class="far fa-clock text-[11px]"></i>
                                                        <span>สิ้นสุดรับข้อเสนอ: {{ $item->formatted_deadline }}</span>
                                                    </div>
                                                @endif

                                                <!-- Excerpt / Description -->
                                                @if($item->description)
                                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm line-clamp-2 leading-relaxed mb-3">
                                                        {{ $item->description }}
                                                    </p>
                                                @endif

                                                <!-- External Link / คลิกลิงค์ (ตัวอย่างตาม trattc.ac.th/post3) -->
                                                @if(!empty($item->link_url))
                                                    <div class="mt-2.5 mb-2.5 p-2.5 rounded-xl bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/60 flex items-center justify-between gap-2 shadow-2xs">
                                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                                            <i class="fas fa-link text-[#7a1a40] dark:text-amber-400 text-[11px]"></i>
                                                            <span>คลิกลิงค์ :</span>
                                                        </span>
                                                        <a href="{{ $item->link_url }}"
                                                           target="_blank"
                                                           rel="noopener noreferrer"
                                                           @click.stop
                                                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold transition-all shadow-xs hover:scale-105 cursor-pointer"
                                                           title="เปิดคลิกลิงค์เอกสาร/รายละเอียด">
                                                            <i class="fab fa-google-drive text-[11px] text-emerald-300"></i>
                                                            <span>คลิกลิงค์</span>
                                                            <i class="fas fa-external-link-alt text-[9px]"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                <!-- Attached Files Direct Download Chips (PDF, Word, Excel, PPT, ZIP) -->
                                                @if(!empty($item->attachment_files))
                                                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                        <div class="text-[11px] font-semibold text-slate-400 mb-1.5 flex items-center justify-between">
                                                            <span class="flex items-center gap-1"><i class="fas fa-paperclip text-[10px]"></i> เอกสารแนบ ({{ count($item->attachment_files) }})</span>
                                                            <span class="text-[10px] text-slate-400">คลิกเพื่อดาวน์โหลด</span>
                                                        </div>
                                                        <div class="flex flex-wrap gap-1.5">
                                                            @foreach(array_slice($item->attachment_files, 0, 3) as $file)
                                                                <a href="{{ $file['url'] }}"
                                                                   download
                                                                   @click.stop
                                                                   class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[11px] font-bold border transition-all hover:scale-105 {{ $file['bg_color'] }}"
                                                                   title="{{ $file['name'] }} ({{ $file['size'] }})">
                                                                    <i class="{{ $file['icon'] }} {{ $file['color'] }} text-[11px]"></i>
                                                                    <span class="truncate max-w-[90px]">{{ $file['ext'] }}</span>
                                                                    @if($file['size'])
                                                                        <span class="text-[9.5px] opacity-75">({{ $file['size'] }})</span>
                                                                    @endif
                                                                </a>
                                                            @endforeach

                                                            @if(count($item->attachment_files) > 3)
                                                                <button type="button"
                                                                        @click.stop="openModal({{ $idx }})"
                                                                        class="inline-flex items-center px-1.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200">
                                                                    +{{ count($item->attachment_files) - 3 }} ไฟล์
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Footer Button -->
                                            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                                <button @click="openModal({{ $idx }})"
                                                        type="button"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                                    <span>ดูรายละเอียด / เอกสาร</span>
                                                    <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                                </button>

                                                <div class="flex items-center gap-1 text-[11px] text-slate-400 truncate min-w-0 font-medium"
                                                     title="{{ $item->uploader_name ?: 'งานพัสดุ' }}">
                                                    <i class="far fa-user text-slate-400 shrink-0 text-xs"></i>
                                                    <span class="truncate">{{ $item->uploader_name ?: 'งานพัสดุ' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Slider Indicators / Pagination Dots -->
                <div class="flex items-center justify-center gap-2 mt-6" x-show="!showAll && maxSlide > 0">
                    <template x-for="s in (maxSlide + 1)" :key="s">
                        <button @click="currentSlide = s - 1"
                                type="button"
                                :aria-label="'สไลด์ ' + s"
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="currentSlide === (s - 1) ? 'w-8 bg-[#7a1a40] dark:bg-rose-500' : 'w-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300'"></button>
                    </template>
                </div>

                <!-- Fullscreen Lightbox Modal for Procurement Details & Attachments -->
                <div x-show="modalOpen"
                     class="fixed inset-0 z-100 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                     style="display: none;"
                     @keydown.escape.window="modalOpen = false">

                    <!-- Backdrop -->
                    <div x-show="modalOpen"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-950/80 backdrop-blur-md"
                         @click="modalOpen = false"></div>

                    <!-- Modal Dialog -->
                    <div x-show="modalOpen"
                         @click.stop
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                         class="relative w-full max-w-3xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-200 dark:border-slate-700 max-h-[92vh] flex flex-col">

                        <!-- Header -->
                        <div class="flex items-center justify-between p-4 sm:p-5 border-b border-slate-100 dark:border-slate-700 bg-[#7a1a40] text-white shrink-0">
                            <div class="flex items-center gap-2.5 min-w-0 pr-3">
                                <i class="fas fa-file-contract text-amber-300 text-lg shrink-0"></i>
                                <h3 class="font-bold text-white text-base sm:text-lg truncate" x-text="selectedItem ? selectedItem.title : ''"></h3>
                            </div>
                            <button @click="modalOpen = false"
                                    type="button"
                                    class="p-2 text-white/80 hover:text-white rounded-full hover:bg-white/10 transition-colors cursor-pointer shrink-0">
                                <i class="fas fa-times text-base"></i>
                            </button>
                        </div>

                        <!-- Modal Body (Scrollable) -->
                        <div class="p-5 sm:p-6 overflow-y-auto space-y-5 flex-1">
                            <!-- Image Cover -->
                            <template x-if="selectedItem && selectedItem.image">
                                <div class="w-full flex justify-center bg-slate-100/60 dark:bg-slate-900/40 rounded-2xl p-3 border border-slate-200 dark:border-slate-700">
                                    <img :src="selectedItem.image"
                                         :alt="selectedItem.title"
                                         class="max-h-[40vh] w-auto rounded-xl shadow-md object-contain" />
                                </div>
                            </template>

                            <!-- Information Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700 text-xs sm:text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">ประเภทประกาศ:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedItem ? selectedItem.procurement_type : ''"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">สถานะ:</span>
                                    <span class="px-2 py-0.5 rounded-md font-bold text-xs" :class="selectedItem ? selectedItem.status_color : ''" x-text="selectedItem ? selectedItem.status_label : ''"></span>
                                </div>
                                <template x-if="selectedItem && selectedItem.budget">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 min-w-[90px]">วงเงินงบประมาณ:</span>
                                        <span class="font-bold text-amber-600 dark:text-amber-400" x-text="selectedItem.budget"></span>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">วันที่ประกาศ:</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300" x-text="selectedItem ? selectedItem.date : ''"></span>
                                </div>
                                <template x-if="selectedItem && selectedItem.deadline">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 min-w-[90px]">สิ้นสุดรับข้อเสนอ:</span>
                                        <span class="font-semibold text-rose-600 dark:text-rose-400" x-text="selectedItem.deadline"></span>
                                    </div>
                                </template>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 min-w-[90px]">ผู้ประกาศ / ยอดชม:</span>
                                    <span class="text-slate-600 dark:text-slate-300" x-text="(selectedItem ? selectedItem.uploader : '') + ' • ' + (selectedItem ? Number(selectedItem.views).toLocaleString() : '') + ' ครั้ง'"></span>
                                </div>
                            </div>

                            <!-- Description -->
                            <template x-if="selectedItem && selectedItem.description">
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-align-left text-[#7a1a40]"></i>
                                        <span>รายละเอียดประกาศ</span>
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line leading-relaxed p-3.5 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-100 dark:border-slate-700"
                                       x-text="selectedItem.description"></p>
                                </div>
                            </template>

                            <!-- External Link / คลิกลิงค์ (เช่น Google Drive หรือ URL รายละเอียดตาม trattc.ac.th/post3) -->
                            <template x-if="selectedItem && selectedItem.link_url">
                                <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border border-blue-200 dark:border-blue-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md">
                                            <i class="fab fa-google-drive text-lg text-emerald-300"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-blue-900 dark:text-blue-200 flex items-center gap-1.5">
                                                <span>คลิกลิงค์ : เอกสารและรายละเอียดประกาศจัดซื้อจัดจ้าง</span>
                                            </div>
                                            <div class="text-[11px] text-blue-600/90 dark:text-blue-300/90 truncate max-w-xs sm:max-w-md mt-0.5" x-text="selectedItem.link_url"></div>
                                        </div>
                                    </div>
                                    <a :href="selectedItem.link_url"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold shadow-md transition-all hover:scale-105 shrink-0 cursor-pointer">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                        <span>คลิกลิงค์เปิดดูเอกสาร</span>
                                    </a>
                                </div>
                            </template>

                            <!-- Attachments Download Section (PDF, Word, Excel, PPT, ZIP) -->
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-2 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-cloud-arrow-down text-[#7a1a40] dark:text-amber-400"></i>
                                        <span>เอกสารแนบและแบบฟอร์มดาวน์โหลด</span>
                                    </span>
                                    <span class="text-xs font-normal text-slate-400"
                                          x-text="(selectedItem && selectedItem.attachments ? selectedItem.attachments.length : 0) + ' ไฟล์'"></span>
                                </h4>

                                <template x-if="selectedItem && selectedItem.attachments && selectedItem.attachments.length > 0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <template x-for="(file, fIdx) in selectedItem.attachments" :key="fIdx">
                                            <a :href="file.url"
                                               download
                                               class="group/file flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-[#7a1a40] dark:hover:border-rose-400 bg-white dark:bg-slate-900 hover:bg-rose-50/30 dark:hover:bg-slate-800 transition-all duration-200 shadow-xs">
                                                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 border"
                                                         :class="file.bg_color">
                                                        <i :class="file.icon + ' ' + file.color + ' text-base'"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate group-hover/file:text-[#7a1a40] transition-colors"
                                                           x-text="file.name"></p>
                                                        <p class="text-[10.5px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                                            <span class="font-semibold uppercase" x-text="file.ext"></span>
                                                            <template x-if="file.size">
                                                                <span x-text="'• ' + file.size"></span>
                                                            </template>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="shrink-0">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#7a1a40] text-white text-[11px] font-bold shadow-xs group-hover/file:bg-[#661434] transition-colors">
                                                        <i class="fas fa-download text-[10px]"></i>
                                                        <span class="hidden sm:inline">ดาวน์โหลด</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!selectedItem || !selectedItem.attachments || selectedItem.attachments.length === 0">
                                    <div class="p-4 text-center text-xs text-slate-400 bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                                        ไม่มีเอกสารแนบในประกาศนี้
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-4 sm:p-5 flex items-center justify-between border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 shrink-0">
                            <span class="text-xs text-slate-400">วิทยาลัยเทคนิคตราด งานพัสดุ</span>
                            <div class="flex items-center gap-2">
                                <template x-if="selectedItem && selectedItem.link_url">
                                    <a :href="selectedItem.link_url"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                                        <i class="fab fa-google-drive text-[11px] text-emerald-300"></i>
                                        <span>คลิกลิงค์</span>
                                        <i class="fas fa-external-link-alt text-[9.5px]"></i>
                                    </a>
                                </template>
                                <button @click="modalOpen = false"
                                        type="button"
                                        class="px-4 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-colors cursor-pointer">
                                    ปิดหน้าต่าง
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- Section: ข่าวสารล่าสุด (Latest News Section with white background container matching director/menu section) --}}
    <div class="mx-auto my-4 p-2 max-w-7xl">
        <div class="bg-white rounded-xl dark:bg-slate-900 p-4 sm:p-6 shadow-2xl">
            <section id="latest-news-section" class="w-full">
                <!-- Header matching the solid maroon bar of Director & Featured Post -->
                <div class="bg-[#7a1a40] text-white px-5 py-3.5 rounded-xl flex items-center justify-between mb-6 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-bullhorn text-amber-300 text-base"></i>
                        <span class="font-bold text-base tracking-wide">ข่าวสารล่าสุด</span>
                    </div>
                    <a href="#latest-posts" class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-white/90 hover:text-amber-300 transition-colors">
                        <span>ดูทั้งหมด</span>
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>

                @php
                    $latestHighlightPosts = \App\Models\Post::post()->published()->with(['categories', 'author'])->latest('published_at')->take(3)->get();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($latestHighlightPosts as $post)
                        <div class="group flex flex-col bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                            <!-- Image Section -->
                            <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @php $cardImages = $post->feature_images; @endphp
                                @if(count($cardImages) > 0)
                                    <img src="{{ asset('storage/' . $cardImages[0]) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i class="fas fa-newspaper text-3xl"></i>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>

                                <!-- Category Badge -->
                                @if ($post->categories->isNotEmpty())
                                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs">
                                        {{ $post->categories->first()->name }}
                                    </span>
                                @endif

                                @if (count($cardImages) > 1)
                                    <span class="absolute top-3 right-3 px-2 py-0.5 bg-black/60 backdrop-blur-md text-white text-[11px] font-bold rounded-md border border-white/10 flex items-center gap-1 shadow-xs">
                                        <i class="fas fa-camera text-amber-300 text-[10px]"></i>
                                        <span>{{ count($cardImages) }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Content Section -->
                            <div class="p-5 flex flex-col flex-1 justify-between bg-white dark:bg-slate-900">
                                <div>
                                    <!-- Meta Row -->
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2 font-medium">
                                        <i class="far fa-calendar-alt text-[#7a1a40]"></i>
                                        <span>{{ $post->published_at ? $post->published_at->format('j M Y') : '' }}</span>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white line-clamp-2 leading-snug group-hover:text-[#7a1a40] transition-colors mb-2">
                                        <a wire:navigate href="{{ route('posts.show', $post) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    <!-- Excerpt -->
                                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm line-clamp-2 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                </div>

                                <!-- Read More Footer -->
                                <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                    <a wire:navigate href="{{ route('posts.show', $post) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5">
                                        <span>อ่านรายละเอียด</span>
                                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8 text-slate-400 text-sm">
                            ยังไม่มีข้อมูลข่าวสารล่าสุด
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    {{-- ค้นหาประกาศและข่าวประชาสัมพันธ์ --}}
    <div class="reveal w-full bg-[#7a1a40] py-10 px-4 shadow-inner mb-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-white text-base md:text-lg font-bold mb-4 tracking-wide">
                ค้นหาประกาศและข่าวประชาสัมพันธ์
            </h2>
            <div class="relative max-w-2xl mx-auto">
                <div class="relative flex items-center bg-white dark:bg-slate-800 rounded-full p-1.5 w-full shadow-lg border border-slate-100 dark:border-slate-700">
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           placeholder="ป้อนคำค้นหา เช่น ประกวดราคา, สมัครงาน..."
                           class="w-full pl-6 pr-28 py-3 text-slate-800 dark:text-slate-100 bg-transparent placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none text-sm md:text-base border-none">
                    <button type="button"
                            class="absolute right-2 px-6 py-2.5 bg-[#f59e0b] hover:bg-[#d97706] text-[#07162c] text-sm font-bold rounded-full transition-colors flex items-center gap-1.5 shadow-sm">
                        <i class="fas fa-search text-xs"></i> ค้นหา
                    </button>
                </div>

                {{-- Search Results Dropdown --}}
                @if(!empty($search) && strlen($search) >= 2)
                    <div x-data="{ showResults: true }" @click.away="showResults = false" x-show="showResults"
                         class="absolute left-0 right-0 mt-3 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-30 text-left">

                        <!-- Categories (หมวดหมู่) -->
                        @if($this->searchResults['categories']->isNotEmpty())
                            <div class="p-2.5 bg-slate-50 dark:bg-slate-700/50 text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700">
                                หมวดหมู่ที่เกี่ยวข้อง
                            </div>
                            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($this->searchResults['categories'] as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center gap-3 p-3.5 hover:bg-amber-50 dark:hover:bg-slate-700/60 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                            <i class="fas fa-tag text-sm"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $cat->name }}</span>
                                            @if($cat->description)
                                                <span class="text-[11px] text-slate-400 dark:text-slate-400 line-clamp-1">{{ $cat->description }}</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Posts (บทความ) -->
                        <div class="p-2.5 bg-slate-50 dark:bg-slate-700/50 text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest border-y border-slate-100 dark:border-slate-700">
                            ประกาศและข่าวประชาสัมพันธ์
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($this->searchResults['posts'] as $res)
                                <a wire:navigate href="{{ route('posts.show', $res->slug) }}" class="flex items-center gap-4 p-3.5 hover:bg-red-50 dark:hover:bg-slate-700/60 transition-colors">
                                    <div class="shrink-0 w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 overflow-hidden border border-slate-200/50 dark:border-slate-600 shadow-xs">
                                        @if($res->feature_images && count($res->feature_images) > 0)
                                            <img src="{{ asset('storage/' . $res->feature_images[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                                <x-heroicon-o-photo class="w-5 h-5" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100 line-clamp-1 hover:text-[#801810] dark:hover:text-rose-400">{{ $res->title }}</span>
                                        <span class="text-[11px] text-slate-400 dark:text-slate-400 mt-0.5 flex items-center gap-1.5">
                                            <i class="far fa-calendar-alt"></i> {{ $res->published_at ? $res->published_at->format('j M Y') : '' }}
                                            @if($res->categories->isNotEmpty())
                                                <span class="px-1.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-md text-[9px] font-semibold border border-blue-100 dark:border-blue-900/60">
                                                    {{ $res->categories->first()->name }}
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </a>
                            @empty
                                @if($this->searchResults['categories']->isEmpty())
                                    <div class="p-8 text-center text-sm text-slate-500 dark:text-slate-400 italic flex flex-col items-center justify-center gap-2">
                                        <i class="fas fa-search text-slate-300 dark:text-slate-600 text-2xl animate-bounce"></i>
                                        <span>ไม่พบข้อมูลที่ตรงกับ "{{ $search }}"</span>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Inner Content Container (Restricted Width) --}}
    <div class="mx-auto my-2 p-2 max-w-7xl">
        <div class="bg-white rounded-xl dark:bg-slate-900 p-2 shadow-2xl">
            {{-- Slider Hero Section --}}
            {{-- <section id="latest-posts" class=" max-w-full mx-auto p-2 mt-4 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h2
                        class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-3">
                        <span class="w-2 h-8 bg-linear-to-b from-amber-500 to-amber-700 rounded-full shadow-sm"></span>
                        ข่าวสารประชาสัมพันธ์ <span class="text-amber-600">วิทยาลัยเทคนิคตราด</span>
                    </h2>
                </div>

                <div x-data="{ activeSlide: 0, slides: 3, timer: null }" x-init="timer = setInterval(() => activeSlide = (activeSlide + 1) % slides, 6000)"
                    class="relative rounded-2xl md:rounded-3xl overflow-hidden shadow-[0_20px_50px_-12px_rgba(0,0,0,0.15)] group bg-slate-900 w-full h-87.5 sm:h-100 md:h-112.5 lg:h-125">

                    <div class="flex transition-transform duration-1000 ease-out h-full"
                        :style="`transform: translateX(-${activeSlide * 100}%)`">

                        <!-- Slide 1 -->
                        <div class="min-w-full h-full relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1680725779155-456faadefa26"
                                class="w-full h-full object-cover opacity-90 transform transition-transform duration-10000 ease-linear"
                                :class="activeSlide === 0 ? 'scale-110' : 'scale-100'" alt="News 1">
                            <div
                                class="absolute inset-0 bg-linear-to-t from-slate-900/95 via-slate-900/40 to-transparent">
                            </div>
                            <div
                                class="absolute inset-0 bg-linear-to-r from-slate-900/60 via-slate-900/10 to-transparent">
                            </div>
                            <div class="absolute bottom-0 left-0 p-6 pb-16 sm:p-10 md:p-16 w-full max-w-5xl transform transition-all duration-1000 delay-100"
                                :class="activeSlide === 0 ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 md:px-4 md:py-2 bg-amber-500/90 backdrop-blur-md text-white text-[11px] md:text-sm font-bold tracking-wider rounded-full mb-3 md:mb-5 shadow-lg border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white mr-2 animate-pulse"></span>
                                    ประกาศสำคัญ
                                </span>
                                <h2
                                    class="text-white text-lg sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow-xl line-clamp-2 md:line-clamp-3 hover:text-amber-100 transition-colors cursor-pointer">
                                    ๑. ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี และพลังของแผ่นดิน
                                    เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม ๒๕๖๗
                                </h2>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="min-w-full h-full relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1759675795062-a657fcb278b1"
                                class="w-full h-full object-cover opacity-90 transform transition-transform duration-10000 ease-linear"
                                :class="activeSlide === 1 ? 'scale-110' : 'scale-100'" alt="News 2">
                            <div
                                class="absolute inset-0 bg-linear-to-t from-slate-900/95 via-slate-900/40 to-transparent">
                            </div>
                            <div
                                class="absolute inset-0 bg-linear-to-r from-slate-900/60 via-slate-900/10 to-transparent">
                            </div>
                            <div class="absolute bottom-0 left-0 p-6 pb-16 sm:p-10 md:p-16 w-full max-w-5xl transform transition-all duration-1000 delay-100"
                                :class="activeSlide === 1 ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 md:px-4 md:py-2 bg-blue-500/90 backdrop-blur-md text-white text-[11px] md:text-sm font-bold tracking-wider rounded-full mb-3 md:mb-5 shadow-lg border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white mr-2 animate-pulse"></span>
                                    กิจกรรมวิทยาลัย
                                </span>
                                <h2
                                    class="text-white text-lg sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow-xl line-clamp-2 md:line-clamp-3 hover:text-blue-100 transition-colors cursor-pointer">
                                    ๒. ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี และพลังของแผ่นดิน
                                    เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม ๒๕๖๗
                                </h2>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="min-w-full h-full relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1760311174612-4cc057f93e2c"
                                class="w-full h-full object-cover opacity-90 transform transition-transform duration-10000 ease-linear"
                                :class="activeSlide === 2 ? 'scale-110' : 'scale-100'" alt="News 3">
                            <div
                                class="absolute inset-0 bg-linear-to-t from-slate-900/95 via-slate-900/40 to-transparent">
                            </div>
                            <div
                                class="absolute inset-0 bg-linear-to-r from-slate-900/60 via-slate-900/10 to-transparent">
                            </div>
                            <div class="absolute bottom-0 left-0 p-6 pb-16 sm:p-10 md:p-16 w-full max-w-5xl transform transition-all duration-1000 delay-100"
                                :class="activeSlide === 2 ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'">
                                <span
                                    class="inline-flex items-center px-3 py-1.5 md:px-4 md:py-2 bg-green-500/90 backdrop-blur-md text-white text-[11px] md:text-sm font-bold tracking-wider rounded-full mb-3 md:mb-5 shadow-lg border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white mr-2 animate-pulse"></span>
                                    ข่าวสารทั่วไป
                                </span>
                                <h2
                                    class="text-white text-lg sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow-xl line-clamp-2 md:line-clamp-3 hover:text-green-100 transition-colors cursor-pointer">
                                    ๓. ถวายสัตย์ปฏิญาณเพื่อเป็นข้าราชการที่ดี และพลังของแผ่นดิน
                                    เนื่องในโอกาสพระราชพิธีมหามงคลเฉลิมพระชนมพรรษา ๖ รอบ ๒๘ กรกฎาคม ๒๕๖๗
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <button @click="activeSlide = (activeSlide - 1 + slides) % slides; clearInterval(timer)"
                        class="absolute top-1/2 left-4 md:left-6 -translate-y-1/2 w-10 h-10 md:w-14 md:h-14 flex items-center justify-center bg-white/10 hover:bg-white/30 backdrop-blur-xl border border-white/20 rounded-full text-white transition-all duration-300 opacity-0 group-hover:opacity-100 hover:scale-110 shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
                        <x-heroicon-o-chevron-left class="w-5 h-5 md:w-7 md:h-7 stroke-2" />
                    </button>

                    <button @click="activeSlide = (activeSlide + 1) % slides; clearInterval(timer)"
                        class="absolute top-1/2 right-4 md:right-6 -translate-y-1/2 w-10 h-10 md:w-14 md:h-14 flex items-center justify-center bg-white/10 hover:bg-white/30 backdrop-blur-xl border border-white/20 rounded-full text-white transition-all duration-300 opacity-0 group-hover:opacity-100 hover:scale-110 shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
                        <x-heroicon-o-chevron-right class="w-5 h-5 md:w-7 md:h-7 stroke-2" />
                    </button>

                    <!-- Indicators -->
                    <div
                        class="absolute bottom-5 md:bottom-8 left-1/2 -translate-x-1/2 flex space-x-2.5 md:space-x-3 bg-black/20 backdrop-blur-md px-4 py-2.5 md:py-3 rounded-full border border-white/10">
                        <template x-for="i in slides" :key="i">
                            <button @click="activeSlide = i - 1; clearInterval(timer)"
                                class="h-1.5 md:h-2 rounded-full transition-all duration-500 ease-out shadow-sm relative overflow-hidden focus:outline-none"
                                :class="activeSlide === i - 1 ? 'w-8 md:w-12 bg-amber-400' :
                                    'w-2 md:w-2.5 bg-white/40 hover:bg-white/80'">
                            </button>
                        </template>
                    </div>
                </div>
            </section> --}}

            {{-- Director's Message & Quick Links --}}
            {{-- <section class=" max-w-full mx-auto my-4 px-4">
                <div
                    class="bg-linear-to-br from-white to-gray-50 dark:bg-none dark:bg-slate-950  border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-4xl p-8 md:p-12 relative overflow-hidden">
                    <!-- Decorative Circle -->
                    <div
                        class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-amber-50/50 blur-3xl pointer-events-none">
                    </div>

                    <div class="grid lg:grid-cols-12 gap-12 items-center relative z-10">
                        <!-- Text Content -->
                        <div class="lg:col-span-7 space-y-8">
                            <div>
                                <h2 class="text-[#801810] text-3xl md:text-5xl font-extrabold leading-tight mb-6">
                                    สาส์นจากผู้อำนวยการ
                                </h2>
                                <p class="text-slate-600 dark:text-white text-lg leading-relaxed">
                                    ขอขอบคุณผู้ปกครองนักเรียน นักศึกษาที่ให้ความไว้วางใจวิทยาลัย
                                    ที่ให้ความร่วมมืออย่างดีในกิจกรรมต่างๆ ของวิทยาลัยฯ
                                    ขอขอบใจนักเรียนนักศึกษาทุกคนที่ปฏิบัติตนอยู่ในระเบียบของวิทยาลัย อยู่ในโอวาทของทุกคน
                                    เป็นนักเรียนนักศึกษาที่วิทยาลัยภาคภูมิใจ
                                </p>
                            </div>

                            <div class="p-6 bg-amber-50/70 backdrop-blur-sm border-l-4 border-[#801810] rounded-r-2xl">
                                <h3 class="text-xl md:text-2xl font-bold text-[#801810] leading-snug italic">
                                    "เทคนิคตราดโฉมใหม่ ใส่ใจบุตรหลาน<br>จัดการศึกษา เพื่อการมีงานทำ"
                                </h3>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 pt-4">
                                <a href="{{ route('home') }}"
                                    class="inline-flex items-center justify-center px-8 py-3.5 bg-linear-to-r from-[#801810] to-red-700 text-white font-bold rounded-full shadow-lg hover:shadow-[0_8px_20px_rgba(128,24,16,0.3)] hover:-translate-y-0.5 transition-all duration-300">
                                    อ่านข้อมูลเพิ่มเติม
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-5 h-5 ml-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                                <a href="javascript:void(0)"
                                    class="text-slate-500 hover:text-slate-800 text-[15px] font-bold underline transition-colors px-4">
                                    คู่มือนักศึกษา
                                </a>
                            </div>
                        </div>

                        <!-- Image & Quick Links -->
                        <div class="lg:col-span-5 flex flex-col gap-8">
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-2xl group border-[6px] border-white">
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-[#801810]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10">
                                </div>
                                <img src='images/child2568.jpg'
                                    class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700"
                                    alt="ผู้อำนวยการ" />
                            </div>

                            <!-- Quick Links Grid -->
                            <div class="grid grid-cols-3 gap-3">
                                <a href="https://rms.trattc.ac.th/?p=login" target="_blank"
                                    class="group relative bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-center">
                                    <img src="images/rms.png"
                                        class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                                        alt="RMS" />
                                </a>
                                <a href="https://std2018.vec.go.th/web/" target="_blank"
                                    class="group relative bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-center">
                                    <img src="images/std02.png"
                                        class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                                        alt="STD02" />
                                </a>
                                <a href="https://admission.vec.go.th/web/Login.htm?mode=index" target="_blank"
                                    class="group relative bg-white border border-gray-100 rounded-2xl p-3 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex items-center justify-center">
                                    <img src="images/std_register.png"
                                        class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300"
                                        alt="Admission" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}

            {{-- Featured Post --}}
            @php
                // $featuredPost = Post::with('categories') // ดึงหมวดหมู่มาด้วย (Eager Loading)
                //     ->post() // เอาเฉพาะบทความ (Local Scope)
                //     ->published() // เอาเฉพาะที่เผยแพร่แล้ว (Local Scope)
                //     ->orderBy('published_at', 'desc') // เรียงจากใหม่ไปเก่า
                //     ->first(); // ขอดึงแค่ "อันดับแรกสุด" อันเดียวเท่านั้น

                $featuredPost = $this->featuredPost;
            @endphp

            {{-- 1. Top Section: ผู้อำนวยการวิทยาลัย (Top-Left - Narrow) & บทความแนะนำ (Top-Right - Wide) --}}
            <div class="w-full px-2 sm:px-3 lg:px-2 my-4">
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 items-stretch">

                    <!-- Left: ผู้อำนวยการวิทยาลัย (Narrow: lg:w-[285px] matching Menu below) -->
                    <div class="w-full lg:w-[285px] shrink-0 flex flex-col">
                        <div class="w-full h-full bg-white dark:bg-slate-800 rounded-2xl shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col justify-between">
                            <div class="bg-[#7a1a40] text-white px-4 py-3.5 flex items-center gap-2.5">
                                <i class="fas fa-user-tie text-amber-300 text-base"></i>
                                <span class="font-bold text-base tracking-wide">ผู้อำนวยการวิทยาลัย</span>
                            </div>

                            @php
                                $topDepartment = \App\Models\Department::whereNull('parent_id')
                                     ->orderBy('sort_order', 'asc')
                                     ->first();

                                $director = $topDepartment
                                    ? $topDepartment->staff()->where('is_active', true)->first()
                                    : null;
                            @endphp

                            <div class="p-6 flex flex-col items-center text-center flex-1 justify-between">
                                <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-tr from-amber-400 to-[#7a1a40] shadow-md mb-3 relative shrink-0">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-slate-100 border-2 border-white">
                                        @if ($director && $director->hasMedia('profile'))
                                            <img src="{{ $director->getFirstMediaUrl('profile') }}" alt="ผู้อำนวยการ"
                                                 class="w-full h-full object-cover object-top">
                                        @else
                                            <img src="{{ asset('images/child2568.jpg') }}" alt="ผู้อำนวยการ"
                                                 class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                </div>

                                <h3 class="text-base font-extrabold text-slate-800 dark:text-white mb-1">
                                    {{ $director ? $director->full_name : 'นายชาญชัย แสนจันทร์' }}
                                </h3>

                                <p class="text-gray-500 dark:text-slate-400 text-xs mb-3">
                                    {{ $director && $director->position ? $director->position->name : 'ผู้อำนวยการวิทยาลัยเทคนิคตราด' }}
                                </p>

                                <div class="p-3 bg-rose-50/60 dark:bg-rose-950/40 rounded-xl border border-rose-100/60 dark:border-rose-900/50 text-xs font-semibold text-[#7a1a40] dark:text-rose-300 mb-4 italic w-full">
                                    "เทคนิคตราดโฉมใหม่ ใส่ใจบุตรหลาน จัดการศึกษา เพื่อการมีงานทำ"
                                </div>

                                <a href="{{ route('executive.board') }}"
                                   class="w-full inline-flex items-center justify-center gap-2 bg-[#7a1a40] text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-[#661434] transition-colors shadow-xs">
                                    <span>คณะผู้บริหารสถานศึกษา</span>
                                    <x-heroicon-s-chevron-right class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: บทความแนะนำ (Expanded: flex-1 matching Facebook below) -->
                    <div class="flex-1 min-w-0 w-full flex flex-col">
                        @if ($featuredPost)
                            <div class="w-full h-full bg-white dark:bg-slate-800 rounded-2xl shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col justify-between group">
                                <div class="bg-[#7a1a40] text-white px-5 py-4 flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fas fa-newspaper text-amber-300"></i>
                                        <span class="font-bold text-base tracking-wide">บทความแนะนำ</span>
                                    </div>
                                    @if ($featuredPost->categories->isNotEmpty())
                                        <span class="px-2.5 py-0.5 bg-white/20 backdrop-blur-xs text-white text-xs font-bold rounded-lg border border-white/20">
                                            {{ $featuredPost->categories->first()->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="md:flex flex-1">
                                    <!-- Image -->
                                    <div class="md:w-5/12 relative overflow-hidden min-h-[220px] md:min-h-full bg-slate-100 dark:bg-slate-700">
                                        @php $featImages = $featuredPost->feature_images; @endphp
                                        @if(count($featImages) > 0)
                                            <img src="{{ asset('storage/' . $featImages[0]) }}"
                                                alt="{{ $featuredPost->title }}"
                                                class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                        @endif
                                        <div class="absolute inset-0 bg-linear-to-t from-slate-950/40 via-transparent to-transparent opacity-60"></div>

                                        @if(count($featImages) > 1)
                                            <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/10 shadow-xs">
                                                <x-heroicon-s-camera class="w-3.5 h-3.5 text-amber-400" />
                                                <span>{{ count($featImages) }} ภาพ</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="md:w-7/12 p-6 md:p-8 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-400 mb-2">
                                                <x-heroicon-o-calendar-days class="w-4 h-4 text-[#7a1a40] dark:text-rose-400" />
                                                <span>{{ $featuredPost->published_at ? $featuredPost->published_at->format('j M Y') : '' }}</span>
                                            </div>

                                            <h3 class="text-lg md:text-xl font-extrabold text-slate-800 dark:text-white mb-2 leading-snug group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors">
                                                <a wire:navigate href="{{ route('posts.show', $featuredPost) }}">
                                                    {{ $featuredPost->title }}
                                                </a>
                                            </h3>

                                            <p class="text-slate-500 dark:text-slate-300 text-xs sm:text-sm leading-relaxed line-clamp-3 mb-4">
                                                {{ $featuredPost->excerpt }}
                                            </p>
                                        </div>

                                        <div class="pt-2">
                                            <a wire:navigate href="{{ route('posts.show', $featuredPost) }}"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#7a1a40] hover:bg-[#661434] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5">
                                                <span>อ่านบทความแนะนำ</span>
                                                <x-heroicon-s-chevron-right class="w-4 h-4" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. Bottom Section: เมนูหลักวิทยาลัย (Left - Narrow & Compact) & Facebook วิทยาลัย (Right - Wide) --}}
            <div class="w-full px-2 sm:px-3 lg:px-2 mb-8">
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 items-start">

                    <!-- Left: Main Menu Links Box (lg:w-[285px] with larger readable font) -->
                    <div class="w-full lg:w-[285px] shrink-0 bg-white dark:bg-slate-800 rounded-2xl shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-gray-100 dark:border-slate-700 overflow-hidden">
                        <div class="bg-[#7a1a40] text-white px-4 py-3.5 flex items-center gap-2.5">
                            <i class="fas fa-th-large text-amber-300 text-base"></i>
                            <span class="font-bold text-base tracking-wide">เมนูหลักวิทยาลัย</span>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-slate-700">
                            @php
                                $menus = [
                                    ['icon' => 'far fa-clock', 'title' => 'ประวัติวิทยาลัย', 'url' => '/history-us'],
                                    ['icon' => 'far fa-eye', 'title' => 'วิสัยทัศน์ / พันธกิจ', 'url' => '/vision'],
                                    ['icon' => 'fas fa-sitemap', 'title' => 'โครงสร้างการบริหาร', 'url' => route('executive.board')],
                                    ['icon' => 'fas fa-users', 'title' => 'ข้อมูลบุคลากร', 'url' => 'https://rms.trattc.ac.th/pms_graph.php'],
                                    ['icon' => 'fas fa-book-open', 'title' => 'หลักสูตรการเรียนการสอน', 'url' => 'https://bsq.vec.go.th'],
                                    ['icon' => 'fas fa-graduation-cap', 'title' => 'ระบบรับสมัครนักเรียน-นักศึกษา', 'url' => 'https://admission.vec.go.th'],
                                    ['icon' => 'fas fa-laptop-code', 'title' => 'ระบบ RMS วิทยาลัย', 'url' => 'https://rms.trattc.ac.th/?p=login'],
                                    ['icon' => 'fas fa-map-marker-alt', 'title' => 'ที่ตั้งและการติดต่อ', 'url' => 'tel:039511141'],
                                    ['icon' => 'fas fa-book-open', 'title' => 'หลักสูตรประกาศนียบัตรวิชาชีพ', 'url' => 'https://shorturl.at/Kx6rQ'],
                                    ['icon' => 'fas fa-book-open', 'title' => 'หลักสูตรประกาศนียบัตรวิชาชีพชั้นสูง', 'url' => 'https://shorturl.at/GLAMJ'],

                                ];
                            @endphp

                            @foreach ($menus as $menu)
                                @php
                                    $isExternalMenu = \Illuminate\Support\Str::startsWith($menu['url'], ['http://', 'https://']) && !\Illuminate\Support\Str::contains($menu['url'], request()->getHost());
                                @endphp
                                <a href="{{ $menu['url'] }}"
                                   @if($isExternalMenu) target="_blank" rel="noopener noreferrer" @endif
                                   class="flex items-center justify-between px-4 py-3 group hover:bg-rose-50/40 dark:hover:bg-slate-700/60 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <i class="{{ $menu['icon'] }} text-[#7a1a40] dark:text-rose-400 w-4 text-center text-base transition-colors group-hover:text-[#5c132f] dark:group-hover:text-rose-300 shrink-0"></i>
                                        <span class="text-slate-800 dark:text-slate-200 text-[15px] font-bold group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 transition-colors truncate">
                                            {{ $menu['title'] }}
                                        </span>
                                    </div>
                                    @if($isExternalMenu)
                                        <i class="fas fa-external-link-alt text-[11px] text-gray-400 dark:text-slate-500 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 shrink-0 opacity-80"></i>
                                    @else
                                        <x-heroicon-s-chevron-right class="w-4 h-4 text-gray-300 dark:text-slate-500 group-hover:text-[#7a1a40] dark:group-hover:text-rose-400 group-hover:translate-x-0.5 transition-transform shrink-0" />
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Right: Facebook Fanpage (Wide: flex-1) -->
                    <div class="flex-1 min-w-0 w-full bg-white dark:bg-slate-800 rounded-2xl shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-gray-100 dark:border-slate-700 overflow-hidden flex flex-col justify-between">
                        <div class="bg-[#1877F2] text-white px-5 py-3.5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <i class="fab fa-facebook-f text-lg"></i>
                                <span class="font-bold text-base tracking-wide">Facebook วิทยาลัย</span>
                            </div>
                            <a href="https://www.facebook.com/profile.php?id=100093014446740" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 hover:bg-white/30 text-xs text-white font-semibold rounded-lg transition-colors">
                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                <span>เปิดหน้าเพจ</span>
                            </a>
                        </div>

                        <div class="p-4 sm:p-5 flex-1 flex flex-col lg:flex-row gap-5 items-stretch bg-slate-50/30 dark:bg-slate-900/50">
                            <!-- Facebook Real Post Embed (Live Post of Trat Technical College) -->
                            <div class="w-full lg:w-[480px] xl:w-[500px] shrink-0 flex justify-center bg-white dark:bg-slate-900 rounded-xl p-2 border border-slate-100 dark:border-slate-800 shadow-xs overflow-hidden">
                                <iframe
                                    loading="lazy"
                                    src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2F100093014446740%2Fposts%2F1033513649759107&show_text=true&width=500"
                                    class="w-full max-w-[500px] border-none rounded-lg shadow-xs overflow-auto"
                                    height="520"
                                    style="border:none; overflow-y:auto;"
                                    scrolling="yes"
                                    allowfullscreen="true"
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                                </iframe>
                            </div>

                            <!-- College Facebook Community Info & Quick Links (Wide Complement) -->
                            <div class="flex-1 w-full bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-100 dark:border-slate-700 shadow-xs flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-3.5 mb-3.5 pb-3.5 border-b border-slate-100 dark:border-slate-700">
                                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-900/60 flex items-center justify-center text-[#1877F2] shadow-xs shrink-0">
                                            <i class="fab fa-facebook text-2xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                <h4 class="font-bold text-slate-800 dark:text-white text-sm sm:text-base truncate">วิทยาลัยเทคนิคตราด</h4>
                                                <i class="fas fa-check-circle text-[#1877F2] text-xs" title="เพจทางการ"></i>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">@trattech.ac.th • เพจทางการ</p>
                                            <div class="flex items-center gap-1.5 text-xs text-blue-600 dark:text-blue-400 font-semibold mt-0.5">
                                                <i class="fas fa-users text-[11px]"></i>
                                                <span>ผู้ติดตาม 4,168 คน</span>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                                        ติดตามข่าวสาร กิจกรรม ประกาศรับสมัครนักเรียน-นักศึกษา ผลงานวิชาการ และภาพบรรยากาศการเรียนการสอนแบบเรียลไทม์
                                    </p>

                                    <!-- Latest Facebook Album Highlight -->
                                    <div class="p-3 bg-blue-50/60 dark:bg-blue-950/40 rounded-xl border border-blue-100/70 dark:border-blue-900/60 mb-3">
                                        <div class="flex items-center justify-between text-xs font-bold text-blue-900 dark:text-blue-300 mb-1">
                                            <div class="flex items-center gap-1.5">
                                                <i class="fas fa-images text-[#1877F2]"></i>
                                                <span>อัลบั้มภาพกิจกรรมล่าสุด</span>
                                            </div>
                                            <span class="text-[10px] px-2 py-0.5 bg-blue-200/60 dark:bg-blue-900/80 text-blue-800 dark:text-blue-200 rounded-full font-semibold">24 ภาพ</span>
                                        </div>
                                        <p class="text-[11px] text-slate-600 dark:text-slate-300 line-clamp-2 mb-2 font-medium">
                                            ร่วมพิธีวางพวงมาลาเนื่องในวันปิยมหาราช ประจำปี พ.ศ. 2569 ณ บริเวณพระบรมราชานุสาวรีย์ฯ
                                        </p>
                                        <a href="https://www.facebook.com/media/set/?vanity=100093014446740&set=a.1033327863111019" target="_blank"
                                           class="inline-flex items-center gap-1 text-[11px] text-[#1877F2] dark:text-blue-400 hover:underline font-bold">
                                            <span>เปิดดูอัลบั้มบน Facebook</span>
                                            <i class="fas fa-arrow-right text-[9px]"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                                    <a href="https://www.facebook.com/profile.php?id=100093014446740" target="_blank"
                                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#1877F2] hover:bg-blue-600 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-xs hover:shadow-md">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                        <span>กดติดตามเพจ Facebook</span>
                                    </a>
                                    <a href="https://www.facebook.com/messages/t/100093014446740" target="_blank"
                                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-300 font-semibold text-xs rounded-xl transition-colors">
                                        <i class="fab fa-facebook-messenger text-sm text-[#1877F2]"></i>
                                        <span>ส่งข้อความ Messenger</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 3. Breaking News Slider (สไลด์ข่าวด่วน ด้านบนโพสล่าสุด) --}}
            @php
                $breakingNews = $this->breakingNews;
            @endphp

            @if ($breakingNews && $breakingNews->isNotEmpty())
                <div class="w-full px-2 sm:px-3 lg:px-2 mb-8">
                    <div x-data="{
                            active: 0,
                            total: {{ $breakingNews->count() }},
                            paused: false,
                            timer: null,
                            init() {
                                this.start();
                            },
                            start() {
                                this.timer = setInterval(() => {
                                    if (!this.paused) {
                                        this.next();
                                    }
                                }, 5000);
                            },
                            next() {
                                this.active = (this.active + 1) % this.total;
                            },
                            prev() {
                                this.active = (this.active - 1 + this.total) % this.total;
                            },
                            goTo(i) {
                                this.active = i;
                            }
                         }"
                         @mouseenter="paused = true"
                         @mouseleave="paused = false"
                         class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_4px_25px_rgba(0,0,0,0.04)] border border-rose-100 dark:border-slate-800 overflow-hidden relative group">

                        <!-- Top Announcement Header / Ticker Bar -->
                        <div class="bg-gradient-to-r from-[#7a1a40] via-rose-700 to-red-600 text-white px-4 sm:px-5 py-3 flex flex-wrap items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center gap-3">
                                <!-- Flashing Beacon / Badge -->
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-black tracking-wider uppercase border border-white/25 shadow-xs">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-300 opacity-80"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                                    </span>
                                    <i class="fas fa-bolt text-amber-300 text-xs"></i>
                                    <span>ข่าวด่วน</span>
                                </div>

                                <span class="text-white/90 text-xs sm:text-sm font-semibold hidden sm:inline-block">
                                    เกาะติดข่าวสารและประกาศสำคัญล่าสุด วิทยาลัยเทคนิคตราด
                                </span>
                            </div>

                            <!-- Navigation Controls & Counter -->
                            <div class="flex items-center gap-2">
                                <!-- Slide Counter -->
                                <div class="px-2.5 py-0.5 bg-black/25 backdrop-blur-sm rounded-lg text-xs font-mono font-bold text-white/90 border border-white/10">
                                    <span x-text="active + 1"></span> / <span x-text="total"></span>
                                </div>

                                <!-- Prev Button -->
                                <button @click="prev()"
                                        type="button"
                                        aria-label="ข่าวด่วนก่อนหน้า"
                                        class="w-7 h-7 rounded-lg bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 focus:outline-none cursor-pointer">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>

                                <!-- Next Button -->
                                <button @click="next()"
                                        type="button"
                                        aria-label="ข่าวด่วนถัดไป"
                                        class="w-7 h-7 rounded-lg bg-white/15 hover:bg-white/30 text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 focus:outline-none cursor-pointer">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Slider Body Container -->
                        <div class="relative overflow-hidden bg-gradient-to-br from-rose-50/20 via-white to-amber-50/20 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800/50">
                            <div class="flex transition-transform duration-700 ease-out"
                                 :style="`transform: translateX(-${active * 100}%)`">
                                @foreach ($breakingNews as $idx => $news)
                                    @php
                                        $newsImages = $news->feature_images;
                                        $firstImg = count($newsImages) > 0 ? asset('storage/' . $newsImages[0]) : null;
                                        $newsCategory = $news->categories->first();
                                    @endphp
                                    <div class="w-full shrink-0 min-w-full p-4 sm:p-5 lg:p-6">
                                        <div class="flex flex-col md:flex-row gap-4 sm:gap-6 items-center">

                                            <!-- Slide Image Thumbnail -->
                                            <div class="w-full md:w-[300px] lg:w-[340px] shrink-0">
                                                <a wire:navigate href="{{ route('posts.show', $news) }}"
                                                   class="block relative aspect-[16/10] rounded-xl overflow-hidden shadow-md group/img border border-slate-100 dark:border-slate-800 bg-slate-100">
                                                    @if ($firstImg)
                                                        <img src="{{ $firstImg }}"
                                                             alt="{{ $news->title }}"
                                                             class="w-full h-full object-cover transform group-hover/img:scale-105 transition-transform duration-700 ease-out">
                                                    @else
                                                        <div class="w-full h-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                                            <i class="fas fa-newspaper text-3xl"></i>
                                                        </div>
                                                    @endif
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60 group-hover/img:opacity-40 transition-opacity"></div>

                                                    <!-- Category Badge on Image -->
                                                    @if ($newsCategory)
                                                        <span class="absolute top-3 left-3 px-2.5 py-1 bg-[#7a1a40]/90 backdrop-blur-md text-white text-[11px] font-bold rounded-lg border border-white/20 shadow-xs">
                                                            {{ $newsCategory->name }}
                                                        </span>
                                                    @endif

                                                    @if (count($newsImages) > 1)
                                                        <span class="absolute top-3 right-3 px-2 py-0.5 bg-black/60 backdrop-blur-md text-white text-[11px] font-bold rounded-md border border-white/10 flex items-center gap-1 shadow-xs">
                                                            <i class="fas fa-camera text-amber-300 text-[10px]"></i>
                                                            <span>{{ count($newsImages) }}</span>
                                                        </span>
                                                    @endif
                                                </a>
                                            </div>

                                            <!-- Slide Details & Excerpt -->
                                            <div class="flex-1 min-w-0 w-full flex flex-col justify-between py-1">
                                                <div>
                                                    <!-- Meta Info Row -->
                                                    <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400 mb-2.5">
                                                        <span class="inline-flex items-center gap-1 text-rose-600 dark:text-rose-400 font-bold bg-rose-50 dark:bg-rose-950/40 px-2.5 py-0.5 rounded-md border border-rose-100 dark:border-rose-900/50">
                                                            <i class="fas fa-bullhorn text-[11px]"></i>
                                                            <span>อัปเดตด่วน</span>
                                                        </span>

                                                        <span class="flex items-center gap-1.5 font-medium">
                                                            <i class="far fa-calendar-alt text-[#7a1a40]"></i>
                                                            <span>{{ $news->published_at ? $news->published_at->format('j M Y') : '' }}</span>
                                                        </span>

                                                        @if ($news->author)
                                                            <span class="hidden sm:inline-flex items-center gap-1.5 font-medium">
                                                                <i class="far fa-user text-slate-400"></i>
                                                                <span>{{ $news->author->name }}</span>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Post Title -->
                                                    <h3 class="text-base sm:text-lg md:text-xl font-extrabold text-slate-800 dark:text-white leading-snug mb-2 group-hover:text-[#7a1a40] transition-colors">
                                                        <a wire:navigate href="{{ route('posts.show', $news) }}" class="hover:underline">
                                                            {{ $news->title }}
                                                        </a>
                                                    </h3>

                                                    <!-- Post Excerpt -->
                                                    <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">
                                                        {{ $news->excerpt ?: Str::limit(strip_tags($news->content), 180) }}
                                                    </p>
                                                </div>

                                                <!-- Bottom Action & Pagination Dots -->
                                                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                                                    <a wire:navigate href="{{ route('posts.show', $news) }}"
                                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#7a1a40] to-rose-700 hover:from-[#661434] hover:to-rose-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                                                        <span>อ่านรายละเอียดข่าวด่วน</span>
                                                        <i class="fas fa-arrow-right text-xs"></i>
                                                    </a>

                                                    <!-- Slide Dot Indicators -->
                                                    <div class="flex items-center gap-1.5">
                                                        @foreach ($breakingNews as $dotIdx => $dotNews)
                                                            <button @click="goTo({{ $dotIdx }})"
                                                                    type="button"
                                                                    aria-label="ไปยังสไลด์ที่ {{ $dotIdx + 1 }}"
                                                                    class="h-2 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                                                                    :class="active === {{ $dotIdx }} ? 'w-6 bg-[#7a1a40]' : 'w-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300'"></button>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Blog -->
            <div id="latest-posts">
                @include('components.⚡bloglist-page', [
                    'sectionTitle' => 'โพสล่าสุด',
                    'posts' => $this->posts,
                    'categories' => $this->categories, // ส่งข้อมูลหมวดหมู่ไปด้วยเพื่อแสดงใน sidebar
                ])
            </div>

            <!-- Gallery Section (Modern Glassmorphism & Micro-Interactions) -->
            @if ($this->dashboardImages->isNotEmpty())
                <section id="event-gallery" class="max-w-7xl mx-auto my-2 px-4 sm:px-6 lg:px-2 p-2">
                    {{-- Main Container แบบ Glassmorphism เบาๆ --}}
                    <div
                        class="bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl border border-gray-200 dark:border-slate-800/50 rounded-2xl p-2 sm:p-2 shadow-[0_24px_70px_rgba(0,0,0,0.03)] dark:shadow-[0_24px_70px_rgba(0,0,0,0.2)]">
                        {{-- Section Header: ออกแบบให้มีมิติคมชัดขึ้น --}}
                        <div
                            class="flex items-center justify-between mb-2 pb-4 border-b border-slate-200 dark:border-slate-800/50">
                            <h2
                                class="text-2xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-4">
                                <span
                                    class="flex h-8 w-2 rounded-full bg-linear-to-b from-[#7a1a40] to-rose-700 shadow-[0_4px_12px_rgba(122,26,64,0.3)]"></span>
                                คลังภาพกิจกรรม
                            </h2>
                            <div
                                class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 hidden sm:block">
                                Event Gallery
                            </div>
                        </div>

                        {{-- Photo Grid --}}
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-4 p-2 shadow-2xl rounded-2xl">
                            @foreach ($this->dashboardImages as $imageRecord)
                                @php
                                    $mediaItems = $imageRecord->getMedia('images');
                                    $firstMedia = $mediaItems->first();
                                @endphp
                                @if ($firstMedia)
                                    <a wire:navigate href="{{ route('images.show', $imageRecord->slug) }}"
                                        class="group relative block aspect-4/3 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)] border border-slate-100 dark:border-slate-800/60 transition-all duration-500 ease-out">

                                        {{-- Image Layer --}}
                                        <img src="{{ $firstMedia->getUrl() }}" alt="{{ $imageRecord->title }}"
                                            class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out">

                                        {{-- Modern Gradient Overlay: นวลตา ไม่มืดเกินไปจนบังภาพ --}}
                                        <div
                                            class="absolute inset-0 bg-linear-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-500">
                                        </div>

                                        {{-- Badge: จำนวนรูปภาพ (Glassmorphism Badge) --}}
                                        @if ($mediaItems->count() > 1)
                                            <div
                                                class="absolute top-4 right-4 bg-white/20 dark:bg-black/30 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1.5 rounded-xl flex items-center gap-1.5 border border-white/20 shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                                <span>+{{ $mediaItems->count() - 1 }}</span>
                                            </div>
                                        @endif

                                        {{-- Content Layer: ดันเนื้อหาขึ้นอย่างสมูทเวลามีการ Hover --}}
                                        <div
                                            class="absolute inset-x-0 bottom-0 p-5 sm:p-6 flex flex-col justify-end transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500 ease-out">
                                            <h3
                                                class="text-white font-bold text-base sm:text-lg leading-snug line-clamp-2 drop-shadow-sm group-hover:text-blue-300 transition-colors duration-300">
                                                {{ $imageRecord->title }}
                                            </h3>

                                            <div
                                                class="max-h-0 opacity-0 group-hover:max-h-10 group-hover:opacity-100 transition-all duration-500 ease-out overflow-hidden">
                                                <p
                                                    class="text-slate-300 text-xs font-medium mt-2 flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        class="w-3.5 h-3.5 text-blue-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                    </svg>
                                                    {{ $imageRecord->published_at ? $imageRecord->published_at->format('j M Y') : 'No Date' }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-10 border-t border-slate-100 dark:border-slate-800/60 pt-0">
                            <hr class="border border-gray-100 mb-4">
                            {{ $this->dashboardImages->links(data: ['scrollTo' => '#event-gallery']) }}
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>
</div>

