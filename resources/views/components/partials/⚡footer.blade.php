<?php

use Livewire\Component;
new class extends Component {
    //
};
?>

<div>
    <footer class="bg-linear-to-br from-[#801810] to-[#5a110b] dark:bg-none dark:bg-slate-900 pt-24 pb-8 px-6 md:px-12 mt-24 relative font-['sarabun'] rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
        <!-- Floating CTA Card -->
        <div class="absolute -top-16 left-4 right-4 md:left-12 md:right-12 max-w-full xl:mx-auto">
            <div class="bg-white/90 dark:bg-slate-800/95 backdrop-blur-xl rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-white/40 dark:border-slate-700/60 gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-2 h-12 bg-linear-to-b from-amber-400 to-amber-600 rounded-full shadow-inner"></div>
                    <h6 class="text-[#801810] dark:text-rose-400 text-xl md:text-2xl font-extrabold tracking-tight">วิทยาลัยเทคนิคตราด</h6>
                </div>
                <a href="#" class="px-8 py-3.5 text-sm md:text-base font-bold rounded-full text-white bg-linear-to-r from-[#801810] to-red-700 hover:shadow-[0_8px_20px_rgba(128,24,16,0.3)] hover:-translate-y-1 transition-all duration-300 text-center">
                    สํานักงานคณะกรรมการการอาชีวศึกษา
                </a>
            </div>
        </div>

        <div class="max-w-full mx-auto">
            <!-- Main Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 mt-8 mb-6">
                <!-- Col 1 -->
                <div class="lg:col-span-1 text-white">
                    <h4 class="text-lg font-bold flex items-center gap-2 mb-2 text-white/95">
                        <x-heroicon-s-home class="h-6 w-6 text-amber-400" />
                        เมนูหลัก
                    </h4>
                    <div class="w-10 h-1.5 bg-amber-500 rounded-full mb-6"></div>
                    <ul class="space-y-3">
                        <li>
                            <a href='#' class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> About Us
                            </a>
                        </li>
                        <li>
                            <a href='#' class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> Contact Us
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 2 -->
                <div class="lg:col-span-1 text-white">
                    <h4 class="text-lg font-bold flex items-center gap-2 mb-2 text-white/95">
                        <x-heroicon-o-chart-bar-square class="h-6 w-6 text-amber-400" />
                        สถิติผู้เข้าชม
                    </h4>
                    <div class="w-10 h-1.5 bg-amber-500 rounded-full mb-6"></div>
                    <div class="bg-white/5 p-4 rounded-2xl backdrop-blur-sm border border-white/10 w-fit hover:bg-white/10 transition-colors">
                        <script type="text/javascript" async src="https://www.stat-counter.org/count/ib7h"></script>
                    </div>
                </div>

                <!-- Col 3 -->
                <div class="lg:col-span-1 text-white">
                    <h4 class="text-lg font-bold flex items-center gap-2 mb-2 text-white/95">
                        <x-heroicon-o-wrench-screwdriver class="h-6 w-6 text-amber-400" />
                        บริการของเรา
                    </h4>
                    <div class="w-10 h-1.5 bg-amber-500 rounded-full mb-6"></div>
                    <ul class="space-y-3">
                        <li>
                            <a href="javascript:void(0)" class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> Web Development
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> Mobile App Development
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> UI/UX Design
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="text-white/70 hover:text-amber-400 flex items-center gap-3 transition-colors duration-300 group">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/20 group-hover:bg-amber-400 group-hover:scale-150 transition-all"></span> Digital Marketing
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div class="lg:col-span-1 text-white">
                    <h4 class="text-lg font-bold flex items-center gap-2 mb-2 text-white/95">
                        <x-heroicon-o-map-pin class="h-6 w-6 text-amber-400" />
                        ติดต่อเรา
                    </h4>
                    <div class="w-10 h-1.5 bg-amber-500 rounded-full mb-6"></div>
                    <ul class="space-y-4 text-white/70 text-sm leading-relaxed">
                        <li class="flex items-start gap-3 group">
                            <x-heroicon-o-building-office-2 class="w-5 h-5 text-white/40 shrink-0 mt-0.5 group-hover:text-amber-400 transition-colors" />
                            <span>วิทยาลัยเทคนิคตราด<br>480 หมู่ 9 ถ.สุขุมวิท ต.วังกระแจะ<br>อ.เมือง จ.ตราด 23000</span>
                        </li>
                        <li class="flex items-center gap-3 group">
                            <x-heroicon-o-envelope class="w-5 h-5 text-white/40 shrink-0 group-hover:text-amber-400 transition-colors" />
                            <a href="mailto:official@trattc.ac.th" class="hover:text-amber-400 transition-colors">official@trattc.ac.th</a>
                        </li>
                        <li class="flex items-center gap-3 group">
                            <x-heroicon-o-phone class="w-5 h-5 text-white/40 shrink-0 group-hover:text-amber-400 transition-colors" />
                            <a href="tel:039518105" class="hover:text-amber-400 transition-colors">039-518-105</a>
                        </li>
                    </ul>
                </div>

                <!-- Col 5 -->
                <div class="lg:col-span-1 text-white">
                    <h4 class="text-lg font-bold flex items-center gap-2 mb-2 text-white/95">
                        <x-heroicon-o-device-phone-mobile class="h-6 w-6 text-amber-400" />
                        ติดตามเรา
                    </h4>
                    <div class="w-10 h-1.5 bg-amber-500 rounded-full mb-6"></div>
                    <div class="flex flex-wrap gap-3">
                        <a href="javascript:void(0)" class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-blue-600 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                            <x-bi-facebook class="h-5 w-5 text-white group-hover:scale-110 transition-transform" />
                        </a>
                        <a href="javascript:void(0)" class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-blue-800 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                            <x-bi-linkedin class="h-5 w-5 text-white group-hover:scale-110 transition-transform" />
                        </a>
                        <a href="javascript:void(0)" class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-pink-600 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                            <x-bi-instagram class="h-5 w-5 text-white group-hover:scale-110 transition-transform" />
                        </a>
                        <a href="javascript:void(0)" class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-slate-900 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
                            <x-bi-twitter-x class="h-5 w-5 text-white group-hover:scale-110 transition-transform" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="pt-4 border-t border-white/10 flex flex-col xl:flex-row items-center justify-between gap-4 text-white/60 text-xs sm:text-sm">
                <div class="flex items-center gap-1.5 font-medium">
                    Copyright &copy; {{ date('Y') }} วิทยาลัยเทคนิคตราด สงวนลิขสิทธิ์
                </div>
                <div class="flex flex-wrap justify-center items-center gap-x-3 gap-y-2">
                    <span class="flex items-center gap-1.5 hover:text-white transition-colors"><x-heroicon-s-globe-alt class="h-4 w-4 text-white/40" /> Laravel & Tall Stack</span>
                    <span class="hidden md:inline text-white/20">|</span>
                    <span class="flex items-center gap-1.5 hover:text-white transition-colors"><x-heroicon-s-user-group class="h-4 w-4 text-white/40" /> Webmaster: Mr.Chuchart Saetiew</span>
                    <span class="hidden md:inline text-white/20">|</span>
                    <span class="flex items-center gap-1.5 hover:text-white transition-colors"><x-heroicon-s-list-bullet class="h-4 w-4 text-white/40" /> Full Stack Developer</span>
                </div>
            </div>
        </div>
    </footer>

</div>
