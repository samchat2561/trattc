<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('images/trat.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sarabun {
            font-family: "Sarabun", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>

    <script>
        // 1. ตรวจสอบธีมที่บันทึกไว้ หรือใช้ค่าเริ่มต้นจาก OS
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // 2. ฟังก์ชันสำหรับปุ่มกดสลับโหมด
        function toggleDarkMode() {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { isDark: isDark } }));
            return isDark;
        }

        // 3. ป้องกัน BFCache ค้างเมื่อกดกลับมาจากลิงก์ภายนอก
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-slate-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 sarabun overflow-x-hidden">
    {{-- <livewire:homepage-banners /> --}}
    @if(empty($hideHeaderFooter))
        <livewire:partials.navbar />
    @endif
    <main>
        {{ $slot }}
    </main>
    @if(empty($hideHeaderFooter))
        <livewire:partials.footer />
    @endif

    @if(empty($hideHeaderFooter))
    <!-- 📞 Floating Contact Container -->
    <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50 flex flex-col items-center gap-3.5">

        <!-- 📱 เมนูช่องทางย่อย (เด้งเรียงขึ้นด้านบนเมื่อคลิกเปิด) -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="flex flex-col items-center gap-3"
             style="display: none;">

            <!-- 📞 ช่องทาง: โทรศัพท์ -->
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

            <!-- 🟢 ช่องทาง: Line -->
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

            <!-- 🔵 ช่องทาง: Facebook -->
            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        Facebook Fanpage
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="#" target="_blank" class="w-12 h-12 bg-[#1877f2] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fab fa-facebook-f text-base"></i>
                </a>
            </div>

            <!-- 🔴 ช่องทาง: Email -->
            <div class="flex items-center relative group">
                <div class="absolute right-14 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                    <div class="bg-slate-900 text-white text-[12px] font-medium px-3 py-1.5 rounded-xl shadow-md whitespace-nowrap relative">
                        ส่งอีเมลสอบถาม
                        <div class="absolute top-1/2 -right-1 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45 rounded-sm"></div>
                    </div>
                </div>
                <a href="mailto:info@domain.com" class="w-12 h-12 bg-[#ea4335] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all">
                    <i class="fas fa-envelope text-base"></i>
                </a>
            </div>

            <!-- 💬 ช่องทาง: กล่องข้อความ/แชท -->
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

        <!-- 👑 ปุ่มหลักด้านล่างสุด (ปกติสีเขียว คลิกแล้วเปลี่ยนเป็นสีแดง) -->
        <div class="flex items-center relative group">

            <!-- ป้ายคำว่า "ติดต่อเรา" (จะแสดงเฉพาะตอนที่ยังไม่กดปุ่มเปิดเมนู) -->
            <div x-show="!open" class="absolute right-16 flex items-center opacity-0 -translate-x-3 pointer-events-none transition-all duration-300 ease-out group-hover:opacity-100 group-hover:translate-x-0">
                <div class="bg-slate-900 text-white text-[13px] font-medium px-4 py-2 rounded-xl shadow-lg tracking-wide whitespace-nowrap relative">
                    ติดต่อเรา
                    <div class="absolute top-1/2 -right-1.5 -translate-y-1/2 w-3 h-3 bg-slate-900 rotate-45 rounded-sm"></div>
                </div>
            </div>

            <!-- ตัวปุ่มหลัก: สลับสีและเงาเรืองแสง (ปกติ bg-[#22c55e] สีเขียว | คลิกแล้ว bg-[#ef4444] สีแดง) -->
            <button @click="open = !open"
                    :class="open ? 'bg-[#ef4444] rotate-135 shadow-[0_0_15px_rgba(239,68,68,0.5)]' : 'bg-[#22c55e] shadow-[0_0_15px_rgba(34,197,94,0.4)]'"
                    class="w-14 h-14 text-white rounded-full flex items-center justify-center shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 relative z-10">

                <!-- เอฟเฟกต์คลื่นเรดาร์วิบวับรอบปุ่ม (จะแสดงเฉพาะตอนปุ่มเป็นสีเขียวปกติ) -->
                <span x-show="!open" class="absolute inset-0 rounded-full bg-green-500/30 animate-ping opacity-75 pointer-events-none"></span>

                <!-- ไอคอนหูฟังคอลเซ็นเตอร์ (แสดงตอนปุ่มสีเขียว) -->
                <template x-if="!open">
                    <i class="fas fa-headset text-2xl"></i>
                </template>

                <!-- ไอคอนกากบาท (แสดงตอนปุ่มเปลี่ยนเป็นสีแดง) -->
                <template x-if="open">
                    <i class="fas fa-times text-xl"></i>
                </template>
            </button>
        </div>
    </div>
    @endif

    @livewireScripts
</body>
</html>
