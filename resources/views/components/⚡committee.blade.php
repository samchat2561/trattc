<?php

use Livewire\Component;
use App\Models\Department;
use App\Models\Staff;

new class extends Component {
    public function render()
    {
        $mainDepartments = Department::whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('components.⚡committee', [
            'mainDepartments' => $mainDepartments,
        ])->layout('layouts.app');
    }
};
?>

<div x-data="{
        modalOpen: false,
        modalData: {
            name: '',
            position: '',
            department: '',
            phone: '',
            img: '',
            tasks: []
        },
        openModal(data) {
            this.modalData = data;
            this.modalOpen = true;
        }
    }"
    class="w-full min-h-screen pt-36 pb-20 bg-slate-50/70 dark:bg-slate-950 font-['sarabun'] text-slate-800 dark:text-slate-100 antialiased transition-colors duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 🧭 Breadcrumbs Navigation -->
        <nav class="flex items-center space-x-2 text-sm font-medium text-slate-500 dark:text-slate-400 mb-6">
            <a wire:navigate href="{{ route('home') }}" class="flex items-center gap-1.5 hover:text-[#7a1a40] dark:hover:text-amber-300 transition-colors">
                <x-heroicon-s-home class="w-4 h-4" />
                <span>หน้าแรก</span>
            </a>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 dark:text-slate-600 shrink-0" />
            <span class="text-slate-500 dark:text-slate-400">เกี่ยวกับวิทยาลัย</span>
            <x-heroicon-s-chevron-right class="w-4 h-4 text-slate-300 dark:text-slate-600 shrink-0" />
            <span class="text-slate-800 dark:text-slate-200 font-semibold truncate">ผังโครงสร้างและการบริหารองค์กร</span>
        </nav>

        <!-- 🏛️ Hero Section / Page Header -->
        <div class="relative overflow-hidden bg-[#7a1a40] text-white py-12 md:py-16 px-6 sm:px-10 rounded-3xl shadow-[0_20px_50px_rgba(122,26,64,0.25)] border border-rose-900/30 mb-14"
             style="background: linear-gradient(135deg, #7a1a40 0%, #8f1e4a 50%, #5c132f 100%) !important;">
            <!-- Decorative Orbs -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-rose-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative max-w-4xl mx-auto text-center space-y-4">
                <!-- Badge Pill -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-white/15 backdrop-blur-md text-amber-300 border border-white/20 shadow-xs">
                    <i class="fas fa-sitemap text-amber-400"></i>
                    <span class="tracking-wider uppercase">Trat Technical College Executive Chart</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white leading-tight drop-shadow-sm">
                    ผังโครงสร้างและการบริหารองค์กร
                </h1>

                <!-- Gold Accent Line -->
                <div class="w-20 h-1.5 bg-amber-400 mx-auto rounded-full shadow-xs"
                     style="background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 50%, #fbbf24 100%);"></div>

                <p class="text-rose-100/90 text-sm sm:text-base max-w-2xl mx-auto font-medium leading-relaxed pt-1">
                    วิทยาลัยเทคนิคตราด มุ่งมั่นพัฒนาการศึกษาวิชาชีพสู่มาตรฐานสากล ด้วยการบริหารจัดการที่เป็นเลิศ โปร่งใส และมีประสิทธิภาพ
                </p>

                <!-- Quick Overview Badges -->
                <div class="pt-3 flex flex-wrap justify-center items-center gap-2.5 sm:gap-3 text-xs">
                    <span class="px-3.5 py-1.5 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20 text-white flex items-center gap-2 font-medium">
                        <i class="fas fa-user-tie text-amber-400"></i>
                        <span>1 ผู้อำนวยการสถานศึกษา</span>
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20 text-white flex items-center gap-2 font-medium">
                        <i class="fas fa-users-gear text-amber-400"></i>
                        <span>4 ฝ่ายบริหารหลัก</span>
                    </span>
                    <span class="px-3.5 py-1.5 rounded-xl bg-white/15 backdrop-blur-sm border border-white/20 text-white flex items-center gap-2 font-medium">
                        <i class="fas fa-award text-amber-400"></i>
                        <span>สำนักงานคณะกรรมการการอาชีวศึกษา</span>
                    </span>
                </div>
            </div>
        </div>

        @php
            // รายการงานมาตรฐานครบถ้วนของแต่ละฝ่ายในวิทยาลัยเทคนิคตราด
            $allDivisionTasks = [
                'ฝ่ายบริหารทรัพยากร' => [
                    'งานบริหารงานทั่วไป (สารบรรณ)',
                    'งานบุคลากร',
                    'งานการเงินและบัญชี',
                    'งานพัสดุ',
                    'งานอาคารสถานที่และยานพาหนะ',
                    'งานประชาสัมพันธ์',
                ],
                'ฝ่ายแผนงานและความร่วมมือ' => [
                    'งานวางแผนและงบประมาณ',
                    'งานศูนย์ข้อมูลสารสนเทศ',
                    'งานความร่วมมือและทวิภาคี',
                    'งานวิจัย พัฒนา นวัตกรรมและสิ่งประดิษฐ์',
                    'งานประกันคุณภาพและมาตรฐานการศึกษา',
                    'งานส่งเสริมผลิตผล การค้าและธุรกิจ',
                ],
                'ฝ่ายพัฒนากิจการนักเรียน-นักศึกษา' => [
                    'งานกิจกรรมนักเรียน นักศึกษา',
                    'งานครูที่ปรึกษา',
                    'งานปกครองและความประพฤติ',
                    'งานแนะแนวอาชีพและการมีงานทำ',
                    'งานสวัสดิการนักเรียน นักศึกษา',
                    'งานโครงการพิเศษและบริการชุมชน',
                ],
                'ฝ่ายวิชาการ' => [
                    'งานพัฒนาหลักสูตรการเรียนการสอน',
                    'งานวัดผลและประเมินผล',
                    'งานวิทยบริการและห้องสมุด',
                    'งานอาชีวศึกษาระบบทวิภาคี',
                    'งานสื่อการเรียนการสอน',
                    'แผนกวิชาช่างและพาณิชยกรรม',
                ],
            ];

            // กำหนดธีมสีเฉพาะของแต่ละฝ่าย
            $deptTheme = [
                'ฝ่ายบริหารทรัพยากร' => [
                    'borderTop' => 'border-t-4 border-t-emerald-500',
                    'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/80',
                    'badgeText' => 'text-emerald-700 dark:text-emerald-300',
                    'badgeBorder' => 'border-emerald-200/80 dark:border-emerald-800/80',
                    'barStyle' => 'background: linear-gradient(90deg, #059669, #0d9488);',
                    'icon' => 'fas fa-building-columns',
                    'iconColor' => 'text-emerald-600 dark:text-emerald-400',
                    'dropLineColor' => 'bg-emerald-500',
                ],
                'ฝ่ายแผนงานและความร่วมมือ' => [
                    'borderTop' => 'border-t-4 border-t-blue-500',
                    'badgeBg' => 'bg-blue-50 dark:bg-blue-950/80',
                    'badgeText' => 'text-blue-700 dark:text-blue-300',
                    'badgeBorder' => 'border-blue-200/80 dark:border-blue-800/80',
                    'barStyle' => 'background: linear-gradient(90deg, #2563eb, #4f46e5);',
                    'icon' => 'fas fa-chart-line',
                    'iconColor' => 'text-blue-600 dark:text-blue-400',
                    'dropLineColor' => 'bg-blue-500',
                ],
                'ฝ่ายพัฒนากิจการนักเรียน-นักศึกษา' => [
                    'borderTop' => 'border-t-4 border-t-amber-500',
                    'badgeBg' => 'bg-amber-50 dark:bg-amber-950/80',
                    'badgeText' => 'text-amber-800 dark:text-amber-300',
                    'badgeBorder' => 'border-amber-200/80 dark:border-amber-800/80',
                    'barStyle' => 'background: linear-gradient(90deg, #f59e0b, #ea580c);',
                    'icon' => 'fas fa-user-graduate',
                    'iconColor' => 'text-amber-600 dark:text-amber-400',
                    'dropLineColor' => 'bg-amber-500',
                ],
                'ฝ่ายวิชาการ' => [
                    'borderTop' => 'border-t-4 border-t-[#7a1a40]',
                    'badgeBg' => 'bg-rose-50 dark:bg-rose-950/80',
                    'badgeText' => 'text-[#7a1a40] dark:text-rose-300',
                    'badgeBorder' => 'border-rose-200/80 dark:border-rose-800/80',
                    'barStyle' => 'background: linear-gradient(90deg, #7a1a40, #e11d48);',
                    'icon' => 'fas fa-book-bookmark',
                    'iconColor' => 'text-[#7a1a40] dark:text-rose-400',
                    'dropLineColor' => 'bg-[#7a1a40]',
                ],
            ];
        @endphp

        <!-- 🌳 แผนผังโครงสร้างการบริหาร (Organizational Structure Tree) -->
        <div class="space-y-4">

            @forelse($mainDepartments as $topDepartment)
                @php
                    $director = $topDepartment->staff()->where('is_active', true)->first();
                    $directorPhoto = ($director && $director->hasMedia('profile'))
                        ? $director->getFirstMediaUrl('profile')
                        : null;
                @endphp

                <!-- 👑 ระดับที่ 1: ผู้อำนวยการสถานศึกษา (Director Apex Card) -->
                <div class="flex flex-col items-center">
                    
                    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-7 shadow-[0_15px_40px_rgba(122,26,64,0.12)] dark:shadow-[0_15px_40px_rgba(0,0,0,0.4)] border-2 border-amber-400/50 dark:border-amber-500/40 text-center transition-all duration-300 hover:shadow-[0_20px_50px_rgba(122,26,64,0.2)] hover:-translate-y-1 group">
                        
                        <!-- Top Crown Banner -->
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-slate-900 text-xs font-black tracking-wide shadow-md uppercase mb-5"
                             style="background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);">
                            <i class="fas fa-crown text-slate-950"></i>
                            <span>ผู้อำนวยการสถานศึกษา</span>
                        </div>

                        <!-- 📸 Director Portrait Frame -->
                        <div class="relative w-52 h-68 sm:w-56 sm:h-72 mx-auto rounded-2xl overflow-hidden shadow-md ring-4 ring-rose-50 dark:ring-slate-700/80 mb-5 bg-slate-100 dark:bg-slate-700 cursor-pointer"
                             @click="openModal({
                                 name: '{{ $director ? $director->full_name : 'นายจิรพงค์ ร่มเงิน' }}',
                                 position: '{{ $director && $director->position ? $director->position->name : 'ผู้อำนวยการสถานศึกษา' }}',
                                 department: '{{ $topDepartment->name }}',
                                 phone: '{{ $director ? $director->phone : '' }}',
                                 img: '{{ $directorPhoto }}',
                                 tasks: ['บริหารจัดการวิทยาลัยตามนโยบายสำนักงานคณะกรรมการการอาชีวศึกษา', 'กำหนดทิศทางยุทธศาสตร์และแผนพัฒนาสถานศึกษา', 'กำกับดูแลฝ่ายบริหารทรัพยากร ฝ่ายแผนงานและความร่วมมือ ฝ่ายพัฒนากิจการฯ และฝ่ายวิชาการ']
                             })">
                            
                            @if($directorPhoto)
                                <img src="{{ $directorPhoto }}"
                                     alt="{{ $director->full_name }}"
                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-user-tie text-5xl"></i>
                                </div>
                            @endif

                            <!-- Hover Overlay with Zoom Icon -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                <span class="px-3.5 py-1 bg-white/95 dark:bg-slate-900/95 text-[#7a1a40] dark:text-amber-300 rounded-full text-xs font-bold shadow-md flex items-center gap-1.5">
                                    <i class="fas fa-search-plus"></i> ขยายดูรายละเอียด
                                </span>
                            </div>
                        </div>

                        <!-- Director Information -->
                        <div class="space-y-1.5">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                {{ $director ? $director->full_name : 'นายจิรพงค์ ร่มเงิน' }}
                            </h2>
                            <p class="text-sm font-bold text-[#7a1a40] dark:text-amber-300">
                                {{ $director && $director->position ? $director->position->name : 'ผู้อำนวยการสถานศึกษา วิทยาลัยเทคนิคตราด' }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                วิทยาลัยเทคนิคตราด • สังกัดสำนักงานคณะกรรมการการอาชีวศึกษา
                            </p>
                        </div>

                        <!-- Director Direct Contact Action -->
                        @if($director && $director->phone)
                            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-center gap-3">
                                <a href="tel:{{ preg_replace('/[^0-9]/', '', $director->phone) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-50 dark:bg-slate-700 text-[#7a1a40] dark:text-amber-300 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-amber-400 dark:hover:text-slate-950 transition-all duration-300 text-xs font-bold shadow-xs">
                                    <i class="fas fa-phone-alt text-green-600 dark:text-green-400"></i>
                                    <span>โทร. {{ $director->phone }}</span>
                                </a>
                            </div>
                        @endif

                    </div>

                    <!-- 🔗 Tree Connector Stem -->
                    <div class="w-1 h-8 bg-gradient-to-b from-amber-400 to-[#7a1a40] dark:to-rose-400 rounded-full"></div>
                    
                    <!-- Center Junction Badge -->
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-white text-xs sm:text-sm font-bold shadow-md border border-white/20 z-10"
                         style="background: linear-gradient(135deg, #7a1a40 0%, #8f1e4a 100%) !important;">
                        <i class="fas fa-sitemap text-amber-300"></i>
                        <span>สายการบังคับบัญชา 4 ฝ่ายบริหารสถานศึกษา</span>
                    </div>

                    <div class="w-1 h-6 bg-[#7a1a40] dark:bg-amber-400 rounded-full"></div>

                </div>

                <!-- 👥 ระดับที่ 2: กลุ่ม 4 ฝ่ายบริหารและรองผู้อำนวยการสถานศึกษา -->
                @php
                    $subDepartments = Department::where('parent_id', $topDepartment->id)
                        ->orderBy('sort_order', 'asc')
                        ->get();
                @endphp

                @if($subDepartments->count() > 0)
                    <div class="space-y-6">

                        <!-- SVG Tree Connector Rail (Desktop) -->
                        <div class="hidden lg:block w-full max-w-6xl mx-auto -mb-2">
                            <svg class="w-full h-8" viewBox="0 0 1000 32" fill="none" preserveAspectRatio="none">
                                <!-- Main Horizontal Branch -->
                                <path d="M 125 32 L 125 10 Q 125 0 135 0 L 865 0 Q 875 0 875 10 L 875 32" stroke="#7a1a40" stroke-width="2.5" fill="none" class="dark:stroke-amber-400/80" />
                                <!-- Intermediate Stems -->
                                <line x1="375" y1="0" x2="375" y2="32" stroke="#2563eb" stroke-width="2.5" />
                                <line x1="625" y1="0" x2="625" y2="32" stroke="#f59e0b" stroke-width="2.5" />
                                <!-- Center Stem Connection Point -->
                                <circle cx="500" cy="0" r="4" fill="#7a1a40" class="dark:fill-amber-400" />
                            </svg>
                        </div>

                        <!-- 4 Columns Grid of Deputy Directors -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 xl:gap-7 items-stretch">
                            @foreach($subDepartments as $sub)
                                @php
                                    $subLeader = $sub->staff()->where('is_active', true)->first();
                                    $subPhoto = ($subLeader && $subLeader->hasMedia('profile'))
                                        ? $subLeader->getFirstMediaUrl('profile')
                                        : null;

                                    $theme = $deptTheme[$sub->name] ?? [
                                        'borderTop' => 'border-t-4 border-t-[#7a1a40]',
                                        'badgeBg' => 'bg-slate-50 dark:bg-slate-800',
                                        'badgeText' => 'text-slate-700 dark:text-slate-300',
                                        'badgeBorder' => 'border-slate-200 dark:border-slate-700',
                                        'barStyle' => 'background: linear-gradient(90deg, #7a1a40, #e11d48);',
                                        'icon' => 'fas fa-briefcase',
                                        'iconColor' => 'text-[#7a1a40] dark:text-rose-400',
                                        'dropLineColor' => 'bg-[#7a1a40]',
                                    ];

                                    // รายการงานมาตรฐานครบถ้วน
                                    $taskList = $allDivisionTasks[$sub->name] ?? [];
                                @endphp

                                <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 sm:p-6 border border-slate-100 dark:border-slate-700/80 {{ $theme['borderTop'] }} shadow-[0_4px_25px_rgba(0,0,0,0.04)] hover:shadow-[0_15px_35px_rgba(122,26,64,0.12)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">

                                    <div>
                                        <!-- Division Title Badge -->
                                        <div class="mb-4 text-center">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $theme['badgeBg'] }} {{ $theme['badgeText'] }} border {{ $theme['badgeBorder'] }} shadow-2xs">
                                                <i class="{{ $theme['icon'] }} {{ $theme['iconColor'] }}"></i>
                                                <span class="truncate">{{ $sub->name }}</span>
                                            </div>
                                        </div>

                                        <!-- 📸 Deputy Director Portrait Frame -->
                                        <div class="relative w-44 h-56 sm:w-48 sm:h-60 mx-auto rounded-2xl overflow-hidden shadow-inner ring-4 ring-slate-100 dark:ring-slate-700 mb-4 bg-slate-50 dark:bg-slate-700/60 cursor-pointer"
                                             @click="openModal({
                                                 name: '{{ $subLeader ? $subLeader->full_name : 'ประจำตำแหน่ง' }}',
                                                 position: '{{ $subLeader && $subLeader->position ? $subLeader->position->name : 'รองผู้อำนวยการ' }}',
                                                 department: '{{ $sub->name }}',
                                                 phone: '{{ $subLeader ? $subLeader->phone : '' }}',
                                                 img: '{{ $subPhoto }}',
                                                 tasks: {{ json_encode($taskList) }}
                                             })">
                                            
                                            @if($subPhoto)
                                                <img src="{{ $subPhoto }}"
                                                     alt="{{ $subLeader ? $subLeader->full_name : $sub->name }}"
                                                     class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                                                    <i class="fas fa-user-tie text-4xl"></i>
                                                    <span class="text-[11px] mt-2 font-medium">รอการแต่งตั้ง</span>
                                                </div>
                                            @endif

                                            <!-- Hover Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3">
                                                <span class="px-3 py-1 bg-white/95 dark:bg-slate-900/95 text-slate-800 dark:text-white rounded-full text-xs font-bold shadow-md flex items-center gap-1.5">
                                                    <i class="fas fa-search-plus text-[10px]"></i> ดูรายละเอียด
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Name & Position Info -->
                                        <div class="text-center space-y-1">
                                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base leading-snug">
                                                {{ $subLeader ? $subLeader->full_name : 'ประจำตำแหน่ง' }}
                                            </h3>
                                            <p class="text-xs font-bold text-[#7a1a40] dark:text-amber-300">
                                                {{ $subLeader && $subLeader->position ? $subLeader->position->name : 'รองผู้อำนวยการ' }}
                                            </p>
                                            <p class="text-[11px] font-medium text-slate-400 dark:text-slate-400">
                                                {{ $sub->name }}
                                            </p>
                                        </div>

                                        <!-- Phone Call Link -->
                                        @if($subLeader && $subLeader->phone)
                                            <div class="text-center mt-3">
                                                <a href="tel:{{ preg_replace('/[^0-9]/', '', $subLeader->phone) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-700/80 text-slate-700 dark:text-slate-200 hover:bg-[#7a1a40] hover:text-white dark:hover:bg-amber-400 dark:hover:text-slate-950 transition-colors text-xs font-semibold border border-slate-200/60 dark:border-slate-600/60 shadow-2xs">
                                                    <i class="fas fa-phone-alt text-[10px] text-green-600 dark:text-green-400"></i>
                                                    <span>{{ $subLeader->phone }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- 📑 ภารกิจและงานในกำกับดูแล (Internal Tasks) -->
                                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700/70">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                                <i class="fas fa-list-check text-[#7a1a40] dark:text-amber-400"></i>
                                                <span>งานในกำกับดูแล ({{ count($taskList) }})</span>
                                            </span>
                                        </div>

                                        <div class="space-y-1.5">
                                            @foreach($taskList as $taskName)
                                                <div class="text-[11px] text-slate-600 dark:text-slate-300 font-medium flex items-center gap-2 bg-slate-50/90 dark:bg-slate-700/50 px-2.5 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700/60 hover:bg-rose-50/50 dark:hover:bg-slate-700 transition-colors">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $theme['iconColor'] }} shrink-0"></span>
                                                    <span class="truncate">{{ $taskName }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Detail Button -->
                                        <button @click="openModal({
                                                    name: '{{ $subLeader ? $subLeader->full_name : 'ประจำตำแหน่ง' }}',
                                                    position: '{{ $subLeader && $subLeader->position ? $subLeader->position->name : 'รองผู้อำนวยการ' }}',
                                                    department: '{{ $sub->name }}',
                                                    phone: '{{ $subLeader ? $subLeader->phone : '' }}',
                                                    img: '{{ $subPhoto }}',
                                                    tasks: {{ json_encode($taskList) }}
                                                })"
                                                class="w-full mt-3 py-2 px-3 rounded-xl bg-slate-50 hover:bg-rose-50 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-[#7a1a40] dark:text-amber-300 text-xs font-bold border border-slate-200/80 dark:border-slate-600/80 transition-colors flex items-center justify-center gap-1.5">
                                            <i class="fas fa-id-card text-[11px]"></i>
                                            <span>ดูประวัติและภารกิจ</span>
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

            @empty
                <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <i class="fas fa-folder-open text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('ไม่พบข้อมูลโครงสร้างทำเนียบผู้บริหาร') }}</p>
                </div>
            @endforelse

        </div>

        <!-- 🏢 Contact / Quick Links Bar -->
        <div class="mt-16 bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-100 dark:border-slate-700/80 shadow-[0_10px_30px_rgba(0,0,0,0.03)] flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-[#7a1a40] dark:text-amber-300 flex items-center justify-center text-2xl shrink-0 shadow-xs">
                    <i class="fas fa-building-flag"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">วิทยาลัยเทคนิคตราด</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">480 หมู่ 9 ถ.สุขุมวิท ต.วังกระแจะ อ.เมือง จ.ตราด 23000 • โทร. 039-518-105</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    <span>พิมพ์แผนผัง</span>
                </button>
                <a href="tel:039511141" class="px-5 py-2.5 rounded-xl bg-[#7a1a40] hover:bg-[#661434] text-white font-bold text-xs shadow-md transition-colors flex items-center gap-2">
                    <i class="fas fa-phone-alt"></i>
                    <span>ติดต่อวิทยาลัย</span>
                </a>
            </div>
        </div>

    </div>

    <!-- 🔍 Interactive Profile Modal -->
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/75 backdrop-blur-md"
         style="display: none;">
        
        <div class="relative w-full max-w-lg bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden"
             @click.away="modalOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <!-- Modal Header -->
            <div class="bg-[#7a1a40] text-white px-6 py-4 flex items-center justify-between"
                 style="background: linear-gradient(135deg, #7a1a40 0%, #8f1e4a 100%) !important;">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-id-card text-amber-300 text-lg"></i>
                    <h3 class="font-bold text-base tracking-wide" x-text="modalData.department"></h3>
                </div>
                <button @click="modalOpen = false" class="text-white/80 hover:text-white text-xl leading-none focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 sm:p-7 space-y-6">
                <!-- Large Image & Bio -->
                <div class="flex flex-col sm:flex-row gap-5 items-center sm:items-start text-center sm:text-left">
                    <div class="w-36 h-48 sm:w-40 sm:h-52 shrink-0 rounded-2xl overflow-hidden shadow-md ring-4 ring-rose-50 dark:ring-slate-700 bg-slate-100 dark:bg-slate-700">
                        <template x-if="modalData.img">
                            <img :src="modalData.img" :alt="modalData.name" class="w-full h-full object-cover object-top">
                        </template>
                        <template x-if="!modalData.img">
                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                <i class="fas fa-user-tie text-4xl"></i>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-2 flex-1">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/60 text-[#7a1a40] dark:text-amber-300 border border-rose-200/50 dark:border-rose-900/50" x-text="modalData.position"></span>
                        <h4 class="text-xl font-black text-slate-900 dark:text-white" x-text="modalData.name"></h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="modalData.department + ' • วิทยาลัยเทคนิคตราด'"></p>

                        <template x-if="modalData.phone">
                            <div class="pt-2">
                                <a :href="'tel:' + modalData.phone" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[#22c55e] text-white hover:bg-[#16a34a] text-xs font-bold shadow-xs transition-colors">
                                    <i class="fas fa-phone-alt"></i>
                                    <span x-text="'โทร: ' + modalData.phone"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Responsibilities List -->
                <template x-if="modalData.tasks && modalData.tasks.length > 0">
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                            <i class="fas fa-tasks text-[#7a1a40] dark:text-amber-300"></i>
                            <span>ภารกิจและงานในสังกัด</span>
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <template x-for="(task, index) in modalData.tasks" :key="index">
                                <div class="text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-700/50 px-3 py-2 rounded-xl flex items-center gap-2 border border-slate-100 dark:border-slate-700/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#7a1a40] dark:bg-amber-400 shrink-0"></span>
                                    <span class="truncate" x-text="task"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-50 dark:bg-slate-900/60 px-6 py-3 border-t border-slate-100 dark:border-slate-700/60 flex justify-end">
                <button @click="modalOpen = false" class="px-5 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 font-bold text-xs transition-colors">
                    ปิดหน้าต่าง
                </button>
            </div>

        </div>
    </div>

</div>
