<?php

use Livewire\Component;
new class extends Component {
    //
};
?>

<div>
    <footer class="bg-[#801810] py-6 px-8 tracking-wide mt-12 font-[sarabun]">
        <div class="relative max-w-7xl mx-auto">
            <div
                class="bg-[#ffffff] rounded-b-lg gap-3 rounded-md shadow-2xl flex flex-wrap items-center justify-between sm:px-8 max-sm:px-4 py-5 absolute -top-16.5 w-full">
                <h6 class="text-[#7c150d] sm:text-xl max-sm:text-base font-semibold">วิทยาลัยเทคนิคตราด</h6>
                <button type='button'
                    class="px-4 py-2 text-[15px] font-semibold rounded-full text-[#ffffff] gap-2 bg-[#801810] cursor-pointer">
                    สํานักงานคณะกรรมการการอาชีวศึกษา
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pt-15 px-4 rounded-b-md bg-[#801810]">
                <div class="lg:col-span-1 max-w-md">
                    <h4 class="text-base font-medium mb-3 text-gray-100 flex">
                        <x-heroicon-s-home class="mr-1 h-5 w-5" />
                        วิทยาลัยเทคนิคตราด
                    </h4>
                    <hr class="w-54 bg-gray-100 border-spacing-x-0.5 text-white rounded-sm dark:bg-gray-700">
                    <ul class="space-y-2 ml-6 mt-2 text-white" style="list-style-type:square;">
                        <li class="text-white text-sm">
                            <a href='#'>About Us</a>
                        </li>

                        <li class="text-white text-sm">
                            <a href='#'>Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-1 max-w-md">
                    <h4 class="text-base font-medium mb-3 text-gray-100 flex">
                        <x-heroicon-o-chart-bar-square class="mr-1 h-5 w-5" />
                        สถิติการเข้าใช้งาน
                    </h4>
                    <hr class="w-54 bg-gray-100 border-spacing-x-0.5 text-white rounded-sm dark:bg-gray-700 mb-1">
                    <script type="text/javascript" src="https://www.stat-counter.org/count/ib7h"></script><br>
                </div>

                <div class="lg:col-span-1 max-w-md">
                    <h4 class="text-base font-medium mb-3 text-gray-100 flex">
                        <x-heroicon-o-wrench-screwdriver class="mr-1 h-5 w-5" />
                        บริการ-วิทยาลัยเทคนิคตราด
                    </h4>
                    <hr class="w-54 bg-gray-100  border-spacing-x-0.5 text-white rounded-sm dark:bg-gray-700">
                    <ul class="space-y-2 ml-6 mt-2 text-white" style="list-style-type:square;">
                        <li><a href="javascript:void(0)" class="text-white text-sm mt-2 hover:text-gray-200 ">Web
                                Development</a></li>
                        <li><a href="javascript:void(0)" class="text-white text-sm mt-2 hover:text-gray-200">Mobile App
                                Development</a></li>
                        <li><a href="javascript:void(0)" class="text-white text-sm mt-2 hover:text-gray-200">UI/UX
                                Design</a></li>
                        <li><a href="javascript:void(0)" class="text-white text-sm mt-2 hover:text-gray-200">Digital
                                Marketing</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-1 max-w-md">
                    <h4 class="text-base font-medium mb-3 text-gray-100 flex">
                        <x-heroicon-o-credit-card class="mr-1 h-5 w-5" />
                        ติดต่อ-วิทยาลัยเทคนิคตราด
                    </h4>
                    <hr class="w-54 bg-gray-100 border-spacing-x-0.5 text-white rounded-sm dark:bg-gray-700">
                    <ul class="space-y-2 ml-6" style="list-style-type:square;">
                        <li class="text-white text-sm mt-2">วิทยาลัยเทคนิคตราด</li>
                        <li class="text-white text-sm mt-2">480 หมู่ 9 ถ.สุขุมวิท ต.วังกระแจะ</li>
                        <li class="text-white text-sm">อ.เมือง จ.ตราด 23000</li>
                        <li class="text-white text-sm">official@trattc.ac.th</li>
                        <li class="text-white text-sm">039-518-105</li>
                    </ul>
                </div>

                <div class="lg:col-span-1 max-w-md">
                    <h4 class="text-base font-medium mb-3 text-gray-100 flex">
                        <x-heroicon-o-device-phone-mobile class="mr-1 h-5 w-5" />
                        Follow Us
                    </h4>
                    <hr class="w-54 bg-gray-100  border-spacing-x-0.5 text-white rounded-sm dark:bg-gray-700">
                    <ul class="flex flex-wrap gap-4 mt-2">
                        <li>
                            <a href='javascript:void(0)'>
                                <x-bi-facebook class="mr-1 h-8 w-8 text-white" />
                            </a>
                        </li>

                        <li>
                            <a href='javascript:void(0)'>
                                <x-bi-linkedin class="mr-1 h-8 w-8 text-white" />
                            </a>
                        </li>

                        <li>
                            <a href='javascript:void(0)'>
                                <x-bi-instagram class="mr-1 h-8 w-8 text-white" />
                            </a>
                        </li>

                        <li>
                            <a href='javascript:void(0)'>
                                <x-bi-twitter-x class="mr-1 h-8 w-8 text-white" />
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-[#801810] pt-5 sm:px-30 w-full">
            <hr class="grow border-gray-300 my-4 mx-auto w-full" />
            <p class="text-white text-sm flex mx-auto justify-center items-center">
                <x-heroicon-s-inbox-stack class="mr-1 h-5 w-5 text-white" />
                Copyright &copy; 2025 วิทยาลัยเทคนิคตราด สงวนลิขสิทธิ์ | เว็บไซต์ ขับเคลื่อนด้วย
                <x-heroicon-s-globe-alt class="mr-1 ml-1 h-5 w-5 text-white" />
                PHP Framework Laravel | Tall Stak
                <x-heroicon-s-user-group class="mr-1 ml-1 h-5 w-5 text-white" />
                Webmaster: Mr.Chuchart Saetiew |
                <x-heroicon-s-list-bullet class="mr-1 ml-1 h-5 w-5 text-white" />
                Full Stack Developer.
            </p>
        </div>
    </footer>

</div>
